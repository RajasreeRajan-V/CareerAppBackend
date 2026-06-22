@extends('college.layout.app')

@push('styles')
<style>
    /* ── PAGE HEADER ───────────────────────────── */
    .fac-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 28px;
    }

    .fac-header-left h2 {
        font-size: 1.45rem;
        font-weight: 700;
        color: #1e2d3d;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fac-header-left h2 .icon-badge {
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

    .fac-header-left p {
        margin: 0;
        font-size: .82rem;
        color: #8a97a6;
    }

    .fac-count-badge {
        background: #eaf3f3;
        color: #306060;
        font-size: .75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: .3px;
    }

    /* ── ADD CARD ──────────────────────────────── */
    .fac-add-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #e4e9ee;
        padding: 22px 24px;
        margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    .fac-add-card .card-label {
        font-size: .78rem;
        font-weight: 700;
        color: #8a97a6;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 12px;
    }

    .fac-add-row {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .fac-input-wrap {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .fac-input-wrap i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0bac6;
        font-size: .82rem;
        pointer-events: none;
    }

    .fac-input {
        width: 100%;
        padding: 10px 14px 10px 36px;
        border: 1.5px solid #e4e9ee;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .88rem;
        color: #1e2d3d;
        background: #f7f9fb;
        outline: none;
        transition: border-color .18s, box-shadow .18s, background .18s;
    }

    .fac-input:focus {
        border-color: #306060;
        background: #fff;
        box-shadow: 0 0 0 3.5px rgba(48,96,96,.12);
    }

    .fac-input::placeholder { color: #adb8c4; }

    .btn-add {
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
        box-shadow: 0 4px 14px rgba(48,96,96,.28);
    }

    .btn-add:hover {
        opacity: .9;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(48,96,96,.34);
    }

    .btn-add:active { transform: translateY(0); }

    /* ── FACILITIES TABLE CARD ─────────────────── */
    .fac-table-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #e4e9ee;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    .fac-table-head {
        display: grid;
        grid-template-columns: 52px 1fr auto;
        align-items: center;
        padding: 12px 22px;
        background: #f7f9fb;
        border-bottom: 1.5px solid #e4e9ee;
        font-size: .72rem;
        font-weight: 700;
        color: #8a97a6;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .fac-table-body {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .fac-row {
        display: grid;
        grid-template-columns: 52px 1fr auto;
        align-items: center;
        padding: 14px 22px;
        border-bottom: 1px solid #f0f4f7;
        transition: background .15s;
        gap: 8px;
    }

    .fac-row:last-child { border-bottom: none; }
    .fac-row:hover { background: #f7fbfb; }

    .fac-num {
        width: 28px;
        height: 28px;
        background: #eaf3f3;
        color: #306060;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
    }

    .fac-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .9rem;
        font-weight: 500;
        color: #1e2d3d;
    }

    .fac-name i {
        color: #306060;
        font-size: .8rem;
        opacity: .7;
    }

    .fac-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: 1.5px solid #306060;
        color: #306060;
        background: transparent;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }

    .btn-edit:hover {
        background: #306060;
        color: #fff;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
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

    /* ── EMPTY STATE ───────────────────────────── */
    .fac-empty {
        padding: 52px 24px;
        text-align: center;
    }

    .fac-empty .empty-icon {
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

    .fac-empty h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e2d3d;
        margin: 0 0 6px;
    }

    .fac-empty p {
        font-size: .83rem;
        color: #8a97a6;
        margin: 0;
    }

    /* ── ALERT MESSAGES ────────────────────────── */
    .fac-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 11px;
        font-size: .85rem;
        font-weight: 500;
        margin-bottom: 18px;
    }

    .fac-alert.success {
        background: #eaf7f0;
        color: #1a7f4b;
        border: 1px solid #c3e9d7;
    }

    .fac-alert.error {
        background: #fff5f5;
        color: #c0392b;
        border: 1px solid #fde0dd;
    }

    /* ── EDIT MODAL ────────────────────────────── */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 25, 36, 0.45);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity .22s ease, visibility .22s ease;
    }

    .modal-backdrop.open {
        opacity: 1;
        visibility: visible;
    }

    .modal-box {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 460px;
        box-shadow: 0 24px 60px rgba(0,0,0,.18);
        transform: translateY(18px) scale(.97);
        transition: transform .25s cubic-bezier(.34,1.56,.64,1);
        overflow: hidden;
    }

    .modal-backdrop.open .modal-box {
        transform: translateY(0) scale(1);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 18px;
        border-bottom: 1.5px solid #f0f4f7;
    }

    .modal-header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .modal-icon {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #306060, #254848);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .88rem;
        flex-shrink: 0;
    }

    .modal-header-left h3 {
        margin: 0 0 2px;
        font-size: 1rem;
        font-weight: 700;
        color: #1e2d3d;
    }

    .modal-header-left p {
        margin: 0;
        font-size: .75rem;
        color: #8a97a6;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        border: none;
        background: #f2f5f7;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #5a6a7a;
        font-size: .85rem;
        transition: background .15s, color .15s;
        flex-shrink: 0;
    }

    .modal-close:hover {
        background: #fde0dd;
        color: #c0392b;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-field-label {
        font-size: .75rem;
        font-weight: 700;
        color: #8a97a6;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 8px;
        display: block;
    }

    .modal-input-wrap {
        position: relative;
    }

    .modal-input-wrap i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #b0bac6;
        font-size: .82rem;
        pointer-events: none;
    }

    .modal-input {
        width: 100%;
        padding: 11px 14px 11px 36px;
        border: 1.5px solid #e4e9ee;
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .9rem;
        color: #1e2d3d;
        background: #f7f9fb;
        outline: none;
        transition: border-color .18s, box-shadow .18s, background .18s;
    }

    .modal-input:focus {
        border-color: #306060;
        background: #fff;
        box-shadow: 0 0 0 3.5px rgba(48,96,96,.12);
    }

    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px 20px;
        border-top: 1.5px solid #f0f4f7;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 20px;
        border-radius: 9px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #e4e9ee;
        color: #5a6a7a;
        background: #fff;
        transition: background .15s, border-color .15s;
    }

    .btn-cancel:hover {
        background: #f2f5f7;
        border-color: #d0d8e0;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 22px;
        background: linear-gradient(135deg, #306060, #254848);
        color: #fff;
        border: none;
        border-radius: 9px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .18s, transform .15s, box-shadow .18s;
        box-shadow: 0 4px 14px rgba(48,96,96,.28);
    }

    .btn-save:hover {
        opacity: .9;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(48,96,96,.34);
    }

    .btn-save:active { transform: translateY(0); }
</style>
@endpush

@section('content')

    {{-- Alerts --}}
    @if (session('success'))
        <div class="fac-alert success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="fac-alert error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="fac-header">
        <div class="fac-header-left">
            <h2>
                <span class="icon-badge"><i class="fa-solid fa-building-columns"></i></span>
                Facilities
            </h2>
            <p>Manage the facilities offered by your institution</p>
        </div>
        <span class="fac-count-badge">
            <i class="fa-solid fa-layer-group" style="margin-right:5px;"></i>
            {{ $facilities->count() }} {{ Str::plural('Facility', $facilities->count()) }}
        </span>
    </div>

    {{-- Add Facility Card --}}
    <div class="fac-add-card">
        <div class="card-label"><i class="fa-solid fa-plus" style="margin-right:5px;"></i>Add New Facility</div>
        <form action="{{ route('college.facilities.store') }}" method="POST">
            @csrf
            <div class="fac-add-row">
                <div class="fac-input-wrap">
                    <i class="fa-solid fa-tag"></i>
                    <input
                        class="fac-input"
                        type="text"
                        name="facility"
                        placeholder="e.g. Library, Sports Complex, Canteen…"
                        required
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                    Add Facility
                </button>
            </div>
        </form>
    </div>

    {{-- Facilities List --}}
    <div class="fac-table-card">

        @if ($facilities->isEmpty())
            <div class="fac-empty">
                <div class="empty-icon"><i class="fa-solid fa-building-columns"></i></div>
                <h4>No facilities added yet</h4>
                <p>Use the form above to add your first facility.</p>
            </div>
        @else
            <div class="fac-table-head">
                <span>#</span>
                <span>Facility Name</span>
                <span>Actions</span>
            </div>
            <ul class="fac-table-body">
                @foreach ($facilities as $key => $facility)
                    <li class="fac-row">
                        <div class="fac-num">{{ $key + 1 }}</div>

                        <div class="fac-name">
                            <i class="fa-solid fa-circle-dot"></i>
                            {{ $facility->facility }}
                        </div>

                        <div class="fac-actions">
                            {{-- Edit triggers modal instead of page redirect --}}
                            <button
                                type="button"
                                class="btn-edit"
                                onclick="openEditModal({{ $facility->id }}, '{{ addslashes($facility->facility) }}')"
                            >
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>

                            <form action="{{ route('college.facilities.destroy', $facility->id) }}" method="POST"
                                onsubmit="return confirm('Delete this facility?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fa-solid fa-trash-can"></i> Delete
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>

    {{-- ── EDIT MODAL ─────────────────────────── --}}
    <div class="modal-backdrop" id="editModal" onclick="handleBackdropClick(event)">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle">Edit Facility</h3>
                        <p>Update the facility name below</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeEditModal()" aria-label="Close modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <label class="modal-field-label" for="editFacilityInput">
                        <i class="fa-solid fa-tag" style="margin-right:5px;"></i>Facility Name
                    </label>
                    <div class="modal-input-wrap">
                        <i class="fa-solid fa-tag"></i>
                        <input
                            class="modal-input"
                            type="text"
                            id="editFacilityInput"
                            name="facility"
                            placeholder="Enter facility name…"
                            required
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    const editModal     = document.getElementById('editModal');
    const editForm      = document.getElementById('editForm');
    const editInput     = document.getElementById('editFacilityInput');
    const baseUpdateUrl = "{{ url('college/facilities') }}";

    function openEditModal(id, currentName) {
        // Point form action to the correct update route
        editForm.action = baseUpdateUrl + '/' + id;
        editInput.value = currentName;
        editModal.classList.add('open');
        // Focus after CSS transition completes
        setTimeout(() => editInput.focus(), 250);
    }

    function closeEditModal() {
        editModal.classList.remove('open');
    }

    // Click on dark backdrop (not the box) to close
    function handleBackdropClick(e) {
        if (e.target === editModal) closeEditModal();
    }

    // Escape key to close
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endpush