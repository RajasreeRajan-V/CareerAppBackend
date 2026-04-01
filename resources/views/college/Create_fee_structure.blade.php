@extends('college.layout.app')

@section('title', 'Add Fee Structure — ' . ($collegeCourse->course->name ?? 'Course'))

@section('content')

<style>
    /* ── Page Variables ── */
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
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .8rem;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .breadcrumb-nav a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color .15s;
    }

    .breadcrumb-nav a:hover { color: var(--brand); }

    .breadcrumb-nav .sep {
        color: #d0d8e0;
        font-size: .65rem;
    }

    .breadcrumb-nav .current {
        color: var(--text);
        font-weight: 500;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px;
        line-height: 1.2;
    }

    .page-sub {
        font-size: .82rem;
        color: var(--text-muted);
        margin: 0;
    }

    .page-sub span {
        font-weight: 600;
        color: var(--text);
    }

    /* ── Back Button ── */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: .82rem;
        font-weight: 500;
        font-family: inherit;
        color: var(--text-muted);
        text-decoration: none;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
        white-space: nowrap;
    }

    .btn-back:hover {
        background: #f2f5f7;
        color: var(--text);
        border-color: #c8d0da;
    }

    /* ── Validation Alert ── */
    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid #ef4444;
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: .82rem;
        color: #b91c1c;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 16px;
    }

    .alert-danger li { margin-bottom: 2px; }
    .alert-danger li:last-child { margin-bottom: 0; }

    /* ── Layout Grid ── */
    .form-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .form-grid { grid-template-columns: 1fr; }
    }

    /* ── Card ── */
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        overflow: hidden;
    }

    .form-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .form-card-title {
        font-size: .92rem;
        font-weight: 600;
        color: var(--text);
    }

    .form-card-body {
        padding: 20px;
    }

    .form-card-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: #fafbfc;
    }

    /* ── Form Elements ── */
    .field-group {
        margin-bottom: 18px;
    }

    .field-group:last-child { margin-bottom: 0; }

    .field-label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 6px;
        letter-spacing: .01em;
    }

    .field-label .req {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: .855rem;
        font-family: inherit;
        color: var(--text);
        background: #fff;
        outline: none;
        transition: border-color .18s, box-shadow .18s;
        appearance: none;
        -webkit-appearance: none;
    }

    .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='%238a97a6' d='M0 0l5 6 5-6z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
        cursor: pointer;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(48,96,96,.1);
    }

    .form-input.is-invalid,
    .form-select.is-invalid {
        border-color: #ef4444;
    }

    .form-input.readonly-input {
        background: #f7f9fb;
        color: var(--text-muted);
        cursor: default;
    }

    .invalid-msg {
        font-size: .75rem;
        color: #ef4444;
        margin-top: 4px;
    }

    .field-hint {
        font-size: .74rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* ── Fee Mode Pill Radios ── */
    .pill-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .pill-option { cursor: pointer; user-select: none; }

    .pill-option input[type="radio"] { display: none; }

    .pill-option span {
        display: inline-block;
        padding: 6px 16px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 500;
        color: var(--text-muted);
        background: #fff;
        transition: all .15s;
        line-height: 1.4;
    }

    .pill-option input[type="radio"]:checked + span {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        box-shadow: 0 2px 8px rgba(48,96,96,.25);
    }

    .pill-option:hover span {
        border-color: var(--brand);
        color: var(--brand);
        background: var(--brand-light);
    }

    /* ── Amount Input Group ── */
    .input-addon-group {
        display: flex;
        align-items: stretch;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        overflow: hidden;
        transition: border-color .18s, box-shadow .18s;
    }

    .input-addon-group:focus-within {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(48,96,96,.1);
    }

    .input-addon {
        padding: 0 12px;
        background: #f7f9fb;
        border-right: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        font-size: .78rem;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .input-addon.right {
        border-right: none;
        border-left: 1.5px solid var(--border);
        font-size: .72rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        font-weight: 600;
        color: #b0bac6;
    }

    .input-addon-group .form-input {
        border: none;
        border-radius: 0;
        flex: 1;
        min-width: 0;
        box-shadow: none !important;
    }

    /* ── Grand Total Box ── */
    .grand-total-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: var(--brand-light);
        border: 1.5px solid var(--brand-mid);
        border-radius: var(--radius-sm);
        margin-top: 16px;
    }

    .grand-total-label {
        font-size: .82rem;
        font-weight: 500;
        color: var(--brand-dark);
    }

    .grand-total-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--brand);
        letter-spacing: -.02em;
    }

    /* ── Breakdown Table Header ── */
    .breakdown-col-headers {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0 6px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .breakdown-col-headers span {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--text-muted);
    }

    /* ── Breakdown Rows ── */
    .breakdown-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        transition: all .15s;
    }

    .breakdown-row .form-input {
        padding: 8px 10px;
        font-size: .84rem;
    }

    .breakdown-row:hover .form-input {
        border-color: #b5cece;
    }

    .row-label-input  { flex: 1; min-width: 0; }
    .row-amount-wrap  { width: 136px; flex-shrink: 0; }
    .row-seq-input    { width: 50px; flex-shrink: 0; text-align: center; }

    .btn-remove-row {
        width: 32px; height: 34px;
        border: 1.5px solid var(--border);
        background: #fff;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--text-muted);
        font-size: .75rem;
        flex-shrink: 0;
        transition: background .15s, color .15s, border-color .15s;
        font-family: inherit;
    }

    .btn-remove-row:hover:not(:disabled) {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .btn-remove-row:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* ── Add Row Button ── */
    .btn-add-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        background: var(--brand-light);
        border: 1.5px dashed var(--brand-mid);
        border-radius: var(--radius-sm);
        font-size: .82rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--brand);
        cursor: pointer;
        transition: background .15s, border-color .15s;
        width: 100%;
        justify-content: center;
        margin-top: 4px;
    }

    .btn-add-row:hover {
        background: rgba(48,96,96,.12);
        border-color: var(--brand);
    }

    /* ── Empty Breakdown State ── */
    .breakdown-empty {
        text-align: center;
        padding: 32px 16px;
        color: var(--text-muted);
        font-size: .83rem;
    }

    .breakdown-empty i {
        font-size: 1.5rem;
        opacity: .25;
        display: block;
        margin-bottom: 8px;
    }

    /* ── Breakdown Totals Footer ── */
    .breakdown-total-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-top: 1px solid var(--border);
    }

    .breakdown-total-bar .lbl {
        font-size: .8rem;
        color: var(--text-muted);
    }

    .breakdown-total-bar .val {
        font-size: 1rem;
        font-weight: 700;
        color: var(--brand);
    }

    /* ── Form Actions ── */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-cancel {
        padding: 10px 22px;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: .855rem;
        font-weight: 500;
        font-family: inherit;
        color: var(--text-muted);
        text-decoration: none;
        cursor: pointer;
        transition: background .15s, color .15s;
    }

    .btn-cancel:hover {
        background: #f2f5f7;
        color: var(--text);
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: var(--brand);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        font-size: .855rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: background .18s, box-shadow .18s, transform .12s;
        box-shadow: 0 2px 8px rgba(48,96,96,.25);
    }

    .btn-save:hover {
        background: var(--brand-dark);
        box-shadow: 0 4px 14px rgba(48,96,96,.3);
        transform: translateY(-1px);
    }

    .btn-save:active { transform: translateY(0); }

    /* ── Item count badge ── */
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px; height: 20px;
        padding: 0 6px;
        background: var(--brand-light);
        color: var(--brand);
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 700;
    }

    /* ── Fee Type Cards ── */
    .fee-type-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .fee-type-option { cursor: pointer; user-select: none; }

    .fee-type-option input[type="radio"] { display: none; }

    .fee-type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 8px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        background: #fff;
        transition: all .15s;
        text-align: center;
    }

    .fee-type-card .type-icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        background: var(--brand-light);
        color: var(--brand);
        transition: all .15s;
    }

    .fee-type-card .type-label {
        font-size: .77rem;
        font-weight: 600;
        color: var(--text-muted);
        line-height: 1.2;
        transition: color .15s;
    }

    .fee-type-card .type-desc {
        font-size: .68rem;
        color: #b0bac6;
        line-height: 1.3;
    }

    .fee-type-option input[type="radio"]:checked + .fee-type-card {
        border-color: var(--brand);
        background: var(--brand-light);
        box-shadow: 0 2px 10px rgba(48,96,96,.15);
    }

    .fee-type-option input[type="radio"]:checked + .fee-type-card .type-icon {
        background: var(--brand);
        color: #fff;
    }

    .fee-type-option input[type="radio"]:checked + .fee-type-card .type-label {
        color: var(--brand-dark);
    }

    .fee-type-option:hover .fee-type-card {
        border-color: var(--brand);
        background: var(--brand-light);
    }
    .alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #22c55e;
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: .82rem;
    color: #166534;
}
</style>

<div x-data="feeForm()" x-init="init()">

{{-- ── Breadcrumb ── --}}
<nav class="breadcrumb-nav">
    <a href="{{ route('college.feeStructure.index') }}">Fee Structures</a>
    <i class="fa-solid fa-chevron-right sep"></i>
    <span class="current">{{ $course->name ?? 'Course' }}</span>
</nav>

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h4 class="page-title">
            <span style="color:var(--text-muted); font-weight:500; font-size:1rem;">Add Fee Structure</span>
            <span style="color:#d0d8e0; margin:0 6px;">·</span>
            {{ $course->name ?? '—' }}
        </h4>
        <p class="page-sub">
            <i class="fa-solid fa-building" style="margin-right:4px; font-size:.75rem"></i>
            <span>{{ $course->college->name ?? '—' }}</span>
        </p>
    </div>
    <a href="{{ route('college.feeStructure.show', $course->id) }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

{{-- ── Success Message ── --}}
@if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- ── Validation Errors ── --}}
@if($errors->any())
    <div class="alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ── Duplicate Error from Controller ── --}}
@if(session('error'))
    <div class="alert-danger">
        <i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i>
        {{ session('error') }}
    </div>
@endif

    {{-- Form action --}}
<form method="POST" action="{{ route('college.feeStructure.store', $course->id) }}">
        @csrf

        <div class="form-grid">

            {{-- ── LEFT: Fee Details ── --}}
            <div class="form-card">
                <div class="form-card-header">
                    <span class="form-card-title">
                        <i class="fa-solid fa-circle-info" style="color:var(--brand); margin-right:6px; font-size:.85rem"></i>
                        Fee Details
                    </span>
                </div>
                <div class="form-card-body">

                    {{-- Fee Type — Card Radios (enum: government | management | nri) --}}
                    <div class="field-group">
                        <label class="field-label">Fee Type <span class="req">*</span></label>
                        <div class="fee-type-group">

                            <label class="fee-type-option">
                                <input type="radio" name="fee_type" value="government"
                                       {{ old('fee_type') === 'government' ? 'checked' : '' }} required>
                                <div class="fee-type-card">
                                    <div class="type-icon">
                                        <i class="fa-solid fa-landmark"></i>
                                    </div>
                                    <div class="type-label">Government</div>
                                    <div class="type-desc">Govt. quota</div>
                                </div>
                            </label>

                            <label class="fee-type-option">
                                <input type="radio" name="fee_type" value="management"
                                       {{ old('fee_type') === 'management' ? 'checked' : '' }}>
                                <div class="fee-type-card">
                                    <div class="type-icon">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div class="type-label">Management</div>
                                    <div class="type-desc">Mgmt. quota</div>
                                </div>
                            </label>

                            <label class="fee-type-option">
                                <input type="radio" name="fee_type" value="nri"
                                       {{ old('fee_type') === 'nri' ? 'checked' : '' }}>
                                <div class="fee-type-card">
                                    <div class="type-icon">
                                        <i class="fa-solid fa-earth-asia"></i>
                                    </div>
                                    <div class="type-label">NRI</div>
                                    <div class="type-desc">NRI quota</div>
                                </div>
                            </label>

                        </div>
                        @error('fee_type')
                            <div class="invalid-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fee Mode — Pill Radios (enum: total | yearly | semester) --}}
                    <div class="field-group">
                        <label class="field-label">Fee Mode <span class="req">*</span></label>
                        <div class="pill-group">

                            <label class="pill-option">
                                <input type="radio" name="fee_mode" value="total"
                                       {{ old('fee_mode', 'total') === 'total' ? 'checked' : '' }} required>
                                <span>
                                    <i class="fa-solid fa-sigma" style="margin-right:5px; font-size:.75rem"></i>Total
                                </span>
                            </label>

                            <label class="pill-option">
                                <input type="radio" name="fee_mode" value="yearly"
                                       {{ old('fee_mode') === 'yearly' ? 'checked' : '' }}>
                                <span>
                                    <i class="fa-solid fa-calendar" style="margin-right:5px; font-size:.75rem"></i>Yearly
                                </span>
                            </label>

                            <label class="pill-option">
                                <input type="radio" name="fee_mode" value="semester"
                                       {{ old('fee_mode') === 'semester' ? 'checked' : '' }}>
                                <span>
                                    <i class="fa-solid fa-calendar-half" style="margin-right:5px; font-size:.75rem"></i>Semester
                                </span>
                            </label>

                        </div>
                        @error('fee_mode')
                            <div class="invalid-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Currency --}}
                    <div class="field-group">
                        <label class="field-label">Currency <span class="req">*</span></label>
                        <select name="currency"
                                class="form-select @error('currency') is-invalid @enderror"
                                required>
                            @foreach($currencies as $code => $label)
                                @if($code === 'USD') @continue @endif
                                <option value="{{ $code }}" {{ old('currency', 'INR') == $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency')
                            <div class="invalid-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Total Amount (auto-calculated) --}}
                    <div class="field-group">
                        <label class="field-label">Total Amount</label>
                        <div class="input-addon-group">
                            <span class="input-addon">
                                <i class="fa-solid fa-calculator" style="font-size:.75rem"></i>
                            </span>
                            <input type="number"
                                   name="total_amount"
                                   :value="totalAmount"
                                   class="form-input readonly-input @error('total_amount') is-invalid @enderror"
                                   placeholder="Auto-calculated"
                                   readonly>
                            <span class="input-addon right">auto</span>
                        </div>
                        <div class="field-hint">Sum of all breakdown amounts below</div>
                        @error('total_amount')
                            <div class="invalid-msg">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Grand Total Box --}}
                <div class="form-card-footer" x-show="totalAmount > 0">
                    <div class="grand-total-box">
                        <span class="grand-total-label">
                            <i class="fa-solid fa-sigma" style="margin-right:6px; font-size:.8rem"></i>
                            Grand Total
                        </span>
                        <span class="grand-total-value" x-text="formatAmount(totalAmount)"></span>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Fee Breakdowns ── --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="form-card-title">
                            <i class="fa-solid fa-list-ul" style="color:var(--brand); margin-right:6px; font-size:.85rem"></i>
                            Fee Breakdown
                        </span>
                        <span class="count-badge" x-text="breakdowns.length"></span>
                    </div>
                    <button type="button" @click="addRow()" class="btn-back" style="border-style:dashed; color:var(--brand); border-color:var(--brand-mid);">
                        <i class="fa-solid fa-plus" style="font-size:.75rem"></i> Add Row
                    </button>
                </div>

                <div class="form-card-body" style="padding-bottom:12px;">

                    {{-- Column headers --}}
                    <div class="breakdown-col-headers">
                        <span style="flex:1;">Label</span>
                        <span style="width:136px;">Amount</span>
                        <span style="width:50px; text-align:center;">Seq</span>
                        <span style="width:32px;"></span>
                    </div>

                    {{-- Rows --}}
                    <div>
                        <template x-for="(row, index) in breakdowns" :key="row.id">
                            <div class="breakdown-row">

                                {{-- Label (→ college_fee_breakdowns.label) --}}
                                <input type="text"
                                       :name="`breakdowns[${index}][label]`"
                                       x-model="row.label"
                                       class="form-input row-label-input"
                                       placeholder="e.g. Tuition Fee"
                                       required>

                                {{-- Amount (→ college_fee_breakdowns.amount decimal 10,2) --}}
                                <div class="input-addon-group row-amount-wrap">
                                    <span class="input-addon" style="font-size:.8rem; font-weight:600; color:var(--brand-dark);">₹</span>
                                    <input type="number"
                                           :name="`breakdowns[${index}][amount]`"
                                           x-model.number="row.amount"
                                           @input="calcTotal()"
                                           class="form-input"
                                           placeholder="0"
                                           min="0"
                                           step="0.01"
                                           required>
                                </div>

                                {{-- Sequence (→ college_fee_breakdowns.sequence) --}}
                                <input type="number"
                                       :name="`breakdowns[${index}][sequence]`"
                                       x-model.number="row.sequence"
                                       class="form-input row-seq-input"
                                       min="1"
                                       :value="index + 1">

                                {{-- Remove --}}
                                <button type="button"
                                        @click="removeRow(index)"
                                        class="btn-remove-row"
                                        :disabled="breakdowns.length === 1"
                                        title="Remove row">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                            </div>
                        </template>
                    </div>

                    {{-- Empty state --}}
                    <div x-show="breakdowns.length === 0" class="breakdown-empty">
                        <i class="fa-solid fa-table-list"></i>
                        No breakdown rows yet. Click "Add Row" to start.
                    </div>

                    {{-- Add Row (inline) --}}
                    <button type="button" @click="addRow()" class="btn-add-row">
                        <i class="fa-solid fa-plus" style="font-size:.75rem"></i> Add Another Row
                    </button>

                </div>

                {{-- Totals Footer --}}
                <div class="breakdown-total-bar">
                    <span class="lbl">
                        <i class="fa-solid fa-circle-info" style="margin-right:4px; font-size:.72rem"></i>
                        Total is auto-summed from all rows
                    </span>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-size:.8rem; color:var(--text-muted);">Total:</span>
                        <span class="val" x-text="formatAmount(totalAmount)"></span>
                    </div>
                </div>
            </div>

        </div>{{-- /form-grid --}}

        {{-- ── Form Actions ── --}}
        <div class="form-actions">
            <a href="{{ url()->previous() }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Save Fee Structure
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
function feeForm() {
    return {
        breakdowns: [],
        totalAmount: 0,
        nextId: 1,

        init() {
            const old = @json(old('breakdowns', []));
            if (old.length > 0) {
                this.breakdowns = old.map((r, i) => ({
                    id: i + 1,
                    label: r.label ?? '',
                    amount: parseFloat(r.amount) || 0,
                    sequence: parseInt(r.sequence) || (i + 1),
                }));
                this.nextId = old.length + 1;
            } else {
                this.addRow();
            }
            this.calcTotal();
        },

        addRow() {
            this.breakdowns.push({
                id: this.nextId++,
                label: '',
                amount: 0,
                sequence: this.breakdowns.length + 1,
            });
        },

        removeRow(index) {
            if (this.breakdowns.length <= 1) return;
            this.breakdowns.splice(index, 1);
            this.breakdowns.forEach((r, i) => r.sequence = i + 1);
            this.calcTotal();
        },

        calcTotal() {
            this.totalAmount = this.breakdowns.reduce((sum, r) => {
                return sum + (parseFloat(r.amount) || 0);
            }, 0);
        },

        formatAmount(val) {
            if (!val || val === 0) return '—';
            return new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                minimumFractionDigits: 0,
            }).format(val);
        },
    };
}
</script>
@endpush

@endsection