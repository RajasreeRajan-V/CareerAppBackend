<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeView;
use Illuminate\Support\Facades\Auth;
use App\Mail\CollegeViewersReportMail;
use Illuminate\Support\Facades\Mail;

class CollegeViewersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    try {
        $college = Auth::guard('college')->user();
        $collegeId = $college->id;

        CollegeView::where('college_id', $collegeId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $query = CollegeView::with('user')
            ->where('college_id', $collegeId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $viewers      = $query->paginate(10)->withQueryString();
        $totalViews   = CollegeView::where('college_id', $collegeId)->count();
        $thisWeekViews = CollegeView::where('college_id', $collegeId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $todayViews   = CollegeView::where('college_id', $collegeId)
            ->whereDate('created_at', today())
            ->count();
        $latestView   = CollegeView::where('college_id', $collegeId)
            ->latest()->first();

        return view('college.college_viewed_details', compact(
            'viewers', 'totalViews', 'thisWeekViews', 'todayViews', 'latestView'
        ));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}

public function sendReport(Request $request)
{
    try {
        $college   = Auth::guard('college')->user();
        $collegeId = $college->id;

        // Build the same filtered query used on the page
        $query = CollegeView::with('user')
            ->where('college_id', $collegeId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Fetch all (no pagination) for the report
        $allViewers = $query->get();

        $viewerRows = $allViewers->map(fn($v) => [
            'name'      => $v->user->name  ?? '—',
            'email'     => $v->user->email ?? '',
            'phone'     => $v->user->phone ?? '',
            'viewed_at' => $v->created_at->format('d M Y, h:i A'),
        ])->toArray();

        $stats = [
            'total'     => CollegeView::where('college_id', $collegeId)->count(),
            'week'      => CollegeView::where('college_id', $collegeId)
                            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count(),
            'today'     => CollegeView::where('college_id', $collegeId)
                            ->whereDate('created_at', today())
                            ->count(),
            'recipient' => $college->email,
        ];

        Mail::to($college->email)->send(new CollegeViewersReportMail(
            collegeName: $college->name,
            stats:       $stats,
            viewerRows:  $viewerRows,
            fromDate:    $request->from_date,
            toDate:      $request->to_date,
        ));

        return redirect()->back()
            ->with('success', 'Report sent to ' . $college->email);

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to send report: ' . $e->getMessage());
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
