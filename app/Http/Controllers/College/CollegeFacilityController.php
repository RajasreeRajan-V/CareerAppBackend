<?php

namespace App\Http\Controllers\college;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CollegeFacility;

class CollegeFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
       $college    = Auth::guard('college')->user();// assuming relation exists
        $facilities = $college->facilities;

        return view('college.college_facilities', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $college = Auth::guard('college')->user();

    $request->validate([
        'facility' => [
            'required', 'string', 'max:255',
            \Illuminate\Validation\Rule::unique('college_facilities', 'facility')
                ->where(fn($q) => $q->where('college_id', $college->id)),
        ],
    ]);

    $college->facilities()->create([
        'facility' => $request->facility,
    ]);

    return redirect()->route('college.facilities.index')
        ->with('success', 'Facility added successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, $id)
{
    $college = Auth::guard('college')->user();

    $request->validate([
        'facility' => [
            'required', 'string', 'max:255',
            \Illuminate\Validation\Rule::unique('college_facilities', 'facility')
                ->where(fn($q) => $q->where('college_id', $college->id))
                ->ignore($id),
        ],
    ]);

    $facility = CollegeFacility::where('id', $id)
        ->where('college_id', $college->id)
        ->firstOrFail();

    $facility->update(['facility' => $request->facility]);

    return redirect()->route('college.facilities.index')
        ->with('success', 'Facility updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    $college = Auth::guard('college')->user();

    $facility = CollegeFacility::where('id', $id)
        ->where('college_id', $college->id) 
        ->firstOrFail();

    $facility->delete();

    return redirect()->route('college.facilities.index')
        ->with('success', 'Facility deleted successfully.');
}

}
