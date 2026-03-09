<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegeRegistration;

use App\Http\Requests\UpdateCollegeRegistrationRequest;
use Illuminate\Http\Request;

class CollegeRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collegeRegistrations = CollegeRegistration::latest()->paginate(10);
        return view('admin.admin_college_registration', compact('collegeRegistrations'));
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
       $request->validate([
            'college_name'   => 'required|string|max:255',
            'principal_name' => 'required|string|max:255',
            'email'          => 'required|email|unique:college_registrations,email',
            'contact_no'     => 'required|string|max:12|unique:college_registrations,contact_no',
            'website'        => 'nullable|url',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'state'          => 'required|string|max:255',
            'pincode'        => 'required|string|max:10',
        ]);
    CollegeRegistration::create([
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
    return redirect()->route('admin.college_registration.index')->with('success', 'College registration successful.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CollegeRegistration $collegeRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollegeRegistration $collegeRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $college = CollegeRegistration::findOrFail($id);
        $request->validate([
            'college_name'   => 'required|string|max:255',
            'principal_name' => 'required|string|max:255',
            'email'          => 'required|email|unique:college_registrations,email,' . $id,
            'contact_no'     => 'required|string|max:12|unique:college_registrations,contact_no,' . $id,
            'website'        => 'nullable|url',
            'address'        => 'required|string',
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
        return redirect()->route('admin.college_registration.index')->with('success', 'College registration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $college = CollegeRegistration::findOrFail($id);
        $college->delete();
        return redirect()->route('admin.college_registration.index')->with('success', 'College registration deleted successfully.');
    }
}
