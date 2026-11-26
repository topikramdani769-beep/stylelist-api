<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use Illuminate\Http\Request;

class OutfitController extends Controller
{
    public function index(Request $request)
    {
        $outfits = Outfit::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $outfits]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $outfit = Outfit::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Kategori outfit berhasil dibuat',
            'data' => $outfit,
        ], 201);
    }

    public function show(Request $request, Outfit $outfit)
    {
        if ($outfit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        return response()->json(['data' => $outfit]);
    }

    public function update(Request $request, Outfit $outfit)
    {
        if ($outfit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $outfit->update($validated);

        return response()->json([
            'message' => 'Kategori outfit berhasil diperbarui',
            'data' => $outfit,
        ]);
    }

    public function destroy(Request $request, Outfit $outfit)
    {
        if ($outfit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $outfit->delete();

        return response()->json([
            'message' => 'Kategori outfit berhasil dihapus',
        ]);
    }
}