@extends('college.layout.app')

@section('title', 'Fee Structures')

@section('content')

    <style>
        :root {
            --brand: #306060;
            --brand-dark: #254848;
            --brand-light: rgba(48, 96, 96, .08);
            --brand-mid: rgba(48, 96, 96, .15);
            --border: #e4e9ee;
            --text: #1e2d3d;
            --text-muted: #8a97a6;
            --radius: 12px;
            --radius-sm: 8px;
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px;
        }

        .page-sub {
            font-size: .82rem;
            color: var(--text-muted);
            margin: 0 0 24px;
        }

        .search-bar {
            width: 100%;
            max-width: 300px;
            padding: 9px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: .855rem;
            font-family: inherit;
            outline: none;
            margin-bottom: 20px;
            transition: border-color .18s;
        }

        .search-bar:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(48, 96, 96, .1);
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .course-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 6px rgba(0, 0, 0, .04);
            overflow: hidden;
            transition: box-shadow .2s, border-color .2s;
        }

        .course-card:hover {
            border-color: var(--brand);
            box-shadow: 0 4px 16px rgba(48, 96, 96, .12);
        }

        .course-card-header {
            padding: 16px 18px 12px;
            border-bottom: 1px solid var(--border);
        }

        .course-name {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px;
        }

        .course-college {
            font-size: .78rem;
            color: var(--text-muted);
            margin: 0;
        }

        .course-card-body {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .fee-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .fee-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }

        .fee-badge.govt {
            background: #e0f2fe;
            color: #0369a1;
        }

        .fee-badge.mgmt {
            background: #fef9c3;
            color: #854d0e;
        }

        .fee-badge.nri {
            background: #f0fdf4;
            color: #166534;
        }

        .fee-badge.none {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .course-card-footer {
            padding: 12px 18px;
            border-top: 1px solid var(--border);
            background: #fafbfc;
            display: flex;
            gap: 8px;
        }

        .btn-view {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--brand-light);
            border: 1.5px solid var(--brand-mid);
            border-radius: var(--radius-sm);
            font-size: .8rem;
            font-weight: 600;
            font-family: inherit;
            color: var(--brand);
            text-decoration: none;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-view:hover {
            background: var(--brand-mid);
            color: var(--brand-dark);
        }

        .btn-add {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--brand);
            border: 1.5px solid var(--brand);
            border-radius: var(--radius-sm);
            font-size: .8rem;
            font-weight: 600;
            font-family: inherit;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-add:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 2.5rem;
            opacity: .2;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: .9rem;
            margin: 0;
        }
    </style>

    {{-- Header --}}
    <h4 class="page-title">
        <i class="fa-solid fa-indian-rupee-sign" style="color:var(--brand); margin-right:8px;"></i>
        Fee Structures
    </h4>
    <p class="page-sub">
        {{ $college->college_name ?? ($college->name ?? '—') }} &nbsp;·&nbsp;
        {{ $courses->count() }} course(s)
    </p>

    @if ($courses->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-folder-open"></i>
            <p>No courses found for this college.</p>
        </div>
    @else
        <input type="text" id="courseSearch" class="search-bar" placeholder="Search courses...">

        <div class="course-grid" id="courseGrid">
            @foreach ($courses as $course)
                <div class="course-card" data-name="{{ strtolower($course->name) }}">

                    {{-- Card Header --}}
                    <div class="course-card-header">
                        <h5 class="course-name">{{ $course->name }}</h5>
                        <p class="course-college">
                            <i class="fa-solid fa-building" style="margin-right:4px; font-size:.7rem"></i>
                            {{ $college->college_name ?? ($college->name ?? '—') }}
                        </p>
                    </div>

                    {{-- Fee Type Badges --}}
                    <div class="course-card-body">
                        <div class="fee-badges">
                            @if ($course->feeStructures->isEmpty())
                                <span class="fee-badge none">
                                    <i class="fa-solid fa-circle-xmark"></i> No fee structure
                                </span>
                            @else
                                @foreach ($course->feeStructures as $fee)
                                    @if ($fee->fee_type === 'government')
                                        <span class="fee-badge govt">
                                            <i class="fa-solid fa-landmark"></i> Govt
                                        </span>
                                    @elseif($fee->fee_type === 'management')
                                        <span class="fee-badge mgmt">
                                            <i class="fa-solid fa-building"></i> Management
                                        </span>
                                    @elseif($fee->fee_type === 'nri')
                                        <span class="fee-badge nri">
                                            <i class="fa-solid fa-earth-asia"></i> NRI
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
 
                    {{-- Actions --}}
                    <div class="course-card-footer">
                        <a href="{{ route('college.feeStructure.show', $course->id) }}" class="btn-view">
                            <i class="fa-solid fa-eye" style="font-size:.75rem"></i> View
                        </a>
                        {{-- Only show Add if NO fee structures exist yet for this course --}}
                        @if ($course->feeStructures->isEmpty())
                            <a href="{{ route('college.feeStructure.create', $course->id) }}" class="btn-add">
                                <i class="fa-solid fa-plus" style="font-size:.75rem"></i> Add Fee
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    @endif

    <script>
        document.getElementById('courseSearch').addEventListener('input', function() {
            let q = this.value.toLowerCase();
            document.querySelectorAll('.course-card').forEach(card => {
                card.style.display = card.dataset.name.includes(q) ? '' : 'none';
            });
        });
    </script>

@endsection
