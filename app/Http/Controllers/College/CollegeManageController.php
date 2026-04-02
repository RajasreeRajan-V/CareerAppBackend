<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeRegistration;

class CollegeManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $college = auth()->guard('college')->user();
        return view('college.college_edit', compact('college'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $college = CollegeRegistration::findOrFail($id);
        return view('college.college_edit', compact('college'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $collegeRegistration = auth()->guard('college')->user();

        $validated = $request->validate([
            'college_name'   => 'required|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'email'          => 'required|email|max:255',
            'contact_no'     => 'required|string|max:12',
            'website'        => 'nullable|url|max:255',
            'address'        => 'nullable|string',
            'city'           => 'required|string|max:255',
            'state'          => 'required|string|max:255',
            'pincode'        => 'required|string|max:10',
        ]);

        // 1. Update CollegeRegistration (the auth user table)
        $collegeRegistration->update($validated);

        // 2. Also sync to the College table if linked
        if ($collegeRegistration->college_id) {
            $collegeModel = \App\Models\College::find($collegeRegistration->college_id);

            if ($collegeModel) {
                $collegeModel->update([
                    'name'     => $request->college_name,
                    'phone'    => $request->contact_no,
                    'email'    => $request->email,
                    'website'  => $request->website,
                    'location' => $request->city . ', ' . $request->state,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}