@extends('layouts.app')

@section('title', 'Career Nodes')

@section('content')
    <div class="container-fluid px-4">
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Career Nodes</h1>
                <p class="text-muted mb-0">Manage career information</p>
            </div>
            <a href="{{ route('admin.career_nodes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Career
            </a>
        </div>

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
                                            @php $videoId = $career->video; @endphp
                                            <div class="position-relative video-thumbnail"
                                                style="width:120px;height:75px;cursor:pointer;overflow:hidden;border-radius:6px;"
                                                data-video-id="{{ $videoId }}" data-video-title="{{ $career->title }}">
                                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                                    class="w-100 h-100" style="object-fit:cover;">
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-3x text-white"
                                                        style="text-shadow:0 0 10px rgba(0,0,0,0.8);opacity:0.9;"></i>
                                                </div>
                                            </div>
                                        @elseif ($career->thumbnail)
                                            <img src="{{ asset('storage/' . $career->thumbnail) }}"
                                                style="width:120px;height:75px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="align-middle">{{ $career->title }}</td>

                                    <td class="align-middle">
                                        @php
                                            $subjects = $career->subjects;
                                            if (is_string($subjects)) {
                                                $decoded  = json_decode($subjects, true);
                                                $subjects = json_last_error() === JSON_ERROR_NONE ? $decoded : explode(',', $subjects);
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
                                                $options = json_last_error() === JSON_ERROR_NONE ? $decoded : explode(',', $options);
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
                                            <button type="button" class="btn btn-sm btn-outline-primary editCareerBtn"
                                                data-bs-toggle="modal" data-bs-target="#editCareerModal"
                                                data-id="{{ $career->id }}"
                                                data-title="{{ e($career->title) }}"
                                                data-description="{{ e($career->description) }}"
                                                data-specialization="{{ e($career->specialization) }}"
                                                data-thumbnail="{{ $career->thumbnail ? asset('storage/' . $career->thumbnail) : '' }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

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
                        {{ $careerNodes->links() }}
                    </div>
                </div>

                <div class="modal fade" id="editCareerModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form method="POST" id="editCareerForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">

                            <div class="modal-content">
                                <div class="modal-header text-white" style="background-color: #306060;">
                                    <h5 class="modal-title">Edit Career</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <h5 class="mb-3">Basic Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" name="title" id="edit_title" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description *</label>
                                        <textarea name="description" id="edit_description" class="form-control" rows="4" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Video</label>
                                        <input type="text" name="video" id="edit_video" class="form-control"
                                            placeholder="https://www.youtube.com/watch?v=...">
                                        <small class="text-muted">Enter a YouTube URL OR upload a thumbnail below (not both).</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thumbnail</label>
                                        <div id="thumbnailPreviewWrapper" class="mb-2" style="display:none;">
                                            <img id="thumbnailPreview" src="" alt="Thumbnail Preview"
                                                width="120" class="img-thumbnail d-block mb-1">
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeThumbnailBtn">
                                                <i class="fas fa-times me-1"></i>Remove Thumbnail
                                            </button>
                                        </div>
                                        <input type="file" name="thumbnail" class="form-control" id="thumbnailInput" accept="image/*">
                                    </div>

                                    <div id="editMediaError" class="text-danger mb-3" style="display:none;">
                                        Please provide either a YouTube video URL or a thumbnail image.
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="form-label mb-3">Subjects (Optional)</h5>
                                    <div id="edit-subject-wrapper"></div>
                                    <button type="button" class="btn btn-outline-primary mb-3" onclick="editAddSubject()">Add Subject</button>

                                    <div class="mb-3">
                                        <label class="form-label">Specialization</label>
                                        <input type="text" name="specialization" id="edit_specialization"
                                            class="form-control"
                                            placeholder="Example: Artificial Intelligence, Finance, Pediatrics">
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="form-label mb-3">Career Options</h5>
                                    <div id="edit-career-wrapper"></div>
                                    <button type="button" class="btn btn-outline-primary mb-3" onclick="editAddCareerOption()">Add Career Option</button>
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
    var careerData = {
        @foreach($careerNodes as $career)
        @php
            $subjectsArr = $career->subjects;
            if (is_string($subjectsArr)) {
                $dec = json_decode($subjectsArr, true);
                $subjectsArr = (json_last_error() === JSON_ERROR_NONE) ? $dec : explode(',', $subjectsArr);
            }
            $optionsArr = $career->career_options;
            if (is_string($optionsArr)) {
                $dec = json_decode($optionsArr, true);
                $optionsArr = (json_last_error() === JSON_ERROR_NONE) ? $dec : explode(',', $optionsArr);
            }
        @endphp
        "{{ $career->id }}": {
            subjects: {!! json_encode(is_array($subjectsArr) ? array_map('trim', $subjectsArr) : []) !!},
            options:  {!! json_encode(is_array($optionsArr)  ? array_map('trim', $optionsArr)  : []) !!}
        },
        @endforeach
    };
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const videoModalEl = document.getElementById('videoModal');
    const youtubeFrame = document.getElementById('youtubeFrame');
    const modalTitle   = document.getElementById('videoModalLabel');
    const videoModal   = new bootstrap.Modal(videoModalEl);

    document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            const videoId    = this.dataset.videoId;
            const videoTitle = this.dataset.videoTitle;
            if (!videoId) return;
            youtubeFrame.src       = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1`;
            modalTitle.textContent = videoTitle || 'Career Video';
            videoModal.show();
        });
    });

    videoModalEl.addEventListener('hidden.bs.modal', function () {
        youtubeFrame.src = '';
    });

    document.querySelectorAll('.editCareerBtn').forEach(button => {
        button.addEventListener('click', function () {
            const id             = this.dataset.id;
            const title          = this.dataset.title;
            const description    = this.dataset.description;
            const specialization = this.dataset.specialization || '';
            const thumbnail      = this.dataset.thumbnail || '';
            const videoThumb     = this.closest('tr').querySelector('.video-thumbnail');
            const videoId        = videoThumb ? videoThumb.dataset.videoId : '';

            const careerEntry = (typeof careerData !== 'undefined' && careerData[id]) ? careerData[id] : {};
            const subjects    = careerEntry.subjects || [];
            const options     = careerEntry.options  || [];

            const editForm    = document.getElementById('editCareerForm');
            const urlTemplate = "{{ route('admin.career_nodes.update', ':id') }}";
            editForm.action   = urlTemplate.replace(':id', id);

            document.getElementById('edit_title').value          = title;
            document.getElementById('edit_description').value    = description;
            document.getElementById('edit_specialization').value = specialization;

            const editVideoEl    = document.getElementById('edit_video');
            const preview        = document.getElementById('thumbnailPreview');
            const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
            const removeFlag     = document.getElementById('remove_thumbnail');
            const thumbInput     = document.getElementById('thumbnailInput');

            removeFlag.value  = '0';
            thumbInput.value  = '';
            thumbInput.disabled  = false;
            editVideoEl.disabled = false;

            if (videoId) {
                editVideoEl.value            = 'https://www.youtube.com/watch?v=' + videoId;
                preview.src                  = '';
                previewWrapper.style.display = 'none';
                thumbInput.disabled          = true;
            } else if (thumbnail) {
                editVideoEl.value            = '';
                preview.src                  = thumbnail;
                previewWrapper.style.display = 'block';
                editVideoEl.disabled         = true;
            } else {
                editVideoEl.value            = '';
                preview.src                  = '';
                previewWrapper.style.display = 'none';
            }

            const subjectWrapper = document.getElementById('edit-subject-wrapper');
            subjectWrapper.innerHTML = '';
            (subjects.length ? subjects : ['']).forEach(s => editAddSubject(s));

            const careerWrapper = document.getElementById('edit-career-wrapper');
            careerWrapper.innerHTML = '';
            (options.length ? options : ['']).forEach(o => editAddCareerOption(o));

            document.getElementById('editMediaError').style.display = 'none';
        });
    });

    document.getElementById('edit_video').addEventListener('input', function () {
        const thumbInput     = document.getElementById('thumbnailInput');
        const preview        = document.getElementById('thumbnailPreview');
        const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
        const removeFlag     = document.getElementById('remove_thumbnail');

        if (this.value.trim() !== '') {
            thumbInput.value             = '';
            thumbInput.disabled          = true;
            preview.src                  = '';
            previewWrapper.style.display = 'none';
            removeFlag.value             = '0';
            document.getElementById('removeThumbnailBtn').disabled = true;
        } else {
            thumbInput.disabled = false;
            document.getElementById('removeThumbnailBtn').disabled = false;
        }
    });

    document.getElementById('removeThumbnailBtn').addEventListener('click', function () {
        document.getElementById('thumbnailPreview').src              = '';
        document.getElementById('thumbnailPreviewWrapper').style.display = 'none';
        document.getElementById('remove_thumbnail').value            = '1';
        document.getElementById('thumbnailInput').value              = '';
        document.getElementById('edit_video').disabled               = false;
    });

    document.getElementById('thumbnailInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const editVideoEl = document.getElementById('edit_video');
        editVideoEl.value    = '';
        editVideoEl.disabled = true;
        document.getElementById('remove_thumbnail').value = '0';

        const reader = new FileReader();
        reader.onload = event => {
            const preview        = document.getElementById('thumbnailPreview');
            const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
            preview.src                  = event.target.result;
            previewWrapper.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('editCareerForm').addEventListener('submit', function (e) {
        const videoVal         = document.getElementById('edit_video').value.trim();
        const fileCount        = document.getElementById('thumbnailInput').files.length;
        const removeFlag       = document.getElementById('remove_thumbnail').value;
        const previewWrapper   = document.getElementById('thumbnailPreviewWrapper');
        const hasExistingThumb = previewWrapper.style.display !== 'none' && removeFlag !== '1';
        const errorDiv         = document.getElementById('editMediaError');

        document.getElementById('edit_video').disabled      = false;
        document.getElementById('thumbnailInput').disabled  = false;

        if (!videoVal && fileCount === 0 && !hasExistingThumb) {
            e.preventDefault();
            errorDiv.style.display = 'block';
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            errorDiv.style.display = 'none';
        }
    });
});

function editAddSubject(value = '') {
    document.getElementById('edit-subject-wrapper').insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="subjects[]" class="form-control"
                   value="${escapeHtml(value)}" placeholder="Enter Subject">
            <button type="button" class="btn btn-outline-danger"
                    onclick="this.parentElement.remove()">Remove</button>
        </div>
    `);
}

function editAddCareerOption(value = '') {
    document.getElementById('edit-career-wrapper').insertAdjacentHTML('beforeend', `
        <div class="input-group mb-2">
            <input type="text" name="career_options[]" class="form-control"
                   value="${escapeHtml(value)}" placeholder="Enter Career Option">
            <button type="button" class="btn btn-outline-danger"
                    onclick="this.parentElement.remove()">Remove</button>
        </div>
    `);
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str)));
    return d.innerHTML;
}
</script>
@endpush