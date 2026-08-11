<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Attributes;       
use App\Models\AttributeValues; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
{
    /**
     * 💡 دالة الـ index المحدثة (بديلة getVariantsByProduct)
     * لتتوافق مع الـ Route والـ JavaScript والـ Pagination معاً.
     */
    public function index(Request $request, $productId)
    {
        // جلب الفارينت مع علاقة القيم والمواصفات (Attributes) لضمان الترقيم
        $variantsQuery = Variant::with(['attributeValues.attribute'])
                                ->where('product_id', $productId)
                                ->orderBy('id', 'desc');

        // استخدام الترقيم متوافق مع رقم الصفحة المرسل من الـ Ajax
        $variants = $variantsQuery->paginate(10); 

        // تحويل البيانات (Mapping) لتطابق مسميات الـ JavaScript المتوقعة في buildVariantRow
        $variants->getCollection()->transform(function($variant) {
            
            // تحويل الـ attributeValues إلى الشكل الذي ينتظره الـ JavaScript (attributes: [{name, value}])
            $formattedAttributes = [];
            if ($variant->attributeValues) {
                foreach ($variant->attributeValues as $attrValue) {
                    $formattedAttributes[] = [
                        'name'  => $attrValue->attribute->name ?? 'خاصية', 
                        'value' => $attrValue->value
                    ];
                }
            }

            return [
                'id'               => $variant->id,
                'name'             => $variant->name ?? ($variant->product->name ?? 'فارينت بدون اسم'),
                'variant_image'    => get_image_url($variant->variant_image, null),
                'color'            => $variant->color, 
                'additional_price' => $variant->variant_price, // مطابقته لـ additional_price المتوقع بالـ JS
                'stock'            => $variant->stock,
                'notes'            => $variant->notes,
                'attributes'       => $formattedAttributes // المصفوفة الجاهزة للـ JS للـ Badges
            ];
        });

        // إرجاع النتيجة كـ JSON متوافق مع نظام الـ Pagination لـ Laravel والـ JS لديك
        return response()->json($variants);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products_tabel,id',
            'name'             => 'required|string|max:255',
            'additional_price' => 'nullable|numeric',
            'stock'            => 'required|integer|min:0', 
            'color'            => 'nullable', 
            'variant_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        try {
            DB::beginTransaction();

            $variant = new Variant();
            $variant->product_id = $request->product_id;
            $variant->name = $request->name;
            $variant->sku = 'VAR-' . time();
            $variant->color = $request->color;
            $variant->additional_price = $request->additional_price ?? 0;
            $variant->variant_price = $request->additional_price ?? 0;
            $variant->stock = $request->stock;
            $variant->status = $request->status ?? 'active';
            $variant->notes = $request->notes;

            if ($request->hasFile('variant_image')) {
                $disk = config('filesystems.default', 'public');
                $imagePath = $request->file('variant_image')->store('variants', $disk);
                $variant->variant_image = $imagePath;
            }

            $variant->save();

            if ($request->has('attribute_name')) {
                foreach ($request->attribute_name as $index => $attrName) {
                    if (!empty($attrName) && !empty($request->attribute_value[$index])) {
                        $attribute = Attributes::firstOrCreate(['name' => $attrName]);
                        $attributeValue = AttributeValues::firstOrCreate([
                            'attributes_id' => $attribute->id, 
                            'value'         => $request->attribute_value[$index]
                        ]);

                        DB::table('variant_attribute_values')->insert([
                            'variants_id'          => $variant->id,
                            'attribute_values_id'  => $attributeValue->id,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'variant' => $variant]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'product_id'       => 'required|exists:products_tabel,id',
            'name'             => 'required|string|max:255',
            'color'            => 'nullable|string|max:20', 
            'additional_price' => 'nullable|numeric',
            'stock'            => 'nullable|integer|min:0',
            'status'           => 'nullable|in:active,inactive', 
            'variant_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = [
                'product_id'       => $request->product_id,
                'name'             => $request->name,
                'color'            => $request->color, 
                'additional_price' => $request->additional_price ?? 0,
                'variant_price'    => $request->additional_price ?? 0,
                'stock'            => $request->stock,
                'status'           => $request->status,
                'notes'            => $request->notes,
            ];

            if ($request->hasFile('variant_image')) {
                $disk = config('filesystems.default', 'public');
                if ($variant->variant_image) {
                    Storage::disk($disk)->delete($variant->variant_image);
                }
                $data['variant_image'] = $request->file('variant_image')->store('variants', $disk);
            }

            $variant->update($data);

            return response()->json([
                'status' => 'success', 
                'variant' => $variant,
                'image_url' => get_image_url($variant->variant_image, null)
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $variant = DB::table('variants')->where('id', $id)->first();

            if (!$variant) {
                return response()->json(['status' => 'error', 'message' => 'السجل غير موجود'], 404);
            }

            DB::table('variant_attribute_values')->where('variants_id', $id)->delete();

            if (!empty($variant->variant_image)) {
                $disk = config('filesystems.default', 'public');
                Storage::disk($disk)->delete($variant->variant_image);
            }

            DB::table('variants')->where('id', $id)->delete();

            DB::commit();
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($productId)
    {
        $variants = Variant::where('product_id', $productId)
                           ->with('attributeValues.attribute') 
                           ->orderBy('id', 'desc')
                           ->get()
                           ->map(function($variant) {


                           
                               $variant->image_url = get_image_url($variant->variant_image);
                               return $variant;
                           });

        return response()->json([
            'status' => 'success',
            'data'   => $variants
        ]);
    }
}