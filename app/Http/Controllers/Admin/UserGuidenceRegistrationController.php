<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerGuidanceRegistration;
class UserGuidenceRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $search = $request->input('search');

    $registrations = CareerGuidanceRegistration::with('banner')
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(15);

    return view('admin.admin_user_guidence_register', compact('registrations'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CareerGuidanceRegistration::findOrFail($id)->delete();
          return redirect()->route('admin.career_guidance_registration.index')
                     ->with('success', ' Deleted successfully');
    }
}
