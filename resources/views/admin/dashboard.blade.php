@extends('layouts.app') 

@section('content')

    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="font-semibold text-2xl text-gray-800">
            Admin Dashboard
        </h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white shadow-sm rounded-lg p-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <i class="fa-solid fa-users text-blue-500 text-3xl"></i>
        </div>

        <!-- Students -->
        <div class="bg-white shadow-sm rounded-lg p-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Students</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
            </div>
            <i class="fa-solid fa-user text-green-500 text-3xl"></i>

        </div>

        <!-- Parents -->
        <div class="bg-white shadow-sm rounded-lg p-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Parents</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_parents'] }}</p>
            </div>
            <i class="fa-solid fa-user text-red-500 text-3xl"></i>
        </div>

        <!-- Recent Users -->
        <div class="bg-white shadow-sm rounded-lg p-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Recent</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['recent_users'] }}</p>
            </div>
           <i class="fa-solid fa-clock text-orange-500 text-3xl"></i>

        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white shadow-sm rounded-lg mb-6 p-6">
        <form action="{{ route('admin.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email, or phone"
                class="md:col-span-2 rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
            >

            <div class="flex gap-2">
                <select name="role" class="flex-1 rounded-md border-gray-300">
                    <option value="All">All Roles</option>
                    <option value="Student" {{ request('role') == 'Student' ? 'selected' : '' }}>Students</option>
                    <option value="Parent" {{ request('role') == 'Parent' ? 'selected' : '' }}>Parents</option>
                </select>

                <button class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3">Contact</th>
                <th class="px-6 py-3">Role</th>
                <th class="px-6 py-3">Education / Children</th>
                <th class="px-6 py-3">Joined</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->phone }}</td>
                    <td class="px-6 py-4">{{ $user->role }}</td>
                    <td class="px-6 py-4">
                        {{ $user->role === 'Student'
                            ? ($user->current_education ?? 'N/A')
                            : $user->children->count().' children' }}
                    </td>
                    <td class="px-6 py-4">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-indigo-600">View</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        No users found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>

@endsection
