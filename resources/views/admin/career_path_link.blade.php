<!-- resources/views/admin/career_path_links.blade.php -->
@extends('layouts.app')

@push('styles')
    <style>
        /* Custom Select Box Styles */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
            user-select: none;
        }

        .custom-select-trigger {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .custom-select-trigger:hover {
            border-color: #86b7fe;
        }

        .custom-select-trigger.open {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .selected-value {
            color: #212529;
        }

        .arrow {
            transition: transform 0.3s ease;
            font-size: 12px;
            color: #6c757d;
        }

        .custom-select-trigger.open .arrow {
            transform: rotate(180deg);
        }

        .custom-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 6px 6px;
            max-height: 350px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .custom-options.show {
            display: block;
        }

        /* Search Box Styles */
        .custom-search-box {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Options List Styles */
        .options-list {
            max-height: 250px;
            overflow-y: auto;
        }

        .custom-option {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-option:hover {
            background-color: #f8f9fa;
        }

        .custom-option.selected {
            background-color: #e7f1ff;
            color: #0d6efd;
        }

        .custom-option.hidden {
            display: none;
        }

        .newgen-tag {
            color: #4d4dff;
            font-weight: bold;
            margin-left: 8px;
            font-size: 12px;
        }

        /* No results message */
        .no-results {
            padding: 10px 15px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        /* Remove button styling */
        .remove-parent,
        .remove-child {
            margin-top: 5px;
            width: 100%;
        }

        .parent-career-item,
        .child-career-item {
            margin-bottom: 10px;
        }

        /* Search result highlight */
        .search-highlight {
            background-color: #fff3cd;
            transition: background-color 0.3s ease;
        }

        /* Parent career highlight in search results */
        .parent-match {
            font-weight: bold;
            color: #0d6efd;
        }


        /* Highlight matching text */
        .highlight-text {
            background-color: #ffeb3b;
            color: #000;
            font-weight: bold;
            padding: 0 2px;
            border-radius: 3px;
            animation: pulse 0.5s ease;
        }

        /* Cell highlight animation */
        .search-highlight-match {
            animation: cellPulse 0.5s ease;
        }

        @keyframes cellPulse {
            0% {
                background-color: transparent;
            }

            50% {
                background-color: #fff3cd;
            }

            100% {
                background-color: transparent;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fade in animation for rows */
        @keyframes fadeIn {
            from {
                opacity: 0;
                background-color: #e8f5e9;
            }

            to {
                opacity: 1;
                background-color: transparent;
            }
        }

        /* Search input enhancement */
        #searchParent {
            transition: all 0.3s ease;
        }

        #searchParent:focus {
            border-color: #306060;
            box-shadow: 0 0 0 0.2rem rgba(48, 96, 96, 0.25);
        }

        /* Results count styling */
        #searchResultsCount {
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

/* Additional styles for modal dropdown z-index */
#editCareerLinkModal .custom-options {
    z-index: 1060;
}

#editCareerLinkModal .custom-select-trigger.open {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Pagination responsive fixes */
#paginationLinks {
    width: 100%;
    display: block;
    text-align: center;
    padding: 0.5rem 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

#paginationLinks .pagination {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 0.3rem;
    margin-bottom: 0;
    padding: 0;
    min-width: max-content;
}

#paginationLinks .page-item {
    flex: 0 1 auto;
    min-width: 38px;
}

#paginationLinks .page-link {
    padding: 0.42rem 0.7rem;
    min-width: 38px;
    text-align: center;
    white-space: nowrap;
}

@media (max-width: 1139px) {
    #paginationLinks .pagination {
        gap: 0.2rem;
        flex-wrap: nowrap;
    }

    #paginationLinks .page-item {
        min-width: 32px;
    }

    #paginationLinks .page-link {
        padding: 0.35rem 0.6rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 992px) {
    #paginationLinks {
        padding: 0.35rem 0.25rem;
    }

    #paginationLinks .pagination {
        gap: 0.18rem;
    }

    #paginationLinks .page-item {
        min-width: 28px;
    }

    #paginationLinks .page-link {
        padding: 0.3rem 0.55rem;
        font-size: 0.82rem;
        white-space: normal;
    }
}

@media (max-width: 576px) {
    #paginationLinks {
        padding: 0 0.25rem;
    }

    #paginationLinks .pagination {
        justify-content: center;
        gap: 0.2rem;
    }

    #paginationLinks .page-item {
        min-width: 32px;
    }

    #paginationLinks .page-link {
        padding: 0.3rem 0.55rem;
        font-size: 0.8rem;
        white-space: normal;
    }
}

/* Table mobile responsive fixes */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-responsive table {
    width: 100%;
    min-width: 720px;
}

@media (max-width: 1139px) {
    .table-responsive table {
        min-width: 680px;
    }
}

@media (max-width: 992px) {
    .table-responsive table {
        min-width: 620px;
    }

    #careerLinksTable th,
    #careerLinksTable td {
        white-space: nowrap;
    }
}

@media (max-width: 576px) {
    .table-responsive table {
        min-width: 520px;
    }

    #careerLinksTable th,
    #careerLinksTable td {
        font-size: 0.82rem;
        padding: 0.45rem 0.55rem;
    }
}

/* Ensure modal body doesn't clip dropdowns */
.modal-body {
    overflow: visible !important;
}

.modal-dialog {
    overflow-y: initial !important;
}

.modal-content {
    overflow-y: visible !important;
}
</style>
@endpush
@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header text-white text-center" style="background-color: #306060;">
                        <h4 class="mb-0">Manage Career Links</h4>
                    </div>
                    <div class="card-body">

                        <!-- Add Career Link Form -->
                        <form action="{{ route('admin.career_link.store') }}" method="POST" class="mb-4"
                            id="careerLinkForm">
                            @csrf

                            <div class="card shadow-sm p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="row g-4">
                                    <!-- Main Career -->
                                    <div class="col-12">
                                        <label for="parent_career_id" class="form-label fw-semibold">
                                            Main Career <span class="text-danger">*</span>
                                        </label>
                                        <div class="custom-select-wrapper">
                                            <div class="custom-select-trigger" data-target="main_career">
                                                <span class="selected-value">Select Main Career</span>
                                                <span class="arrow">▼</span>
                                            </div>
                                            <div class="custom-options" id="main_career_options">
                                                <div class="custom-search-box">
                                                    <input type="text" class="search-input"
                                                        placeholder="Search careers..." autocomplete="off">
                                                </div>
                                                <div class="options-list">
                                                    @foreach ($careerNodes as $node)
                                                        <div class="custom-option" data-value="{{ $node->id }}"
                                                            data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                                                            {{ $node->title }}
                                                            @if ($node->newgen_course)
                                                                <span class="newgen-tag">[NewGen]</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="parent_career_id" id="main_career_input" required>
                                        <small class="text-muted">This will be the middle node in the hierarchy</small>
                                    </div>

                                    <!-- Parent Careers (Multiple) -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Parent Careers <span class="text-danger">*</span>
                                        </label>
                                        <div id="parents-container">
                                            <div class="parent-career-item mb-2">
                                                <div class="custom-select-wrapper">
                                                    <div class="custom-select-trigger" data-target="parent_0">
                                                        <span class="selected-value">Select Parent Career</span>
                                                        <span class="arrow">▼</span>
                                                    </div>
                                                    <div class="custom-options" id="parent_0_options">
                                                        <div class="custom-search-box">
                                                            <input type="text" class="search-input"
                                                                placeholder="Search careers..." autocomplete="off">
                                                        </div>
                                                        <div class="options-list">
                                                            @foreach ($careerNodes as $node)
                                                                <div class="custom-option" data-value="{{ $node->id }}"
                                                                    data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                                                                    {{ $node->title }}
                                                                    @if ($node->newgen_course)
                                                                        <span class="newgen-tag">[NewGen]</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="parent_careers[]" class="parent-input"
                                                    data-id="parent_0">
                                                <button type="button" class="btn btn-outline-danger remove-parent"
                                                    style="display:none; margin-top: 5px;">
                                                    <i class="fa-solid fa-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" id="add-parent-btn"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> Add Another Parent
                                        </button>
                                        <small class="text-muted d-block mt-1">Parents of Main Career</small>
                                    </div>

                                    <!-- Child Careers (Multiple) -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Child Careers <span class="text-danger">*</span>
                                        </label>
                                        <div id="children-container">
                                            <div class="child-career-item mb-2">
                                                <div class="custom-select-wrapper">
                                                    <div class="custom-select-trigger" data-target="child_0">
                                                        <span class="selected-value">Select Child Career</span>
                                                        <span class="arrow">▼</span>
                                                    </div>
                                                    <div class="custom-options" id="child_0_options">
                                                        <div class="custom-search-box">
                                                            <input type="text" class="search-input"
                                                                placeholder="Search careers..." autocomplete="off">
                                                        </div>
                                                        <div class="options-list">
                                                            @foreach ($careerNodes as $node)
                                                                <div class="custom-option" data-value="{{ $node->id }}"
                                                                    data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                                                                    {{ $node->title }}
                                                                    @if ($node->newgen_course)
                                                                        <span class="newgen-tag">[NewGen]</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="child_careers[]" class="child-input"
                                                    data-id="child_0">
                                                <button type="button" class="btn btn-outline-danger remove-child"
                                                    style="display:none; margin-top: 5px;">
                                                    <i class="fa-solid fa-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" id="add-child-btn"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> Add Another Child
                                        </button>
                                        <small class="text-muted d-block mt-1">Children of Main Career</small>
                                    </div>

                                    <!-- Hierarchy Visualization -->
                                    <div class="col-12">
                                        <div class="alert alert-info mb-0">
                                            <strong>Hierarchy:</strong> Parent Career(s) → Main Career → Child Career(s)
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success px-4">
                                            <i class="bi bi-plus-circle"></i> Add Career Links
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="GET" action="{{ route('admin.career_link.index') }}" class="card mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-search"></i> Search Careers
                                        </label>
                                        <input type="text" class="form-control" name="search"
                                            value="{{ $search ?? '' }}"
                                            placeholder="Search in Parent or Child careers...">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search"></i> Search Connections
                                        </button>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <a href="{{ route('admin.career_link.index') }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="bi bi-arrow-clockwise"></i> Show All
                                        </a>
                                        @if ($search ?? false)
                                            <span class="ms-3 text-muted">
                                                Results for: <strong>{{ $search }}</strong> —
                                                <strong>{{ $careerLinks->total() }}</strong> found
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Career Links Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="careerLinksTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Parent Career</th>
                                        <th>Child Career</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($careerLinks as $link)
                                        <tr data-parent="{{ Str::lower($link->parent?->title ?? '') }}"
                                            data-parent-id="{{ $link->parent_career_id }}"
                                            data-child="{{ Str::lower($link->child?->title ?? '') }}">
                                            <td>{{ $careerLinks->firstItem() + $loop->index }}</td>
                                            <td>
                                                {{ $link->parent?->title ?? '-' }}
                                                @if ($link->parent?->newgen_course)
                                                    <span style="color: #4d4dff; font-weight: bold;">[NewGen]</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $link->child?->title ?? '-' }}
                                                @if ($link->child?->newgen_course)
                                                    <span style="color: #4d4dff; font-weight: bold;">[NewGen]</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-primary edit-link-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editCareerLinkModal"
                                                        data-update-url="{{ route('admin.career_link.update', $link->id) }}"
                                                        data-parent="{{ $link->parent_career_id }}"
                                                        data-child="{{ $link->child_career_id }}">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>

                                                    <form action="{{ route('admin.career_link.destroy', $link->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this career link?\n\n{{ $link->parent?->title }} → {{ $link->child?->title }}\n\nThis action cannot be undone!');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="noDataRow">
                                            <td colspan="4" class="text-center">No career links found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3" id="paginationLinks">
                            @if ($careerLinks->hasPages())
                                {{ $careerLinks->links() }}
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Career Link Modal -->
   <!-- Edit Career Link Modal -->
<div class="modal fade" id="editCareerLinkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editCareerLinkForm">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background-color:#306060;">
                    <h5 class="modal-title text-white">Edit Career Link</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Parent Career <span class="text-danger">*</span></label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select-trigger" id="edit_parent_trigger">
                                    <span class="selected-value" id="edit_parent_selected">Select Parent Career</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="custom-options" id="edit_parent_options">
                                    <div class="custom-search-box">
                                        <input type="text" class="search-input" placeholder="Search careers..." autocomplete="off">
                                    </div>
                                    <div class="options-list">
                                        @foreach ($careerNodes as $node)
                                            <div class="custom-option" data-value="{{ $node->id }}" data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                                                {{ $node->title }}
                                                @if ($node->newgen_course)
                                                    <span class="newgen-tag">[NewGen]</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="parent_career_id" id="edit_parent_input">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Child Career <span class="text-danger">*</span></label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select-trigger" id="edit_child_trigger">
                                    <span class="selected-value" id="edit_child_selected">Select Child Career</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="custom-options" id="edit_child_options">
                                    <div class="custom-search-box">
                                        <input type="text" class="search-input" placeholder="Search careers..." autocomplete="off">
                                    </div>
                                    <div class="options-list">
                                        @foreach ($careerNodes as $node)
                                            <div class="custom-option" data-value="{{ $node->id }}" data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                                                {{ $node->title }}
                                                @if ($node->newgen_course)
                                                    <span class="newgen-tag">[NewGen]</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="child_career_id" id="edit_child_input">
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <strong>Hierarchy:</strong> Parent Career → Child Career
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Career Link</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        // Custom Select Box Controller with Search
        class CustomSelect {
            constructor(trigger, optionsContainer, hiddenInput) {
                this.trigger = trigger;
                this.optionsContainer = optionsContainer;
                this.hiddenInput = hiddenInput;
                this.isOpen = false;
                this.selectedOption = null;
                this.searchInput = optionsContainer.querySelector('.search-input');
                this.optionsList = optionsContainer.querySelector('.options-list');
                this.allOptions = Array.from(this.optionsList.querySelectorAll('.custom-option'));

                this.init();
            }

            init() {
                // Toggle dropdown
                this.trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggle();
                });

                // Setup search functionality with enhanced case-insensitive matching
                if (this.searchInput) {
                    this.searchInput.addEventListener('input', (e) => {
                        this.filterOptions(e.target.value);
                    });

                    this.searchInput.addEventListener('keydown', (e) => {
                        // Prevent form submission when pressing Enter in search box
                        if (e.key === 'Enter') {
                            e.stopPropagation();
                            e.preventDefault();
                        }
                    });

                    this.searchInput.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }

                // Select option
                this.allOptions.forEach(option => {
                    option.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const value = option.dataset.value;
                        const text = this.getOptionText(option);
                        const hasNewgen = option.querySelector('.newgen-tag') !== null;

                        // Update trigger text
                        let displayText = text;
                        if (hasNewgen) {
                            displayText = text + ' [NewGen]';
                        }
                        this.trigger.querySelector('.selected-value').textContent = displayText;

                        // Update hidden input
                        if (this.hiddenInput) {
                            this.hiddenInput.value = value;
                        }

                        // Update selected class
                        this.allOptions.forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');

                        // Clear search and close dropdown
                        if (this.searchInput) {
                            this.searchInput.value = '';
                            this.filterOptions('');
                        }
                        this.close();
                    });
                });

                // Close when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.trigger.parentElement.contains(e.target)) {
                        this.close();
                        if (this.searchInput) {
                            this.searchInput.value = '';
                            this.filterOptions('');
                        }
                    }
                });
            }

            getOptionText(option) {
                // Get text without the NewGen tag and normalize it
                const textNode = option.childNodes[0];
                let text = textNode ? textNode.nodeValue.trim() : '';
                // Normalize unicode characters (e.g., accented characters)
                text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                return text;
            }

            filterOptions(searchTerm) {
                // Enhanced case-insensitive search with normalization
                let term = searchTerm.toLowerCase().trim();
                // Also normalize the search term for accented characters
                term = term.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                let hasVisible = false;

                this.allOptions.forEach(option => {
                    let text = this.getOptionText(option).toLowerCase();
                    // Normalize option text for accented characters
                    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                    // Case-insensitive matching
                    if (term === '' || text.includes(term)) {
                        option.classList.remove('hidden');
                        hasVisible = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                // Show/hide no results message
                let noResultsMsg = this.optionsList.querySelector('.no-results');
                if (!hasVisible && term !== '') {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results';
                        noResultsMsg.textContent = 'No careers found matching "' + searchTerm + '"';
                        this.optionsList.appendChild(noResultsMsg);
                    } else {
                        noResultsMsg.textContent = 'No careers found matching "' + searchTerm + '"';
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            toggle() {
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            }

            open() {
                // Close all other custom selects
                document.querySelectorAll('.custom-select-trigger').forEach(trigger => {
                    if (trigger !== this.trigger && trigger.classList.contains('open')) {
                        const wrapper = trigger.closest('.custom-select-wrapper');
                        const options = wrapper.querySelector('.custom-options');
                        trigger.classList.remove('open');
                        options.classList.remove('show');
                        // Clear search in other selects
                        const otherSearch = options.querySelector('.search-input');
                        if (otherSearch) {
                            otherSearch.value = '';
                            const otherOptionsList = options.querySelector('.options-list');
                            if (otherOptionsList) {
                                otherOptionsList.querySelectorAll('.custom-option').forEach(opt => {
                                    opt.classList.remove('hidden');
                                });
                                const noResults = otherOptionsList.querySelector('.no-results');
                                if (noResults) noResults.remove();
                            }
                        }
                    }
                });

                this.trigger.classList.add('open');
                this.optionsContainer.classList.add('show');
                this.isOpen = true;

                // Focus search input and clear previous search
                if (this.searchInput) {
                    setTimeout(() => {
                        this.searchInput.focus();
                        this.searchInput.value = '';
                        this.filterOptions('');
                    }, 100);
                }
            }

            close() {
                this.trigger.classList.remove('open');
                this.optionsContainer.classList.remove('show');
                this.isOpen = false;
            }

            setValue(value, text) {
                this.trigger.querySelector('.selected-value').textContent = text;
                if (this.hiddenInput) {
                    this.hiddenInput.value = value;
                }

                // Update selected class
                this.allOptions.forEach(option => {
                    if (option.dataset.value == value) {
                        option.classList.add('selected');
                    } else {
                        option.classList.remove('selected');
                    }
                });
            }
        }

        // Rest of your code remains the same...
        // Store all custom select instances
        let customSelects = [];

        // Function to generate options HTML
        function generateOptionsHTML() {
            return `
            <div class="custom-search-box">
                <input type="text" class="search-input" placeholder="Search careers (case-insensitive)..." autocomplete="off">
            </div>
            <div class="options-list">
                @foreach ($careerNodes as $node)
                    <div class="custom-option" data-value="{{ $node->id }}" data-newgen="{{ $node->newgen_course ? 'true' : 'false' }}">
                        {{ $node->title }}
                        @if ($node->newgen_course)
                            <span class="newgen-tag">[NewGen]</span>
                        @endif
                    </div>
                @endforeach
            </div>
        `;
        }

        // Initialize custom selects
        function initializeCustomSelects() {
            // Clear existing instances
            customSelects = [];

            // Initialize main career select
            const mainTrigger = document.querySelector('[data-target="main_career"]');
            const mainOptions = document.getElementById('main_career_options');
            const mainInput = document.getElementById('main_career_input');
            if (mainTrigger && mainOptions) {
                customSelects.push(new CustomSelect(mainTrigger, mainOptions, mainInput));
            }

            // Initialize parent selects
            document.querySelectorAll('[data-target^="parent_"]').forEach(trigger => {
                const targetId = trigger.dataset.target;
                const options = document.getElementById(`${targetId}_options`);
                const input = document.querySelector(`input[data-id="${targetId}"]`);
                if (trigger && options) {
                    customSelects.push(new CustomSelect(trigger, options, input));
                }
            });

            // Initialize child selects
            document.querySelectorAll('[data-target^="child_"]').forEach(trigger => {
                const targetId = trigger.dataset.target;
                const options = document.getElementById(`${targetId}_options`);
                const input = document.querySelector(`input[data-id="${targetId}"]`);
                if (trigger && options) {
                    customSelects.push(new CustomSelect(trigger, options, input));
                }
            });
        }

        // Generate unique ID
        let parentCounter = 1;
        let childCounter = 1;

        // Add new parent select
        function addParentSelect() {
            const container = document.getElementById('parents-container');
            const newId = parentCounter++;
            const html = `
                <div class="parent-career-item mb-2">
                    <div class="custom-select-wrapper">
                        <div class="custom-select-trigger" data-target="parent_${newId}">
                            <span class="selected-value">Select Parent Career</span>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="custom-options" id="parent_${newId}_options">
                            ${generateOptionsHTML()}
                        </div>
                    </div>
                    <input type="hidden" name="parent_careers[]" class="parent-input" data-id="parent_${newId}">
                    <button type="button" class="btn btn-outline-danger remove-parent" style="margin-top: 5px;">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);

            // Initialize the new select
            const newTrigger = document.querySelector(`[data-target="parent_${newId}"]`);
            const newOptions = document.getElementById(`parent_${newId}_options`);
            const newInput = document.querySelector(`input[data-id="parent_${newId}"]`);
            customSelects.push(new CustomSelect(newTrigger, newOptions, newInput));

            // Add remove functionality
            const removeBtn = container.lastElementChild.querySelector('.remove-parent');
            removeBtn.addEventListener('click', function() {
                const item = this.closest('.parent-career-item');
                const selectWrapper = item.querySelector('.custom-select-wrapper');
                if (selectWrapper) {
                    const index = customSelects.findIndex(cs => cs.trigger.closest('.custom-select-wrapper') ===
                        selectWrapper);
                    if (index !== -1) customSelects.splice(index, 1);
                }
                item.remove();
                updateRemoveButtons();
            });

            updateRemoveButtons();
        }

        // Add new child select
        function addChildSelect() {
            const container = document.getElementById('children-container');
            const newId = childCounter++;
            const html = `
                <div class="child-career-item mb-2">
                    <div class="custom-select-wrapper">
                        <div class="custom-select-trigger" data-target="child_${newId}">
                            <span class="selected-value">Select Child Career</span>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="custom-options" id="child_${newId}_options">
                            ${generateOptionsHTML()}
                        </div>
                    </div>
                    <input type="hidden" name="child_careers[]" class="child-input" data-id="child_${newId}">
                    <button type="button" class="btn btn-outline-danger remove-child" style="margin-top: 5px;">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);

            // Initialize the new select
            const newTrigger = document.querySelector(`[data-target="child_${newId}"]`);
            const newOptions = document.getElementById(`child_${newId}_options`);
            const newInput = document.querySelector(`input[data-id="child_${newId}"]`);
            customSelects.push(new CustomSelect(newTrigger, newOptions, newInput));

            // Add remove functionality
            const removeBtn = container.lastElementChild.querySelector('.remove-child');
            removeBtn.addEventListener('click', function() {
                const item = this.closest('.child-career-item');
                const selectWrapper = item.querySelector('.custom-select-wrapper');
                if (selectWrapper) {
                    const index = customSelects.findIndex(cs => cs.trigger.closest('.custom-select-wrapper') ===
                        selectWrapper);
                    if (index !== -1) customSelects.splice(index, 1);
                }
                item.remove();
                updateRemoveButtons();
            });

            updateRemoveButtons();
        }

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const parentItems = document.querySelectorAll('.parent-career-item');
            parentItems.forEach((item) => {
                const btn = item.querySelector('.remove-parent');
                if (btn) {
                    btn.style.display = parentItems.length > 1 ? 'block' : 'none';
                }
            });

            const childItems = document.querySelectorAll('.child-career-item');
            childItems.forEach((item) => {
                const btn = item.querySelector('.remove-child');
                if (btn) {
                    btn.style.display = childItems.length > 1 ? 'block' : 'none';
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCustomSelects();

            // Add button listeners
            document.getElementById('add-parent-btn').addEventListener('click', addParentSelect);
            document.getElementById('add-child-btn').addEventListener('click', addChildSelect);

            // Initial remove buttons update
            updateRemoveButtons();

            // Form validation
            document.getElementById('careerLinkForm').addEventListener('submit', function(e) {
                const mainCareer = document.getElementById('main_career_input').value;
                if (!mainCareer) {
                    e.preventDefault();
                    alert('Please select a Main Career');
                    return false;
                }

                const parentInputs = document.querySelectorAll('.parent-input');
                const childInputs = document.querySelectorAll('.child-input');

                const selectedParents = Array.from(parentInputs).map(input => input.value).filter(v => v);
                const selectedChildren = Array.from(childInputs).map(input => input.value).filter(v => v);

                if (selectedParents.length === 0 && selectedChildren.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one Parent Career or one Child Career');
                    return false;
                }

                if (new Set(selectedParents).size !== selectedParents.length) {
                    e.preventDefault();
                    alert('Duplicate Parent Careers selected. Please choose unique parents.');
                    return false;
                }

                if (new Set(selectedChildren).size !== selectedChildren.length) {
                    e.preventDefault();
                    alert('Duplicate Child Careers selected. Please choose unique children.');
                    return false;
                }

                const overlap = selectedParents.filter(id => selectedChildren.includes(id));
                if (overlap.length > 0) {
                    e.preventDefault();
                    alert('A career cannot be both Parent and Child.');
                    return false;
                }
            });
        });
    </script>

    <script>
// Edit Modal Custom Select Classes
class ModalCustomSelect {
    constructor(triggerId, optionsContainerId, hiddenInputId) {
        this.trigger = document.getElementById(triggerId);
        this.optionsContainer = document.getElementById(optionsContainerId);
        this.hiddenInput = document.getElementById(hiddenInputId);
        this.isOpen = false;
        this.searchInput = this.optionsContainer.querySelector('.search-input');
        this.optionsList = this.optionsContainer.querySelector('.options-list');
        this.allOptions = Array.from(this.optionsList.querySelectorAll('.custom-option'));
        
        this.init();
    }
    
    init() {
        // Toggle dropdown
        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });
        
        // Setup search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.filterOptions(e.target.value);
            });
            
            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.stopPropagation();
                    e.preventDefault();
                }
            });
            
            this.searchInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
        
        // Select option
        this.allOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const value = option.dataset.value;
                const text = this.getOptionText(option);
                const hasNewgen = option.querySelector('.newgen-tag') !== null;
                
                let displayText = text;
                if (hasNewgen) {
                    displayText = text + ' [NewGen]';
                }
                this.trigger.querySelector('.selected-value').textContent = displayText;
                this.hiddenInput.value = value;
                
                this.allOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                
                if (this.searchInput) {
                    this.searchInput.value = '';
                    this.filterOptions('');
                }
                this.close();
            });
        });
        
        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.trigger.parentElement.contains(e.target)) {
                this.close();
                if (this.searchInput) {
                    this.searchInput.value = '';
                    this.filterOptions('');
                }
            }
        });
    }
    
    getOptionText(option) {
        const textNode = option.childNodes[0];
        let text = textNode ? textNode.nodeValue.trim() : '';
        text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        return text;
    }
    
    filterOptions(searchTerm) {
        let term = searchTerm.toLowerCase().trim();
        term = term.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        
        let hasVisible = false;
        
        this.allOptions.forEach(option => {
            let text = this.getOptionText(option).toLowerCase();
            text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            
            if (term === '' || text.includes(term)) {
                option.classList.remove('hidden');
                hasVisible = true;
            } else {
                option.classList.add('hidden');
            }
        });
        
        let noResultsMsg = this.optionsList.querySelector('.no-results');
        if (!hasVisible && term !== '') {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results';
                noResultsMsg.textContent = 'No careers found matching "' + searchTerm + '"';
                this.optionsList.appendChild(noResultsMsg);
            } else {
                noResultsMsg.textContent = 'No careers found matching "' + searchTerm + '"';
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
    
    open() {
        // Close all other modal custom selects
        const allTriggers = document.querySelectorAll('#edit_parent_trigger, #edit_child_trigger');
        allTriggers.forEach(trigger => {
            if (trigger !== this.trigger && trigger.classList.contains('open')) {
                const wrapper = trigger.closest('.custom-select-wrapper');
                const options = wrapper.querySelector('.custom-options');
                trigger.classList.remove('open');
                if (options) options.classList.remove('show');
                const otherSearch = options.querySelector('.search-input');
                if (otherSearch) {
                    otherSearch.value = '';
                    const otherOptionsList = options.querySelector('.options-list');
                    if (otherOptionsList) {
                        otherOptionsList.querySelectorAll('.custom-option').forEach(opt => {
                            opt.classList.remove('hidden');
                        });
                        const noResults = otherOptionsList.querySelector('.no-results');
                        if (noResults) noResults.remove();
                    }
                }
            }
        });
        
        this.trigger.classList.add('open');
        this.optionsContainer.classList.add('show');
        this.isOpen = true;
        
        if (this.searchInput) {
            setTimeout(() => {
                this.searchInput.focus();
                this.searchInput.value = '';
                this.filterOptions('');
            }, 100);
        }
    }
    
    close() {
        this.trigger.classList.remove('open');
        this.optionsContainer.classList.remove('show');
        this.isOpen = false;
    }
    
    setValue(value, text) {
        this.trigger.querySelector('.selected-value').textContent = text;
        this.hiddenInput.value = value;
        
        this.allOptions.forEach(option => {
            if (option.dataset.value == value) {
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    }
}

// Initialize modal custom selects when modal is shown
let modalParentSelect = null;
let modalChildSelect = null;

function initializeModalCustomSelects() {
    modalParentSelect = new ModalCustomSelect('edit_parent_trigger', 'edit_parent_options', 'edit_parent_input');
    modalChildSelect = new ModalCustomSelect('edit_child_trigger', 'edit_child_options', 'edit_child_input');
}

// Edit button click handler
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editCareerLinkModal');
    const editForm = document.getElementById('editCareerLinkForm');
    
    // Initialize modal custom selects when modal is first shown
    editModal.addEventListener('shown.bs.modal', function() {
        if (!modalParentSelect) {
            initializeModalCustomSelects();
        }
    });
    
    // Handle edit button clicks
    document.querySelectorAll('.edit-link-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const updateUrl = this.dataset.updateUrl;
            const parentId = this.dataset.parent;
            const childId = this.dataset.child;
            
            editForm.action = updateUrl;
            
            // Set parent value
            const parentOption = document.querySelector('#edit_parent_options .custom-option[data-value="' + parentId + '"]');
            if (parentOption) {
                const parentText = parentOption.childNodes[0].nodeValue.trim();
                const parentHasNewgen = parentOption.querySelector('.newgen-tag');
                const parentDisplayText = parentHasNewgen ? parentText + ' [NewGen]' : parentText;
                
                if (modalParentSelect) {
                    modalParentSelect.setValue(parentId, parentDisplayText);
                } else {
                    document.getElementById('edit_parent_selected').textContent = parentDisplayText;
                    document.getElementById('edit_parent_input').value = parentId;
                }
            }
            
            // Set child value
            const childOption = document.querySelector('#edit_child_options .custom-option[data-value="' + childId + '"]');
            if (childOption) {
                const childText = childOption.childNodes[0].nodeValue.trim();
                const childHasNewgen = childOption.querySelector('.newgen-tag');
                const childDisplayText = childHasNewgen ? childText + ' [NewGen]' : childText;
                
                if (modalChildSelect) {
                    modalChildSelect.setValue(childId, childDisplayText);
                } else {
                    document.getElementById('edit_child_selected').textContent = childDisplayText;
                    document.getElementById('edit_child_input').value = childId;
                }
            }
        });
    });
    
    // Form validation for edit modal
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const parentValue = document.getElementById('edit_parent_input').value;
            const childValue = document.getElementById('edit_child_input').value;
            
            if (!parentValue) {
                e.preventDefault();
                alert('Please select a Parent Career');
                return false;
            }
            
            if (!childValue) {
                e.preventDefault();
                alert('Please select a Child Career');
                return false;
            }
            
            if (parentValue === childValue) {
                e.preventDefault();
                alert('Parent Career and Child Career cannot be the same');
                return false;
            }
        });
    }
});
</script>


    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('editCareerLinkModal'));
                modal.show();
            });
        </script
    @endif

    <script>
        // AJAX Pagination Handler
        document.addEventListener('DOMContentLoaded', function() {
            const paginationContainer = document.getElementById('paginationLinks');

            if (paginationContainer) {
                paginationContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link && link.href) {
                        e.preventDefault();
                        loadPage(link.href);
                    }
                });
            }

            function loadPage(url) {
                // Show loading indicator
                const tableContainer = document.querySelector('.table-responsive');
                const originalContent = tableContainer.innerHTML;
                tableContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update table
                    const newTable = doc.querySelector('.table-responsive');
                    if (newTable) {
                        tableContainer.innerHTML = newTable.innerHTML;
                    }

                    // Update pagination
                    const newPagination = doc.querySelector('#paginationLinks');
                    if (newPagination) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }

                    // Update search results count if present
                    const searchResults = doc.querySelector('.col-md-12.mt-2 .text-muted');
                    const currentSearchResults = document.querySelector('.col-md-12.mt-2 .text-muted');
                    if (searchResults && currentSearchResults) {
                        currentSearchResults.innerHTML = searchResults.innerHTML;
                    }

                    // Update URL without reload
                    window.history.pushState({}, '', url);
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    tableContainer.innerHTML = originalContent;
                    alert('Error loading page. Please try again.');
                });
            }
        });
    </script>

@endsection
