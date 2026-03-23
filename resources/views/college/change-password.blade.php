@extends('college.layout.app')

@section('content')

    <div class="max-w-xl mx-auto">

        {{-- Page Heading --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Change Password</h1>
            <p class="text-sm text-gray-500">You must set a new password before continuing.</p>
        </div>

        {{-- Error: session --}}
        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-100 border border-red-400 text-red-700 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                <div class="flex items-center mb-1">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    <span class="font-medium text-sm">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-white rounded-xl shadow p-6" style="border: 1.5px solid #306060;">
            <form method="POST" action="{{ route('college.password.update') }}" class="space-y-4">
                @csrf

                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                        New Password
                    </label>
                    <input type="password"
                           id="new_password"
                           name="new_password"
                           placeholder="Min. 8 characters"
                           required
                           minlength="8"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('new_password') ? 'border-red-400' : '' }}">
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm Password
                    </label>
                    <input type="password"
                           id="new_password_confirmation"
                           name="new_password_confirmation"
                           placeholder="Re-enter your password"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('new_password_confirmation') ? 'border-red-400' : '' }}">
                    @error('new_password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full px-6 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition">
                        <i class="fa-solid fa-key mr-1"></i> Update Password
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection