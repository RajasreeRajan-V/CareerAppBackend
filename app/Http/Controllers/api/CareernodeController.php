<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerNode;
use Illuminate\Support\Facades\Validator;

class CareernodeController extends Controller
{
    /**
     * Search Career Nodes
     */
   public function searchCareerNodes(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'keyword'  => 'nullable|string|max:255',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }
        $keyword  = trim($request->keyword ?? '');
        $perPage  = $request->per_page ?? 10;
        $page     = $request->page ?? 1;
        // ✅ Normalize keyword (remove special chars + spaces)
        $normalizedKeyword = strtolower($keyword);
        $normalizedKeyword = preg_replace('/[^a-z0-9]/', '', $normalizedKeyword);
        $query = CareerNode::query()
            ->orderByDesc('newgen_course')
            ->latest();
        // ✅ Apply normalized search on title AND career_options
        if (!empty($normalizedKeyword)) {
            $query->where(function ($q) use ($normalizedKeyword) {
                // Search in title (normalized)
                $q->whereRaw("
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        LOWER(title), '.', ''
                                    ), ' ', ''
                                ), '-', ''
                            ), '_', ''
                        ), ',', ''
                    ) LIKE ?
                ", ["%{$normalizedKeyword}%"])
                // Search in career_options (normalized)
                ->orWhereRaw("
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        LOWER(career_options), '.', ''
                                    ), ' ', ''
                                ), '-', ''
                            ), '_', ''
                        ), ',', ''
                    ) LIKE ?
                ", ["%{$normalizedKeyword}%"]);
            });
        }
        // ✅ Pagination
        $careerNodes = $query->paginate($perPage, ['*'], 'page', $page);
        if ($careerNodes->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "careernodes"   => [],
                    "current_page"  => "1",
                    "last_page"     => "1",
                    "per_page"      => (string)$perPage,
                    "total_nodes"   => "0"
                ],
                "message" => "No career nodes found"
            ], 200);
        }
        $formattedNodes = $careerNodes->getCollection()->map(function ($node) {
            if ($node->video) {
                $thumbnail = "https://img.youtube.com/vi/{$node->video}/0.jpg";
            } elseif ($node->thumbnail) {
                $thumbnail = asset('storage/' . $node->thumbnail);
            } else {
                $thumbnail = null;
            }
            return [
                "id"               => (string) $node->id,
                "title"            => $node->title,
                "thumbnail"        => $thumbnail,
                "is_newgen_course" => $node->newgen_course ? 1 : 0,
            ];
        });
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "careernodes"  => $formattedNodes,
                "current_page" => (string) $careerNodes->currentPage(),
                "last_page"    => (string) $careerNodes->lastPage(),
                "per_page"     => (string) $careerNodes->perPage(),
                "total_nodes"  => (string) $careerNodes->total(),
            ],
            "message" => "Career nodes fetched successfully"
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong. Please try again later."
        ], 500);
    }
}
    /**
     * Career Node Details
     */
    public function careerNodeDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric|exists:career_nodes,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "0",
                    "status_code" => "422",
                    "data" => (object)[],
                    "message" => collect($validator->errors()->all())->first()
                ], 422);
            }

            $node = CareerNode::find($request->id);

            if (!$node) {
                return response()->json([
                    "status" => "0",
                    "status_code" => "404",
                    "data" => (object)[],
                    "message" => "Career node not found"
                ], 404);
            }
            $hasFuturePath = $node->children()->exists();

            $formattedNode = [
                "id" => (string) $node->id,
                "title" => $node->title,
                "subjects" => $node->subjects ? json_decode($node->subjects, true) : [],
                "career_options" => $node->career_options ? json_decode($node->career_options, true) : [],
                "description" => $node->description,
                
                "is_newgen_course" => $node->newgen_course ? 1 : 0,
                "has_future_path" => $hasFuturePath ? 1 : 0,
                "video_id" => $node->video,
                "video_url" => $node->video
                    ? "https://www.youtube.com/watch?v={$node->video}"
                    : null,
                "thumbnail" => $node->video
                    ? "https://img.youtube.com/vi/{$node->video}/0.jpg"
                    : ($node->thumbnail ? asset('storage/' . $node->thumbnail) : null),
                    "growth" => $node->growth,
                    "demand" => $node->demand,
            ];


            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "careernode" => $formattedNode
                ],
                "message" => "Career node details fetched successfully"
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                "status" => "0",
                "status_code" => "500",
                "data" => (object)[],
                "message" => "Something went wrong. Please try again later."
            ], 500);
        }
    }
    
    public function getCareerHomeNodes()
{
    try {

        $nodes = CareerNode::where('show_on_home', 1)->get();


        $formattedNodes = [];

        foreach ($nodes as $node) {
            $formattedNodes[] = [
                "id" => (string) $node->id,
                "title" => $node->title,
                // "subjects" => $node->subjects ? json_decode($node->subjects, true) : [],
                "career_options" => $node->career_options ? json_decode($node->career_options, true) : [],
                // "description" => $node->description,
                // "video" => $node->video ? asset('storage/' . $node->video) : null,
                // "thumbnail" => $node->thumbnail ? asset('storage/' . $node->thumbnail) : null,
            ];
        }

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "career_nodes" => $formattedNodes,
                "total_nodes" => count($formattedNodes)
            ],
            "message" => "Career home nodes fetched successfully"
        ]);

    } catch (\Exception $e) {

        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong."
        ], 500);
    }
}

public function getChildCareerNodesBasic(Request $request)
{
    try {

        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'id'       => 'required|numeric|exists:career_nodes,id',
            'keyword'  => 'nullable|string|max:255',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        // ✅ Inputs
        $perPage = $request->per_page ?? 10;
        $page    = $request->page ?? 1;
        $keyword = trim($request->keyword ?? '');

        // ✅ Normalize keyword
        $normalizedKeyword = strtolower($keyword);
        $normalizedKeyword = preg_replace('/[^a-z0-9]/', '', $normalizedKeyword);

        // ✅ Get parent node
        $node = CareerNode::find($request->id);

        // ✅ Base query
        $childrenQuery = $node->children()
            ->join('career_nodes as child_nodes', 'career_links.child_career_id', '=', 'child_nodes.id');

        // ✅ Apply search ONLY if keyword exists
        if (!empty($normalizedKeyword)) {
            $childrenQuery->whereRaw("
                REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    LOWER(child_nodes.title), '.', ''
                                ), ' ', ''
                            ), '-', ''
                        ), '_', ''
                    ), ',', ''
                ) LIKE ?
            ", ["%{$normalizedKeyword}%"]);
        }

        // ✅ Ordering + Pagination
        $children = $childrenQuery
            ->orderByRaw('CASE WHEN child_nodes.newgen_course = 1 THEN 0 ELSE 1 END')
            ->orderByDesc('child_nodes.created_at')
            ->select('career_links.*')
            ->with('child')
            ->paginate($perPage, ['*'], 'page', $page);

        // ✅ No data case
        if ($children->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "child_nodes"    => [],
                    "current_page"   => "1",
                    "last_page"      => "1",
                    "per_page"       => (string)$perPage,
                    "total_children" => "0"
                ],
                "message" => "No child nodes found"
            ], 200);
        }

        // ✅ Format response
        $childNodes = $children->getCollection()->map(function ($link) {

            if (!$link->child) return null;

            if ($link->child->video) {
                $thumbnail = "https://img.youtube.com/vi/{$link->child->video}/0.jpg";
            } elseif ($link->child->thumbnail) {
                $thumbnail = asset('storage/' . $link->child->thumbnail);
            } else {
                $thumbnail = null;
            }

            return [
                "id" => (string) $link->child->id,
                "title" => $link->child->title,
                "thumbnail" => $thumbnail,
                "is_newgen_course" => $link->child->newgen_course ? 1 : 0,
            ];
        })->filter()->values();

        // ✅ Success response
        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "child_nodes"    => $childNodes,
                "current_page"   => (string) $children->currentPage(),
                "last_page"      => (string) $children->lastPage(),
                "per_page"       => (string) $children->perPage(),
                "total_children" => (string) $children->total(),
            ],
            "message" => "Child career nodes fetched successfully"
        ], 200);

    } catch (\Exception $e) {

        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong."
        ], 500);
    }
}

public function getNewGenCourses(Request $request)
{
    try {

        $validator = Validator::make($request->all(), [
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "0",
                "status_code" => "422",
                "data" => (object)[],
                "message" => collect($validator->errors()->all())->first()
            ], 422);
        }

        $perPage = $request->per_page ?? 10;
        $page    = $request->page ?? 1;

        // ✅ FILTER NEWGEN COURSES
        $nodes = CareerNode::where('newgen_course', 1)
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        if ($nodes->isEmpty()) {
            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "newgen_courses" => [],
                    "current_page"   => "1",
                    "last_page"      => "1",
                    "per_page"       => (string)$perPage,
                    "total_courses"  => "0"
                ],
                "message" => "No new gen courses found"
            ], 200);
        }

        $formattedNodes = $nodes->getCollection()->map(function ($node) {

            if ($node->video) {
                $thumbnail = "https://img.youtube.com/vi/{$node->video}/0.jpg";
            } elseif ($node->thumbnail) {
                $thumbnail = asset('storage/' . $node->thumbnail);
            } else {
                $thumbnail = null;
            }

            return [
                "id" => (string) $node->id,
                "title" => $node->title,
                "thumbnail" => $thumbnail,
                "is_newgen_course" => $node->newgen_course ? 1 : 0,
            ];
        });

        return response()->json([
            "status" => "1",
            "status_code" => "200",
            "data" => [
                "newgen_courses" => $formattedNodes,
                "current_page"   => (string) $nodes->currentPage(),
                "last_page"      => (string) $nodes->lastPage(),
                "per_page"       => (string) $nodes->perPage(),
                "total_courses"  => (string) $nodes->total(),
            ],
            "message" => "New gen courses fetched successfully"
        ], 200);

    } catch (\Exception $e) {

        return response()->json([
            "status" => "0",
            "status_code" => "500",
            "data" => (object)[],
            "message" => "Something went wrong."
        ], 500);
    }
}

}
