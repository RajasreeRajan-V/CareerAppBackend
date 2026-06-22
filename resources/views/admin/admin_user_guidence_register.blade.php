@extends('layouts.app')

@section('header', 'Career Guidance Registrations')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="p-3 rounded-3 border" style="background:#f9f9f9;">
            <div style="font-size:22px; font-weight:600; color:#1a1a1a;">{{ $registrations->total() }}</div>
            <div style="font-size:12px; color:#888; margin-top:2px;">Total Registrations</div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="p-3 rounded-3 border" style="background:#f9f9f9;">
            <div style="font-size:22px; font-weight:600; color:#1a1a1a;">
                {{ \App\Models\CareerGuidanceRegistration::whereDate('created_at', today())->count() }}
            </div>
            <div style="font-size:12px; color:#888; margin-top:2px;">Registered Today</div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="p-3 rounded-3 border" style="background:#f9f9f9;">
            <div style="font-size:22px; font-weight:600; color:#1a1a1a;">
                {{ \App\Models\CareerGuidanceRegistration::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
            </div>
            <div style="font-size:12px; color:#888; margin-top:2px;">This Week</div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold" style="font-size:15px;">
                All Registrations
                @if(request('search'))
                    <span style="font-size:12px; color:#888; font-weight:400; margin-left:6px;">
                        — results for "{{ request('search') }}"
                    </span>
                @endif
            </h6>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('admin.career_guidance_registration.index') }}"
                  class="d-flex align-items-center gap-2">
                <div style="position:relative;">
                    <i class="fa-solid fa-magnifying-glass"
                       style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#aaa; font-size:12px;"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name, email, phone…"
                        style="
                            padding: 7px 12px 7px 30px;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            font-size: 13px;
                            outline: none;
                            width: 240px;
                            background: #fafafa;
                            transition: border-color 0.2s;
                        "
                        onfocus="this.style.borderColor='#306060'; this.style.background='#fff';"
                        onblur="this.style.borderColor='#ddd'; this.style.background='#fafafa';"
                    >
                </div>
                <button type="submit" class="btn btn-sm"
                    style="background:#306060; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-size:13px;">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.career_guidance_registration.index') }}"
                       style="font-size:13px; color:#888; text-decoration:none; white-space:nowrap;">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        @if($registrations->isEmpty())
            <div class="text-center py-5" style="color:#aaa;">
                <i class="fa-regular fa-folder-open fa-2x mb-3 d-block"></i>
                @if(request('search'))
                    <p class="mb-0">No results found for "{{ request('search') }}".</p>
                @else
                    <p class="mb-0">No registrations found.</p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px;">
                    <thead style="background:#fafafa;">
                        <tr>
                            <th class="fw-medium text-muted border-bottom">#</th>
                            <th class="fw-medium text-muted border-bottom">Name</th>
                            <th class="fw-medium text-muted border-bottom">Email</th>
                            <th class="fw-medium text-muted border-bottom">Phone</th>
                            <th class="fw-medium text-muted border-bottom">Banner</th>
                            <th class="fw-medium text-muted border-bottom">Registered</th>
                            <th class="fw-medium text-muted border-bottom">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $index => $registration)
                            <tr>
                                <td class="text-muted">{{ $registrations->firstItem() + $index }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="
                                            width:32px; height:32px; border-radius:50%;
                                            background:#E1F5EE; color:#0F6E56;
                                            font-size:12px; font-weight:500;
                                            display:flex; align-items:center; justify-content:center;
                                            flex-shrink:0;
                                        ">
                                            {{ strtoupper(substr($registration->name, 0, 2)) }}
                                        </div>
                                        <span>{{ $registration->name }}</span>
                                    </div>
                                </td>

                                <td style="color:#306060;">{{ $registration->email }}</td>
                                <td>{{ $registration->phone ?? '—' }}</td>

                                <td>
                                    <span class="badge rounded-pill"
                                        style="background:#E1F5EE; color:#0F6E56; font-size:11px; font-weight:500;">
                                        {{ $registration->careerGuidanceBanner->title ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="text-muted">{{ $registration->created_at->format('M d, Y') }}</td>

                                <td>
                                    <form action="{{ route('admin.career_guidance_registration.destroy', $registration) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                            style="font-size:12px; color:#e53e3e; border:0.5px solid #f5c6c6; background:#fff; border-radius:8px;">
                                            <i class="fa-solid fa-trash-can me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination (preserves search query) --}}
            @if($registrations->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $registrations->appends(request()->query())->links() }}
                </div>
            @endif
        @endif

    </div>
</div>

@endsection