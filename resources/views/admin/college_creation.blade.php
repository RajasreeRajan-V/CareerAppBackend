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
                    <form action="{{ route('admin.college.store') }}" method="POST" enctype="multipart/form-data" id="collegeForm" novalidate>
                        @csrf
                        
                        <h5 class="mb-3">Basic College Information</h5>

                        <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">College Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            

                            <div class="col-md-6">
                                <label class="form-label">Street <span class="text-danger">*</span></label>
                                <input type="text" name="street"
                                       class="form-control @error('street') is-invalid @enderror"
                                       value="{{ old('street') }}" required>
                                @error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- State Dropdown --}}
                           <div class="col-md-6">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="state_id" id="state_id"
                                        class="form-select @error('state_id') is-invalid @enderror" required>
                                    <option value="">-- Select State --</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- District Dropdown (populated by JS) --}}
                            <div class="col-md-6">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select name="district_id" id="district_id"
                                        class="form-select @error('district_id') is-invalid @enderror" required>
                                    <option value="">-- Select District --</option>
                                </select>
                                @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                             <div class="col-md-6">
                                <label class="form-label">Rating <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="number" step="0.1" min="0" max="5" name="rating"
                                       class="form-control @error('rating') is-invalid @enderror"
                                       value="{{ old('rating') }}">
                                @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                             <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="phone" id="phone_create"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        pattern="[0-9]{10}"
                                        maxlength="10"
                                        inputmode="numeric"
                                        title="Enter 10 digit mobile number"
                                        required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>


                           <div class="col-md-6">
                                <label class="form-label">Website <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="url" name="website"
                                       class="form-control @error('website') is-invalid @enderror"
                                       value="{{ old('website') }}" placeholder="https://example.com">
                                @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                             <div class="col-12">
                                <label class="form-label">About College <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="about" rows="4"
                                          class="form-control @error('about') is-invalid @enderror">{{ old('about') }}</textarea>
                                @error('about') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- IMAGES --}}
                            <h5 class="mb-3">College Images (optional)</h5>
                            
                            <div class="mb-3">
                                <input type="file"
                                       id="imageInput"
                                       name="images[]"
                                       class="form-control {{ $errors->has('images') || $errors->has('images.*') ? 'is-invalid' : '' }}"
                                       accept="image/*"
                                       multiple>
                            
                                <small class="text-muted">Select multiple images</small>
                            
                                {{-- Validation Errors --}}
                                @if ($errors->has('images') || $errors->has('images.*'))
                                    <div class="mt-2">
                                        @foreach ($errors->get('images') as $messages)
                                            @foreach ($messages as $message)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @endforeach
                                        @endforeach
                            
                                        @foreach ($errors->get('images.*') as $messages)
                                            @foreach ($messages as $message)
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <div id="imagePreview" class="row g-3"></div>

                        <hr class="my-4">

                        {{-- FACILITIES --}}
                        <h5 class="mb-3">Facilities (optional)</h5>
                        <small class="text-danger d-block mb-2">
                            Each facility name must be under 255 characters.
                        </small>
                        <div id="facility-wrapper"></div>
                        <button type="button" class="btn btn-outline-primary mb-3" onclick="addFacility()">
                            Add Facility
                        </button>

                        <hr class="my-4">

                        {{-- COURSES --}}
                        <h5 class="mb-3">Courses (optional)</h5>
                        <small class="text-danger d-block mb-2">
                            Each course name must be under 255 characters.
                        </small>
                        <div id="course-wrapper"></div>
                        <button type="button" class="btn btn-outline-primary mb-4" onclick="addCourse()">
                            Add Course
                        </button>

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

<script>
    // ── District loader ──────────────────────────────────────────────
    const oldDistrictId = "{{ old('district_id') }}";

    document.getElementById('state_id').addEventListener('change', function () {
        loadDistricts(this.value, null);
    });

function loadDistricts(stateId, selectedDistrictId) {
    const districtSelect = document.getElementById('district_id');
    districtSelect.innerHTML = '<option value="">Loading...</option>';

    if (!stateId) {
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        return;
    }

    fetch(`{{ route('get.districts') }}?state_id=${stateId}`)
        .then(res => res.json())
        .then(data => {
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';

            if (!data.length) {
                districtSelect.innerHTML += `<option value="">No districts found</option>`;
                return;
            }

            data.forEach(d => {
                const selected = selectedDistrictId && d.id == selectedDistrictId ? 'selected' : '';
                districtSelect.innerHTML += `<option value="${d.id}" ${selected}>${d.name}</option>`;
            });
        })
        .catch(err => {
            console.error('Error:', err);
            districtSelect.innerHTML = '<option value="">Error loading districts</option>';
        });
}


    // Re-populate on validation error (old values)
    document.addEventListener('DOMContentLoaded', () => {
        const stateId = document.getElementById('state_id').value;
        if (stateId) loadDistricts(stateId, oldDistrictId);

        addFacility();
        addCourse();
    });

    // ── Facilities & Courses ─────────────────────────────────────────

function getExistingValues(wrapperId, excludeInput = null) {
    return Array.from(document.querySelectorAll(`#${wrapperId} input[type="text"]`))
        .filter(input => input !== excludeInput)
        .map(input => input.value.trim().toLowerCase())
        .filter(v => v !== '');
}

function attachDuplicateCheck(input, wrapperId) {
    input.addEventListener('input', function () {
        const current = this.value.trim().toLowerCase();
        const others = getExistingValues(wrapperId, this);
        const isDuplicate = current !== '' && others.includes(current);

        this.classList.toggle('is-invalid', isDuplicate);

        let errEl = this.parentElement.querySelector('.duplicate-error');
        if (isDuplicate && !errEl) {
            this.parentElement.insertAdjacentHTML('beforeend',
                '<div class="invalid-feedback duplicate-error d-block">Duplicate value not allowed.</div>');
        } else if (!isDuplicate && errEl) {
            errEl.remove();
        }
    });
}

function addFacility() {
    const wrapper = document.getElementById('facility-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="facilities[]" class="form-control" placeholder="Facility" maxlength="255">
        <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Remove</button>
    `;
    attachDuplicateCheck(div.querySelector('input'), 'facility-wrapper');
    wrapper.appendChild(div);
}

function addCourse() {
    const wrapper = document.getElementById('course-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="courses[]" class="form-control" placeholder="Course" maxlength="255">
        <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Remove</button>
    `;
    attachDuplicateCheck(div.querySelector('input'), 'course-wrapper');
    wrapper.appendChild(div);
}

    // ── Image preview ────────────────────────────────────────────────
    let selectedFiles = [];

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
    selectedFiles = Array.from(this.files);
    renderImages();
});

    document.getElementById('collegeForm').addEventListener('submit', function (e) {
    if (document.querySelectorAll('.duplicate-error').length > 0) {
        e.preventDefault();
        alert('Please remove duplicate facility or course names before submitting.');
        return;
    }

    // existing image attach logic
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

document.getElementById('phone_create').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 10);
});

</script>

@endsection