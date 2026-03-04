@extends('layouts.app')

@section('title', 'Career Record Videos')

@section('content')
    <div class="container-fluid px-4">

        {{-- Video Modal --}}
        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="videoModalLabel">Video</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9">
                            <iframe id="youtubeFrame" src=""
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
                <h1 class="h3 mb-0">Career Record Videos</h1>
                <p class="text-muted mb-0">Manage video records</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVideoModal">
                <i class="fas fa-plus me-2"></i>Add Video
            </button>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Creator</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($videos as $video)
                                <tr>
                                    <td>
                                        <div class="position-relative video-thumbnail"
                                            style="width:120px;height:75px;cursor:pointer;overflow:hidden;border-radius:6px;"
                                            data-video-id="{{ $video->url }}" data-video-title="{{ $video->title }}">

                                            <img src="https://img.youtube.com/vi/{{ $video->url }}/hqdefault.jpg"
                                                class="w-100 h-100" style="object-fit:cover;">

                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="fas fa-play-circle fa-3x text-white"
                                                    style="text-shadow:0 0 10px rgba(0,0,0,0.8);"></i>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle">{{ $video->title }}</td>
                                    <td class="align-middle">{{ $video->creator }}</td>
                                    <td class="align-middle">{{ $video->duration }}</td>

                                    <td class="align-middle">
                                        <div class="btn-group">

                                            {{-- Edit --}}
                                            <button type="button" class="btn btn-sm btn-outline-primary editBtn"
                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-id="{{ $video->id }}" data-title="{{ e($video->title) }}"
                                                data-about="{{ e($video->about) }}" data-url="{{ $video->url }}"
                                                data-duration="{{ $video->duration }}"
                                                data-creator="{{ $video->creator }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.RecordVideo.destroy', $video->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this video?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No videos found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $videos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Create Video Modal -->
    <div class="modal fade" id="createVideoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form method="POST" action="{{ route('admin.RecordVideo.store') }}">
                @csrf

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Career Record Video</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- About -->
                        <div class="mb-3">
                            <label class="form-label">About</label>
                            <textarea name="about" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- YouTube URL -->
                        <div class="mb-3">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="url" class="form-control" required>
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <label class="form-label">Duration (Example: 10:35)</label>
                            <input type="text" name="duration" class="form-control" required>
                        </div>

                        <!-- Creator -->
                        <div class="mb-3">
                            <label class="form-label">Creator</label>
                            <input type="text" name="creator" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Save Video
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- Edit Video Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Career Record Video</h5>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="edit_title"
                            class="form-control" required>
                    </div>

                    <!-- About -->
                    <div class="mb-3">
                        <label class="form-label">About</label>
                        <textarea name="about" id="edit_about"
                            class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- YouTube URL -->
                    <div class="mb-3">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="url" id="edit_url"
                            class="form-control" required>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="duration" id="edit_duration"
                            class="form-control" required>
                    </div>

                    <!-- Creator -->
                    <div class="mb-3">
                        <label class="form-label">Creator</label>
                        <input type="text" name="creator" id="edit_creator"
                            class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Update Video
                    </button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                </div>

            </div>
        </form>
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

            document.querySelectorAll('.video-thumbnail').forEach(function(thumbnail) {

                thumbnail.addEventListener('click', function() {

                    const videoId = this.dataset.videoId;
                    const videoTitle = this.dataset.videoTitle;

                    if (!videoId) return;

                    youtubeFrame.src =
                        "https://www.youtube.com/embed/" + videoId + "?autoplay=1";

                    modalTitle.textContent = videoTitle ?? 'Video';

                    videoModal.show();
                });

            });

            // Stop video when modal closes
            videoModalEl.addEventListener('hidden.bs.modal', function() {
                youtubeFrame.src = "";
            });

        });

        // EDIT MODAL LOGIC
document.querySelectorAll('.editBtn').forEach(function(button) {

    button.addEventListener('click', function() {

        const id = this.dataset.id;
        const title = this.dataset.title;
        const about = this.dataset.about;
        const url = this.dataset.url;
        const duration = this.dataset.duration;
        const creator = this.dataset.creator;

        // Set form action dynamically
        const updateUrlTemplate = "{{ route('admin.RecordVideo.update', ':id') }}";
        const editForm = document.getElementById('editForm');
        editForm.action = updateUrlTemplate.replace(':id', id);

        // Fill modal inputs
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_about').value = about;

        // Convert stored video ID back to full URL
        document.getElementById('edit_url').value =
            "https://www.youtube.com/watch?v=" + url;

        document.getElementById('edit_duration').value = duration;
        document.getElementById('edit_creator').value = creator;

    });

});
    </script>
@endpush
