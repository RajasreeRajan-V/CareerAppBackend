<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
use App\Models\CareerLink;
use Illuminate\Validation\Rule;

class CareerLinkController 
{
   public function index()
   {
        $careerLinks = CareerLink::with(['parent', 'child'])->paginate(20);
        $careerNodes = CareerNode::orderBy('title')->get();
        return view('admin.career_path_link', compact('careerLinks', 'careerNodes'));
   }
   public function create()
   {
     //
   }
public function store(Request $request)
{
    $request->validate([
        'parent_career_id' => 'required|exists:career_nodes,id',
        'parent_careers' => 'nullable|array',
        'parent_careers.*' => 'nullable|exists:career_nodes,id',
        'child_careers' => 'nullable|array',
        'child_careers.*' => 'nullable|exists:career_nodes,id',
    ]);

    $mainCareerId = $request->parent_career_id;
    
    $parentCareerIds = array_values(array_unique(array_filter($request->parent_careers, function($value) {
        return !empty($value);
    })));
    
    $childCareerIds = array_values(array_unique(array_filter($request->child_careers, function($value) {
        return !empty($value);
    })));

    if (empty($parentCareerIds) && empty($childCareerIds)) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Please select at least one parent or child career.']);
    }

    if (in_array($mainCareerId, $parentCareerIds)) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Main career cannot be its own parent.']);
    }

    if (in_array($mainCareerId, $childCareerIds)) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Main career cannot be its own child.']);
    }

    $overlap = array_intersect($parentCareerIds, $childCareerIds);
    if (!empty($overlap)) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'A career cannot be both a parent and a child of the main career.']);
    }

    try {
        $linksCreated = 0;
        $linksSkipped = 0;

        foreach ($parentCareerIds as $parentId) {
            $exists = CareerLink::where('parent_career_id', $parentId)
                               ->where('child_career_id', $mainCareerId)
                               ->exists();
            
            if (!$exists) {
                CareerLink::create([
                    'parent_career_id' => $parentId,
                    'child_career_id' => $mainCareerId,
                ]);
                $linksCreated++;
            } else {
                $linksSkipped++;
            }
        }

        foreach ($childCareerIds as $childId) {
            $exists = CareerLink::where('parent_career_id', $mainCareerId)
                               ->where('child_career_id', $childId)
                               ->exists();
            
            if (!$exists) {
                CareerLink::create([
                    'parent_career_id' => $mainCareerId,
                    'child_career_id' => $childId,
                ]);
                $linksCreated++;
            } else {
                $linksSkipped++;
            }
        }

        $message = '';
        if ($linksCreated > 0) {
            $message = "Successfully created {$linksCreated} career link(s)!";
        }
        
        if ($linksSkipped > 0) {
            $message .= ($linksCreated > 0 ? ' ' : '') . "{$linksSkipped} link(s) already existed and were skipped.";
        }

        return back()->with('success', $message);

    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Failed to create career links: ' . $e->getMessage()]);
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
        $childTitle = $careerLink->child?->title ?? 'Unknown';
        
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