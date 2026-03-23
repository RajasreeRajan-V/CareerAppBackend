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
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-6 rounded-xl bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                <div class="flex items-center mb-1">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Edit Form --}}
        <div class="bg-white rounded-xl shadow p-6" style="border: 1.5px solid #306060;">
            <form action="{{ route('college.collegeEdit.update', $college->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- College Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        College Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="college_name" value="{{ old('college_name', $college->college_name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('college_name') ? 'border-red-400' : '' }}">
                    @error('college_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Principal Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Principal Name</label>
                    <input type="text" name="principal_name" value="{{ old('principal_name', $college->principal_name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('principal_name') ? 'border-red-400' : '' }}">
                    @error('principal_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $college->email) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('email') ? 'border-red-400' : '' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact No --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact No</label>
                    <input type="text" name="contact_no" value="{{ old('contact_no', $college->contact_no) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('contact_no') ? 'border-red-400' : '' }}">
                    @error('contact_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Website --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $college->website) }}"
                        placeholder="https://example.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('website') ? 'border-red-400' : '' }}">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('address') ? 'border-red-400' : '' }}">{{ old('address', $college->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City / State / Pincode --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $college->city) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('city') ? 'border-red-400' : '' }}">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', $college->state) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('state') ? 'border-red-400' : '' }}">
                        @error('state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $college->pincode) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 {{ $errors->has('pincode') ? 'border-red-400' : '' }}">
                        @error('pincode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection