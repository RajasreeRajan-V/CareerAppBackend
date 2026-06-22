@extends('layouts.app')

@section('title', 'Location Management')

@section('content')

    <div x-data="{
        openState: false,
        openDistrict: false,
        openEditState: false,
        openEditDistrict: false,
        openDeleteState: false,
        openDeleteDistrict: false,
        editStateId: null,
        editStateName: '',
        editDistrictId: null,
        editDistrictName: '',
        editDistrictStateId: '',
        deleteStateId: null,
        deleteStateName: '',
        deleteDistrictId: null,
        deleteDistrictName: '',
        setEditState(id, name) {
            this.editStateId = id;
            this.editStateName = name;
            this.openEditState = true;
        },
        setDeleteState(id, name) {
            this.deleteStateId = id;
            this.deleteStateName = name;
            this.openDeleteState = true;
        },
        setEditDistrict(id, name, stateId) {
            this.editDistrictId = id;
            this.editDistrictName = name;
            this.editDistrictStateId = String(stateId);
            this.openEditDistrict = true;
        },
        setDeleteDistrict(id, name) {
            this.deleteDistrictId = id;
            this.deleteDistrictName = name;
            this.openDeleteDistrict = true;
        }
    }">

        {{-- ── Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-semibold mb-0 text-dark">State & District</h4>
                <small class="text-muted">Manage locations</small>
            </div>
            <div class="d-flex gap-2">
                <button @click="openState = true" class="btn btn-sm btn-teal">
                    <i class="fa-solid fa-plus me-1"></i> Add State
                </button>
                <button @click="openDistrict = true" class="btn btn-sm btn-indigo">
                    <i class="fa-solid fa-plus me-1"></i> Add District
                </button>
            </div>
        </div>

        {{-- ── Flash Messages ── --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2 mb-3"
                role="alert">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2 mb-3"
                role="alert">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── States Table ── --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <span class="fw-semibold text-dark">States</span>
                <span class="badge bg-light text-muted border">{{ $states->total() }} total</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold" style="font-size:11px; width:50px">#</th>
                            <th class="text-uppercase text-muted fw-semibold" style="font-size:11px">State Name</th>
                            <th class="text-uppercase text-muted fw-semibold text-end pe-4" style="font-size:11px">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $i => $state)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $states->firstItem() + $i }}</td>
                                <td class="fw-medium">{{ $state->name }}</td>
                                <td class="text-end pe-4">
                                    <button @click="setEditState({{ $state->id }}, '{{ addslashes($state->name) }}')"
                                        class="btn btn-sm btn-outline-teal me-1">
                                        <i class="fa-solid fa-pen fa-xs me-1"></i>Edit
                                    </button>
                                    <button @click="setDeleteState({{ $state->id }}, '{{ addslashes($state->name) }}')"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash fa-xs me-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-map-pin fa-2x d-block mb-2 opacity-25"></i>
                                    No states added yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($states->hasPages())
                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-2">
                    <small class="text-muted">
                        Showing {{ $states->firstItem() }}–{{ $states->lastItem() }} of {{ $states->total() }}
                    </small>
                    <div class="d-flex gap-1">
                        @if ($states->onFirstPage())
                            <span class="btn btn-sm btn-light disabled"><i
                                    class="fa-solid fa-chevron-left fa-xs"></i></span>
                        @else
                            <a href="{{ $states->previousPageUrl() }}&district_page={{ request('district_page', 1) }}"
                                class="btn btn-sm btn-light"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
                        @endif

                        @foreach ($states->getUrlRange(max(1, $states->currentPage() - 2), min($states->lastPage(), $states->currentPage() + 2)) as $page => $url)
                            @if ($page == $states->currentPage())
                                <span class="btn btn-sm btn-teal">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}&district_page={{ request('district_page', 1) }}"
                                    class="btn btn-sm btn-light">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($states->hasMorePages())
                            <a href="{{ $states->nextPageUrl() }}&district_page={{ request('district_page', 1) }}"
                                class="btn btn-sm btn-light"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
                        @else
                            <span class="btn btn-sm btn-light disabled"><i
                                    class="fa-solid fa-chevron-right fa-xs"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Districts Table ── --}}
        <div class="card border-0 shadow-sm mb-4">

            {{-- Card header: title + filter form --}}
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-dark">Districts</span>
                        <span class="badge bg-light text-muted border">{{ $districts->total() }} total</span>
                        {{-- Active filter badge --}}
                        @if (request('filter_state'))
                            <span class="badge bg-indigo-soft text-indigo border border-indigo-soft">
                                <i class="fa-solid fa-filter fa-xs me-1"></i>
                                {{ $allStates->firstWhere('id', request('filter_state'))?->name ?? 'Unknown' }}
                                <a href="{{ route('admin.createLocation.index', ['page' => request('page', 1), 'district_page' => request('district_page', 1)]) }}"
                                    class="text-indigo ms-1" style="text-decoration:none;" title="Clear filter">
                                    <i class="fa-solid fa-times fa-xs"></i>
                                </a>
                            </span>
                        @endif
                    </div>

                    {{-- Filter form --}}
                    <form method="GET" action="{{ route('admin.createLocation.index') }}"
                        class="d-flex align-items-center gap-2">
                        {{-- Preserve states pagination --}}
                        <input type="hidden" name="page" value="{{ request('page', 1) }}">

                        <div class="input-group input-group-sm" style="min-width:200px">
                            <label class="input-group-text bg-white text-muted" style="font-size:12px">
                                <i class="fa-solid fa-filter fa-xs"></i>
                            </label>
                            <select name="filter_state" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All States</option>
                                @foreach ($allStates as $state)
                                    <option value="{{ $state->id }}"
                                        {{ request('filter_state') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if (request('filter_state'))
                            <a href="{{ route('admin.createLocation.index', ['page' => request('page', 1)]) }}"
                                class="btn btn-sm btn-light" title="Clear filter">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold" style="font-size:11px; width:50px">#
                            </th>
                            <th class="text-uppercase text-muted fw-semibold" style="font-size:11px">District Name</th>
                            <th class="text-uppercase text-muted fw-semibold" style="font-size:11px">State</th>
                            <th class="text-uppercase text-muted fw-semibold text-end pe-4" style="font-size:11px">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districts as $i => $district)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $districts->firstItem() + $i }}</td>
                                <td class="fw-medium">{{ $district->name }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-indigo-soft text-indigo">
                                        {{ $district->state->name }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button
                                        @click="setEditDistrict({{ $district->id }}, '{{ addslashes($district->name) }}', {{ $district->state_id }})"
                                        class="btn btn-sm btn-outline-teal me-1">
                                        <i class="fa-solid fa-pen fa-xs me-1"></i>Edit
                                    </button>
                                    <button
                                        @click="setDeleteDistrict({{ $district->id }}, '{{ addslashes($district->name) }}')"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash fa-xs me-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-location-dot fa-2x d-block mb-2 opacity-25"></i>
                                    @if (request('filter_state'))
                                        No districts found for this state.
                                    @else
                                        No districts added yet.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($districts->hasPages())
                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-2">
                    <small class="text-muted">
                        Showing {{ $districts->firstItem() }}–{{ $districts->lastItem() }} of {{ $districts->total() }}
                    </small>
                    <div class="d-flex gap-1">
                        @if ($districts->onFirstPage())
                            <span class="btn btn-sm btn-light disabled"><i
                                    class="fa-solid fa-chevron-left fa-xs"></i></span>
                        @else
                            {{-- Preserve filter when paginating --}}
                            <a href="{{ $districts->previousPageUrl() }}&page={{ request('page', 1) }}&filter_state={{ request('filter_state') }}"
                                class="btn btn-sm btn-light"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
                        @endif

                        @foreach ($districts->getUrlRange(max(1, $districts->currentPage() - 2), min($districts->lastPage(), $districts->currentPage() + 2)) as $page => $url)
                            @if ($page == $districts->currentPage())
                                <span class="btn btn-sm btn-teal">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}&page={{ request('page', 1) }}&filter_state={{ request('filter_state') }}"
                                    class="btn btn-sm btn-light">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($districts->hasMorePages())
                            <a href="{{ $districts->nextPageUrl() }}&page={{ request('page', 1) }}&filter_state={{ request('filter_state') }}"
                                class="btn btn-sm btn-light"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
                        @else
                            <span class="btn btn-sm btn-light disabled"><i
                                    class="fa-solid fa-chevron-right fa-xs"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        </div>


        {{-- ════════════════════════════════════════
         MODALS — x-teleport moves them to <body>
    ════════════════════════════════════════ --}}

        {{-- Add State --}}
        <template x-teleport="body">
            <div x-show="openState" x-cloak class="loc-overlay" @click.self="openState = false"
                @keydown.escape.window="openState = false">
                <div class="loc-modal">
                    <div class="loc-modal-header">
                        <h6 class="mb-0 fw-semibold">Add State</h6>
                        <button type="button" @click="openState = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.createLocation.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="state">
                        <div class="loc-modal-body">
                            <label class="form-label small fw-medium">State Name</label>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="e.g. Kerala" required>
                        </div>
                        <div class="loc-modal-footer">
                            <button type="button" @click="openState = false"
                                class="btn btn-sm btn-light">Cancel</button>
                            <button class="btn btn-sm btn-teal">Save State</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Add District --}}
        <template x-teleport="body">
            <div x-show="openDistrict" x-cloak class="loc-overlay" @click.self="openDistrict = false"
                @keydown.escape.window="openDistrict = false">
                <div class="loc-modal">
                    <div class="loc-modal-header">
                        <h6 class="mb-0 fw-semibold">Add District</h6>
                        <button type="button" @click="openDistrict = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.createLocation.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="district">
                        <div class="loc-modal-body">
                            <label class="form-label small fw-medium">State</label>
                            <select name="state_id" class="form-select form-select-sm mb-3" required>
                                <option value="">Select State</option>
                                @foreach ($allStates as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label small fw-medium">District Name</label>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="e.g. Kozhikode" required>
                        </div>
                        <div class="loc-modal-footer">
                            <button type="button" @click="openDistrict = false"
                                class="btn btn-sm btn-light">Cancel</button>
                            <button class="btn btn-sm btn-indigo">Save District</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Edit State --}}
        <template x-teleport="body">
            <div x-show="openEditState" x-cloak class="loc-overlay" @click.self="openEditState = false"
                @keydown.escape.window="openEditState = false">
                <div class="loc-modal">
                    <div class="loc-modal-header">
                        <h6 class="mb-0 fw-semibold">Edit State</h6>
                        <button type="button" @click="openEditState = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" :action="'{{ url('admin/createLocation') }}/' + editStateId">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="state">
                        <input type="hidden" name="id" :value="editStateId">
                        <div class="loc-modal-body">
                            <label class="form-label small fw-medium">State Name</label>
                            <input type="text" name="name" x-model="editStateName"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="loc-modal-footer">
                            <button type="button" @click="openEditState = false"
                                class="btn btn-sm btn-light">Cancel</button>
                            <button class="btn btn-sm btn-teal">Update State</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Edit District --}}
        <template x-teleport="body">
            <div x-show="openEditDistrict" x-cloak class="loc-overlay" @click.self="openEditDistrict = false"
                @keydown.escape.window="openEditDistrict = false">
                <div class="loc-modal">
                    <div class="loc-modal-header">
                        <h6 class="mb-0 fw-semibold">Edit District</h6>
                        <button type="button" @click="openEditDistrict = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form method="POST" :action="'{{ url('admin/createLocation') }}/' + editStateId">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="district">
                        <input type="hidden" name="id" :value="editDistrictId">
                        <div class="loc-modal-body">
                            <label class="form-label small fw-medium">State</label>
                            <select name="state_id" x-model="editDistrictStateId" class="form-select form-select-sm mb-3"
                                required>
                                <option value="">Select State</option>
                                @foreach ($allStates as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <label class="form-label small fw-medium">District Name</label>
                            <input type="text" name="name" x-model="editDistrictName"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="loc-modal-footer">
                            <button type="button" @click="openEditDistrict = false"
                                class="btn btn-sm btn-light">Cancel</button>
                            <button class="btn btn-sm btn-indigo">Update District</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Delete State --}}
        <template x-teleport="body">
            <div x-show="openDeleteState" x-cloak class="loc-overlay" @click.self="openDeleteState = false"
                @keydown.escape.window="openDeleteState = false">
                <div class="loc-modal loc-modal-sm">
                    <div class="loc-modal-header" style="border-bottom:0; padding-bottom:0">
                        <div></div>
                        <button type="button" @click="openDeleteState = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="loc-modal-body text-center pt-2">
                        <div class="loc-danger-icon mb-3">
                            <i class="fa-solid fa-trash"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">Delete State</h6>
                        <p class="text-muted small mb-0">
                            Are you sure you want to delete
                            <strong x-text="deleteStateName" class="text-dark"></strong>?
                            <br>This will also remove all associated districts.
                        </p>
                    </div>
                    <form method="POST" :action="`{{ url('admin/createLocation') }}/${deleteDistrictId}`">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="type" value="state">
                        <input type="hidden" name="id" :value="deleteStateId">

                        <div class="loc-modal-footer">
                            <button type="button" @click="openDeleteDistrict = false"
                                class="btn btn-sm btn-light flex-fill">Cancel</button>
                            <button class="btn btn-sm btn-danger flex-fill">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Delete District --}}
        <template x-teleport="body">
            <div x-show="openDeleteDistrict" x-cloak class="loc-overlay" @click.self="openDeleteDistrict = false"
                @keydown.escape.window="openDeleteDistrict = false">

                <div class="loc-modal loc-modal-sm">

                    <div class="loc-modal-header" style="border-bottom:0; padding-bottom:0">
                        <div></div>
                        <button type="button" @click="openDeleteDistrict = false" class="loc-close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>

                    <div class="loc-modal-body text-center pt-2">
                        <div class="loc-danger-icon mb-3">
                            <i class="fa-solid fa-trash"></i>
                        </div>

                        <h6 class="fw-semibold mb-1">Delete District</h6>

                        <p class="text-muted small mb-0">
                            Are you sure you want to delete
                            <strong x-text="deleteDistrictName" class="text-dark"></strong>?
                        </p>
                    </div>

                    <form method="POST" :action="`{{ url('admin/createLocation') }}/${deleteDistrictId}`">

                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="type" value="district">
                        <input type="hidden" name="id" :value="deleteDistrictId">

                        <div class="loc-modal-footer">
                            <button type="button" @click="openDeleteDistrict = false"
                                class="btn btn-sm btn-light flex-fill">
                                Cancel
                            </button>

                            <button class="btn btn-sm btn-danger flex-fill">
                                Yes, Delete
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </template>

    </div>

    <style>
        .btn-teal {
            background: #0d9488;
            color: #fff;
            border-color: #0d9488;
        }

        .btn-teal:hover {
            background: #0f766e;
            color: #fff;
            border-color: #0f766e;
        }

        .btn-indigo {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
        }

        .btn-indigo:hover {
            background: #4338ca;
            color: #fff;
            border-color: #4338ca;
        }

        .btn-outline-teal {
            color: #0d9488;
            border-color: #0d9488;
        }

        .btn-outline-teal:hover {
            background: #f0fdfa;
            color: #0f766e;
            border-color: #0f766e;
        }

        .bg-indigo-soft {
            background: #eef2ff;
        }

        .text-indigo {
            color: #4f46e5;
        }

        .border-indigo-soft {
            border-color: #c7d2fe !important;
        }

        .loc-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 16px;
        }

        .loc-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .loc-modal-sm {
            max-width: 360px;
        }

        .loc-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .loc-modal-body {
            padding: 16px 20px;
        }

        .loc-modal-footer {
            display: flex;
            gap: 8px;
            padding: 12px 20px;
            border-top: 1px solid #f1f5f9;
            justify-content: flex-end;
        }

        .loc-close-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1;
        }

        .loc-close-btn:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .loc-danger-icon {
            width: 52px;
            height: 52px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #ef4444;
            font-size: 20px;
        }
    </style>
@endsection
