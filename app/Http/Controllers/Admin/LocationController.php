<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\District;
class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
{
    $allStates = State::orderBy('name')->get(); 

    $states = State::orderBy('name')->paginate(10, ['*'], 'page');

    $districts = District::with('state')
        ->when($request->filter_state, fn($q) => $q->where('state_id', $request->filter_state))
        ->orderBy('name')
        ->paginate(10, ['*'], 'district_page');

    return view('admin.admin_locationcreation', compact('states', 'districts', 'allStates'));
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
        if ($request->type === 'state') {
            $request->validate([
                'name' => 'required|string|max:255|unique:states,name',
            ]);

            State::create([
                'name' => $request->name,
            ]);

            return back()->with('success', 'State added successfully');
        }

        if ($request->type === 'district') {
            $request->validate([
                'state_id' => 'required|exists:states,id',
                'name' => 'required|string|max:255',
            ]);

            District::create([
                'state_id' => $request->state_id,
                'name' => $request->name,
            ]);

            return back()->with('success', 'District added successfully');
        }

        return back()->with('error', 'Invalid request');
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
    public function update(Request $request)
    {
        if ($request->type === 'state') {
            $request->validate([
                'id' => 'required|exists:states,id',
                'name' => 'required|string|max:255',
            ]);

            $state = State::findOrFail($request->id);
            $state->update([
                'name' => $request->name,
            ]);

            return back()->with('success', 'State updated successfully');
        }

        if ($request->type === 'district') {
            $request->validate([
                'id' => 'required|exists:districts,id',
                'state_id' => 'required|exists:states,id',
                'name' => 'required|string|max:255',
            ]);

            $district = District::findOrFail($request->id);
            $district->update([
                'state_id' => $request->state_id,
                'name' => $request->name,
            ]);

            return back()->with('success', 'District updated successfully');
        }

        return back()->with('error', 'Invalid update request');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->type === 'state') {
            $state = State::findOrFail($request->id);
            $state->delete(); // districts auto delete (cascade)

            return back()->with('success', 'State deleted successfully');
        }

        if ($request->type === 'district') {
            $district = District::findOrFail($request->id);
            $district->delete();

            return back()->with('success', 'District deleted successfully');
        }

        return back()->with('error', 'Invalid delete request');
    }
}
