@extends('college.layout.app')

@section('title', 'My Courses')

@section('content')

<div class="max-w-4xl mx-auto"
     x-data="{
        showModal: false,
        showAddModal: false,
        showDeleteModal: false,
        courseId: null,
        courseName: '',

        openEditModal(id, name) {
            this.courseId = id;
            this.courseName = name;
            this.showModal = true;
        },

        openAddModal() {
            this.courseName = '';
            this.showAddModal = true;
        },

        openDeleteModal(id) {
            this.courseId = id;
            this.showDeleteModal = true;
        }
     }">

    {{-- Page Heading --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">My Courses</h1>
            <p class="text-sm text-gray-500">Courses offered by your college</p>
        </div>

        <button 
            @click="openAddModal()"
            class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Course
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

    {{-- Courses Table --}}
    <div class="bg-white rounded-xl shadow" style="border: 1.5px solid #306060;">

        @if ($course->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fa-solid fa-book-open text-4xl mb-3" style="color: #306060; opacity: 0.3;"></i>
                <p class="text-base font-medium text-gray-500">No courses found</p>
                <p class="text-sm text-gray-400 mt-1">No courses have been added for your college yet.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr style="background-color: #306060;">
                            <th class="px-5 py-3 text-xs font-semibold text-white uppercase tracking-wider w-12">#</th>
                            <th class="px-5 py-3 text-xs font-semibold text-white uppercase tracking-wider">Course Name</th>
                            <th class="px-5 py-3 text-xs font-semibold text-white uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($course as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 font-medium text-gray-800">
                                    <i class="fa-solid fa-book-open mr-2 text-teal-600"></i>
                                    {{ $item->name }}
                                </td>
                                <td class="px-5 py-3 space-x-2">
                                    
                                    {{-- Edit --}}
                                    <button 
                                        @click='openEditModal({{ $item->id }}, @json($item->name))'
                                        class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <button 
                                        @click="openDeleteModal({{ $item->id }})"
                                        class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

    {{-- Count --}}
    @if ($course->isNotEmpty())
        <p class="text-xs text-gray-400 mt-3 text-right">
            Showing {{ $course->count() }} course(s)
        </p>
    @endif

    {{-- ================= EDIT MODAL ================= --}}
    <div x-show="showModal"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

        <div @click.away="showModal = false"
             class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

            <h2 class="text-lg font-semibold mb-4">Edit Course</h2>

            <form method="POST" :action="`{{ route('college.collegeCourse.update', ':id') }}`.replace(':id', courseId)">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1">Course Name</label>
                    <input type="text"
                           name="name"
                           x-model="courseName"
                           required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-200">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                            @click="showModal = false"
                            class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm bg-teal-600 text-white rounded hover:bg-teal-700">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ================= ADD MODAL ================= --}}
    <div x-show="showAddModal"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

        <div @click.away="showAddModal = false"
             class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

            <h2 class="text-lg font-semibold mb-4">Add Course</h2>

            <form method="POST" action="{{ route('college.collegeCourse.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1">Course Name</label>
                    <input type="text"
                           name="name"
                           x-model="courseName"
                           required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-teal-200">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button"
                            @click="showAddModal = false"
                            class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm bg-teal-600 text-white rounded hover:bg-teal-700">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ================= DELETE MODAL ================= --}}
    <div x-show="showDeleteModal"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

        <div @click.away="showDeleteModal = false"
             class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

            <h2 class="text-lg font-semibold mb-4 text-red-600">Delete Course</h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete this course? This action cannot be undone.
            </p>

            <form method="POST" :action="`{{ route('college.collegeCourse.destroy', ':id') }}`.replace(':id', courseId)">
                @csrf
                @method('DELETE')

                <div class="flex justify-end space-x-2">
                    <button type="button"
                            @click="showDeleteModal = false"
                            class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection
