<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use App\Models\WardrobeItem;
use Illuminate\Http\Request;

class WardrobeItemController extends Controller
{
    public function index(Request $request)
    {
        $query = WardrobeItem::with('outfit')
            ->where('user_id', $request->user()->id);

        if ($request->filled('outfit_id')) {
            $query->where('outfit_id', $request->outfit_id);
        }

        $items = $query->latest()->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outfit_id' => 'required|exists:outfits,id',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:500',
        ]);

        $outfit = Outfit::where('id', $validated['outfit_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $outfit) {
            return response()->json(['message' => 'Kategori outfit tidak valid'], 403);
        }

        $item = WardrobeItem::create([
            'user_id' => $request->user()->id,
            'outfit_id' => $validated['outfit_id'],
            'title' => $validated['title'],
            'notes' => $validated['notes'] ?? null,
            'price' => $validated['price'] ?? 0,
            'color' => $validated['color'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        return response()->json([
            'message' => 'Item wardrobe berhasil ditambahkan',
            'data' => $item->load('outfit'),
        ], 201);
    }

    public function show(Request $request, WardrobeItem $wardrobeItem)
    {
        if ($wardrobeItem->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        return response()->json(['data' => $wardrobeItem->load('outfit')]);
    }

    public function update(Request $request, WardrobeItem $wardrobeItem)
    {
        if ($wardrobeItem->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $validated = $request->validate([
            'outfit_id' => 'sometimes|required|exists:outfits,id',
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:500',
        ]);

        if (isset($validated['outfit_id'])) {
            $outfit = Outfit::where('id', $validated['outfit_id'])
                ->where('user_id', $request->user()->id)
                ->first();

            if (! $outfit) {
                return response()->json(['message' => 'Kategori outfit tidak valid'], 403);
            }
        }

        $wardrobeItem->update($validated);

        return response()->json([
            'message' => 'Item wardrobe berhasil diperbarui',
            'data' => $wardrobeItem->load('outfit'),
        ]);
    }

    public function destroy(Request $request, WardrobeItem $wardrobeItem)
    {
        if ($wardrobeItem->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $wardrobeItem->delete();

        return response()->json([
            'message' => 'Item wardrobe berhasil dihapus',
        ]);
    }
}