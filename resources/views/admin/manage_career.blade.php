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

        <div class="row align-items-center gy-3 mb-4">
            <div class="col-12 col-md">
                <h1 class="h3 mb-0">Career Nodes</h1>
                <p class="text-muted mb-0">Manage career information</p>
            </div>

            <div class="col-12 col-md-6">
                <form method="GET" action="{{ route('admin.career_nodes.index') }}" class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Search career title, subjects or options..." autocomplete="off">
                    @if(request('search'))
                        <a href="{{ route('admin.career_nodes.index') }}" class="btn btn-outline-secondary ms-2">Clear</a>
                    @endif
                </form>
            </div>

            <div class="col-12 col-md-auto">
                <a href="{{ route('admin.career_nodes.create') }}" class="btn btn-primary w-100 w-md-auto">
                    <i class="fas fa-plus me-2"></i>Add Career
                </a>
            </div>
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
                            @include('admin.partials.career_table', compact('careerNodes', 'search'))
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
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <!-- BASIC INFO -->
                                    <h5 class="mb-3">Basic Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" name="title" id="edit_title" class="form-control"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description *</label>
                                        <textarea name="description" id="edit_description" class="form-control" rows="4" required></textarea>
                                    </div>

                                    <!-- NEWGEN COURSE -->
                                    <div class="mb-3">
                                        <label class="form-label">Newgen Course *</label>
                                        <select name="newgen_course" id="edit_newgen_course" class="form-control"
                                            required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- VIDEO -->
                                    <div class="mb-3">
                                        <label class="form-label">Video</label>
                                        <input type="text" name="video" id="edit_video" class="form-control"
                                            placeholder="https://www.youtube.com/watch?v=...">
                                        <small class="text-muted">
                                            Enter a YouTube URL OR upload a thumbnail below (at least one is required).
                                        </small>
                                    </div>

                                    <!-- THUMBNAIL -->
                                    <div class="mb-3">
                                        <label class="form-label">Thumbnail</label>

                                        <div id="thumbnailPreviewWrapper" class="mb-2" style="display:none;">
                                            <img id="thumbnailPreview" src="" alt="Thumbnail Preview"
                                                width="120" class="img-thumbnail d-block mb-1">

                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                id="removeThumbnailBtn">
                                                <i class="fas fa-times me-1"></i>Remove Thumbnail
                                            </button>
                                        </div>

                                        <input type="file" name="thumbnail" class="form-control" id="thumbnailInput"
                                            accept="image/*">
                                    </div>

                                    <!-- MEDIA ERROR -->
                                    <div id="editMediaError" class="text-danger mb-3" style="display:none;">
                                        Please provide a YouTube video URL or a thumbnail image.
                                    </div>

                                    <hr class="my-4">

                                    <!-- SUBJECTS -->
                                    <h5 class="form-label mb-3">Subjects (Optional)</h5>
                                    <div id="edit-subject-wrapper"></div>

                                    <button type="button" class="btn btn-outline-primary mb-3"
                                        onclick="editAddSubject()">
                                        Add Subject
                                    </button>

                                    <!-- SPECIALIZATION -->
                                    <div class="mb-3">
                                        <label class="form-label">Specialization</label>
                                        <input type="text" name="specialization" id="edit_specialization"
                                            class="form-control"
                                            placeholder="Example: Artificial Intelligence, Finance, Pediatrics">
                                    </div>

                                    <hr class="my-4">

                                    <!-- CAREER OPTIONS -->
                                    <h5 class="form-label mb-3">Career Options *</h5>
                                    <div id="edit-career-wrapper"></div>

                                    <button type="button" class="btn btn-outline-primary mb-3"
                                        onclick="editAddCareerOption()">
                                        Add Career Option
                                    </button>

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
        var careerData = {
            @foreach ($careerNodes as $career)
                @php
                    $subjectsArr = $career->subjects;
                    if (is_string($subjectsArr)) {
                        $dec = json_decode($subjectsArr, true);
                        $subjectsArr = json_last_error() === JSON_ERROR_NONE ? $dec : explode(',', $subjectsArr);
                    }
                    $optionsArr = $career->career_options;
                    if (is_string($optionsArr)) {
                        $dec = json_decode($optionsArr, true);
                        $optionsArr = json_last_error() === JSON_ERROR_NONE ? $dec : explode(',', $optionsArr);
                    }
                @endphp
                    "{{ $career->id }}": {
                        subjects: {!! json_encode(is_array($subjectsArr) ? array_map('trim', $subjectsArr) : []) !!},
                        options: {!! json_encode(is_array($optionsArr) ? array_map('trim', $optionsArr) : []) !!},
                        newgenCourse: "{{ $career->newgen_course ? '1' : '0' }}"
                        {{-- ADD THIS --}}
                    },
            @endforeach
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const videoModalEl = document.getElementById('videoModal');
            const youtubeFrame = document.getElementById('youtubeFrame');
            const modalTitle = document.getElementById('videoModalLabel');
            const videoModal = new bootstrap.Modal(videoModalEl);

            document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const videoId = this.dataset.videoId;
                    const videoTitle = this.dataset.videoTitle;
                    if (!videoId) return;
                    youtubeFrame.src =
                        `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1`;
                    modalTitle.textContent = videoTitle || 'Career Video';
                    videoModal.show();
                });
            });

            videoModalEl.addEventListener('hidden.bs.modal', function() {
                youtubeFrame.src = '';
            });

            document.querySelectorAll('.editCareerBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const title = this.dataset.title;
                    const description = this.dataset.description;
                    const specialization = this.dataset.specialization || '';
                    const thumbnail = this.dataset.thumbnail || '';

                    const videoThumb = this.closest('tr').querySelector('.video-thumbnail');
                    const videoId = videoThumb ? videoThumb.dataset.videoId : '';

                    const careerEntry = (typeof careerData !== 'undefined' && careerData[id]) ?
                        careerData[id] : {};
                    const subjects = careerEntry.subjects || [];
                    const options = careerEntry.options || [];

                    const newgenCourse = careerEntry.newgenCourse ?? '1';
                    const editForm = document.getElementById('editCareerForm');
                    const urlTemplate = "{{ route('admin.career_nodes.update', ':id') }}";
                    editForm.action = urlTemplate.replace(':id', id);

                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_specialization').value = specialization;
                    document.getElementById('edit_newgen_course').value = newgenCourse;

                    const editVideoEl = document.getElementById('edit_video');
                    const preview = document.getElementById('thumbnailPreview');
                    const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
                    const removeFlag = document.getElementById('remove_thumbnail');
                    const thumbInput = document.getElementById('thumbnailInput');

                    removeFlag.value = '0';
                    thumbInput.value = '';
                    thumbInput.disabled = false;
                    editVideoEl.disabled = false;

                    if (videoId) {
                        editVideoEl.value = 'https://www.youtube.com/watch?v=' + videoId;
                        preview.src = '';
                        previewWrapper.style.display = 'none';
                        thumbInput.disabled = true;
                    } else if (thumbnail) {
                        editVideoEl.value = '';
                        preview.src = thumbnail;
                        previewWrapper.style.display = 'block';
                        editVideoEl.disabled = true;
                    } else {
                        editVideoEl.value = '';
                        preview.src = '';
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

            document.getElementById('edit_video').addEventListener('input', function() {
                const thumbInput = document.getElementById('thumbnailInput');
                const preview = document.getElementById('thumbnailPreview');
                const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
                const removeFlag = document.getElementById('remove_thumbnail');

                if (this.value.trim() !== '') {
                    thumbInput.value = '';
                    thumbInput.disabled = true;
                    preview.src = '';
                    previewWrapper.style.display = 'none';
                    removeFlag.value = '0';
                    document.getElementById('removeThumbnailBtn').disabled = true;
                } else {
                    thumbInput.disabled = false;
                    document.getElementById('removeThumbnailBtn').disabled = false;
                }
            });

            document.getElementById('removeThumbnailBtn').addEventListener('click', function() {
                document.getElementById('thumbnailPreview').src = '';
                document.getElementById('thumbnailPreviewWrapper').style.display = 'none';
                document.getElementById('remove_thumbnail').value = '1';
                document.getElementById('thumbnailInput').value = '';
                document.getElementById('edit_video').disabled = false;
            });

            document.getElementById('thumbnailInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const editVideoEl = document.getElementById('edit_video');
                editVideoEl.value = '';
                editVideoEl.disabled = true;
                document.getElementById('remove_thumbnail').value = '0';

                const reader = new FileReader();
                reader.onload = event => {
                    const preview = document.getElementById('thumbnailPreview');
                    const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
                    preview.src = event.target.result;
                    previewWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('editCareerForm').addEventListener('submit', function(e) {
                const videoVal = document.getElementById('edit_video').value.trim();
                const fileCount = document.getElementById('thumbnailInput').files.length;
                const removeFlag = document.getElementById('remove_thumbnail').value;
                const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
                const hasExistingThumb = previewWrapper.style.display !== 'none' && removeFlag !== '1';
                const errorDiv = document.getElementById('editMediaError');

                document.getElementById('edit_video').disabled = false;
                document.getElementById('thumbnailInput').disabled = false;

                if (!videoVal && fileCount === 0 && !hasExistingThumb) {
                    e.preventDefault();
                    errorDiv.style.display = 'block';
                    errorDiv.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
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

        // Dynamic Search Functionality
        const searchInput = document.querySelector('input[name="search"]');
        const clearBtn = document.querySelector('a[href="{{ route("admin.career_nodes.index") }}"]');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                searchTimeout = setTimeout(() => {
                    if (query.length === 0) {
                        location.href = "{{ route('admin.career_nodes.index') }}";
                        return;
                    }
                    
                    performDynamicSearch(query);
                }, 300); // Debounce for 300ms
            });
        }

        function performDynamicSearch(query) {
            const url = new URL("{{ route('admin.career_nodes.index') }}", window.location.origin);
            url.searchParams.set('search', query);

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                
                // Get the new table body (from the full page)
                const newTableBody = newDoc.querySelector('tbody');
                const currentTableBody = document.querySelector('tbody');
                
                if (newTableBody && currentTableBody) {
                    // Replace entire tbody content
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                    
                    // Re-attach event listeners to newly added elements
                    reattachVideoListeners();
                    reattachEditListeners();
                }
            })
            .catch(error => console.error('Search error:', error));
        }

        function reattachVideoListeners() {
            const videoModalEl = document.getElementById('videoModal');
            const youtubeFrame = document.getElementById('youtubeFrame');
            const modalTitle = document.getElementById('videoModalLabel');
            const videoModal = new bootstrap.Modal(videoModalEl);

            document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
                thumbnail.removeEventListener('click', handleVideoClick);
                thumbnail.addEventListener('click', handleVideoClick);
            });

            function handleVideoClick() {
                const videoId = this.dataset.videoId;
                const videoTitle = this.dataset.videoTitle;
                if (!videoId) return;
                youtubeFrame.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1`;
                modalTitle.textContent = videoTitle || 'Career Video';
                videoModal.show();
            }
        }

        function reattachEditListeners() {
            document.querySelectorAll('.editCareerBtn').forEach(button => {
                button.removeEventListener('click', handleEditClick);
                button.addEventListener('click', handleEditClick);
            });

            function handleEditClick() {
                const id = this.dataset.id;
                const title = this.dataset.title;
                const description = this.dataset.description;
                const specialization = this.dataset.specialization || '';
                const thumbnail = this.dataset.thumbnail || '';

                const videoThumb = this.closest('tr').querySelector('.video-thumbnail');
                const videoId = videoThumb ? videoThumb.dataset.videoId : '';

                const careerEntry = (typeof careerData !== 'undefined' && careerData[id]) ? careerData[id] : {};
                const subjects = careerEntry.subjects || [];
                const options = careerEntry.options || [];

                const newgenCourse = careerEntry.newgenCourse ?? '1';
                const editForm = document.getElementById('editCareerForm');
                const urlTemplate = "{{ route('admin.career_nodes.update', ':id') }}";
                editForm.action = urlTemplate.replace(':id', id);

                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_specialization').value = specialization;
                document.getElementById('edit_newgen_course').value = newgenCourse;

                const editVideoEl = document.getElementById('edit_video');
                const preview = document.getElementById('thumbnailPreview');
                const previewWrapper = document.getElementById('thumbnailPreviewWrapper');
                const removeFlag = document.getElementById('remove_thumbnail');
                const thumbInput = document.getElementById('thumbnailInput');

                removeFlag.value = '0';
                thumbInput.value = '';
                thumbInput.disabled = false;
                editVideoEl.disabled = false;

                if (videoId) {
                    editVideoEl.value = 'https://www.youtube.com/watch?v=' + videoId;
                    preview.src = '';
                    previewWrapper.style.display = 'none';
                    thumbInput.disabled = true;
                } else if (thumbnail) {
                    editVideoEl.value = '';
                    preview.src = thumbnail;
                    previewWrapper.style.display = 'block';
                    editVideoEl.disabled = true;
                } else {
                    editVideoEl.value = '';
                    preview.src = '';
                    previewWrapper.style.display = 'none';
                }

                const subjectWrapper = document.getElementById('edit-subject-wrapper');
                subjectWrapper.innerHTML = '';
                (subjects.length ? subjects : ['']).forEach(s => editAddSubject(s));

                const careerWrapper = document.getElementById('edit-career-wrapper');
                careerWrapper.innerHTML = '';
                (options.length ? options : ['']).forEach(o => editAddCareerOption(o));

                document.getElementById('editMediaError').style.display = 'none';
            }
        }
    </script>
 <style>
    .table-newgen-label {
        position: absolute;
        top: 10px;
        left: -22px;
        font-size: 0.6rem;
        padding: 2px 28px;
        background-color: #4d4dff;
        color: white;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        transform: rotate(-45deg);
        transform-origin: center;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.25);
        white-space: nowrap;
    }
</style>
@endpush
