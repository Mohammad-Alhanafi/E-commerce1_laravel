<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;
use App\Models\AttributeValues;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('sku', 'like', '%' . $request->name . '%');
            });
        }

        // 3. إذا المستخدم اختار قسم معين
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. ترتيب المنتجات وعمل الترقيم (Pagination)
        $products = $query->latest()->paginate(10);
        
        $categories = Category::all();
        $attribute_values = AttributeValues::with('attribute')->get();

        return view('admin.products.index', compact('products', 'categories', 'attribute_values'));
    }

    /**
     * 🚀 الحل السحري: دالة العرض المربوطة بالـ Resource تلقائياً
     * تقوم بتحويل الطلب إلى الدالة المجهزة لعملاء المتجر الذهبي
     */
    public function show($id)
    {
        return $this->showClient($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric',
            'stock'             => 'required|integer|min:0',
            'status'            => 'required|in:active,inactive,outofstock',
            'category_id'       => 'required|exists:category,id',
            'sku'               => 'nullable|string|max:100|unique:products_tabel,sku', 
            'description'       => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'   
        ]);

        if (empty($data['sku'])) {
            $prefix = strtoupper(substr($data['name'], 0, 2));
            do {
                $generatedSku = $prefix . '-' . mt_rand(1000, 9999);
            } while (Product::where('sku', $generatedSku)->exists()); 
            
            $data['sku'] = $generatedSku;
        }

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product = Product::create($data);

        $product->load('category');

        return response()->json([
            'success'       => true,
            'product'       => $product,
            'category_name' => $product->category->name ?? 'عام',
            'message'       => 'تم إضافة المنتج بنجاح'
        ]);
    }

    // حذف منتج
    public function destroy(Product $product)
    {
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }

        // حذف Variants المرتبطة
        $product->variants()->delete();
        $product->delete();

        return response()->json(['success'=>true]);
    }

    public function showClient($id)
    {
        $product = Product::with(['variants.attributeValues.attribute', 'category'])->findOrFail($id);

        // جلب الإعدادات من الداتابيز
        $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(8) // زدنا العدد ليكون السلايدر أجمل
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::where('id', '!=', $product->id)
                ->where('status', 'active')
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        $groupedAttributes = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $attributeValue) {
                $attributeName = $attributeValue->attribute->name;
                $groupedAttributes[$attributeName][] = $attributeValue->value;
            }
        }
        
        foreach ($groupedAttributes as $key => $values) {
            $groupedAttributes[$key] = array_unique($values);
        }

        $groupedAttributes = collect($groupedAttributes);

        return view('client.product-details', compact('product', 'groupedAttributes', 'relatedProducts', 'settings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric',
            'sku'               => 'nullable|string|max:100|unique:products_tabel,sku,' . $id,
            'category_id'       => 'nullable|exists:category,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'care_instructions' => 'nullable|string'
        ]);

        $product = Product::findOrFail($id);
        
        $updateData = [
            'name'              => $request->name,
            'category_id'       => $request->category_id ?? $request->category,
            'price'             => $request->price,
            'stock'             => $request->stock,
            'status'            => $request->status,
            'sku'               => $request->sku, 
            'description'       => $request->description,
            'care_instructions' => $request->care_instructions,
        ];

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة من السيرفر
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // تخزين الجديدة
            $updateData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        // تحميل العلاقة لرد الأياكس
        $product->load('category');

        return response()->json([
            'status'        => 'success',
            'message'       => 'تم تحديث المنتج بنجاح',
            'image_url'     => asset('storage/' . $product->image), 
            'image_path'    => $product->image,                
            'category_name' => $product->category->name ?? 'عام'
        ]);
    }
}