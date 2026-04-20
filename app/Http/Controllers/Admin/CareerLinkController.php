<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
use App\Models\CareerLink;
use App\Services\CareerPathService; 
use Illuminate\Validation\Rule;

class CareerLinkController 
{
    //    public function index()
//    {
//         $careerLinks = CareerLink::with(['parent', 'child'])->paginate(20);
//         $careerNodes = CareerNode::orderBy('title')->get();
//         return view('admin.career_path_link', compact('careerLinks', 'careerNodes'));
//    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = CareerLink::with(['parent', 'child']);

        if ($search) {
            $query->whereHas('parent', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            })->orWhereHas('child', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        $careerLinks = $query->paginate(20)->withQueryString();
        $careerNodes = CareerNode::orderBy('title')->get();

        return view('admin.career_path_link', compact('careerLinks', 'careerNodes', 'search'));
    }

    public function create()
    {
        //
    }

public function store(Request $request)
{
    $request->validate([
        'parent_career_id' => 'required|exists:career_nodes,id',
        'parent_careers'   => 'nullable|array',
        'parent_careers.*' => 'nullable|exists:career_nodes,id',
        'child_careers'    => 'nullable|array',
        'child_careers.*'  => 'nullable|exists:career_nodes,id',
    ]);

    $mainCareerId = $request->parent_career_id;

    $parentCareerIds = array_values(array_unique(array_filter(
        $request->parent_careers ?? [],
        function($v) { return !empty($v); }
    )));

   $childCareerIds = array_values(array_unique(array_filter(
        $request->child_careers ?? [],
        function($v) { return !empty($v); }
    )));

    // ── Basic validations ──────────────────────────────────────
    if (empty($parentCareerIds) && empty($childCareerIds)) {
        return back()->withInput()
            ->withErrors(['error' => 'Please select at least one parent or child career.']);
    }

    if (in_array($mainCareerId, $parentCareerIds)) {
        return back()->withInput()
            ->withErrors(['error' => 'Main career cannot be its own parent.']);
    }

    if (in_array($mainCareerId, $childCareerIds)) {
        return back()->withInput()
            ->withErrors(['error' => 'Main career cannot be its own child.']);
    }

    if (!empty(array_intersect($parentCareerIds, $childCareerIds))) {
        return back()->withInput()
            ->withErrors(['error' => 'A career cannot be both a parent and a child.']);
    }

    try {
        $linksCreated = 0;
        $linksSkipped = 0;
        $warnings     = [];

        // Pre-load main career node once (avoid repeated DB calls)
        $mainCareer = CareerNode::find($mainCareerId);

        // ── HANDLE PARENT RELATIONSHIPS: parent → main ─────────
        foreach ($parentCareerIds as $parentId) {

            $parentCareer = CareerNode::find($parentId);

            // 1. Graph cycle check
            if (CareerPathService::wouldCreateCycle($parentId, $mainCareerId)) {
                $linksSkipped++;
                $warnings[] = " Skipped: '{$parentCareer?->title}' → '{$mainCareer?->title}' would create a circular path.";
                continue;
            }

            // 2. Academic level check — parent must be LOWER level than main
            if (!CareerPathService::isValidLevelProgression($parentId, $mainCareerId)) {
                $linksSkipped++;
                $warnings[] = " Skipped: '{$parentCareer?->title}' ("
                    . CareerPathService::getLevelName($parentCareer?->level)
                    . ") cannot be a parent of '{$mainCareer?->title}' ("
                    . CareerPathService::getLevelName($mainCareer?->level)
                    . "). Parent must be a lower academic level.";
                continue;
            }

            // 3. Duplicate check
            $exists = CareerLink::where('parent_career_id', $parentId)
                ->where('child_career_id', $mainCareerId)
                ->exists();

            if (!$exists) {
                CareerLink::create([
                    'parent_career_id' => $parentId,
                    'child_career_id'  => $mainCareerId,
                ]);
                $linksCreated++;
            } else {
                $linksSkipped++;
                $warnings[] = " Already exists: '{$parentCareer?->title}' → '{$mainCareer?->title}' (skipped).";
            }
        }

        // ── HANDLE CHILD RELATIONSHIPS: main → child ───────────
        foreach ($childCareerIds as $childId) {

            $childCareer = CareerNode::find($childId);

            // 1. Graph cycle check
            if (CareerPathService::wouldCreateCycle($mainCareerId, $childId)) {
                $linksSkipped++;
                $warnings[] = " Skipped: '{$mainCareer?->title}' → '{$childCareer?->title}' would create a circular path.";
                continue;
            }

            // 2. Academic level check — child must be HIGHER level than main
            if (!CareerPathService::isValidLevelProgression($mainCareerId, $childId)) {
                $linksSkipped++;
                $warnings[] = " Skipped: '{$childCareer?->title}' ("
                    . CareerPathService::getLevelName($childCareer?->level)
                    . ") cannot be a child of '{$mainCareer?->title}' ("
                    . CareerPathService::getLevelName($mainCareer?->level)
                    . "). Child must be a higher academic level.";
                continue;
            }

            // 3. Duplicate check
            $exists = CareerLink::where('parent_career_id', $mainCareerId)
                ->where('child_career_id', $childId)
                ->exists();

            if (!$exists) {
                CareerLink::create([
                    'parent_career_id' => $mainCareerId,
                    'child_career_id'  => $childId,
                ]);
                $linksCreated++;
            } else {
                $linksSkipped++;
                $warnings[] = " Already exists: '{$mainCareer?->title}' → '{$childCareer?->title}' (skipped).";
            }
        }

        // ── Build response message ──────────────────────────────
        $message = '';
        if ($linksCreated > 0) {
            $message = "Successfully created {$linksCreated} link(s).";
        }
        if ($linksSkipped > 0) {
            $message .= ($message ? ' ' : '') . "{$linksSkipped} skipped.";
        }
        if ($linksCreated === 0 && $linksSkipped > 0) {
            $message = "No new links were created. {$linksSkipped} skipped.";
        }

        return back()
            ->with('success', $message)
            ->with('warnings', $warnings);

    } catch (\Exception $e) {
        return back()->withInput()
            ->withErrors(['error' => 'Failed to create links: ' . $e->getMessage()]);
    }
}

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_career_id' => 'required|exists:career_nodes,id',
            'child_career_id'  => 'required|exists:career_nodes,id',
        ]);

        if ($request->parent_career_id == $request->child_career_id) {
            return back()->withErrors([
                'error' => 'Parent and Child career cannot be the same.'
            ]);
        }

        // ← FIX: Use CareerPathService instead of CareerLink
        if (CareerPathService::wouldCreateCycle($request->parent_career_id, $request->child_career_id)) {
            return back()->withErrors([
                'error' => 'This connection would create a circular path in the career hierarchy.'
            ]);
        }

        $exists = CareerLink::where('parent_career_id', $request->parent_career_id)
            ->where('child_career_id', $request->child_career_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'error' => 'This career relationship already exists.'
            ]);
        }

        try {
            $careerLink = CareerLink::findOrFail($id);

            $careerLink->update([
                'parent_career_id' => $request->parent_career_id,
                'child_career_id'  => $request->child_career_id,
            ]);

            return redirect()
                ->route('admin.career_link.index')
                ->with('success', 'Career link updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Failed to update: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $careerLink = CareerLink::findOrFail($id);

            $parentTitle = $careerLink->parent?->title ?? 'Unknown';
            $childTitle  = $careerLink->child?->title  ?? 'Unknown';

            $careerLink->delete();

            return redirect()
                ->route('admin.career_link.index')
                ->with('success', "Career link '{$parentTitle} → {$childTitle}' deleted successfully.");

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.career_link.index')
                ->withErrors(['error' => 'Failed to delete career link: ' . $e->getMessage()]);
        }
    }
}