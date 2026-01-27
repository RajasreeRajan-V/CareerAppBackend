@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card shadow-sm">
                <div class="card-header text-white text-center" style="background-color: #306060;">
                    <h4 class="mb-0">Create New College</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.college.store') }}" method="POST" enctype="multipart/form-data" id="collegeForm">
                        @csrf

                        {{-- BASIC DETAILS --}}
                        <h5 class="mb-3">Basic College Information</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">College Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                    <label class="form-label">Street *</label>
                                    <input type="text" name="street" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">District *</label>
                                    <input type="text" name="district" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">State *</label>
                                    <input type="text" name="state" class="form-control" required>
                                </div>

                            <div class="col-md-6">
                                <label class="form-label">Rating</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label">About College</label>
                                <textarea name="about" rows="4" class="form-control"></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- IMAGES --}}
                        <h5 class="mb-3">College Images</h5>

                        <div class="mb-3">
                            <input
                                type="file"
                                id="imageInput"
                                class="form-control"
                                accept="image/*"
                                multiple>
                            <small class="text-muted">Select multiple images</small>
                        </div>

                        <div id="imagePreview" class="row g-3"></div>

                        <hr class="my-4">

                        {{-- FACILITIES --}}
                        <h5 class="mb-3">Facilities</h5>

                        <div id="facility-wrapper"></div>

                        <button type="button" class="btn btn-outline-primary mb-3" onclick="addFacility()">
                            Add Facility
                        </button>

                        <hr class="my-4">

                        {{-- COURSES --}}
                        <h5 class="mb-3">Courses</h5>

                        <div id="course-wrapper"></div>

                        <button type="button" class="btn btn-outline-primary mb-4" onclick="addCourse()">
                            Add Course
                        </button>

                        {{-- ACTION BUTTONS --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('admin.college.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- JS --}}
<script>
    let selectedFiles = [];

    function addFacility() {
        document.getElementById('facility-wrapper').insertAdjacentHTML('beforeend', `
            <div class="input-group mb-2">
                <input type="text" name="facilities[]" class="form-control" placeholder="Facility" required>
                <button class="btn btn-outline-danger" type="button"
                        onclick="this.parentElement.remove()">Remove</button>
            </div>
        `);
    }

    function addCourse() {
        document.getElementById('course-wrapper').insertAdjacentHTML('beforeend', `
            <div class="input-group mb-2">
                <input type="text" name="courses[]" class="form-control" placeholder="Course" required>
                <button class="btn btn-outline-danger" type="button"
                        onclick="this.parentElement.remove()">Remove</button>
            </div>
        `);
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        renderImages();
    }

    function renderImages() {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML += `
                    <div class="col-md-3">
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded">
                            <button type="button"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                    onclick="removeImage(${index})">×</button>
                        </div>
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    }

    document.getElementById('imageInput').addEventListener('change', function () {
        Array.from(this.files).forEach(file => selectedFiles.push(file));
        renderImages();
        this.value = '';
    });

    document.getElementById('collegeForm').addEventListener('submit', function () {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'images[]';
        input.files = dt.files;
        input.multiple = true;
        input.hidden = true;

        this.appendChild(input);
    });

    document.addEventListener('DOMContentLoaded', () => {
        addFacility();
        addCourse();
    });
</script>

@endsection
