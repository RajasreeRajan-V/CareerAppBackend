@extends('layouts.app')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #306060;">
                        <h4 class="mb-0">Create Careers-Course</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.career_nodes.store') }}" method="POST" enctype="multipart/form-data"
                            id="careerForm">
                            @csrf

                            {{-- BASIC INFO --}}
                            <h5 class="mb-3">Basic Information</h5>

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        <div class="mb-3">
    <label for="level" class="form-label fw-semibold">
        Academic Level <span class="text-danger">*</span>
    </label>

    <select name="level" id="level"
        class="form-select @error('level') is-invalid @enderror" required>

        <option value="" hidden {{ old('level', '') == '' ? 'selected' : '' }}>
            -- Select Academic Level --
        </option>

        <option value="0" {{ old('level') == '0' ? 'selected' : '' }}>
            School (10th)
        </option>

        <option value="1" {{ old('level') == '1' ? 'selected' : '' }}>
            Higher Secondary (+2 / 12th)
        </option>

        <option value="2" {{ old('level') == '2' ? 'selected' : '' }}>
            Undergraduate (B.Sc / B.Com / B.Tech)
        </option>

        <option value="3" {{ old('level') == '3' ? 'selected' : '' }}>
            Postgraduate (M.Sc / MBA / M.Tech)
        </option>

        <option value="4" {{ old('level') == '4' ? 'selected' : '' }}>
            Doctorate (PhD)
        </option>
    </select>

    @error('level')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <small class="text-muted">
        This defines where this career sits in the academic hierarchy.
    </small>
</div>

                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MEDIA TYPE TOGGLE --}}
                            <div class="mb-3">
                                <label class="form-label d-block">Media Type</label>
                                <div class="btn-group" role="group" id="mediaToggleGroup">
                                    <input type="radio" class="btn-check" name="media_type_toggle" id="toggleVideo"
                                        autocomplete="off" checked>
                                    <label class="btn btn-media-toggle" for="toggleVideo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                        </svg>
                                        Video URL
                                    </label>

                                    <input type="radio" class="btn-check" name="media_type_toggle" id="toggleThumbnail"
                                        autocomplete="off">
                                    <label class="btn btn-media-toggle" for="toggleThumbnail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            <path
                                                d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                        </svg>
                                        Thumbnail
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">Choose to provide a YouTube video URL or upload a
                                    thumbnail image.</small>
                            </div>

                            {{-- VIDEO INPUT --}}
                            <div class="mb-3" id="videoSection">
                                <label class="form-label">YouTube Video URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FF0000"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                        </svg>
                                    </span>
                                    <input type="text" name="video"
                                        class="form-control @error('video') is-invalid @enderror" id="videoInput"
                                        value="{{ old('video') }}" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                                @error('video')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- THUMBNAIL INPUT --}}
                            <div class="mb-3" id="thumbnailSection" style="display: none;">
                                <label class="form-label">Thumbnail Image</label>
                                <input type="file" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*"
                                    id="thumbnailInput">
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF, WebP</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Preview --}}
                                <div id="thumbnailPreview" class="mt-2" style="display: none;">
                                    <p class="text-muted small mb-1">Preview:</p>
                                    <img id="previewImg" src="#" alt="Thumbnail Preview"
                                        style="max-height: 160px; border-radius: 8px; border: 1px solid #dee2e6; object-fit: cover;">
                                </div>
                            </div>
                            <hr class="my-4">

                            {{-- NEWGEN COURSE --}}
                            <h5 class="mb-3" style="color: #0000; font-weight: bold;">
                                Course Type
                            </h5>

                            <div class="mb-3">
                                <label class="form-label d-block">Is this a NewGen Course?</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="newgen_course" id="newgenYes"
                                        value="1" {{ old('newgen_course') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newgenYes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="newgen_course" id="newgenNo"
                                        value="0" {{ old('newgen_course', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newgenNo">No</label>
                                </div>

                                @error('newgen_course')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="mediaError" class="text-danger mb-3" style="display: none;">
                                Please provide a video URL or upload a thumbnail image.
                            </div>

                            <hr class="my-4">

                            {{-- SUBJECTS --}}
                            <h5 class="form-label mb-3">Subjects (Optional)</h5>
                            <div id="subject-wrapper">
                                @if (old('subjects'))
                                    @foreach (old('subjects') as $subject)
                                        <div class="input-group mb-2">
                                            <input type="text" name="subjects[]" class="form-control"
                                                value="{{ $subject }}" placeholder="Enter Subject">
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="this.parentElement.remove()">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @error('subjects')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="btn btn-outline-primary mb-3" onclick="addSubject()">Add
                                Subject</button>

                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization"
                                    class="form-control @error('specialization') is-invalid @enderror"
                                    value="{{ old('specialization') }}"
                                    placeholder="Example: Artificial Intelligence, Finance, Pediatrics">

                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            {{-- CAREER OPTIONS --}}
                            <h5 class="form-label mb-3">Career Options</h5>
                            <div id="career-wrapper">
                                @if (old('career_options'))
                                    @foreach (old('career_options') as $career)
                                        <div class="input-group mb-2">
                                            <input type="text" name="career_options[]" class="form-control"
                                                value="{{ $career }}" placeholder="Enter Career Option" required>
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="this.parentElement.remove()">Remove</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @error('career_options')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="btn btn-outline-primary mb-4" onclick="addCareerOption()">Add
                                Career Option</button>

                            {{-- ACTION BUTTONS --}}
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('admin.career_nodes.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Custom Toggle Button Styles --}}
    <style>
        /* Unselected state */
        .btn-media-toggle {
            color: #306060;
            background-color: #ffffff;
            border: 1px solid #306060;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
        }

        /* Hover state */
        .btn-media-toggle:hover {
            color: #ffffff;
            background-color: #3d7a7a;
            border-color: #3d7a7a;
        }

        /* Selected / active state */
        .btn-check:checked+.btn-media-toggle {
            color: #ffffff;
            background-color: #306060;
            border-color: #306060;
        }

        /* Focus ring */
        .btn-check:focus+.btn-media-toggle {
            box-shadow: 0 0 0 0.25rem rgba(48, 96, 96, 0.3);
        }
    </style>

    {{-- JS --}}
    <script>
        // ── Media toggle ──────────────────────────────────────────────
        const videoSection = document.getElementById('videoSection');
        const thumbnailSection = document.getElementById('thumbnailSection');
        const videoInput = document.getElementById('videoInput');
        const thumbnailInput = document.getElementById('thumbnailInput');

        function showVideo() {
            videoSection.style.display = 'block';
            thumbnailSection.style.display = 'none';
            thumbnailInput.value = '';
            document.getElementById('thumbnailPreview').style.display = 'none';
        }

        function showThumbnail() {
            videoSection.style.display = 'none';
            thumbnailSection.style.display = 'block';
            videoInput.value = '';
        }

        document.getElementById('toggleVideo').addEventListener('change', showVideo);
        document.getElementById('toggleThumbnail').addEventListener('change', showThumbnail);

        // ── Thumbnail live preview ─────────────────────────────────────
        thumbnailInput.addEventListener('change', function() {
            const preview = document.getElementById('thumbnailPreview');
            const previewImg = document.getElementById('previewImg');

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });

        // ── Subjects & Career Options ──────────────────────────────────
        function addSubject() {
            document.getElementById('subject-wrapper').insertAdjacentHTML('beforeend', `
                <div class="input-group mb-2">
                    <input type="text" name="subjects[]"
                           class="form-control"
                           placeholder="Enter Subject">
                    <button type="button"
                            class="btn btn-outline-danger"
                            onclick="this.parentElement.remove()">Remove</button>
                </div>
            `);
        }

        function addCareerOption() {
            document.getElementById('career-wrapper').insertAdjacentHTML('beforeend', `
                <div class="input-group mb-2">
                    <input type="text" name="career_options[]"
                           class="form-control"
                           placeholder="Enter Career Option" required>
                    <button type="button"
                            class="btn btn-outline-danger"
                            onclick="this.parentElement.remove()">Remove</button>
                </div>
            `);
        }

        document.addEventListener('DOMContentLoaded', () => {
            addSubject();
            addCareerOption();
        });

        // ── Form submit validation ─────────────────────────────────────
        document.getElementById('careerForm').addEventListener('submit', function(e) {
            const isVideo = document.getElementById('toggleVideo').checked;
            const videoVal = videoInput.value.trim();
            const thumbFiles = thumbnailInput.files.length;
            const errorDiv = document.getElementById('mediaError');

            if ((isVideo && videoVal === '') || (!isVideo && thumbFiles === 0)) {
                e.preventDefault();
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    </script>

@endsection
