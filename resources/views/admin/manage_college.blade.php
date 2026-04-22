@extends('layouts.app')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card shadow-sm">
                        <div class="card-header text-white text-center" style="background-color: #306060;">
                            <h4 class="mb-0">College List</h4>
                        </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>College Name</th>
                                    <th>Address</th>
                                    <th>Rating</th>
                                    <th>Contact</th>
                                    <th>Facilities</th>
                                    <th>Courses</th>
                                    <th>Images</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($colleges as $college)
                                    <tr>
                                        <td>{{ $colleges->firstItem() + $loop->index }}</td>

                                        <td>
                                            <strong>{{ $college->name }}</strong><br>
                                            <small class="text-muted">
                                                {{ \Illuminate\Support\Str::limit($college->about, 100) }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $college->location }}

                                        </td>

                                        <td class="text-center">
                                            {{ $college->rating ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $college->phone ?? 'N/A' }}<br>
                                            {{ $college->email ?? 'N/A' }}<br>
                                            @if ($college->website)
                                                <a href="{{ $college->website }}" target="_blank">Visit</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            <ul class="mb-0">
                                                @foreach ($college->facilities as $facility)
                                                    <li>{{ $facility->facility }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td>
                                            <ul class="mb-0">
                                                @foreach ($college->courses as $course)
                                                    <li>{{ $course->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td class="text-center">
                                            @forelse($college->images as $image)
                                                <img src="{{ asset('storage/' . $image->image_url) }}"
                                                    class="img-thumbnail mb-1" width="60">
                                            @empty
                                                <span class="text-muted">No Image</span>
                                            @endforelse
                                        </td>


                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                        
                                               <button class="btn btn-sm btn-warning"
    data-bs-toggle="modal"
    data-bs-target="#editCollegeModal"
    data-id="{{ $college->id }}"
    data-name="{{ $college->name }}"
    data-street="{{ explode(', ', $college->location)[0] ?? '' }}"
    data-state-id="{{ $college->state_id }}"
    data-district-id="{{ $college->district_id }}"
    data-rating="{{ $college->rating }}"
    data-phone="{{ $college->phone }}"
    data-email="{{ $college->email }}"
    data-website="{{ $college->website }}"
    data-about="{{ $college->about }}"
    data-facilities='@json($college->facilities->pluck("facility"))'
    data-courses='@json($college->courses->pluck("name"))'
    data-images='@json($college->images->pluck("image_url"))'>
    <i class="fas fa-edit"></i>
</button>
                                        
                                                <form action="{{ route('admin.college.destroy', $college->id) }}"
                                                      method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this college?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                        
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            No colleges found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($colleges->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $colleges->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

<!-- Edit College Modal -->
<div class="modal fade" id="editCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" id="editCollegeForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Modal Header -->
                <div class="modal-header text-white" style="background-color: #306060;">
                    <h5 class="modal-title">Edit College</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <input type="hidden" name="college_id" id="edit_college_id">

                    <!-- ================= BASIC DETAILS ================= -->
                    <h5 class="mb-3">Basic College Information</h5>

                    <div class="row g-3">

                        <!-- Row 1 -->
                        <div class="col-md-6">
                            <label class="form-label">College Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Street *</label>
                            <input type="text" name="street" id="edit_street" class="form-control" required>
                        </div>

                        <!-- State Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label">State *</label>
                            <select name="state_id" id="edit_state_id" class="form-select" required>
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- District Dropdown (populated by AJAX) -->
                        <div class="col-md-6">
                            <label class="form-label">District *</label>
                            <select name="district_id" id="edit_district_id" class="form-select" required>
                                <option value="">-- Select District --</option>
                            </select>
                            @error('district_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-4">
                            <label class="form-label">Rating *</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating"
                                id="edit_rating" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="phone" id="edit_phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                pattern="[0-9]{10,12}" maxlength="12" inputmode="numeric"
                                title="Enter digits only (10-12 characters)" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" id="edit_email"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" id="edit_website" class="form-control">
                        </div>

                        <!-- Row 4 -->
                        <div class="col-12">
                            <label class="form-label">About College</label>
                            <textarea name="about" id="edit_about" rows="4" class="form-control"></textarea>
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- ================= IMAGES ================= -->
                    <h5 class="mb-3">College Images</h5>

                    <div class="mb-3">
                        <input type="file" id="editImageInput" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Select multiple images</small>
                    </div>

                    <div id="editImagePreview" class="row g-3"></div>

                    <hr class="my-4">

                    <!-- ================= FACILITIES ================= -->
                    <h5 class="mb-3">Facilities</h5>

                    <div id="edit-facility-wrapper"></div>

                    <button type="button" class="btn btn-outline-primary mb-3" onclick="addFacility()">
                        + Add Facility
                    </button>

                    <hr class="my-4">

                    <!-- ================= COURSES ================= -->
                    <h5 class="mb-3">Courses</h5>

                    <div id="edit-course-wrapper"></div>

                    <button type="button" class="btn btn-outline-primary mb-3" onclick="addCourse()">
                        + Add Course
                    </button>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update College</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let facilityCount = 0;
    let courseCount   = 0;

    /* ===============================
       DISTRICT AJAX LOADER
    ================================= */
    function loadEditDistricts(stateId, selectedDistrictId = null) {
        const districtSelect = document.getElementById('edit_district_id');
        districtSelect.innerHTML = '<option value="">Loading...</option>';

        if (!stateId) {
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            return;
        }

        fetch(`{{ route('get.districts') }}?state_id=${stateId}`)
            .then(res => res.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                data.forEach(d => {
                    const selected = selectedDistrictId && d.id == selectedDistrictId ? 'selected' : '';
                    districtSelect.innerHTML += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                });
            })
            .catch(err => {
                console.error('District load error:', err);
                districtSelect.innerHTML = '<option value="">Error loading districts</option>';
            });
    }

    // When state changes manually inside the edit modal
    document.getElementById('edit_state_id').addEventListener('change', function () {
        loadEditDistricts(this.value, null);
    });

    /* ===============================
       FACILITY FUNCTIONS
    ================================= */
    function addFacility(value = '') {
        facilityCount++;
        const container = document.getElementById('edit-facility-wrapper');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.id = `facility-${facilityCount}`;
        div.innerHTML = `
            <input type="text" name="facilities[]" class="form-control"
                   value="${value}" placeholder="Enter facility name" required>
            <button type="button" class="btn btn-outline-danger"
                    onclick="removeFacility(${facilityCount})">
                <i class="fas fa-times"></i>
            </button>`;
        container.appendChild(div);
    }

    function removeFacility(id) {
        const el = document.getElementById(`facility-${id}`);
        if (el) el.remove();
    }

    function clearFacilities() {
        document.getElementById('edit-facility-wrapper').innerHTML = '';
        facilityCount = 0;
    }

    /* ===============================
       COURSE FUNCTIONS
    ================================= */
    function addCourse(value = '') {
        courseCount++;
        const container = document.getElementById('edit-course-wrapper');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.id = `course-${courseCount}`;
        div.innerHTML = `
            <input type="text" name="courses[]" class="form-control"
                   value="${value}" placeholder="Enter course name" required>
            <button type="button" class="btn btn-outline-danger"
                    onclick="removeCourse(${courseCount})">
                <i class="fas fa-times"></i>
            </button>`;
        container.appendChild(div);
    }

    function removeCourse(id) {
        const el = document.getElementById(`course-${id}`);
        if (el) el.remove();
    }

    function clearCourses() {
        document.getElementById('edit-course-wrapper').innerHTML = '';
        courseCount = 0;
    }

    function removeExistingImage(index) {
        const el = document.getElementById(`existing-image-${index}`);
        if (el) el.remove();
    }

    /* ===============================
       MODAL DATA POPULATION
    ================================= */
    document.addEventListener('DOMContentLoaded', function () {

        const modalElement = document.getElementById('editCollegeModal');

        modalElement.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;
            if (!button) return;

            const id         = button.getAttribute('data-id');
            const name       = button.getAttribute('data-name');
            const street     = button.getAttribute('data-street');
            const stateId    = button.getAttribute('data-state-id');
            const districtId = button.getAttribute('data-district-id');
            const rating     = button.getAttribute('data-rating');
            const phone      = button.getAttribute('data-phone');
            const email      = button.getAttribute('data-email');
            const website    = button.getAttribute('data-website');
            const about      = button.getAttribute('data-about');
            const facilitiesJson = button.getAttribute('data-facilities');
            const coursesJson    = button.getAttribute('data-courses');
            const imagesJson     = button.getAttribute('data-images');

            /* Basic fields */
            document.getElementById('edit_college_id').value = id     || '';
            document.getElementById('edit_name').value        = name   || '';
            document.getElementById('edit_street').value      = street || '';
            document.getElementById('edit_rating').value      = rating || '';
            document.getElementById('edit_phone').value       = phone  || '';
            document.getElementById('edit_email').value       = email  || '';
            document.getElementById('edit_website').value     = website || '';
            document.getElementById('edit_about').value       = about  || '';

            /* State dropdown — set value, then load districts with pre-selection */
            document.getElementById('edit_state_id').value = stateId || '';
            loadEditDistricts(stateId, districtId);

            /* Dynamic fields */
            clearFacilities();
            clearCourses();

            try {
                const facilities = JSON.parse(facilitiesJson || '[]');
                facilities.length ? facilities.forEach(f => addFacility(f)) : addFacility();
            } catch (e) { addFacility(); }

            try {
                const courses = JSON.parse(coursesJson || '[]');
                courses.length ? courses.forEach(c => addCourse(c)) : addCourse();
            } catch (e) { addCourse(); }

            /* Existing images */
            const imagePreviewContainer = document.getElementById('editImagePreview');
            imagePreviewContainer.innerHTML = '';

            try {
                const images = JSON.parse(imagesJson || '[]');
                images.forEach((image, index) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3';
                    col.id = `existing-image-${index}`;
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="{{ asset('storage') }}/${image}"
                                 class="img-fluid rounded border"
                                 style="height:120px; object-fit:cover; width:100%;">
                            <input type="hidden" name="existing_images[]" value="${image}">
                            <button type="button"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                    onclick="removeExistingImage(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`;
                    imagePreviewContainer.appendChild(col);
                });
            } catch (e) {
                console.error('Image JSON error:', e);
            }

            /* Form action */
            const updateUrl = "{{ route('admin.college.update', ['college' => '__ID__']) }}";
            document.getElementById('editCollegeForm').action = updateUrl.replace('__ID__', id);
        });

        /* ===============================
           VALIDATION ERROR RE-OPEN
        ================================= */
        @if ($errors->any())
            const editModal = new bootstrap.Modal(document.getElementById('editCollegeModal'));
            editModal.show();

            document.getElementById('edit_name').value    = '{{ old('name') }}'    || '';
            document.getElementById('edit_street').value  = '{{ old('street') }}'  || '';
            document.getElementById('edit_rating').value  = '{{ old('rating') }}'  || '';
            document.getElementById('edit_phone').value   = '{{ old('phone') }}'   || '';
            document.getElementById('edit_email').value   = '{{ old('email') }}'   || '';
            document.getElementById('edit_website').value = '{{ old('website') }}' || '';
            document.getElementById('edit_about').value   = '{{ old('about') }}'   || '';

            // Restore state & district dropdowns
            const oldStateId    = '{{ old('state_id') }}';
            const oldDistrictId = '{{ old('district_id') }}';
            document.getElementById('edit_state_id').value = oldStateId;
            if (oldStateId) loadEditDistricts(oldStateId, oldDistrictId);

            // Restore college_id so the form action is correct
            const oldCollegeId = '{{ old('college_id') }}';
            document.getElementById('edit_college_id').value = oldCollegeId;
            const updateUrl = "{{ route('admin.college.update', ['college' => '__ID__']) }}";
            document.getElementById('editCollegeForm').action = updateUrl.replace('__ID__', oldCollegeId);

            clearFacilities();
            @if(old('facilities'))
                @foreach(old('facilities') as $facility)
                    addFacility('{{ $facility }}');
                @endforeach
            @else
                addFacility();
            @endif

            clearCourses();
            @if(old('courses'))
                @foreach(old('courses') as $course)
                    addCourse('{{ $course }}');
                @endforeach
            @else
                addCourse();
            @endif
        @endif

    });
</script>
@endpush
