@extends('layouts.app')

@section('title', 'Career Guidance Banners')

@section('content')
    <div class="container-fluid px-4">

        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Career Guidance Banners</h1>
                <p class="text-muted mb-0">Manage career guidance events</p>
            </div>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
                <i class="fas fa-plus me-2"></i>Add Banner
            </button>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Event Name</th>
                                <th>Instructor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Meet Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($guidanceBanners as $banner)
                                <tr>

                                    <!-- Image -->
                                    <td>
                                        @if ($banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" width="80"
                                                height="60" style="object-fit:cover;border-radius:6px;">
                                        @endif
                                    </td>

                                    <td>{{ $banner->title }}</td>
                                    <td>{{ $banner->name }}</td>
                                    <td>{{ $banner->instructor_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($banner->event_date)->format('d M Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($banner->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($banner->end_time)->format('h:i A') }}
                                    </td>

                                    <!-- Meet Link -->
                                    <td>
                                        <a href="{{ 'https://meet.google.com/' . $banner->google_meet_link }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="btn btn-sm btn-outline-success">
                                            Join
                                        </a>
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="btn-group">

                                            <!-- Edit -->
                                            <button class="btn btn-sm btn-outline-primary editBtn" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-id="{{ $banner->id }}"
                                                data-title="{{ e($banner->title) }}" data-name="{{ e($banner->name) }}"
                                                data-instructor_name="{{ e($banner->instructor_name) }}"
                                                data-description="{{ e($banner->description) }}"
                                                data-event_date="{{ $banner->event_date }}"
                                                data-start_time="{{ $banner->start_time }}"
                                                data-end_time="{{ $banner->end_time }}"
                                                data-google_meet_link="{{ e($banner->google_meet_link) }}"
                                                data-image="{{ $banner->image ? asset('storage/' . $banner->image) : '' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.guidance_banners.destroy', $banner->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this banner?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No banners found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guidanceBanners->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- ===========================
                                CREATE MODAL (With Validation)
                            =========================== -->
    <div class="modal fade" id="createBannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form method="POST" action="{{ route('admin.guidance_banners.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Career Guidance Banner</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Row 1 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instructor Name</label>
                                <input type="text" name="instructor_name" value="{{ old('instructor_name') }}"
                                    class="form-control @error('instructor_name') is-invalid @enderror">

                                @error('instructor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Date</label>
                                <input type="date" name="event_date" value="{{ old('event_date') }}"
                                    class="form-control @error('event_date') is-invalid @enderror">

                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}"
                                    class="form-control @error('start_time') is-invalid @enderror">

                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" value="{{ old('end_time') }}"
                                    class="form-control @error('end_time') is-invalid @enderror">

                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Google Meet Link</label>
                                <input type="url" name="google_meet_link" value="{{ old('google_meet_link') }}"
                                    class="form-control @error('google_meet_link') is-invalid @enderror">

                                @error('google_meet_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Banner Image</label>
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 5 -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Save Banner
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- ===========================
                                EDIT MODAL
                            =========================== -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Career Guidance Banner</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Event Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Instructor Name</label>
                            <input type="text" name="instructor_name" id="edit_instructor_name" class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Event Date</label>
                            <input type="date" name="event_date" id="edit_event_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Google Meet Link</label>
                            <input type="text" name="google_meet_link" id="edit_google_meet_link"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Current Banner Image</label>
                            <div>
                                <img id="edit_image_preview" src="" width="120"
                                    style="border-radius:6px; display:none; object-fit:cover;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Change Banner Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty if you don't want to change image</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Update Banner
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(button) {

            button.addEventListener('click', function() {

                const id = this.dataset.id;

                const updateUrlTemplate = "{{ route('admin.guidance_banners.update', ':id') }}";
                document.getElementById('editForm').action =
                    updateUrlTemplate.replace(':id', id);

                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_instructor_name').value = this.dataset.instructor_name;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_event_date').value = this.dataset.event_date;
                document.getElementById('edit_start_time').value = this.dataset.start_time;
                document.getElementById('edit_end_time').value = this.dataset.end_time;

                console.log(this.dataset.google_meet_link);

                document.getElementById('edit_google_meet_link').value =
                    this.dataset.google_meet_link;

                // Image preview
                const image = this.dataset.image;
                const preview = document.getElementById('edit_image_preview');

                if (image) {
                    preview.src = image;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }

            });

        });

        @if ($errors->any())
            var createModal = new bootstrap.Modal(document.getElementById('createBannerModal'));
            createModal.show();
        @endif

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        @endif
    </script>
@endpush
