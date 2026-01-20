<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function dashboard(Request $request)
{
    $query = User::with('children');

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
              ->orWhere('phone', 'like', "%{$request->search}%");
        });
    }

    if ($request->role && $request->role !== 'All') {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(10)->withQueryString();

    $stats = [
        'total_users'     => User::count(),
        'total_students'  => User::where('role', 'Student')->count(),
        'total_parents'   => User::where('role', 'Parent')->count(),
        'recent_users'    => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
    ];

    return view('admin.dashboard', compact('users', 'stats'));
}


 public function search(Request $request)
{
    $search = $request->input('search');
    $role   = $request->input('role');

    $users = User::query()
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        })
        ->when($role && $role != 'All', function ($q) use ($role) {
            $q->where('role', $role);
        })
        ->paginate(10);  // use paginate to avoid errors in view

    $stats = [
        'total_users'    => User::count(),
        'total_students' => User::where('role', 'Student')->count(),
        'total_parents'  => User::where('role', 'Parent')->count(),
        'recent_users'   => User::latest()->take(5)->count(),
    ];

    return view('admin.dashboard', compact('users', 'stats'));
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
        //
    }
}
