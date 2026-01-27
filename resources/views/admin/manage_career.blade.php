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
                                        {{ is_array($career->subjects) ? implode(', ', $career->subjects) : $career->subjects }}
                                    </td>
                                    <td class="align-middle">
                                        {{ is_array($career->career_options) ? implode(', ', $career->career_options) : $career->career_options }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.career_nodes.edit', $career->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
    </script>
@endpush