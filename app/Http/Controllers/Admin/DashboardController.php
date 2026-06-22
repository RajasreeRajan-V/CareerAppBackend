<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_users'  => User::count(),
            'recent_users' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.dashboard', compact('users', 'stats'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total_users'  => User::count(),
            'recent_users' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.dashboard', compact('users', 'stats'));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}