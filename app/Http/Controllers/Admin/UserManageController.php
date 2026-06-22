<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $query = User::latest();

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    $allUsers = (clone $query)->get(); 
    $users = $query->paginate(10)->withQueryString();

    return view('admin.admin_user', compact('users', 'allUsers'));
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
    $user = User::with('children')->findOrFail($id);

    return view('admin.admin_usermanagement_show', compact('user'));
}

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(User $userManagement)
    {
        $userManagement->load('children');

        return view('admin.admin_usermanagement_edit', [
            'user' => $userManagement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, User $userManagement)
{
    $validated = $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        'email'             => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userManagement->id)],
        'phone'             => ['nullable', 'string', 'max:20'],
        'password'          => ['nullable', 'string', 'min:8', 'confirmed'],
        'role'              => ['required', Rule::in(['admin', 'user'])],
        'current_education' => ['nullable', 'string', 'max:255'],
    ]);

    // Handle password properly
    if ($request->filled('password')) {
        $validated['password'] = Hash::make($request->password);
    } else {
        unset($validated['password']);
    }

    $userManagement->update($validated);

    return redirect()
        ->route('admin.userManagement.index')
        ->with('success', 'User updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $user = User::findOrFail($id); 
    $user->delete();

    return redirect()->route('admin.userManagement.index')
                     ->with('success', 'User deleted successfully');
}
}
