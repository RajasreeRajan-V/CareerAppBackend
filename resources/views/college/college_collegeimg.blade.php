@extends('college.layout.app')

@push('styles')
    <style>
        .img-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

        .img-header-left h2 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1e2d3d;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .img-header-left h2 .icon-badge {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #306060, #254848);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .88rem;
            flex-shrink: 0;
        }

        .img-header-left p {
            margin: 0;
            font-size: .82rem;
            color: #8a97a6;
        }

        .img-count-badge {
            background: #eaf3f3;
            color: #306060;
            font-size: .75rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: .3px;
        }

        .img-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 11px;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: 18px;
        }

        .img-alert.success {
            background: #eaf7f0;
            color: #1a7f4b;
            border: 1px solid #c3e9d7;
        }

        .img-alert.error {
            background: #fff5f5;
            color: #c0392b;
            border: 1px solid #fde0dd;
        }

        .upload-card {
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid #e4e9ee;
            padding: 22px 24px;
            margin-bottom: 28px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .upload-card .card-label {
            font-size: .78rem;
            font-weight: 700;
            color: #8a97a6;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 14px;
        }

        .upload-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .file-input-wrap {
            flex: 1;
            min-width: 220px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border: 1.5px dashed #c8d4de;
            border-radius: 10px;
            background: #f7f9fb;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            font-size: .85rem;
            color: #8a97a6;
            font-weight: 500;
        }

        .file-input-label:hover {
            border-color: #306060;
            background: #f0f7f7;
            color: #306060;
        }

        .file-input-label i {
            font-size: .88rem;
            color: #306060;
        }

        .file-input-label input[type="file"] {
            display: none;
        }

        .file-name-display {
            font-size: .8rem;
            color: #306060;
            margin-top: 6px;
            padding-left: 4px;
            min-height: 18px;
            font-weight: 600;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #306060, #254848);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity .18s, transform .15s, box-shadow .18s;
            box-shadow: 0 4px 14px rgba(48, 96, 96, .28);
        }

        .btn-upload:hover {
            opacity: .9;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(48, 96, 96, .34);
        }

        .btn-upload:active {
            transform: translateY(0);
        }

        .img-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .img-card {
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid #e4e9ee;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            transition: box-shadow .2s, transform .2s;
            display: flex;
            flex-direction: column;
        }

        .img-card:hover {
            box-shadow: 0 6px 24px rgba(48, 96, 96, .12);
            transform: translateY(-2px);
        }

        .img-thumb-wrap {
            position: relative;
            width: 100%;
            padding-top: 65%;
            overflow: hidden;
            background: #f0f4f7;
        }

        .img-thumb-wrap img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .img-card:hover .img-thumb-wrap img {
            transform: scale(1.04);
        }

        .img-thumb-wrap .img-overlay {
            position: absolute;
            inset: 0;
            background: rgba(30, 45, 61, .45);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .22s;
        }

        .img-card:hover .img-overlay {
            opacity: 1;
        }

        .img-overlay a {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, .18);
            border: 1.5px solid rgba(255, 255, 255, .5);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .9rem;
            text-decoration: none;
            backdrop-filter: blur(4px);
            transition: background .15s;
        }

        .img-overlay a:hover {
            background: rgba(255, 255, 255, .32);
        }

        .img-card-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .update-file-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1.5px dashed #c8d4de;
            border-radius: 9px;
            background: #f7f9fb;
            cursor: pointer;
            font-size: .78rem;
            color: #8a97a6;
            font-weight: 500;
            transition: border-color .15s, background .15s, color .15s;
        }

        .update-file-label:hover {
            border-color: #306060;
            background: #f0f7f7;
            color: #306060;
        }

        .update-file-label i {
            font-size: .8rem;
            color: #306060;
            flex-shrink: 0;
        }

        .update-file-label input[type="file"] {
            display: none;
        }

        .update-file-name {
            font-size: .72rem;
            color: #306060;
            font-weight: 600;
            min-height: 14px;
            padding-left: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .img-actions {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .btn-update {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 10px;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid #306060;
            color: #306060;
            background: transparent;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }

        .btn-update:hover {
            background: #306060;
            color: #fff;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid #fde0dd;
            color: #c0392b;
            background: #fff5f5;
            transition: background .15s, color .15s, border-color .15s;
            white-space: nowrap;
        }

        .btn-delete:hover {
            background: #c0392b;
            color: #fff;
            border-color: #c0392b;
        }

        .img-empty {
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid #e4e9ee;
            padding: 60px 24px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .img-empty .empty-icon {
            width: 64px;
            height: 64px;
            background: #eaf3f3;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #306060;
            margin-bottom: 14px;
        }

        .img-empty h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e2d3d;
            margin: 0 0 6px;
        }

        .img-empty p {
            font-size: .83rem;
            color: #8a97a6;
            margin: 0;
        }

        .lightbox-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(10, 18, 28, .88);
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity .22s ease, visibility .22s ease;
        }

        .lightbox-backdrop.open {
            opacity: 1;
            visibility: visible;
        }

        .lightbox-img {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 12px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .6);
            object-fit: contain;
            transform: scale(.92);
            transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
        }

        .lightbox-backdrop.open .lightbox-img {
            transform: scale(1);
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, .12);
            border: 1.5px solid rgba(255, 255, 255, .25);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .95rem;
            cursor: pointer;
            transition: background .15s;
        }

        .lightbox-close:hover {
            background: rgba(255, 255, 255, .22);
        }
    </style>
@endpush

@section('content')

    {{-- Alerts --}}
    @if (session('success'))
        <div class="img-alert success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="img-alert error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="img-header">
        <div class="img-header-left">
            <h2>
                <span class="icon-badge"><i class="fa-solid fa-images"></i></span>
                College Images
            </h2>
            <p>Upload and manage your institution's photo gallery</p>
        </div>
        <span class="img-count-badge">
            <i class="fa-solid fa-layer-group" style="margin-right:5px;"></i>
            {{ $images->count() }} {{ Str::plural('Image', $images->count()) }}
        </span>
    </div>

    {{-- Upload Card --}}
    <div class="upload-card">
        <div class="card-label">
            <i class="fa-solid fa-cloud-arrow-up" style="margin-right:5px;"></i>Upload New Image
        </div>
        <form action="{{ route('college.images.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-row">
                <div class="file-input-wrap">
                    <label class="file-input-label" for="mainFileInput">
                        <i class="fa-solid fa-image"></i>
                        <span>Choose an image to upload…</span>
                        <input type="file" id="mainFileInput" name="image" accept="image/*" required
                            onchange="showFileName(this, 'mainFileName')">
                    </label>
                    <div class="file-name-display" id="mainFileName"></div>
                    @error('image')
                        <div style="color:#c0392b; font-size:.75rem; margin-top:4px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn-upload">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    Upload Image
                </button>
            </div>
        </form>
    </div>

    {{-- Image Grid --}}
    @if ($images->isEmpty())
        <div class="img-empty">
            <div class="empty-icon"><i class="fa-solid fa-images"></i></div>
            <h4>No images uploaded yet</h4>
            <p>Use the form above to upload your first image.</p>
        </div>
    @else
        <div class="img-grid">
            @foreach ($images as $img)
                <div class="img-card">

                    {{-- Thumbnail --}}
                    <div class="img-thumb-wrap">
                        <img src="{{ asset('storage/' . $img->image_url) }}" alt="College image">
                        <div class="img-overlay">
                            <a href="#"
                                onclick="openLightbox('{{ asset('storage/' . $img->image_url) }}'); return false;">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="img-card-body">

                        {{-- Shared file picker (outside both forms) --}}
                        <label class="update-file-label" for="file_{{ $img->id }}">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                            <span>Replace image…</span>
                            <input type="file" id="file_{{ $img->id }}" accept="image/*"
                                onchange="onFilePick(this, {{ $img->id }})">
                        </label>
                        <div class="update-file-name" id="fname_{{ $img->id }}"></div>

                        {{-- Update + Delete side by side --}}
                        <div class="img-actions">

                            {{-- Update form (no file input inside — injected via JS) --}}
                            <form id="updateForm_{{ $img->id }}"
                                action="{{ route('college.images.update', $img->id) }}" method="POST"
                                enctype="multipart/form-data" style="flex:1;"
                                onsubmit="return attachAndSubmit(event, {{ $img->id }})">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-update" style="width:100%;">
                                    <i class="fa-solid fa-floppy-disk"></i> Update
                                </button>
                            </form>

                            {{-- Delete form --}}
                            <form action="{{ route('college.images.destroy', $img->id) }}" method="POST"
                                onsubmit="return confirm('Delete this image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    {{-- Lightbox --}}
    <div class="lightbox-backdrop" id="lightbox" onclick="if(event.target===this) closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img class="lightbox-img" id="lightboxImg" src="" alt="Preview">
    </div>

@endsection

@push('scripts')
    <script>
        /* ── Picked files store ── */
        const pickedFiles = {};

        function onFilePick(input, id) {
            pickedFiles[id] = input.files[0] ?? null;
            const nameEl = document.getElementById('fname_' + id);
            nameEl.textContent = pickedFiles[id] ? pickedFiles[id].name : '';
        }

        function attachAndSubmit(e, id) {
            const file = pickedFiles[id];
            if (!file) {
                alert('Please choose a replacement image first.');
                e.preventDefault();
                return false;
            }

            const form = document.getElementById('updateForm_' + id);
            const dt = new DataTransfer();
            dt.items.add(file);

            // Remove any previously injected input
            const old = form.querySelector('input[type="file"]');
            if (old) old.remove();

            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'image';
            input.style.display = 'none';
            input.files = dt.files;
            form.appendChild(input);

            return true;
        }

        /* ── Upload card filename display ── */
        function showFileName(input, displayId) {
            document.getElementById(displayId).textContent =
                input.files.length ? input.files[0].name : '';
        }

        /* ── Lightbox ── */
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImg');

        function openLightbox(src) {
            lightboxImg.src = src;
            lightbox.classList.add('open');
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
            lightboxImg.src = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endpush
