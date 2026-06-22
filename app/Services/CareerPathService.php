<?php

namespace App\Services;

use App\Models\CareerLink;
use App\Models\CareerNode;

class CareerPathService
{
    // ─── Career Level Constants ───────────────────────────────
    const LEVEL_SCHOOL           = 0; // 10th
    const LEVEL_HIGHER_SECONDARY = 1; // +2 / 12th
    const LEVEL_UNDERGRADUATE    = 2; // Degree
    const LEVEL_POSTGRADUATE     = 3; // Masters
    const LEVEL_DOCTORATE        = 4; // PhD


    // ─── Level Validation ─────────────────────────────────────

    /**
     * Ensure parent level < child level
     */
    public static function isValidLevelProgression(int $parentId, int $childId): bool
    {
        $parent = CareerNode::find($parentId);
        $child  = CareerNode::find($childId);

        if (!$parent || !$child) {
            return false;
        }

        return $child->level > $parent->level;
    }


    // ─── Level Name Helper ────────────────────────────────────

    /**
     * Convert level integer → readable label
     */
    public static function getLevelName(int $level): string
    {
        return match($level) {
            self::LEVEL_SCHOOL           => 'School (10th)',
            self::LEVEL_HIGHER_SECONDARY => 'Higher Secondary (+2)',
            self::LEVEL_UNDERGRADUATE    => 'Undergraduate (Degree)',
            self::LEVEL_POSTGRADUATE     => 'Postgraduate (Masters)',
            self::LEVEL_DOCTORATE        => 'Doctorate (PhD)',
            default                      => 'Unknown Level',
        };
    }


    // ─── Graph Traversal (BFS) ───────────────────────────────

    /**
     * Get all descendant IDs of a career node
     */
    public static function getAllDescendantIds(int $careerId): array
    {
        $visited = [];
        $queue   = [$careerId];

        while (!empty($queue)) {
            $current = array_shift($queue);

            if (in_array($current, $visited)) {
                continue;
            }

            $visited[] = $current;

            $childIds = CareerLink::where('parent_career_id', $current)
                ->pluck('child_career_id')
                ->toArray();

            $queue = array_merge($queue, $childIds);
        }

        return array_values(array_diff($visited, [$careerId]));
    }


    // ─── Cycle Detection ─────────────────────────────────────

    /**
     * Prevent circular relationships
     */
    public static function wouldCreateCycle(int $parentId, int $childId): bool
    {
        if ($parentId === $childId) {
            return true;
        }

        $descendantsOfChild = self::getAllDescendantIds($childId);

        return in_array($parentId, $descendantsOfChild);
    }
}
