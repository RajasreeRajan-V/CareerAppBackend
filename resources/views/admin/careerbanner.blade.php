@extends('layouts.app')

@section('title', 'Admission Banners')

@section('content')

    <div class="max-w-6xl mx-auto" x-data="{
                            openCreate: false,
                            openEdit: false,
                            editBanner: {
                                id: null,
                                title: '',
                                description: '',
                                link: '',
                                image: ''
                            }
                         }">


        {{-- Page Heading --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Career Banners</h1>
                <p class="text-sm text-gray-500">Manage career banners</p>
            </div>

            <!-- Add Banner Button -->
            <button @click="openCreate = true"
                class="px-5 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition">
                <i class="fa-solid fa-plus mr-1"></i> Add Banner
            </button>
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

        {{-- Banner Table --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Image</th>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banners as $banner)
                        <tr class="border-t">
                            <!-- Image -->
                            <td class="px-4 py-3">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="h-12 w-auto rounded-lg">
                            </td>

                            <!-- Title -->
                            <td class="px-4 py-3 font-medium">
                                {{ $banner->title }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">

                                    <!-- Edit -->
                                    <button @click="
                                                                editBanner = {
                                                                    id: {{ $banner->id }},
                                                                    title: '{{ addslashes($banner->title) }}',
                                                                    image: '{{ asset('storage/' . $banner->image) }}'
                                                                };
                                                                openEdit = true;
                                                            " class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.careerBanner.destroy', $banner->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500">
                                No banners found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Modal --}}
        {{-- Create Career Banner Modal --}}
        <div x-show="openCreate" x-cloak x-transition
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">

                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Create Career Banner</h2>

                    <button type="button" @click="openCreate = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.careerBanner.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium">Title *</label>
                        <input type="text" name="title" required class="w-full border rounded-lg px-4 py-2">
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium">Banner Image *</label>
                        <input type="file" name="image" required accept="image/*"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openCreate = false" class="px-4 py-2 rounded-lg border">
                            Cancel
                        </button>

                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        {{-- Edit Modal --}}
        <div x-show="openEdit" x-cloak x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Edit Career Banner</h2>

                    <button type="button" @click="openEdit = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="`{{ url('admin/careerBanner') }}/${editBanner.id}`" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium">Title *</label>
                        <input type="text" name="title" x-model="editBanner.title" required
                            class="w-full border rounded-lg px-4 py-2">
                    </div>

                    <!-- Current Image -->
                    <div>
                        <label class="block text-sm font-medium">Current Image</label>
                        <img :src="editBanner.image" class="h-20 rounded-lg mb-2">

                        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 rounded-lg border">
                            Cancel
                        </button>

                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection