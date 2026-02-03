@extends('layouts.app')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
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
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $college->name }}</strong><br>
                                            <small class="text-muted">{{ $college->about }}</small>
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
                                            @if($college->website)
                                                <a href="{{ $college->website }}" target="_blank">Visit</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            <ul class="mb-0">
                                                @foreach($college->facilities as $facility)
                                                    <li>{{ $facility->facility }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td>
                                            <ul class="mb-0">
                                                @foreach($college->courses as $course)
                                                    <li>{{ $course->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td class="text-center">
                                            @forelse($college->images as $image)
                                                <img src="{{ asset('storage/' . $image->image_url) }}" class="img-thumbnail mb-1"
                                                    width="60">
                                            @empty
                                                <span class="text-muted">No Image</span>
                                            @endforelse
                                        </td>


                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning editBtn" data-bs-toggle="modal"
                                                data-bs-target="#editCollegeModal" data-id="{{ $college->id }}"
                                                data-name="{{ $college->name }}" data-location="{{ $college->location ?? '' }}"
                                                data-rating="{{ $college->rating ?? '' }}"
                                                data-phone="{{ $college->phone ?? '' }}"
                                                data-email="{{ $college->email ?? '' }}"
                                                data-website="{{ $college->website ?? '' }}"
                                                data-about="{{ $college->about ?? '' }}"
                                                data-facilities="{{ json_encode($college->facilities->pluck('facility')->toArray()) }}"
                                                data-courses="{{ json_encode($college->courses->pluck('name')->toArray()) }}">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>

                                            <form action="{{ route('admin.college.destroy', $college->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this college?')">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </form>
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

                </div>

            </div>
        </div>
    </div>

    <!-- Edit College Modal -->
    <div class="modal fade" id="editCollegeModal" tabindex="-1" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="editCollegeForm">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="editCollegeModalLabel">Edit College</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="college_id" name="college_id">

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">College Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Street *</label>
                            <input type="text" name="street" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">District *</label>
                            <input type="text" name="district" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">State *</label>
                            <input type="text" name="state" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_rating" class="form-label">Rating</label>
                            <input type="number" step="0.1" min="0" max="5" name="rating" id="edit_rating"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <!-- Add before the Cancel/Update buttons -->
                        <div class="mb-3">
                            <label class="form-label">Facilities</label>
                            <div id="facilitiesContainer"></div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addFacility()">Add
                                Facility</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Courses</label>
                            <div id="coursesContainer"></div>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addCourse()">Add Course</button>
                        </div>
                        <div class="mb-3">
                            <label for="edit_website" class="form-label">Website</label>
                            <input type="url" name="website" id="edit_website" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="edit_about" class="form-label">About</label>
                            <textarea name="about" id="edit_about" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Update College
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        console.log('Script loaded');

        let facilityCount = 0;
        let courseCount = 0;

        // Function to add a new facility input field
        function addFacility(value = '') {
            facilityCount++;
            const container = document.getElementById('facilitiesContainer');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.id = `facility-${facilityCount}`;
            div.innerHTML = `
                <input type="text" name="facilities[]" class="form-control" value="${value}" placeholder="Enter facility name">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFacility(${facilityCount})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }


        // Function to remove a facility input field
        function removeFacility(id) {
            const element = document.getElementById(`facility-${id}`);
            if (element) {
                element.remove();
            }
        }

        // Function to add a new course input field
        function addCourse(value = '') {
            courseCount++;
            const container = document.getElementById('coursesContainer');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.id = `course-${courseCount}`;
            div.innerHTML = `
                        <input type="text" name="courses[]" class="form-control" value="${value}" placeholder="Enter course name">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeCourse(${courseCount})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
            container.appendChild(div);
        }

        // Function to remove a course input field
        function removeCourse(id) {
            const element = document.getElementById(`course-${id}`);
            if (element) {
                element.remove();
            }
        }

        // Function to clear all facilities
        function clearFacilities() {
            document.getElementById('facilitiesContainer').innerHTML = '';
            facilityCount = 0;
        }

        // Function to clear all courses
        function clearCourses() {
            document.getElementById('coursesContainer').innerHTML = '';
            courseCount = 0;
        }

        // Wait for DOM and Bootstrap to be ready
        document.addEventListener("DOMContentLoaded", function () {
            console.log('DOM loaded');

            // Check if Bootstrap is loaded
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded!');
                return;
            }

            console.log('Bootstrap is loaded');

            // Get the modal element
            const modalElement = document.getElementById('editCollegeModal');
            if (!modalElement) {
                console.error('Modal element not found!');
                return;
            }

            console.log('Modal element found');

            // Listen for the modal show event to populate data
            modalElement.addEventListener('show.bs.modal', function (event) {
                console.log('Modal is opening');

                // Button that triggered the modal
                const button = event.relatedTarget;

                if (!button) {
                    console.error('No button found');
                    return;
                }

                // Extract data from data-* attributes
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const location = button.getAttribute('data-location');
                const rating = button.getAttribute('data-rating');
                const phone = button.getAttribute('data-phone');
                const email = button.getAttribute('data-email');
                const website = button.getAttribute('data-website');
                const about = button.getAttribute('data-about');
                const facilitiesJson = button.getAttribute('data-facilities');
                const coursesJson = button.getAttribute('data-courses');

                console.log('College ID:', id);
                console.log('College Name:', name);
                console.log('Facilities JSON:', facilitiesJson);
                console.log('Courses JSON:', coursesJson);

                // Parse location (assuming format: "street, district, state")
                let street = '', district = '', state = '';
                if (location) {
                    const locationParts = location.split(',').map(part => part.trim());
                    street = locationParts[0] || '';
                    district = locationParts[1] || '';
                    state = locationParts[2] || '';
                }

                // Populate modal fields
                document.getElementById('college_id').value = id || '';
                document.getElementById('edit_name').value = name || '';
                document.querySelector('input[name="street"]').value = street;
                document.querySelector('input[name="district"]').value = district;
                document.querySelector('input[name="state"]').value = state;
                document.getElementById('edit_rating').value = rating || '';
                document.getElementById('edit_phone').value = phone || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_website').value = website || '';
                document.getElementById('edit_about').value = about || '';

                // Clear existing facilities and courses
                clearFacilities();
                clearCourses();

                // Populate facilities
                try {
                    const facilities = JSON.parse(facilitiesJson || '[]');
                    if (facilities.length > 0) {
                        facilities.forEach(facility => {
                            addFacility(facility);
                        });
                    } else {
                        addFacility(); // Add one empty field if no facilities
                    }
                } catch (e) {
                    console.error('Error parsing facilities:', e);
                    addFacility(); // Add one empty field on error
                }

                // Populate courses
                try {
                    const courses = JSON.parse(coursesJson || '[]');
                    if (courses.length > 0) {
                        courses.forEach(course => {
                            addCourse(course);
                        });
                    } else {
                        addCourse(); // Add one empty field if no courses
                    }
                } catch (e) {
                    console.error('Error parsing courses:', e);
                    addCourse(); // Add one empty field on error
                }

                // Set form action URL
                const updateUrl = "{{ route('admin.college.update', ':id') }}".replace(':id', id);
                document.getElementById('editCollegeForm').action = updateUrl;

                console.log('Form action set to:', updateUrl);
            });

            console.log('Event listener attached');
        });
    </script>
@endpush