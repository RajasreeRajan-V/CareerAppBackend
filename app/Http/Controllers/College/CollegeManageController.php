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
        //
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
        // $college = auth()->guard('college')->user();
        $college = CollegeRegistration::findOrFail($id);
        return view('college.college_edit', compact('college'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{
    $college = auth()->guard('college')->user();

   $request->validate([
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

     $college->update([
        'college_name'   => $request->college_name,
        'principal_name' => $request->principal_name,
        'email'          => $request->email,
        'contact_no'     => $request->contact_no,
        'website'        => $request->website,
        'address'        => $request->address,
        'city'           => $request->city,
        'state'          => $request->state,
        'pincode'        => $request->pincode,
    ]);

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
