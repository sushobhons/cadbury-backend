<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VoteController extends Controller
{
    public function check(string $phone): JsonResponse
    {
        if (!preg_match('/^\d{10}$/', $phone)) {
            return response()->json([
                'success' => false,
                'has_voted' => false,
                'message' => 'Invalid phone number.',
            ], 422);
        }

        $vote = Vote::query()->where('phone', $phone)->first();

        if (!$vote) {
            return response()->json([
                'success' => true,
                'has_voted' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'has_voted' => true,
            'vote' => [
                'shop_id' => $vote->shop_id,
                'shop_name' => $vote->shop_name,
                'voted_at' => optional($vote->created_at)->toDateTimeString(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $shops = config('voting.shops', []);
        $shopIds = array_keys($shops);

        $validated = $request->validate([
            'phone' => ['required', 'digits:10'],
            'shop_id' => ['required', 'integer', Rule::in($shopIds)],
            'shop_name' => ['required', 'string', 'max:255'],
        ]);

        $shopId = (int) $validated['shop_id'];
        $expectedShopName = $shops[$shopId]['name'] ?? null;

        if ($expectedShopName === null || $validated['shop_name'] !== $expectedShopName) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shop selection.',
            ], 422);
        }

        try {
            $vote = DB::transaction(function () use ($validated) {
                $existingVote = Vote::query()->where('phone', $validated['phone'])->lockForUpdate()->first();
                if ($existingVote) {
                    return $existingVote;
                }

                return Vote::query()->create([
                    'phone' => $validated['phone'],
                    'shop_id' => (int) $validated['shop_id'],
                    'shop_name' => $validated['shop_name'],
                ]);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vote submission failed. Please try again.',
            ], 500);
        }

        if ($vote->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'already_voted' => false,
                'message' => 'Your vote has been recorded successfully.',
                'vote' => [
                    'shop_id' => $vote->shop_id,
                    'shop_name' => $vote->shop_name,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'already_voted' => true,
            'message' => 'You have already voted for '.$vote->shop_name.'.',
            'vote' => [
                'shop_id' => $vote->shop_id,
                'shop_name' => $vote->shop_name,
            ],
        ]);
    }
}

