@extends('college.layout.app')

@section('title', 'Fee Structure — ' . ($course->name ?? 'Course'))

@section('content')

<style>
    :root {
        --brand:        #306060;
        --brand-dark:   #254848;
        --brand-light:  rgba(48,96,96,.08);
        --brand-mid:    rgba(48,96,96,.15);
        --surface:      #fff;
        --border:       #e4e9ee;
        --text:         #1e2d3d;
        --text-muted:   #8a97a6;
        --radius:       12px;
        --radius-sm:    8px;
    }

    /* ── Breadcrumb ── */
    .breadcrumb-nav {
        display: flex; align-items: center; gap: 6px;
        font-size: .8rem; margin-bottom: 16px; flex-wrap: wrap;
    }
    .breadcrumb-nav a { color: var(--text-muted); text-decoration: none; transition: color .15s; }
    .breadcrumb-nav a:hover { color: var(--brand); }
    .breadcrumb-nav .sep { color: #d0d8e0; font-size: .65rem; }
    .breadcrumb-nav .current { color: var(--text); font-weight: 500; }

    /* ── Page header ── */
    .page-header {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 16px;
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .page-title { font-size: 1.2rem; font-weight: 700; color: var(--text); margin: 0 0 4px; }
    .page-sub   { font-size: .82rem; color: var(--text-muted); margin: 0; }
    .page-sub span { font-weight: 600; color: var(--text); }

    /* ── Buttons ── */
    .btn-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; background: #fff;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        font-size: .82rem; font-weight: 500; color: var(--text-muted);
        text-decoration: none; transition: background .15s, color .15s; white-space: nowrap;
    }
    .btn-back:hover { background: #f2f5f7; color: var(--text); }

    .btn-add {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 18px; background: var(--brand); border: none;
        border-radius: var(--radius-sm); font-size: .82rem; font-weight: 600;
        color: #fff; text-decoration: none; transition: background .15s; white-space: nowrap;
    }
    .btn-add:hover { background: var(--brand-dark); color: #fff; }

    /* ── Alerts ── */
    .alert-success {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-left: 4px solid #22c55e; border-radius: var(--radius-sm);
        padding: 12px 16px; margin-bottom: 20px; font-size: .82rem; color: #166534;
    }
    .alert-error {
        background: #fef2f2; border: 1px solid #fecaca;
        border-left: 4px solid #ef4444; border-radius: var(--radius-sm);
        padding: 12px 16px; margin-bottom: 20px; font-size: .82rem; color: #991b1b;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 60px 20px; color: var(--text-muted);
        background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
    }
    .empty-state i { font-size: 2.5rem; opacity: .2; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: .9rem; margin: 0 0 16px; }

    /* ── Fee grid ── */
    .fee-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }

    .fee-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); box-shadow: 0 1px 6px rgba(0,0,0,.05); overflow: hidden;
    }
    .fee-card-header {
        padding: 14px 18px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }

    .fee-type-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 20px;
        font-size: .78rem; font-weight: 700; letter-spacing: .02em;
    }
    .fee-type-badge.government { background: #e0f2fe; color: #0369a1; }
    .fee-type-badge.management { background: #fef9c3; color: #854d0e; }
    .fee-type-badge.nri        { background: #f0fdf4; color: #166534; }

    .fee-mode-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; background: var(--brand-light);
        border: 1px solid var(--brand-mid); border-radius: 20px;
        font-size: .72rem; font-weight: 600; color: var(--brand);
    }

    .fee-card-body { padding: 16px 18px; }

    .breakdown-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .breakdown-table th {
        text-align: left; font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted);
        padding: 6px 10px; border-bottom: 1.5px solid var(--border);
    }
    .breakdown-table td {
        padding: 9px 10px; border-bottom: 1px solid #f1f5f9;
        color: var(--text); vertical-align: middle;
    }
    .breakdown-table tr:last-child td { border-bottom: none; }
    .breakdown-table .seq-cell { color: var(--text-muted); font-size: .75rem; width: 36px; text-align: center; }
    .breakdown-table .amount-cell { text-align: right; font-weight: 600; color: var(--brand-dark); white-space: nowrap; }

    .fee-card-footer {
        padding: 12px 18px; border-top: 1.5px solid var(--border);
        background: var(--brand-light);
        display: flex; align-items: center; justify-content: space-between;
    }
    .total-label { font-size: .82rem; font-weight: 500; color: var(--brand-dark); }
    .total-value { font-size: 1.1rem; font-weight: 700; color: var(--brand); letter-spacing: -.02em; }

    /* ── Card action buttons ── */
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; background: #fff;
        border: 1.5px solid #fca5a5; border-radius: var(--radius-sm);
        font-size: .75rem; font-weight: 600; color: #dc2626;
        cursor: pointer; font-family: inherit; transition: background .15s;
    }
    .btn-delete:hover { background: #fef2f2; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; background: #fff;
        border: 1.5px solid var(--brand-mid); border-radius: var(--radius-sm);
        font-size: .75rem; font-weight: 600; color: var(--brand);
        cursor: pointer; font-family: inherit; transition: background .15s;
    }
    .btn-edit:hover { background: var(--brand-light); }

    /* ══════════════════════════════════════════
       EDIT MODAL
    ══════════════════════════════════════════ */
    .modal-backdrop {
        position: fixed; inset: 0;
        background: rgba(15,25,35,.45);
        backdrop-filter: blur(3px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; padding: 16px;
        opacity: 0; pointer-events: none;
        transition: opacity .2s ease;
    }
    .modal-backdrop.open { opacity: 1; pointer-events: all; }

    .modal-box {
        background: #fff; border-radius: var(--radius);
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
        width: 100%; max-width: 580px;
        max-height: 90vh; overflow-y: auto;
        transform: translateY(14px) scale(.98);
        transition: transform .22s ease, opacity .22s ease;
        opacity: 0;
    }
    .modal-backdrop.open .modal-box { transform: translateY(0) scale(1); opacity: 1; }

    .modal-header {
        position: sticky; top: 0; z-index: 2;
        padding: 18px 22px 16px;
        border-bottom: 1px solid var(--border);
        background: #fff;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .modal-title { font-size: 1rem; font-weight: 700; color: var(--text); margin: 0; }
    .modal-subtitle { font-size: .77rem; color: var(--text-muted); margin: 2px 0 0; }

    .modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        border: 1.5px solid var(--border); background: #fff;
        color: var(--text-muted); font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s; flex-shrink: 0;
    }
    .modal-close:hover { background: #f2f5f7; color: var(--text); }

    .modal-body { padding: 22px; }

    /* Form elements inside modal */
    .form-label {
        display: block; font-size: .77rem; font-weight: 600;
        color: var(--text); margin-bottom: 6px;
    }
    .form-control {
        width: 100%; padding: 9px 12px;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        font-size: .84rem; color: var(--text); font-family: inherit;
        background: #fff; transition: border-color .15s, box-shadow .15s;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none; border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(48,96,96,.1);
    }
    .form-control:read-only { background: #f8fafc; color: var(--text-muted); cursor: not-allowed; }

    .form-row { margin-bottom: 16px; }
    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }

    /* Breakdown rows inside modal */
    .breakdown-editor { margin-top: 20px; }
    .breakdown-editor-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .breakdown-editor-title {
        font-size: .82rem; font-weight: 700; color: var(--text);
        text-transform: uppercase; letter-spacing: .06em;
    }
    .btn-add-row {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; background: var(--brand-light);
        border: 1.5px solid var(--brand-mid); border-radius: var(--radius-sm);
        font-size: .75rem; font-weight: 600; color: var(--brand);
        cursor: pointer; font-family: inherit; transition: background .15s;
    }
    .btn-add-row:hover { background: rgba(48,96,96,.14); }

    .bd-row {
        display: grid; grid-template-columns: 36px 1fr 130px 32px;
        gap: 8px; align-items: center; margin-bottom: 8px;
    }
    .bd-seq {
        text-align: center; font-size: .78rem; font-weight: 700;
        color: var(--text-muted); padding-top: 2px;
    }
    .btn-remove-row {
        width: 30px; height: 34px; border: 1.5px solid #fca5a5;
        border-radius: var(--radius-sm); background: #fff; color: #dc2626;
        font-size: .8rem; cursor: pointer; display: flex;
        align-items: center; justify-content: center; transition: background .15s;
        flex-shrink: 0;
    }
    .btn-remove-row:hover { background: #fef2f2; }

    .grand-total-preview {
        margin-top: 14px; padding: 12px 16px;
        background: var(--brand-light); border: 1px solid var(--brand-mid);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: space-between;
    }
    .gt-label { font-size: .82rem; font-weight: 500; color: var(--brand-dark); }
    .gt-value { font-size: 1.05rem; font-weight: 700; color: var(--brand); }

    .modal-footer {
        position: sticky; bottom: 0; z-index: 2;
        padding: 14px 22px;
        border-top: 1px solid var(--border);
        background: #fff;
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    }
    .btn-cancel {
        padding: 8px 18px; background: #fff;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        font-size: .83rem; font-weight: 500; color: var(--text-muted);
        cursor: pointer; font-family: inherit; transition: background .15s;
    }
    .btn-cancel:hover { background: #f2f5f7; color: var(--text); }

    .btn-save {
        padding: 8px 22px; background: var(--brand); border: none;
        border-radius: var(--radius-sm); font-size: .83rem; font-weight: 600;
        color: #fff; cursor: pointer; font-family: inherit; transition: background .15s;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-save:hover { background: var(--brand-dark); }
    .btn-save:disabled { opacity: .6; cursor: not-allowed; }

    /* ── Modal fee-type card radios ── */
    .modal-type-group {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
    }
    .modal-type-option { cursor: pointer; }
    .modal-type-option input[type="radio"] { display: none; }
    .modal-type-card {
        display: flex; flex-direction: column; align-items: center;
        gap: 4px; padding: 12px 8px;
        border: 1.5px solid var(--border); border-radius: var(--radius-sm);
        background: #fff; text-align: center;
        transition: border-color .15s, background .15s, box-shadow .15s;
        font-size: .85rem; color: var(--text-muted);
    }
    .modal-type-card i { font-size: 1.1rem; margin-bottom: 2px; }
    .modal-type-option input:checked + .modal-type-card {
        border-color: var(--brand); background: var(--brand-light);
        color: var(--brand); box-shadow: 0 0 0 3px rgba(48,96,96,.1);
    }
    .modal-type-card:hover { border-color: var(--brand-mid); background: var(--brand-light); }
    .mtc-label { font-size: .82rem; font-weight: 700; color: inherit; }
    .mtc-desc  { font-size: .7rem;  color: var(--text-muted); }
    .modal-type-option input:checked + .modal-type-card .mtc-desc { color: var(--brand-dark); }

    /* ── Modal fee-mode pill radios ── */
    .modal-pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .modal-pill-option { cursor: pointer; }
    .modal-pill-option input[type="radio"] { display: none; }
    .modal-pill-option span {
        display: inline-flex; align-items: center;
        padding: 7px 16px;
        border: 1.5px solid var(--border); border-radius: 20px;
        font-size: .82rem; font-weight: 600; color: var(--text-muted);
        background: #fff; transition: border-color .15s, background .15s, color .15s;
        white-space: nowrap;
    }
    .modal-pill-option input:checked + span {
        border-color: var(--brand); background: var(--brand-light); color: var(--brand);
        box-shadow: 0 0 0 3px rgba(48,96,96,.1);
    }
    .modal-pill-option span:hover { border-color: var(--brand-mid); background: var(--brand-light); }

    /* ── Conflict hint ── */
    .modal-field-hint {
        margin-top: 6px; font-size: .76rem;
        color: #b45309; display: flex; align-items: center; gap: 5px;
    }
</style>

{{-- Breadcrumb --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('college.feeStructure.index') }}">Fee Structures</a>
    <i class="fa-solid fa-chevron-right sep"></i>
    <span class="current">{{ $course->name }}</span>
</nav>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h4 class="page-title">
            <i class="fa-solid fa-list-ul" style="color:var(--brand); margin-right:8px;"></i>
            {{ $course->name }}
        </h4>
        <p class="page-sub">
            <i class="fa-solid fa-building" style="font-size:.75rem; margin-right:4px;"></i>
            <span>{{ $college->college_name ?? $college->name ?? '—' }}</span>
            &nbsp;·&nbsp;
            {{ $course->feeStructures->count() }} fee structure(s)
        </p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('college.feeStructure.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        @if($course->feeStructures->count() < 9)
            <a href="{{ route('college.feeStructure.create', $course->id) }}" class="btn-add">
                <i class="fa-solid fa-plus" style="font-size:.75rem"></i> Add Fee Structure
            </a>
        @endif
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-error">
        <i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Empty State --}}
@if($course->feeStructures->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <p>No fee structures added for this course yet.</p>
        <a href="{{ route('college.feeStructure.create', $course->id) }}" class="btn-add">
            <i class="fa-solid fa-plus" style="font-size:.75rem"></i> Add Fee Structure
        </a>
    </div>

@else

    <div class="fee-grid">
        @foreach($course->feeStructures as $fee)
            <div class="fee-card">

                {{-- Card Header --}}
                <div class="fee-card-header">
                    <span class="fee-type-badge {{ $fee->fee_type }}">
                        @if($fee->fee_type === 'government')
                            <i class="fa-solid fa-landmark"></i> Government
                        @elseif($fee->fee_type === 'management')
                            <i class="fa-solid fa-building"></i> Management
                        @else
                            <i class="fa-solid fa-earth-asia"></i> NRI
                        @endif
                    </span>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="fee-mode-pill">
                            @if($fee->fee_mode === 'total')
                                <i class="fa-solid fa-sigma" style="font-size:.7rem"></i> Total
                            @elseif($fee->fee_mode === 'yearly')
                                <i class="fa-solid fa-calendar" style="font-size:.7rem"></i> Yearly
                            @else
                                <i class="fa-solid fa-calendar-half" style="font-size:.7rem"></i> Semester
                            @endif
                        </span>

                        {{-- Edit button --}}
                        <button type="button" class="btn-edit"
                            onclick="openEditModal(
                                {{ $fee->id }},
                                '{{ $fee->fee_type }}',
                                '{{ $fee->fee_mode }}',
                                '{{ $fee->currency }}',
                                {{ $fee->breakdowns->toJson() }},
                                '{{ route('college.feeStructure.update', ['course' => $course->id, 'feeStructure' => $fee->id]) }}'
                            )">
                            <i class="fa-solid fa-pen-to-square" style="font-size:.7rem"></i> Edit
                        </button>

                        <form method="POST"
                              action="{{ route('college.feeStructure.destroy', $fee->id) }}"
                              onsubmit="return confirm('Delete this fee structure and all its breakdowns?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fa-solid fa-trash" style="font-size:.7rem"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Breakdown Table --}}
                <div class="fee-card-body">
                    @if($fee->breakdowns->isEmpty())
                        <p style="font-size:.82rem; color:var(--text-muted); margin:0; text-align:center; padding:12px 0;">
                            No breakdown items.
                        </p>
                    @else
                        <table class="breakdown-table">
                            <thead>
                                <tr>
                                    <th class="seq-cell">#</th>
                                    <th>Label</th>
                                    <th style="text-align:right;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fee->breakdowns as $breakdown)
                                    <tr>
                                        <td class="seq-cell">{{ $breakdown->sequence }}</td>
                                        <td>{{ $breakdown->label }}</td>
                                        <td class="amount-cell">
                                            {{ number_format($breakdown->amount, 0) }}
                                            <span style="font-size:.7rem; color:var(--text-muted); font-weight:400;">
                                                {{ $fee->currency }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="fee-card-footer">
                    <span class="total-label">
                        <i class="fa-solid fa-sigma" style="margin-right:5px; font-size:.78rem"></i>
                        Grand Total
                    </span>
                    <span class="total-value">
                        {{ $fee->currency }}
                        {{ number_format($fee->total_amount, 0) }}
                    </span>
                </div>

            </div>
        @endforeach
    </div>

@endif


{{-- ══════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════ --}}
<div class="modal-backdrop" id="editModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-box">

        <div class="modal-header">
            <div>
                <p class="modal-title" id="modalTitle">Edit Fee Structure</p>
                <p class="modal-subtitle" id="modalSubtitle">Update breakdowns and currency</p>
            </div>
            <button class="modal-close" onclick="closeEditModal()" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editFeeForm" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body">

                {{-- Fee Type — selectable card radios --}}
                <div class="form-row">
                    <label class="form-label">Fee Type <span style="color:#dc2626">*</span></label>
                    <div class="modal-type-group" id="modalFeeTypeGroup">

                        <label class="modal-type-option">
                            <input type="radio" name="fee_type" value="government">
                            <div class="modal-type-card">
                                <i class="fa-solid fa-landmark"></i>
                                <span class="mtc-label">Government</span>
                                <span class="mtc-desc">Govt. quota</span>
                            </div>
                        </label>

                        <label class="modal-type-option">
                            <input type="radio" name="fee_type" value="management">
                            <div class="modal-type-card">
                                <i class="fa-solid fa-building"></i>
                                <span class="mtc-label">Management</span>
                                <span class="mtc-desc">Mgmt. quota</span>
                            </div>
                        </label>

                        <label class="modal-type-option">
                            <input type="radio" name="fee_type" value="nri">
                            <div class="modal-type-card">
                                <i class="fa-solid fa-earth-asia"></i>
                                <span class="mtc-label">NRI</span>
                                <span class="mtc-desc">NRI quota</span>
                            </div>
                        </label>

                    </div>
                    <p class="modal-field-hint" id="feeTypeConflictHint" style="display:none;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        This combination already exists for this course.
                    </p>
                </div>

                {{-- Fee Mode — pill radios --}}
                <div class="form-row">
                    <label class="form-label">Fee Mode <span style="color:#dc2626">*</span></label>
                    <div class="modal-pill-group" id="modalFeeModeGroup">

                        <label class="modal-pill-option">
                            <input type="radio" name="fee_mode" value="total">
                            <span><i class="fa-solid fa-sigma" style="margin-right:5px;font-size:.75rem"></i>Total</span>
                        </label>

                        <label class="modal-pill-option">
                            <input type="radio" name="fee_mode" value="yearly">
                            <span><i class="fa-solid fa-calendar" style="margin-right:5px;font-size:.75rem"></i>Yearly</span>
                        </label>

                        <label class="modal-pill-option">
                            <input type="radio" name="fee_mode" value="semester">
                            <span><i class="fa-solid fa-calendar-half" style="margin-right:5px;font-size:.75rem"></i>Semester</span>
                        </label>

                    </div>
                </div>

                {{-- Currency --}}
                <div class="form-row">
                    <label class="form-label" for="modalCurrency">Currency</label>
                    <select name="currency" id="modalCurrency" class="form-control">
                        <option value="INR">₹ INR</option>
                    </select>
                </div>

                {{-- Breakdown editor --}}
                <div class="breakdown-editor">
                    <div class="breakdown-editor-header">
                        <span class="breakdown-editor-title">
                            <i class="fa-solid fa-list-ul" style="margin-right:5px; font-size:.7rem"></i>
                            Fee Breakdown
                        </span>
                        <button type="button" class="btn-add-row" onclick="addBreakdownRow()">
                            <i class="fa-solid fa-plus" style="font-size:.7rem"></i> Add Row
                        </button>
                    </div>

                    <div id="breakdownRows">
                        {{-- Rows injected by JS --}}
                    </div>

                    <div class="grand-total-preview">
                        <span class="gt-label">
                            <i class="fa-solid fa-sigma" style="margin-right:6px; font-size:.78rem"></i>
                            Grand Total
                        </span>
                        <span class="gt-value" id="grandTotalPreview">INR 0</span>
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save" id="saveBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>

    </div>{{-- /modal-box --}}
</div>{{-- /modal-backdrop --}}


<script>
(function () {

    let currentCurrency = 'INR';

    // Existing combos passed from Blade (fee_id excluded so we can detect conflicts on type/mode change)
    const existingCombos = @json(
        $course->feeStructures->map(fn($f) => ['id' => $f->id, 'fee_type' => $f->fee_type, 'fee_mode' => $f->fee_mode])
    );
    let currentFeeId = null;

    function checkConflict() {
        const type = document.querySelector('input[name="fee_type"]:checked')?.value;
        const mode = document.querySelector('input[name="fee_mode"]:checked')?.value;
        const hint = document.getElementById('feeTypeConflictHint');
        const btn  = document.getElementById('saveBtn');

        const conflict = existingCombos.some(
            c => c.id !== currentFeeId && c.fee_type === type && c.fee_mode === mode
        );

        hint.style.display = conflict ? 'flex' : 'none';
        btn.disabled = conflict;
        if (conflict) {
            btn.innerHTML = '<i class="fa-solid fa-ban"></i> Conflict';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';
        }
    }

    // Attach conflict check to all type/mode radios
    document.querySelectorAll('input[name="fee_type"], input[name="fee_mode"]')
        .forEach(r => r.addEventListener('change', checkConflict));

    /* ── Open ── */
    window.openEditModal = function (feeId, feeType, feeMode, currency, breakdowns, updateUrl) {
        currentCurrency = currency;
        currentFeeId    = feeId;

        // Set form action
        document.getElementById('editFeeForm').action = updateUrl;

        // Subtitle
        document.getElementById('modalSubtitle').textContent =
            feeType.charAt(0).toUpperCase() + feeType.slice(1) + ' / ' +
            feeMode.charAt(0).toUpperCase() + feeMode.slice(1);

        // Select the correct fee_type radio
        const typeRadio = document.querySelector(`input[name="fee_type"][value="${feeType}"]`);
        if (typeRadio) typeRadio.checked = true;

        // Select the correct fee_mode radio
        const modeRadio = document.querySelector(`input[name="fee_mode"][value="${feeMode}"]`);
        if (modeRadio) modeRadio.checked = true;

        // Reset conflict hint & save button
        document.getElementById('feeTypeConflictHint').style.display = 'none';
        const btn = document.getElementById('saveBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Changes';

        // Currency selector
        document.getElementById('modalCurrency').value = currency;

        // Build breakdown rows
        const container = document.getElementById('breakdownRows');
        container.innerHTML = '';

        const rows = Array.isArray(breakdowns) ? breakdowns : Object.values(breakdowns);
        rows.sort((a, b) => a.sequence - b.sequence)
            .forEach(bd => addBreakdownRow(bd.label, bd.amount));

        recalcTotal();

        // Open
        document.getElementById('editModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    /* ── Close ── */
    window.closeEditModal = function () {
        document.getElementById('editModal').classList.remove('open');
        document.body.style.overflow = '';
    };

    // Close on backdrop click
    document.getElementById('editModal').addEventListener('click', function (e) {
        if (e.target === this) closeEditModal();
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeEditModal();
    });

    /* ── Add breakdown row ── */
    window.addBreakdownRow = function (label = '', amount = '') {
        const container = document.getElementById('breakdownRows');
        const index     = container.querySelectorAll('.bd-row').length;
        const seq       = index + 1;

        const row = document.createElement('div');
        row.className = 'bd-row';
        row.innerHTML = `
            <div class="bd-seq">${seq}</div>
            <input type="hidden" name="breakdowns[${index}][sequence]" value="${seq}">
            <input
                type="text"
                name="breakdowns[${index}][label]"
                class="form-control"
                placeholder="e.g. Tuition Fee"
                value="${escHtml(label)}"
                required>
            <input
                type="number"
                name="breakdowns[${index}][amount]"
                class="form-control bd-amount"
                placeholder="0"
                min="0"
                step="0.01"
                value="${amount}"
                required
                oninput="recalcTotal()">
            <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Remove row">
                <i class="fa-solid fa-xmark"></i>
            </button>`;

        container.appendChild(row);
        recalcTotal();
    };

    /* ── Remove row ── */
    window.removeRow = function (btn) {
        const container = document.getElementById('breakdownRows');
        if (container.querySelectorAll('.bd-row').length <= 1) {
            alert('At least one breakdown item is required.');
            return;
        }
        btn.closest('.bd-row').remove();
        reindexRows();
        recalcTotal();
    };

    /* ── Re-index after removal ── */
    function reindexRows() {
        const rows = document.querySelectorAll('#breakdownRows .bd-row');
        rows.forEach((row, i) => {
            const seq = i + 1;
            row.querySelector('.bd-seq').textContent = seq;
            row.querySelector('input[name$="[sequence]"]').name    = `breakdowns[${i}][sequence]`;
            row.querySelector('input[name$="[sequence]"]').value   = seq;
            row.querySelector('input[type="text"]').name           = `breakdowns[${i}][label]`;
            row.querySelector('input[type="number"]').name         = `breakdowns[${i}][amount]`;
        });
    }

    /* ── Recalc grand total preview ── */
    window.recalcTotal = function () {
        const currency = document.getElementById('modalCurrency').value || currentCurrency;
        let total = 0;
        document.querySelectorAll('.bd-amount').forEach(inp => {
            total += parseFloat(inp.value) || 0;
        });
        document.getElementById('grandTotalPreview').textContent =
            currency + ' ' + total.toLocaleString('en-IN', { maximumFractionDigits: 0 });
    };

    // Update preview when currency changes
    document.getElementById('modalCurrency').addEventListener('change', recalcTotal);

    /* ── HTML escape helper ── */
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    /* ── Form submit: show loading state ── */
    document.getElementById('editFeeForm').addEventListener('submit', function () {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
    });

})();
</script>

@endsection