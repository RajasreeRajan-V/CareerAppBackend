@extends('layouts.app')

@section('title', 'College Registrations')

@section('content')
    <div class="container-fluid px-4">

        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">College Registrations</h1>
                <p class="text-muted mb-0">List of registered colleges</p>
            </div>

            <!-- Add Button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCollegeModal">
                <i class="fas fa-plus me-2"></i>Add College
            </button>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>College Name</th>
                                <th>Email</th>
                                <th>Contact No</th>
                                <th>Principal</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Pincode</th>
                                <th>Website</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($collegeRegistrations as $college)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $college->college_name }}</td>
                                    <td>{{ $college->email }}</td>
                                    <td>{{ $college->contact_no }}</td>
                                    <td>{{ $college->principal_name }}</td>
                                    <td>{{ $college->city }}</td>
                                    <td>{{ $college->state }}</td>
                                    <td>{{ $college->pincode }}</td>


                                    <td>
                                        @if ($college->website)
                                            <a href="{{ $college->website }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                Visit
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td style="max-width:200px">
                                        {{ $college->address }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">

                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-outline-primary editCollegeBtn"
                                                data-bs-toggle="modal" data-bs-target="#editCollegeModal"
                                                data-id="{{ $college->id }}"
                                                data-college_name="{{ $college->college_name }}"
                                                data-principal_name="{{ $college->principal_name }}"
                                                data-email="{{ $college->email }}"
                                                data-contact_no="{{ $college->contact_no }}"
                                                data-website="{{ $college->website }}" data-city="{{ $college->city }}"
                                                data-state="{{ $college->state }}" data-pincode="{{ $college->pincode }}"
                                                data-address="{{ $college->address }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.college_registration.destroy', $college->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this college?')">

                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        No colleges registered yet
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $collegeRegistrations->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- =========================
                CREATE COLLEGE MODAL
        ========================= -->
    <div class="modal fade" id="createCollegeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">

            <form action="{{ route('admin.college_registration.store') }}" method="POST">
                @csrf

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Register College</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Row 1 -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">College Name</label>
                                <select name="college_id" class="form-select @error('college_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Select College --</option>
                                    @if(isset($colleges) && $colleges->count())
                                        @foreach ($colleges as $collegeOption)
                                            <option value="{{ $collegeOption->id }}" {{ old('college_id') == $collegeOption->id ? 'selected' : '' }}>{{ $collegeOption->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No colleges available</option>
                                    @endif
                                </select>
                                @error('college_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Principal Name</label>
                                <input type="text" name="principal_name" value="{{ old('principal_name') }}" class="form-control @error('principal_name') is-invalid @enderror" required>
                                @error('principal_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Row 2 -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="tel" name="contact_no" value="{{ old('contact_no') }}" class="form-control @error('contact_no') is-invalid @enderror" required pattern="[0-9]{7,12}" maxlength="12" inputmode="numeric" title="Enter digits only (7-12 characters)">
                                @error('contact_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Row 3 -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" name="website" value="{{ old('website') }}" class="form-control @error('website') is-invalid @enderror">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Row 4 -->
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-control @error('pincode') is-invalid @enderror" required>
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Save College
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <script>
        const collegeRegisterData = @json($colleges->keyBy('id'));

        function parseLocation(location) {
            if (!location) {
                return { address: '', city: '', state: '' };
            }
            const parts = location.split(',').map(part => part.trim());
            if (parts.length >= 3) {
                return {
                    address: parts.slice(0, parts.length - 2).join(', '),
                    city: parts[parts.length - 2] || '',
                    state: parts[parts.length - 1] || '',
                };
            }
            return {
                address: parts[0] || '',
                city: parts[1] || '',
                state: parts[2] || '',
            };
        }

        function setAutoFillReadonly(enabled) {
            const fields = ['email', 'contact_no', 'website', 'city', 'state', 'address'];
            fields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (el) {
                    el.readOnly = enabled;
                    if (!enabled) {
                        el.classList.remove('bg-light');
                    } else {
                        el.classList.add('bg-light');
                    }
                }
            });
        }

        function fillCollegeRegistrationFields(collegeId) {
            const data = collegeRegisterData[collegeId];
            if (!data) {
                ['email', 'contact_no', 'website', 'city', 'state', 'address'].forEach(name => {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (el) {
                        el.value = '';
                    }
                });
                setAutoFillReadonly(false);
                return;
            }

            const location = parseLocation(data.location);
            document.querySelector('[name="email"]').value = data.email || '';
            document.querySelector('[name="contact_no"]').value = data.phone || '';
            document.querySelector('[name="website"]').value = data.website || '';
            document.querySelector('[name="city"]').value = location.city;
            document.querySelector('[name="state"]').value = location.state;
            document.querySelector('[name="address"]').value = location.address;
            setAutoFillReadonly(true);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const select = document.querySelector('[name="college_id"]');
            if (!select) {
                return;
            }

            select.addEventListener('change', function () {
                fillCollegeRegistrationFields(this.value);
            });

            if (select.value) {
                fillCollegeRegistrationFields(select.value);
            }
        });
    </script>

    <div class="modal fade" id="editCollegeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">

            <form method="POST" id="editCollegeForm">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Edit College</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>College Name</label>
                                <input type="text" name="college_name" id="edit_college_name" class="form-control @error('college_name') is-invalid @enderror" required>
                                @error('college_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Principal Name</label>
                                <input type="text" name="principal_name" id="edit_principal_name" class="form-control @error('principal_name') is-invalid @enderror" required>
                                @error('principal_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Contact</label>
                                <input type="tel" name="contact_no" id="edit_contact_no" class="form-control @error('contact_no') is-invalid @enderror" required pattern="[0-9]{7,12}" maxlength="12" inputmode="numeric" title="Enter digits only (7-12 characters)">
                                @error('contact_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Website</label>
                                <input type="text" name="website" id="edit_website" class="form-control @error('website') is-invalid @enderror">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>City</label>
                                <input type="text" name="city" id="edit_city" class="form-control @error('city') is-invalid @enderror" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>State</label>
                                <input type="text" name="state" id="edit_state" class="form-control @error('state') is-invalid @enderror" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Pincode</label>
                                <input type="text" name="pincode" id="edit_pincode" class="form-control @error('pincode') is-invalid @enderror" required>
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" id="edit_address" class="form-control @error('address') is-invalid @enderror" required></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.editCollegeBtn').forEach(button => {

                button.addEventListener('click', function() {

                    let id = this.dataset.id;

                    let updateUrl = "{{ route('admin.college_registration.update', ['college_registration' => 0]) }}";
                    document.getElementById('editCollegeForm').action = updateUrl.replace('/0', `/${id}`);

                    document.getElementById('edit_college_name').value = this.dataset.college_name;
                    document.getElementById('edit_principal_name').value = this.dataset.principal_name;
                    document.getElementById('edit_email').value = this.dataset.email;
                    document.getElementById('edit_contact_no').value = this.dataset.contact_no;
                    document.getElementById('edit_website').value = this.dataset.website;
                    document.getElementById('edit_city').value = this.dataset.city;
                    document.getElementById('edit_state').value = this.dataset.state;
                    document.getElementById('edit_pincode').value = this.dataset.pincode;
                    document.getElementById('edit_address').value = this.dataset.address;

                });

            });

            // Show create modal if there are validation errors
            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function() {
                    var createModal = new bootstrap.Modal(document.getElementById('createCollegeModal'));
                    createModal.show();
                });
            @endif
        </script>
    @endpush
@endsection
