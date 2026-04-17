@extends('college.layout.app')

@section('header', 'College Dashboard')

@section('content')

<style>
    :root {
        --primary: #306060;
        --primary-light: #e8f0f0;
        --border: #e4e8ed;
        --text: #1a2535;
        --text-muted: #6b7a8d;
        --shadow: 0 2px 12px rgba(48,96,96,0.07);
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text); line-height: 1; }
    .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-top: 3px; }

    .section-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .section-card .card-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem; font-weight: 600; color: var(--text);
        display: flex; align-items: center; justify-content: space-between;
    }
    .section-card .card-head a { font-size: 0.78rem; font-weight: 400; color: var(--primary); text-decoration: none; }
    .section-card .card-head a:hover { text-decoration: underline; }

    .dashboard-actions { display: flex; flex-wrap: wrap; gap: 0.45rem; }

    .dashboard-actions .action-btn {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.4rem 0.75rem; border-radius: 8px;
        font-size: 0.8rem; font-weight: 600;
        color: #fff !important; text-decoration: none;
        border: 0; transition: background-color .2s ease;
    }
    .dashboard-actions .action-btn:hover { filter: brightness(0.95); }

    /* Carousel cursor */
    #collegeCarousel { cursor: grab; }
    #collegeCarousel:active { cursor: grabbing; }
</style>

<div class="container-fluid px-0">

    {{-- Welcome Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:var(--text);">
                Welcome, {{ auth()->guard('college')->user()->college_name ?? 'College' }}
            </h5>
            <p class="mb-0 small" style="color:var(--text-muted);">{{ now()->format('l, d M Y') }}</p>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('college.collegeCourse.index') }}" class="action-btn" style="background:#2f7d7d;">
                <i class="fa-solid fa-book-open"></i> Courses
            </a>
            <a href="{{ route('college.feeStructure.index') }}" class="action-btn" style="background:#306060;">
                <i class="fa-solid fa-indian-rupee-sign"></i> Fee Structures
            </a>
            <a href="{{ route('college.collegeEdit.index') }}" class="action-btn" style="background:#4a90e2;">
                <i class="fa-solid fa-user-pen"></i> Edit Profile
            </a>
            <a href="{{ route('college.password.change') }}" class="action-btn" style="background:#f39c12;">
                <i class="fa-solid fa-key"></i> Change Password
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f0f0;color:var(--primary);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($totalStudents ?? 2480) }}</div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fdf0ea;color:#e8855a;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalFaculty ?? 148 }}</div>
                    <div class="stat-label">Faculty</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalCourses ?? 86 }}</div>
                    <div class="stat-label">Courses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fff8e1;color:#c9a84c;">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $placements ?? 312 }}</div>
                    <div class="stat-label">Placements</div>
                </div>
            </div>
        </div>
    </div>

    {{-- College Images Carousel --}}
    @if(isset($images) && $images->isNotEmpty())
    <div class="mb-4">
        <div class="section-card">
            <div class="card-head">
                <span>
                    <i class="fa-solid fa-images" style="color:var(--primary);margin-right:8px;"></i>
                    College Gallery
                </span>
                <span style="font-size:0.78rem;color:var(--text-muted);">{{ $images->count() }} photo(s)</span>
            </div>
            <div style="padding:1rem;">

                {{-- No data-bs-ride / data-bs-touch — handled via JS below --}}
                <div id="collegeCarousel" class="carousel slide">

                    {{-- Indicators --}}
                    <div class="carousel-indicators">
                        @foreach($images as $i => $img)
                            <button
                                type="button"
                                data-bs-target="#collegeCarousel"
                                data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}"
                                style="background-color:var(--primary);"
                            ></button>
                        @endforeach
                    </div>

                    {{-- Slides --}}
                    <div class="carousel-inner" style="border-radius:10px; overflow:hidden;">
                        @foreach($images as $i => $img)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <img
                                    src="{{ asset('storage/' . $img->image_url) }}"
                                    class="d-block w-100"
                                    alt="College Image"
                                    style="height:320px; object-fit:cover; user-select:none;"
                                    draggable="false"
                                >
                            </div>
                        @endforeach
                    </div>

                    {{-- Prev --}}
                    <button class="carousel-control-prev" type="button" data-bs-target="#collegeCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    {{-- Next --}}
                    <button class="carousel-control-next" type="button" data-bs-target="#collegeCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- Events + Notices --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="section-card p-3" style="text-align:center;color:var(--text-muted);">
                <i class="fa-solid fa-circle-info" style="color:var(--primary);margin-right:8px;"></i>
                No current notices or events to display.
            </div>
        </div>
    </div>

    {{-- Attendance + Recent Students --}}

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carouselEl = document.getElementById('collegeCarousel');
        if (!carouselEl) return;

        const bsCarousel = new bootstrap.Carousel(carouselEl, {
            interval: 3000,
            touch: true,
            ride: 'carousel'
        });

        let startX = 0;
        let isDragging = false;

        // Touch swipe
        carouselEl.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        carouselEl.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) {
                diff > 0 ? bsCarousel.next() : bsCarousel.prev();
            }
        }, { passive: true });

        // Mouse drag swipe
        carouselEl.addEventListener('mousedown', e => {
            startX = e.clientX;
            isDragging = true;
        });

        carouselEl.addEventListener('mouseup', e => {
            if (!isDragging) return;
            isDragging = false;
            const diff = startX - e.clientX;
            if (Math.abs(diff) > 40) {
                diff > 0 ? bsCarousel.next() : bsCarousel.prev();
            }
        });

        carouselEl.addEventListener('mouseleave', () => {
            isDragging = false;
        });
    });
</script>
@endpush