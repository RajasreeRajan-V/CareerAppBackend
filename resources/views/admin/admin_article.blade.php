@extends('layouts.app')

@section('header', 'Articles')

@push('styles')
    <style>
        /* ── Page header row ── */
        .articles-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .articles-header h2 {
            font-size: 17px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .articles-header p {
            font-size: 13px;
            color: #888;
            margin-top: 3px;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #306060;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-upload:hover {
            background: #254848;
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Search / filter bar ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-search {
            position: relative;
            flex: 1;
            min-width: 200px;
            max-width: 340px;
        }

        .filter-search input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-search input:focus {
            border-color: #306060;
        }

        .filter-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 13px;
        }

        .filter-count {
            font-size: 13px;
            color: #888;
            margin-left: auto;
        }

        /* ── Articles grid ── */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        /* ── Article card ── */
        .article-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            box-shadow: 0 8px 32px rgba(48, 96, 96, 0.12);
            transform: translateY(-2px);
        }

        /* PDF thumbnail strip */
        .article-thumb {
            background: linear-gradient(135deg, #e8f0f0 0%, #d4e5e5 100%);
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .article-thumb::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 18px,
                    rgba(48, 96, 96, 0.04) 18px,
                    rgba(48, 96, 96, 0.04) 36px);
        }

        .article-thumb-icon {
            width: 56px;
            height: 64px;
            background: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            position: relative;
            z-index: 1;
        }

        .article-thumb-icon i {
            font-size: 26px;
            color: #e53e3e;
        }

        .article-thumb-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #306060;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 3px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            z-index: 1;
        }

        /* Card body */
        .article-body {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            line-height: 1.4;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-meta {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .article-meta i {
            font-size: 11px;
        }

        /* Card actions */
        .article-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-action {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px 0;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background 0.18s, color 0.18s;
        }

        .btn-action-view {
            background: #f0f7f7;
            color: #306060;
        }

        .btn-action-view:hover {
            background: #306060;
            color: #fff;
        }

        .btn-action-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-action-delete:hover {
            background: #dc2626;
            color: #fff;
        }

        /* ── Empty state ── */
        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 72px 24px;
            text-align: center;
            background: #fff;
            border-radius: 12px;
            border: 1.5px dashed #d0dede;
        }

        .empty-state-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #e8f0f0, #d4e5e5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .empty-state-icon i {
            font-size: 30px;
            color: #306060;
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: #888;
            max-width: 320px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* ── Upload modal ── */
        .modal-header-custom {
            background: linear-gradient(135deg, #306060, #254848);
            color: #fff;
            border-radius: 12px 12px 0 0;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom .modal-title {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            margin: 0;
        }

        .modal-header-custom .btn-close {
            filter: invert(1) brightness(2);
            opacity: 0.8;
            margin: 0;
            flex-shrink: 0;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-body-custom {
            padding: 28px 24px;
        }

        /* Drop zone */
        .drop-zone {
            border: 2px dashed #cde0e0;
            border-radius: 10px;
            padding: 36px 20px;
            text-align: center;
            background: #f7fbfb;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
        }

        .drop-zone:hover,
        .drop-zone.dragover {
            border-color: #306060;
            background: #eef6f6;
        }

        .drop-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .drop-zone-icon {
            width: 52px;
            height: 52px;
            background: #e0eded;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            transition: background 0.2s;
        }

        .drop-zone:hover .drop-zone-icon {
            background: #c8dede;
        }

        .drop-zone-icon i {
            font-size: 22px;
            color: #306060;
        }

        .drop-zone-text {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .drop-zone-text span {
            color: #306060;
            font-weight: 600;
        }

        .drop-zone-hint {
            font-size: 12px;
            color: #aaa;
            margin-top: 6px;
        }

        .file-selected-info {
            display: none;
            align-items: center;
            gap: 10px;
            background: #eef6f6;
            border: 1px solid #c8dede;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 12px;
            font-size: 13px;
            color: #306060;
            font-weight: 500;
        }

        .file-selected-info i {
            color: #e53e3e;
            font-size: 18px;
        }

        .form-control-custom {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s;
            color: #1a1a1a;
        }

        .form-control-custom:focus {
            border-color: #306060;
            box-shadow: 0 0 0 3px rgba(48, 96, 96, 0.1);
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 7px;
            display: block;
        }

        .modal-footer-custom {
            padding: 16px 24px 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #555;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #e8e8e8;
        }

        .btn-submit {
            background: #306060;
            color: #fff;
            border: none;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .btn-submit:hover {
            background: #254848;
        }

        /* Delete confirm modal */
        .delete-icon-wrap {
            width: 60px;
            height: 60px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .delete-icon-wrap i {
            font-size: 26px;
            color: #dc2626;
        }

        @media (max-width: 576px) {
            .articles-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="articles-header">
        <div>
            <h2>All Articles</h2>
            <p>Manage and access your uploaded documents &amp; presentations</p>
        </div>
        <button class="btn-upload" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
            Upload Article
        </button>
    </div>

    {{-- ── Filter Bar ── --}}
    @if ($articles->count() > 0)
        <div class="filter-bar">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Search articles..." oninput="filterArticles()">
            </div>
            <span class="filter-count" id="articleCount">{{ $articles->count() }}
                article{{ $articles->count() !== 1 ? 's' : '' }}</span>
        </div>
    @endif

    {{-- ── Articles Grid ── --}}
    <div class="articles-grid" id="articlesGrid">

        @forelse($articles as $article)
            <div class="article-card" data-title="{{ strtolower($article->title) }}">

                {{-- Thumbnail --}}
                <div class="article-thumb">
                    <div class="article-thumb-badge">{{ strtoupper(pathinfo($article->file_path, PATHINFO_EXTENSION)) }}
                    </div>
                    <div class="article-thumb-icon">
                        @php
                            $ext = strtolower(pathinfo($article->file_path, PATHINFO_EXTENSION));
                            $iconMap = [
                                'pdf' => ['fa-file-pdf', '#e53e3e'],
                                'doc' => ['fa-file-word', '#2b579a'],
                                'docx' => ['fa-file-word', '#2b579a'],
                                'txt' => ['fa-file-lines', '#555555'],
                                'rtf' => ['fa-file-lines', '#555555'],
                                'odt' => ['fa-file-lines', '#555555'],
                                'ppt' => ['fa-file-powerpoint', '#d04423'],
                                'pptx' => ['fa-file-powerpoint', '#d04423'],
                                'odp' => ['fa-file-powerpoint', '#d04423'],
                            ];
                            [$icon, $color] = $iconMap[$ext] ?? ['fa-file', '#888888'];
                        @endphp
                        <i class="fa-solid {{ $icon }}" style="color: {{ $color }};"></i>
                    </div>
                </div>

                {{-- Body --}}
                <div class="article-body">
                    <div class="article-title" title="{{ $article->title }}">
                        {{ $article->title }}
                    </div>
                    <div class="article-meta">
                        <i class="fa-regular fa-clock"></i>
                        {{ $article->created_at->format('d M Y') }}
                    </div>

                    {{-- Actions --}}
                   
                    <div class="article-actions">
                        {{-- View --}}
                        <a href="{{ route('admin.articles.show', $article->id) }}" target="_blank"
                            class="btn-action btn-action-view">
                            <i class="fa-solid fa-eye"></i>
                            View
                        </a>
                    
                        {{-- Download --}}
                        <a href="{{ route('admin.articles.download', $article->id) }}" class="btn-action btn-action-view"
                            style="background:#f0f4ff; color:#3b5bdb;">
                            <i class="fa-solid fa-download"></i>
                            Download
                        </a>
                    
                        {{-- Edit --}}
                       {{-- Edit --}}
                        <button type="button" class="btn-action"
                            style="background:#fff8e1; color:#b45309; flex:0 0 auto; padding:7px 12px;"
                            onclick="openEditModal({{ $article->id }}, '{{ addslashes($article->title) }}', '{{ route('admin.articles.show', $article->id) }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    
                        {{-- Delete --}}
                        <button type="button" class="btn-action btn-action-delete"
                            onclick="confirmDelete({{ $article->id }}, '{{ addslashes($article->title) }}')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty

            {{-- Empty state --}}
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <h3>No Articles Yet</h3>
                <p>Upload your first document to get started. PDF, Word, PowerPoint, and more are supported.</p>
                <button class="btn-upload" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    Upload Article
                </button>
            </div>
        @endforelse
    </div>

    {{-- No results message (shown via JS) --}}
    <div id="noResults" style="display:none; text-align:center; padding:48px 0; color:#aaa; font-size:14px;">
        <i class="fa-solid fa-magnifying-glass" style="font-size:28px; margin-bottom:12px; display:block; opacity:0.4;"></i>
        No articles match your search.
    </div>


    {{-- ══════════════════════════════════════
     UPLOAD MODAL
══════════════════════════════════════ --}}
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content">

                <div class="modal-header-custom">
                    <div class="modal-title" id="uploadModalLabel">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        Upload New Article
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data"
                    id="uploadForm">
                    @csrf
                    <div class="modal-body-custom">

                        {{-- Title field --}}
                        <div class="mb-4">
                            <label class="form-label-custom" for="title">
                                Article Title <span style="color:#dc2626;">*</span>
                            </label>
                            
                               <input type="text" name="title" id="title"
                                    class="form-control-custom @error('title') is-invalid @enderror"
                                    placeholder="e.g. Introduction to Machine Learning" value="{{ old('title') }}"
                                    maxlength="40" oninput="updateCounter('title','titleCounter')" required>
                                <div style="text-align:right; font-size:11px; color:#aaa; margin-top:4px;">
                                    <span id="titleCounter">0</span>/40 characters
                                </div>
                            @error('title')
                                <div class="invalid-feedback"
                                    style="display:block; font-size:12px; color:#dc2626; margin-top:5px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- File drop zone --}}
                        <div>
                            <label class="form-label-custom">
                                Document File <span style="color:#dc2626;">*</span>
                            </label>
                            <div class="drop-zone" id="dropZone">
                                <input type="file" name="file" id="fileInput"
                                    accept=".pdf,.doc,.docx,.txt,.rtf,.odt,.ppt,.pptx,.odp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain,application/rtf,application/vnd.oasis.opendocument.text,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.oasis.opendocument.presentation"
                                    required onchange="handleFileSelect(this)">
                                <div class="drop-zone-icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="drop-zone-text">
                                    <span>Click to browse</span> or drag & drop
                                </div>
                                <div class="drop-zone-hint">PDF, DOC, DOCX, TXT, RTF, ODT, PPT, PPTX, ODP · Max 10 MB</div>
                            </div>

                            {{-- File selected indicator --}}
                            <div class="file-selected-info" id="fileSelectedInfo">
                                <i class="fa-solid fa-file" id="fileSelectedIcon"></i>
                                <span id="fileSelectedName">—</span>
                            </div>

                            @error('file')
                                <div style="font-size:12px; color:#dc2626; margin-top:6px;">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                            Upload Article
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
{{-- ══════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content">

            <div class="modal-header-custom">
                <div class="modal-title">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit Article
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body-custom">

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="form-label-custom" for="editTitle">
                            Article Title <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="title" id="editTitle"
                               class="form-control-custom"
                               placeholder="e.g. Introduction to Machine Learning"
                               maxlength="40" oninput="updateCounter('editTitle','editTitleCounter')" required>
                        <div style="text-align:right; font-size:11px; color:#aaa; margin-top:4px;">
                            <span id="editTitleCounter">0</span>/40 characters
                        </div>
                    </div>
                    
                    {{-- Current file preview --}}
<div class="mb-4" id="currentFileWrap">
    <label class="form-label-custom">Current File</label>
    <div style="display:flex; align-items:center; gap:10px; background:#f7fbfb; border:1px solid #cde0e0; border-radius:8px; padding:10px 14px;">
        <i class="fa-solid fa-file-pdf" style="color:#e53e3e; font-size:20px;"></i>
        <span id="currentFileName" style="font-size:13px; color:#306060; font-weight:500; flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">—</span>
        <a id="currentFileView" href="#" target="_blank"
           style="font-size:12px; color:#306060; font-weight:600; text-decoration:none; background:#e0eded; padding:4px 10px; border-radius:6px; white-space:nowrap;">
            <i class="fa-solid fa-eye"></i> View
        </a>
    </div>
</div>
                    {{-- Optional file replacement --}}
                    <div>
                        <label class="form-label-custom">
                            Replace File
                            <span style="font-weight:400; color:#aaa;">(optional — leave blank to keep current)</span>
                        </label>
                        <div class="drop-zone" id="editDropZone">
                            <input type="file" name="file" id="editFileInput"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
                                   onchange="handleEditFileSelect(this)">
                            <div class="drop-zone-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="drop-zone-text">
                                <span>Click to browse</span> or drag &amp; drop
                            </div>
                            <div class="drop-zone-hint">PDF, DOC, DOCX, PPT, PPTX · Max 20 MB</div>
                        </div>
                        <div class="file-selected-info" id="editFileSelectedInfo">
                            <i class="fa-solid fa-file" id="editFileSelectedIcon"></i>
                            <span id="editFileSelectedName">—</span>
                        </div>
                    </div>

                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit" id="editSubmitBtn">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

    {{-- ══════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════ --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content" style="border-radius:12px; border:none;">
                <div class="modal-body" style="padding:32px 24px; text-align:center;">
                    <div class="delete-icon-wrap">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h5 style="font-size:16px; font-weight:700; color:#1a1a1a; margin-bottom:10px;">Delete Article?</h5>
                    <p style="font-size:14px; color:#888; line-height:1.6; margin-bottom:0;">
                        You are about to delete <strong id="deleteArticleName" style="color:#1a1a1a;"></strong>.
                        This action cannot be undone.
                    </p>
                </div>
                <div style="padding:0 24px 24px; display:flex; gap:10px;">
                    <button type="button" class="btn-cancel" style="flex:1;" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="flex:1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="width:100%; background:#dc2626; color:#fff; border:none; padding:9px 0; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer; transition:background 0.2s;">
                            <i class="fa-solid fa-trash" style="margin-right:6px;"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        /* ── File select handler ── */
        const fileIconMap = {
            pdf: {
                cls: 'fa-file-pdf',
                color: '#e53e3e'
            },
            doc: {
                cls: 'fa-file-word',
                color: '#2b579a'
            },
            docx: {
                cls: 'fa-file-word',
                color: '#2b579a'
            },
            txt: {
                cls: 'fa-file-lines',
                color: '#555555'
            },
            rtf: {
                cls: 'fa-file-lines',
                color: '#555555'
            },
            odt: {
                cls: 'fa-file-lines',
                color: '#555555'
            },
            ppt: {
                cls: 'fa-file-powerpoint',
                color: '#d04423'
            },
            pptx: {
                cls: 'fa-file-powerpoint',
                color: '#d04423'
            },
            odp: {
                cls: 'fa-file-powerpoint',
                color: '#d04423'
            },
        };

        function handleFileSelect(input) {
            const info = document.getElementById('fileSelectedInfo');
            const nameEl = document.getElementById('fileSelectedName');
            const iconEl = document.getElementById('fileSelectedIcon');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                const meta = fileIconMap[ext] || {
                    cls: 'fa-file',
                    color: '#888'
                };

                // Update icon
                iconEl.className = 'fa-solid ' + meta.cls;
                iconEl.style.color = meta.color;
                iconEl.style.fontSize = '18px';

                nameEl.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                info.style.display = 'flex';
            } else {
                info.style.display = 'none';
            }
        }

        /* ── Drag & drop visual feedback ── */
        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(e => {
                dropZone.addEventListener(e, ev => {
                    ev.preventDefault();
                    dropZone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(e => {
                dropZone.addEventListener(e, ev => {
                    dropZone.classList.remove('dragover');
                });
            });
        }

        /* ── Upload form: loading state ── */
        document.getElementById('uploadForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading…';
        });

        /* ── Delete confirm ── */
        function confirmDelete(id, title) {
            document.getElementById('deleteArticleName').textContent = '"' + title + '"';
            document.getElementById('deleteForm').action = '{{ url('/articles') }}/' + id;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        /* ── Client-side search filter ── */
        function filterArticles() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.article-card');
            let visible = 0;

            cards.forEach(card => {
                const title = card.dataset.title || '';
                const match = !q || title.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const noResults = document.getElementById('noResults');
            const countEl = document.getElementById('articleCount');
            noResults.style.display = visible === 0 ? 'block' : 'none';
            if (countEl) countEl.textContent = visible + ' article' + (visible !== 1 ? 's' : '');
        }

        /* ── Re-open upload modal if validation failed ── */
        @if ($errors->has('title') || $errors->has('file'))
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Modal(document.getElementById('uploadModal')).show();
            });
        @endif
        
        
        /* ── Edit modal ── */
function openEditModal(id, title, fileUrl) {
    document.getElementById('editTitle').value = title;
    updateCounter('editTitle', 'editTitleCounter'); // ← add this line

    document.getElementById('editForm').action = '{{ url('/articles') }}/' + id + '/update';
    document.getElementById('currentFileName').textContent = title + '.pdf';
    document.getElementById('currentFileView').href = fileUrl;
    document.getElementById('editFileInput').value = '';
    document.getElementById('editFileSelectedInfo').style.display = 'none';

    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function handleEditFileSelect(input) {
    const info   = document.getElementById('editFileSelectedInfo');
    const nameEl = document.getElementById('editFileSelectedName');
    const iconEl = document.getElementById('editFileSelectedIcon');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const ext  = file.name.split('.').pop().toLowerCase();
        const meta = fileIconMap[ext] || { cls: 'fa-file', color: '#888' };
        iconEl.className   = 'fa-solid ' + meta.cls;
        iconEl.style.color = meta.color;
        nameEl.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        info.style.display = 'flex';
    } else {
        info.style.display = 'none';
    }
}
function updateCounter(inputId, counterId) {
    const len = document.getElementById(inputId).value.length;
    const el  = document.getElementById(counterId);
    el.textContent = len;
    el.style.color = len >= 36 ? '#dc2626' : '#aaa'; // turns red near limit
}
/* ── Edit form: loading state ── */
document.getElementById('editForm')?.addEventListener('submit', function () {
    const btn = document.getElementById('editSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
});
    </script>
@endpush
