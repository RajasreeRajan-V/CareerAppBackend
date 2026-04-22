<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;

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
        $college = College::findOrFail($id);
        return view('college.college_edit', compact('college'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $college = College::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'principal_name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:colleges,email,' . $id,
        'phone' => 'nullable|string|max:15',
        'website' => 'nullable|url',
        'location' => 'nullable|string|max:255',
        'rating' => 'nullable|numeric|min:0|max:5',
        'pincode' => 'nullable|string|max:6',
        'about' => 'nullable|string',
    ]);

    $college->update($request->only([
        'name',
        'principal_name',
        'email',
        'phone',
        'website',
        'location',
        'rating',
        'pincode',
        'about'
    ]));

    return back()->with('success', 'Profile updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}