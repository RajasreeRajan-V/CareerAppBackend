@extends('layouts.app')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #306060;">
                        <h4 class="mb-0">Create Careers</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.career_nodes.store') }}" method="POST" enctype="multipart/form-data"
                            id="careerForm">
                            @csrf

                            {{-- BASIC INFO --}}
                            <h5 class="mb-3">Basic Information</h5>

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Video *</label>
                                <input type="file" name="video" class="form-control @error('video') is-invalid @enderror"
                                    accept="video/*" id="videoInput" required>
                                <small class="text-muted">Allowed formats: mp4, mov, avi (Max: 50MB recommended)</small>
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Thumbnail *</label>
                                <input type="file" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*"
                                    id="thumbnailInput" required>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <video id="previewVideo" width="320" controls poster="">
                                <source id="videoSource" src="" type="video/mp4">
                                Your browser does not support HTML video.
                            </video>

                            <hr class="my-4">

                            {{-- SUBJECTS --}}
                            <h5 class="mb-3">Subjects (Optional)</h5>
                            <div id="subject-wrapper">
                                @if(old('subjects'))
                                    @foreach(old('subjects') as $subject)
                                        <div class="input-group mb-2">
                                            <input type="text" name="subjects[]" class="form-control" value="{{ $subject }}"
                                                placeholder="Enter Subject" required>
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

                            <hr class="my-4">

                            {{-- CAREER OPTIONS --}}
                            <h5 class="mb-3">Career Options</h5>
                            <div id="career-wrapper">
                                @if(old('career_options'))
                                    @foreach(old('career_options') as $career)
                                        <div class="input-group mb-2">
                                            <input type="text" name="career_options[]" class="form-control" value="{{ $career }}"
                                                placeholder="Enter Career Option" required>
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

    {{-- JS --}}
    <script>
        function addSubject() {
            document.getElementById('subject-wrapper').insertAdjacentHTML('beforeend', `
                            <div class="input-group mb-2">
                                <input type="text" name="subjects[]" 
                                       class="form-control" 
                                       placeholder="Enter Subject" required>
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
        const videoInput = document.getElementById('videoInput');
        const thumbnailInput = document.getElementById('thumbnailInput');
        const previewVideo = document.getElementById('previewVideo');
        const videoSource = document.getElementById('videoSource');

        // Preview video when selected
        videoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                videoSource.src = url;
                previewVideo.load();
            }
        });

        // Set thumbnail as poster when selected
        thumbnailInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                previewVideo.setAttribute('poster', url);
            }
        });

    </script>

@endsection