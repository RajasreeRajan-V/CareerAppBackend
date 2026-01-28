@extends('layouts.app')

@section('title', 'Career Nodes')

@section('content')
    <div class="container-fluid px-4">
        {{-- Video Modal --}}
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-white" id="videoModalLabel">Career Video</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <video id="modalVideo" class="w-100" controls style="max-height: 80vh;">
                            <source id="modalVideoSource" src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                </div>
            </div>
        </div>

        {{-- Edit Career Modal --}}
        <div class="modal fade" id="editCareerModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <form method="POST" id="editCareerForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Edit Career</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subjects (comma separated)</label>
                                <input type="text" name="subjects" id="edit_subjects" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Career Options (comma separated)</label>
                                <input type="text" name="career_options" id="edit_options" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Change Video</label>
                                <input type="file" name="video" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Change Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Page Heading --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Career Nodes</h1>
                <p class="text-muted mb-0">Manage career information</p>
            </div>
            <a href="{{ route('admin.career_nodes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Career
            </a>
        </div>

        {{-- Career Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Video Preview</th>
                                <th>Title</th>
                                <th>Subjects</th>
                                <th>Career Options</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($careerNodes as $career)
                                <tr>
                                    <td>
                                        @if($career->video)
                                            <div class="position-relative video-thumbnail"
                                                style="width: 100px; height: 75px; cursor: pointer; overflow: hidden; border-radius: 4px;"
                                                data-video-url="{{ asset('storage/' . $career->video) }}"
                                                data-video-title="{{ $career->title }}">
                                                <video class="w-100 h-100" style="object-fit: cover;" muted preload="metadata"
                                                    poster="{{ asset('storage/' . $career->thumbnail ?? 'default-thumb.jpg') }}">
                                                    <source src="{{ asset('storage/' . $career->video) }}" type="video/mp4">
                                                </video>

                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-3x text-white"
                                                        style="text-shadow: 0 0 10px rgba(0,0,0,0.8); opacity: 0.9;"></i>
                                                </div>
                                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1"
                                                    style="font-size: 0.7rem;">
                                                    Click to play
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $career->title }}</td>
                                    <td class="align-middle">
                                        @php
                                            $subjects = $career->subjects;

                                            if (is_string($subjects)) {
                                                $decoded = json_decode($subjects, true);
                                                $subjects = json_last_error() === JSON_ERROR_NONE
                                                    ? $decoded
                                                    : explode(',', $subjects);
                                            }
                                        @endphp

                                        @if(is_array($subjects) && count($subjects))
                                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                                @foreach($subjects as $subject)
                                                    <li>{{ trim($subject) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @php
                                            $options = $career->career_options;

                                            if (is_string($options)) {
                                                $decoded = json_decode($options, true);
                                                $options = json_last_error() === JSON_ERROR_NONE
                                                    ? $decoded
                                                    : explode(',', $options);
                                            }
                                        @endphp

                                        @if(is_array($options) && count($options))
                                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                                @foreach($options as $option)
                                                    <li class="mb-1">{{ trim($option) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            {{-- Edit --}}
                                            @php
                                            // Decode subjects
                                            $subjects = $career->subjects;
                                            if (is_string($subjects)) {
                                                $decodedSubjects = json_decode($subjects, true);
                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                    $subjects = $decodedSubjects;
                                                } else {
                                                    $subjects = explode(',', $subjects);
                                                }
                                            }

                                            // Decode options
                                            $options = $career->career_options;
                                            if (is_string($options)) {
                                                $decodedOptions = json_decode($options, true);
                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                    $options = $decodedOptions;
                                                } else {
                                                    $options = explode(',', $options);
                                                }
                                            }
                                        @endphp

                                        <button type="button" class="btn btn-sm btn-outline-primary editCareerBtn"
                                            data-bs-toggle="modal" data-bs-target="#editCareerModal"
                                            data-id="{{ $career->id }}"
                                            data-title="{{ e($career->title) }}"
                                            data-description="{{ e($career->description) }}"
                                            data-subjects="{{ is_array($subjects) ? implode(', ', $subjects) : $subjects }}"
                                            data-options="{{ is_array($options) ? implode(', ', $options) : $options }}"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                            {{-- Delete --}}
                                            <form action="{{ route('admin.career_nodes.destroy', $career->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this career?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-4x mb-3 d-block opacity-50"></i>
                                        <p class="mb-0">No careers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Edit Career Modal --}}
                <div class="modal fade" id="editCareerModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form method="POST" id="editCareerForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Edit Career</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" id="edit_title" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" id="edit_description" class="form-control"
                                            rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Subjects (comma separated)</label>
                                        <input type="text" name="subjects" id="edit_subjects" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Career Options (comma separated)</label>
                                        <input type="text" name="career_options" id="edit_options" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Change Video</label>
                                        <input type="file" name="video" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Change Thumbnail</label>
                                        <input type="file" name="thumbnail" class="form-control">
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const videoModalEl = document.getElementById('videoModal');
            const modalVideo = document.getElementById('modalVideo');
            const modalVideoSource = document.getElementById('modalVideoSource');
            const modalTitle = document.getElementById('videoModalLabel');

            const videoModal = new bootstrap.Modal(videoModalEl);

            // Handle thumbnail click
            document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
                thumbnail.addEventListener('click', function () {

                    const videoUrl = this.dataset.videoUrl;
                    const videoTitle = this.dataset.videoTitle;

                    if (!videoUrl) return;

                    // Set video source
                    modalVideoSource.src = videoUrl;
                    modalTitle.textContent = videoTitle || 'Career Video';

                    modalVideo.load();
                    videoModal.show();
                });
            });

            // Auto play when modal opens
            videoModalEl.addEventListener('shown.bs.modal', function () {
                modalVideo.play().catch(() => { });
            });

            // Stop video when modal closes
            videoModalEl.addEventListener('hidden.bs.modal', function () {
                modalVideo.pause();
                modalVideo.currentTime = 0;
                modalVideoSource.src = "";
                modalVideo.load();
            });

        });

        document.querySelectorAll('.editCareerBtn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const title = this.dataset.title;
                const description = this.dataset.description;
                const subjects = this.dataset.subjects; 
                const options = this.dataset.options;   

                const editForm = document.getElementById('editCareerForm');
                const updateUrlTemplate = "{{ route('admin.career_nodes.update', ':id') }}";
                editForm.action = updateUrlTemplate.replace(':id', id);

                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_subjects').value = subjects ?? '';
                document.getElementById('edit_options').value = options ?? '';
            });
        });


    </script>
@endpush