<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegeRegistration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeRegisteredMail;
use Illuminate\Http\Request;
use App\Models\College;


class CollegeRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $registeredCollegeIds = CollegeRegistration::pluck('college_id');

    $colleges = College::whereNotIn('id', $registeredCollegeIds)
        ->select('id', 'name', 'location', 'phone', 'email', 'website')
        ->orderBy('name')
        ->get();

    $collegeRegistrations = CollegeRegistration::with('college')
        ->latest()
        ->paginate(10);

    return view('admin.admin_college_registration', compact('colleges', 'collegeRegistrations'));
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
                'college_id'     => 'required|exists:colleges,id',
                'principal_name' => 'required|string|max:255',
                'email'          => 'required|email|unique:college_registrations,email',
                'contact_no'     => 'required|digits_between:10,12|unique:college_registrations,contact_no',
                'website'        => 'nullable|url',
                'address'        => 'required|string',
                'city'           => 'required|string|max:255',
                'state'          => 'required|string|max:255',
                'pincode'        => 'required|string|max:10',
            ]);

        $college = College::findOrFail($request->college_id);
        $plainPassword = Str::random(8);
    CollegeRegistration::create([
        'college_id'     => $request->college_id,
        'college_name'   => $college->name,
        'principal_name' => $request->principal_name,
        'email'          => $request->email,
        'contact_no'     => $request->contact_no,
        'website'        => $request->website,
        'address'        => $request->address,
        'city'           => $request->city,
        'state'          => $request->state,
        'pincode'        => $request->pincode,
        'password'       => Hash::make($plainPassword),
    ]);
   $mail = new CollegeRegisteredMail(
    $college->name,
    $request->email,
    $plainPassword
);

Mail::to($request->email)->send($mail);

        // Sync related College record with registration data
        $collegeModel = College::find($request->college_id);
        if ($collegeModel) {
            $collegeModel->update([
                'name'     => $college->name,
                'location' => $request->address . ', ' . $request->city . ', ' . $request->state,
                'phone'    => $request->contact_no,
                'email'    => $request->email,
                'website'  => $request->website,
            ]);
        }

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
            'contact_no'     => 'required|digits_between:7,12|unique:college_registrations,contact_no,' . $id,
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
            'password_changed' => true,
        ]);

        // Sync related College record with registration updates
        $collegeModel = College::find($college->college_id);
        if ($collegeModel) {
            $collegeModel->update([
                'name'     => $request->college_name,
                'location' => $request->address . ', ' . $request->city . ', ' . $request->state,
                'phone'    => $request->contact_no,
                'email'    => $request->email,
                'website'  => $request->website,
            ]);
        }

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
