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
                                                    <li>{{ $facility->name }}</li>
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
                                            <button class="btn btn-sm btn-warning editBtn" data-bs-toggle="modal"
                                                data-bs-target="#editCollegeModal" data-id="{{ $college->id }}"
                                                data-name="{{ $college->name }}" data-location="{{ $college->location }}"
                                                data-rating="{{ $college->rating }}" data-phone="{{ $college->phone }}"
                                                data-email="{{ $college->email }}" data-website="{{ $college->website }}"
                                                data-about="{{ $college->about }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.college.destroy', $college->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    Delete
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
<div class="modal fade" id="editCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="editCollegeForm">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="college_id">

                    <div class="mb-3">
                        <label>College Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" id="edit_location" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Rating</label>
                        <input type="number" step="0.1" max="5" name="rating" id="edit_rating" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Website</label>
                        <input type="url" name="website" id="edit_website" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>About</label>
                        <textarea name="about" id="edit_about" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_location').value = this.dataset.location;
            document.getElementById('edit_rating').value = this.dataset.rating;
            document.getElementById('edit_phone').value = this.dataset.phone;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_website').value = this.dataset.website;
            document.getElementById('edit_about').value = this.dataset.about;

            document.getElementById('editCollegeForm').action =
                "{{ route('admin.college.update', ':id') }}".replace(':id', id);
        });
    });
});
</script>

@endsection