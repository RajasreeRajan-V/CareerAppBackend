@extends('layouts.app')

@section('title', 'Career Nodes')

@section('content')
    <div class="container-fluid px-4">
        {{-- Video Modal --}}
        <div class="modal fade" id="videoModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="videoModalLabel">Career Video</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe id="youtubeFrame" src="" title="YouTube video"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                </div>
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
                                        @if ($career->video)
                                            @php
                                                // Extract YouTube video ID from URL or use as-is if already an ID
                                                $videoUrl = $career->video;
                                                preg_match(
                                                    '/(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([^&#]+)/',
                                                    $videoUrl,
                                                    $matches,
                                                );
                                                $videoId = $matches[1] ?? $videoUrl; // fallback to raw value if already an ID
                                            @endphp

                                            <div class="position-relative video-thumbnail"
                                                style="width: 120px; height: 75px; cursor: pointer; overflow: hidden; border-radius: 6px;"
                                                data-video-id="{{ $videoId }}" data-video-title="{{ $career->title }}">

                                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                                    class="w-100 h-100" style="object-fit: cover;">

                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-3x text-white"
                                                        style="text-shadow: 0 0 10px rgba(0,0,0,0.8); opacity: 0.9;"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $career->title }}</td>
                                    <td class="align-middle">
                                        @php
                                            $subjects = $career->subjects;

                                            if (is_string($subjects)) {
                                                $decoded = json_decode($subjects, true);
                                                $subjects =
                                                    json_last_error() === JSON_ERROR_NONE
                                                        ? $decoded
                                                        : explode(',', $subjects);
                                            }
                                        @endphp

                                        @if (is_array($subjects) && count($subjects))
                                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                                @foreach ($subjects as $subject)
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
                                                $options =
                                                    json_last_error() === JSON_ERROR_NONE
                                                        ? $decoded
                                                        : explode(',', $options);
                                            }
                                        @endphp

                                        @if (is_array($options) && count($options))
                                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                                @foreach ($options as $option)
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
                                                data-id="{{ $career->id }}" data-title="{{ e($career->title) }}"
                                                data-description="{{ e($career->description) }}"
                                                data-subjects="{{ is_array($subjects) ? implode(', ', $subjects) : '' }}"
                                                data-options="{{ is_array($options) ? implode(', ', $options) : '' }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            {{-- Delete --}}
                                            <form action="{{ route('admin.career_nodes.destroy', $career->id) }}"
                                                method="POST" class="d-inline"
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
                    <div class="d-flex justify-content-center mt-3">
                        {{ $colleges->links() }}
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
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    {{-- Title --}}
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" id="edit_title" class="form-control" required>
                                    </div>

                                    {{-- Description --}}
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                                    </div>

                                    {{-- Subjects --}}
                                    <div class="mb-3">
                                        <label class="form-label">Subjects (comma separated)</label>
                                        <input type="text" id="edit_subjects" class="form-control"
                                            placeholder="Maths, Physics, Chemistry">
                                        {{-- No name here intentionally — JS handles it --}}
                                    </div>

                                    {{-- Career Options --}}
                                    <div class="mb-3">
                                        <label class="form-label">Career Options (comma separated)</label>
                                        <input type="text" id="edit_options" class="form-control"
                                            placeholder="Engineer, Teacher, Scientist">
                                    </div>

                                    {{-- Hidden Array Fields --}}
                                    <div id="hidden-fields"></div>

                                    {{-- Video URL --}}
                                    <div class="mb-3">
                                        <label class="form-label">YouTube Video URL</label>
                                        <input type="text" name="video" id="edit_video" class="form-control"
                                            required>
                                    </div>

                                    {{-- Thumbnail --}}
                                    {{-- <div class="mb-3">
                                        <label class="form-label">Change Thumbnail</label>
                                        <input type="file" name="thumbnail" class="form-control">
                                    </div> --}}

                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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
        document.addEventListener('DOMContentLoaded', function() {

            const videoModalEl = document.getElementById('videoModal');
            const youtubeFrame = document.getElementById('youtubeFrame');
            const modalTitle = document.getElementById('videoModalLabel');
            const videoModal = new bootstrap.Modal(videoModalEl);

            function extractVideoId(url) {
                const regExp = /(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([^&#]+)/;
                const match = url.match(regExp);
                return match ? match[1] : url;
            }

            document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const videoId = this.dataset.videoId;
                    const videoTitle = this.dataset.videoTitle;

                    if (!videoId) return;

                    const cleanId = extractVideoId(videoId);
                    youtubeFrame.src = `https://www.youtube.com/embed/${cleanId}?autoplay=1`;
                    modalTitle.textContent = videoTitle || 'Career Video';

                    videoModal.show();
                });
            });

            videoModalEl.addEventListener('hidden.bs.modal', function() {
                youtubeFrame.src = "";
            });

            // Edit modal logic
            document.querySelectorAll('.editCareerBtn').forEach(button => {
                button.addEventListener('click', function() {

                    const id = this.dataset.id;
                    const title = this.dataset.title;
                    const description = this.dataset.description;
                    const subjects = this.dataset.subjects;
                    const options = this.dataset.options;
                    const videoId = this.closest('tr')
                        .querySelector('.video-thumbnail')?.dataset.videoId;

                    const editForm = document.getElementById('editCareerForm');
                    const updateUrlTemplate = "{{ route('admin.career_nodes.update', ':id') }}";
                    editForm.action = updateUrlTemplate.replace(':id', id);

                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_subjects').value = subjects ?? '';
                    document.getElementById('edit_options').value = options ?? '';

                    if (videoId) {
                        document.getElementById('edit_video').value =
                            "https://www.youtube.com/watch?v=" + videoId;
                    }
                });
            });


            // Convert comma separated to array before submit
            document.getElementById('editCareerForm').addEventListener('submit', function() {

                let hiddenFields = document.getElementById('hidden-fields');
                hiddenFields.innerHTML = '';

                // Subjects
                let subjects = document.getElementById('edit_subjects').value.split(',');
                subjects.forEach(function(subject) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'subjects[]';
                    input.value = subject.trim();
                    hiddenFields.appendChild(input);
                });

                // Career Options
                let options = document.getElementById('edit_options').value.split(',');
                options.forEach(function(option) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'career_options[]';
                    input.value = option.trim();
                    hiddenFields.appendChild(input);
                });
            });

        });
    </script>
@endpush
