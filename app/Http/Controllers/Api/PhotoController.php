<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhotoUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PhotoController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $allowedThemeIds = array_keys(config('themes.map', []));

        $validated = $request->validate([
            'image_data' => ['required', 'string'],
            'phone' => ['required', 'digits:10'],
            'theme_id' => ['required', 'integer', Rule::in($allowedThemeIds)],
        ]);

        if (!preg_match('/^data:image\/(\w+);base64,/', $validated['image_data'], $typeMatches)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image format',
            ], 400);
        }

        $imageType = strtolower($typeMatches[1]);
        if (!in_array($imageType, ['jpg', 'jpeg', 'png'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image type',
            ], 400);
        }

        $encodedPayload = substr($validated['image_data'], strpos($validated['image_data'], ',') + 1);
        $binaryImage = base64_decode($encodedPayload, true);

        if ($binaryImage === false) {
            return response()->json([
                'success' => false,
                'message' => 'Image decode failed',
            ], 400);
        }

        // Guardrail against very large uploads from public kiosks.
        if (strlen($binaryImage) > 6 * 1024 * 1024) {
            return response()->json([
                'success' => false,
                'message' => 'Image is too large',
            ], 413);
        }

        $folder = 'uploads/photos/'.date('Y/m/d');
        $fileName = 'photo_'.$validated['phone'].'_'.time().'_'.Str::lower(Str::random(6)).'.'.$imageType;
        $filePath = $folder.'/'.$fileName;

        Storage::disk('public')->put($filePath, $binaryImage);

        $publicRelativeUrl = Storage::url($filePath);

        PhotoUpload::create([
            'phone' => $validated['phone'],
            'theme_id' => (int) $validated['theme_id'],
            'image_path' => $filePath,
            'image_url' => $publicRelativeUrl,
        ]);

        $themeMeta = config('themes.map.'.(int) $validated['theme_id']);

        return response()->json([
            'success' => true,
            'url' => $publicRelativeUrl,
            'theme' => $themeMeta,
        ]);
    }
}

