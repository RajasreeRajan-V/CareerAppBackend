<!-- resources/views/admin/career_path_links.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Success Message -->
                @if(session('success'))
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
                        <form action="{{ route('admin.career_link.store') }}" method="POST" class="mb-4">
                            @csrf

                            <div class="card shadow-sm p-4">
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
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
                                        <select name="parent_career_id" id="parent_career_id"
                                            class="form-select form-select-lg" required>
                                            <option value="">Select Main Career</option>
                                            @foreach($careerNodes as $node)
                                                <option value="{{ $node->id }}">{{ $node->title }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">This will be the middle node in the hierarchy</small>
                                    </div>

                                    <!-- Parent Careers (Multiple) -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Parent Careers <span class="text-danger">*</span>
                                        </label>
                                        <div id="parents-container">
                                            <div class="parent-career-item mb-2">
                                                <div class="input-group">
                                                    <select name="parent_careers[]" class="form-select parent-select">
                                                        <option value="">Select Parent Career</option>
                                                        @foreach($careerNodes as $node)
                                                            <option value="{{ $node->id }}">{{ $node->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-outline-danger remove-parent"
                                                        style="display:none;">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
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
                                                <div class="input-group">
                                                    <select name="child_careers[]" class="form-select child-select">
                                                        <option value="">Select Child Career</option>
                                                        @foreach($careerNodes as $node)
                                                            <option value="{{ $node->id }}">{{ $node->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-outline-danger remove-child"
                                                        style="display:none;">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
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


                        <!-- Career Links Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
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
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $link->parent?->title ?? '-' }}</td>
                                            <td>{{ $link->child?->title ?? '-' }}</td>

                                            <td>
                                                <button class="btn btn-sm btn-primary edit-link-btn" data-bs-toggle="modal"
                                                    data-bs-target="#editCareerLinkModal"
                                                    data-update-url="{{ route('admin.career_link.update', $link->id) }}"
                                                    data-parent="{{ $link->parent_career_id }}"
                                                    data-child="{{ $link->child_career_id }}">
                                                    Edit
                                                </button>



                                                <form action="{{ route('admin.career_link.destroy', $link->id) }}" method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this career link?\n\n{{ $link->parent?->title }} → {{ $link->child?->title }}\n\nThis action cannot be undone!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No career links found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
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

                            <!-- Parent Career -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_parent_career_id" class="form-label fw-semibold">
                                    Parent Career <span class="text-danger">*</span>
                                </label>
                                <select name="parent_career_id" id="edit_parent_career_id" class="form-select" required>
                                    <option value="">Select Parent Career</option>
                                    @foreach($careerNodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Top-level career</small>
                            </div>

                            <!-- Child Career -->
                            <div class="col-md-6 mb-3">
                                <label for="edit_child_career_id" class="form-label fw-semibold">
                                    Child Career <span class="text-danger">*</span>
                                </label>
                                <select name="child_career_id" id="edit_child_career_id" class="form-select" required>
                                    <option value="">Select Child Career</option>
                                    @foreach($careerNodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Career under the parent</small>
                            </div>

                        </div>

                        <!-- Hierarchy Info -->
                        <div class="alert alert-info mb-0">
                            <strong>Hierarchy:</strong> Parent Career → Child Career
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            Update Career Link
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const childrenContainer = document.getElementById('children-container');
            const parentsContainer = document.getElementById('parents-container');
            const addChildBtn = document.getElementById('add-child-btn');
            const addParentBtn = document.getElementById('add-parent-btn');
            const form = document.querySelector('form[action="{{ route('admin.career_link.store') }}"]');

            // Add new child career dropdown
            addChildBtn.addEventListener('click', function () {
                const newChild = document.querySelector('.child-career-item').cloneNode(true);
                newChild.querySelector('select').value = '';
                newChild.querySelector('.remove-child').style.display = 'block';
                childrenContainer.appendChild(newChild);
                updateRemoveButtons(childrenContainer, '.child-career-item', '.remove-child');
            });

            // Add new parent career dropdown
            addParentBtn.addEventListener('click', function () {
                const newParent = document.querySelector('.parent-career-item').cloneNode(true);
                newParent.querySelector('select').value = '';
                newParent.querySelector('.remove-parent').style.display = 'block';
                parentsContainer.appendChild(newParent);
                updateRemoveButtons(parentsContainer, '.parent-career-item', '.remove-parent');
            });

            // Remove child career dropdown
            childrenContainer.addEventListener('click', function (e) {
                if (e.target.closest('.remove-child')) {
                    e.target.closest('.child-career-item').remove();
                    updateRemoveButtons(childrenContainer, '.child-career-item', '.remove-child');
                }
            });

            // Remove parent career dropdown
            parentsContainer.addEventListener('click', function (e) {
                if (e.target.closest('.remove-parent')) {
                    e.target.closest('.parent-career-item').remove();
                    updateRemoveButtons(parentsContainer, '.parent-career-item', '.remove-parent');
                }
            });

            function updateRemoveButtons(container, itemSelector, buttonSelector) {
                const items = container.querySelectorAll(itemSelector);
                items.forEach(item => {
                    const removeBtn = item.querySelector(buttonSelector);
                    removeBtn.style.display = items.length > 1 ? 'block' : 'none';
                });
            }

            //  UPDATED: Allow either parents OR children (not require both)
            form.addEventListener('submit', function (e) {

                const parentSelects = parentsContainer.querySelectorAll('select.parent-select');
                const childSelects = childrenContainer.querySelectorAll('select.child-select');

                const selectedParents = Array.from(parentSelects)
                    .map(s => s.value)
                    .filter(v => v !== '');

                const selectedChildren = Array.from(childSelects)
                    .map(s => s.value)
                    .filter(v => v !== '');

                //  NEW: At least one parent OR one child (not both required)
                if (selectedParents.length === 0 && selectedChildren.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one Parent Career or one Child Career');
                    return false;
                }

                //  Prevent duplicate parents
                if (new Set(selectedParents).size !== selectedParents.length) {
                    e.preventDefault();
                    alert('Duplicate Parent Careers selected. Please choose unique parents.');
                    return false;
                }

                //  Prevent duplicate children
                if (new Set(selectedChildren).size !== selectedChildren.length) {
                    e.preventDefault();
                    alert('Duplicate Child Careers selected. Please choose unique children.');
                    return false;
                }

                //  Prevent same career being parent & child
                const overlap = selectedParents.filter(id => selectedChildren.includes(id));
                if (overlap.length > 0) {
                    e.preventDefault();
                    alert('A career cannot be both Parent and Child.');
                    return false;
                }

                return true;
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const editForm = document.getElementById('editCareerLinkForm');

            document.querySelectorAll('.edit-link-btn').forEach(btn => {
                btn.addEventListener('click', function () {

                    // Set update URL
                    editForm.action = this.dataset.updateUrl;

                    // Fill values
                    document.getElementById('edit_parent_career_id').value = this.dataset.parent;
                    document.getElementById('edit_child_career_id').value = this.dataset.child;

                });
            });

        });
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(
                    document.getElementById('editCareerLinkModal')
                );
                modal.show();
            });
        </script>
    @endif


@endsection