<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeView;
use Illuminate\Support\Facades\Auth;

class CollegeViewersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    try {
        $college = Auth::guard('college')->user();
        $collegeId = $college->college_id; // ← use $college, not $collegeAuth

        $query = CollegeView::with(['user', 'user.children'])
            ->where('college_id', $collegeId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $viewers = $query->paginate(20)->withQueryString();

        $totalViews = CollegeView::where('college_id', $collegeId)->count();

        $thisWeekViews = CollegeView::where('college_id', $collegeId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $todayViews = CollegeView::where('college_id', $collegeId)
            ->whereDate('created_at', today())
            ->count();

        $latestView = CollegeView::where('college_id', $collegeId)
            ->latest()
            ->first();

        return view('college.college_viewed_details', compact(
            'viewers',
            'totalViews',
            'thisWeekViews',
            'todayViews',
            'latestView'
        ));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
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
