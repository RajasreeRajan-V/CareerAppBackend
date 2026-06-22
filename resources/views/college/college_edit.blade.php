@extends('college.layout.app')

@section('title', 'Edit College Profile')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Page Heading --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit College Profile</h1>
            <p class="text-sm text-gray-500">Update your college information</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-100 border border-red-400 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow p-6" style="border: 1.5px solid #306060;">
        <form action="{{ route('college.collegeEdit.update', $college->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium">College Name *</label>
                <input type="text" name="name"
                    value="{{ old('name', $college->name) }}" required
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('name') ? 'border-red-500' : '' }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Principal --}}
            <div>
                <label class="block text-sm font-medium">Principal Name</label>
                <input type="text" name="principal_name"
                    value="{{ old('principal_name', $college->principal_name) }}"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('principal_name') ? 'border-red-500' : '' }}">
                @error('principal_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $college->email) }}"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('email') ? 'border-red-500' : '' }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium">Phone</label>
                <div class="flex border rounded-lg overflow-hidden {{ $errors->has('phone') ? 'border-red-500' : '' }}">
                    <span class="px-3 py-2 bg-gray-100 text-gray-500 text-sm">+91</span>
                    <input type="text" name="phone"
                        value="{{ old('phone', $college->phone) }}"
                        maxlength="10" inputmode="numeric"
                        class="w-full px-4 py-2 outline-none">
                </div>
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Website --}}
            <div>
                <label class="block text-sm font-medium">Website</label>
                <input type="url" name="website"
                    value="{{ old('website', $college->website) }}"
                    placeholder="https://example.com"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('website') ? 'border-red-500' : '' }}">
                @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>


            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium">Location</label>
                <input type="text" name="location"
                    value="{{ old('location', $college->location) }}"
                    placeholder="Street, City, State"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('location') ? 'border-red-500' : '' }}">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rating --}}
            <div>
                <label class="block text-sm font-medium">Rating</label>
                <input type="number" step="0.1" min="0" max="5"
                    name="rating"
                    value="{{ old('rating', $college->rating) }}"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('rating') ? 'border-red-500' : '' }}">
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Pincode --}}
            <div>
                <label class="block text-sm font-medium">Pincode</label>
                <input type="text" name="pincode"
                    value="{{ old('pincode', $college->pincode) }}"
                    maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('pincode') ? 'border-red-500' : '' }}">
                @error('pincode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- About --}}
            <div>
                <label class="block text-sm font-medium">About</label>
                <textarea name="about" rows="3"
                    class="w-full border rounded-lg px-4 py-2 {{ $errors->has('about') ? 'border-red-500' : '' }}">{{ old('about', $college->about) }}</textarea>
                @error('about') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 border rounded-lg">Cancel</a>

                <button type="submit"
                    class="px-6 py-2 bg-teal-600 text-white rounded-lg">
                    Save Changes
                </button>
            </div>

        </form>
    </div>

</div>

@endsection
