<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeFeeStructure;
use App\Models\CollegeFeeBreakdown;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CollegeFeeController extends Controller
{
    public function index()
    {
        $college = auth()->guard('college')->user();

        $courses = Course::where('college_id', $college->college_id)
                         ->with('feeStructures.breakdowns')
                         ->get();

        return view('college.fee_structure_list', compact('college', 'courses'));
    }

public function create(Course $course)
{
    $course->load('college');

    $currencies = ['INR' => '₹ INR'];

    $usedFeeTypes = $course->feeStructures()
                           ->pluck('fee_type')
                           ->toArray();

    return view('college.Create_fee_structure', compact('course', 'currencies', 'usedFeeTypes'));
}

public function store(Request $request, Course $course)
{
    $validated = $request->validate([
        'fee_type'              => 'required|in:government,management,nri',
        'fee_mode'              => 'required|in:total,yearly,semester',
        'currency'              => 'required|string|max:10',
        'breakdowns'            => 'required|array|min:1',
        'breakdowns.*.label'    => 'required|string|max:255',
        'breakdowns.*.amount'   => 'required|numeric|min:0',
        'breakdowns.*.sequence' => 'required|integer|min:1',
    ]);

    // Each fee_type can only exist once per course
    $alreadyExists = CollegeFeeStructure::where('course_id', $course->id)
                                        ->where('fee_type', $validated['fee_type'])
                                        ->exists();

    if ($alreadyExists) {
        return redirect()->back()
            ->withInput()
            ->with('error', "A fee structure for {$validated['fee_type']} quota already exists for this course.");
    }

    // Max 3 fee structures per course
    $totalCount = CollegeFeeStructure::where('course_id', $course->id)->count();
    if ($totalCount >= 3) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'This course already has all 3 fee structures (Government, Management, NRI).');
    }

    DB::transaction(function () use ($validated, $course) {
        $totalAmount = collect($validated['breakdowns'])->sum('amount');

        $feeStructure = CollegeFeeStructure::create([
            'course_id'    => $course->id,
            'fee_type'     => $validated['fee_type'],
            'fee_mode'     => $validated['fee_mode'],
            'currency'     => $validated['currency'],
            'total_amount' => $totalAmount,
        ]);

        $breakdowns = collect($validated['breakdowns'])
            ->map(fn($item) => [
                'fee_structure_id' => $feeStructure->id,
                'label'            => $item['label'],
                'amount'           => $item['amount'],
                'sequence'         => $item['sequence'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ])->toArray();

        CollegeFeeBreakdown::insert($breakdowns);
    });

    return redirect()->back()
        ->with('success', 'Fee structure saved successfully.');
}

    public function show(string $id)
    {
        $college = auth()->guard('college')->user();

        $course = Course::where('id', $id)
                        ->where('college_id', $college->college_id)
                        ->with('feeStructures.breakdowns')
                        ->firstOrFail();

        return view('college.fee_structure_show', compact('course', 'college'));
    }

    public function edit(string $id)
    {
        $college = auth()->guard('college')->user();

        $course = Course::where('id', $id)
                        ->where('college_id', $college->college_id)
                        ->with('feeStructures.breakdowns')
                        ->firstOrFail();

        $currencies = ['INR' => '₹ INR'];

        return view('college.edit_fee_structure', compact('course', 'college', 'currencies'));
    }

  public function update(Request $request, Course $course, CollegeFeeStructure $feeStructure)
{
    $validated = $request->validate([
        'fee_type'              => 'required|in:government,management,nri',
        'fee_mode'              => 'required|in:total,yearly,semester',
        'currency'              => 'required|string|max:10',
        'breakdowns'            => 'required|array|min:1',
        'breakdowns.*.label'    => 'required|string|max:255',
        'breakdowns.*.amount'   => 'required|numeric|min:0',
        'breakdowns.*.sequence' => 'required|integer|min:1',
    ]);

    // Each fee_type can only exist once per course —
    // check if another structure (not this one) already owns that fee_type
    $conflict = CollegeFeeStructure::where('course_id', $course->id)
                                   ->where('fee_type', $validated['fee_type'])
                                   ->where('id', '!=', $feeStructure->id)
                                   ->exists();

    if ($conflict) {
        return redirect()->back()
            ->withInput()
            ->with('error', "A fee structure for the {$validated['fee_type']} quota already exists for this course.");
    }

    DB::transaction(function () use ($validated, $feeStructure) {

        $totalAmount = collect($validated['breakdowns'])->sum('amount');

        $feeStructure->update([
            'fee_type'     => $validated['fee_type'],
            'fee_mode'     => $validated['fee_mode'],
            'currency'     => $validated['currency'],
            'total_amount' => $totalAmount,
        ]);

        // Delete old breakdowns and re-insert fresh
        $feeStructure->breakdowns()->delete();

        $breakdowns = collect($validated['breakdowns'])
            ->map(fn($item) => [
                'fee_structure_id' => $feeStructure->id,
                'label'            => $item['label'],
                'amount'           => $item['amount'],
                'sequence'         => $item['sequence'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ])->toArray();

        CollegeFeeBreakdown::insert($breakdowns);
    });

    return redirect()->back()
        ->with('success', 'Fee structure updated successfully.');
}
    public function destroy(CollegeFeeStructure $feeStructure)
    {
        $feeStructure->delete();

        return redirect()->route('college.feeStructure.index')
            ->with('success', 'Fee structure deleted.');
    }
}