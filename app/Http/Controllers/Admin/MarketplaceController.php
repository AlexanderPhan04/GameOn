<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductUpdated;
use App\Http\Controllers\Controller;
use App\Models\MarketplaceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    /**
     * Check if user has admin access
     * Cho phép cả admin và super_admin
     */
    private function checkAdminAccess()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access');
        }

        $user = Auth::user();
        // Cho phép cả admin và super_admin
        if (!in_array($user->user_role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access. Chỉ admin và super admin mới có quyền truy cập.');
        }
    }

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = MarketplaceProduct::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(20);

        return view('admin.marketplace.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $this->checkAdminAccess();
        return view('admin.marketplace.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:theme,sticker,game_item,donation',
            'category' => 'required|in:ui_theme,avatar_frame,sticker_pack,emote,weapon_skin,character_skin,currency,other',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'nullable|integer|min:-1',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'preview_url' => 'nullable|url',
            'game_id' => 'nullable|string',
            'rarity' => 'nullable|in:common,uncommon,rare,epic,legendary',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'description', 'type', 'category', 'price', 'discount_price',
            'stock', 'preview_url', 'game_id', 'rarity', 'is_active', 'is_featured'
        ]);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('marketplace/products', 'public');
        }

        // Handle images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('marketplace/products', 'public');
            }
            $data['images'] = $images;
        }

        // Set defaults
        $data['stock'] = $data['stock'] ?? -1;
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['is_featured'] = $request->has('is_featured') ? true : false;
        $data['created_by'] = Auth::id();
        $data['sold_count'] = 0;

        $product = MarketplaceProduct::create($data);

        // Broadcast product created
        broadcast(new ProductUpdated($product, 'created'));

        return redirect()->route('admin.marketplace.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $this->checkAdminAccess();
        $product = MarketplaceProduct::findOrFail($id);
        return view('admin.marketplace.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $this->checkAdminAccess();
        $product = MarketplaceProduct::findOrFail($id);
        return view('admin.marketplace.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $this->checkAdminAccess();

        $product = MarketplaceProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:theme,sticker,game_item,donation',
            'category' => 'required|in:ui_theme,avatar_frame,sticker_pack,emote,weapon_skin,character_skin,currency,other',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'nullable|integer|min:-1',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'preview_url' => 'nullable|url',
            'game_id' => 'nullable|string',
            'rarity' => 'nullable|in:common,uncommon,rare,epic,legendary',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'description', 'type', 'category', 'price', 'discount_price',
            'stock', 'preview_url', 'game_id', 'rarity'
        ]);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('marketplace/products', 'public');
        }

        // Handle images
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('marketplace/products', 'public');
            }
            $data['images'] = $images;
        }

        // Set boolean fields
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        $product->update($data);

        // Broadcast product updated
        broadcast(new ProductUpdated($product, 'updated'));

        return redirect()->route('admin.marketplace.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        $this->checkAdminAccess();

        $product = MarketplaceProduct::findOrFail($id);

        // Delete images
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.marketplace.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($id)
    {
        $this->checkAdminAccess();

        $product = MarketplaceProduct::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        // Broadcast product status change
        broadcast(new ProductUpdated($product, $product->is_active ? 'activated' : 'deactivated'));

        return response()->json([
            'success' => true,
            'is_active' => $product->is_active,
            'message' => $product->is_active ? 'Sản phẩm đã được kích hoạt' : 'Sản phẩm đã được vô hiệu hóa'
        ]);
    }
}
