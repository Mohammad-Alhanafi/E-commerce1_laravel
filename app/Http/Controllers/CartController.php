<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variant; 

class CartController extends Controller
{
    /**
     * إضافة منتج إلى السلة
     */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $variant = Variant::with(['product', 'attributeValues.attribute'])->find($request->variant_id);
        $cart = session()->get('cart', []);

        if(isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += (int)$request->quantity;
        } else {
            $options = [];

            // اللون (لو موجود وله قيمة فعلية)
            if (!empty($variant->color) && strtolower($variant->color) !== '#000000') {
                $options[__('admin.color')] = $variant->color;
            }

            // كل خاصية إضافية (مقاس، وزن، أي شي) بشكل ديناميكي بالكامل
            foreach ($variant->attributeValues as $attrValue) {
                $attrName = $attrValue->attribute->name ?? __('admin.option');
                $options[$attrName] = $attrValue->value;
            }

            $cart[$variant->id] = [
                "id"         => $variant->id,
                "product_id" => $variant->product_id,
                "name"       => $variant->product->name,
                "quantity"   => (int)$request->quantity,
                "price"      => $variant->variant_price,
                "options"    => $options,
                "image"      => $variant->variant_image ?? $variant->product->image ?? null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
            'total_price'=> $this->getCartTotal(),
            'cart_html'  => $this->getCartHtml(), 
        ]);
    }

    /**
     * تحديث الكمية
     */
    public function update(Request $request)
    {
        if($request->id && isset($request->quantity)){
            $cart = session()->get('cart');
            
            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = (int)$request->quantity;
                session()->put('cart', $cart);

                return response()->json([
                    'success'    => true,
                    'cart_html'  => $this->getCartHtml(),
                    'total'      => $this->getCartTotal(),
                    'cart_count' => count(session('cart', [])),
                ]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    /**
     * حذف منتج
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            return response()->json([
                'success'    => true,
                'cart_html'  => $this->getCartHtml(),
                'total'      => $this->getCartTotal(),
                'cart_count' => count(session('cart', [])),
            ]);
        }
        return response()->json(['success' => false], 400);
    }

    /**
     * عرض صفحة السلة
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('front.cart', compact('cart')); 
    }

    /**
     * دالة مساعدة لحساب الإجمالي
     */
    private function getCartTotal() {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) { 
            $total += $item['price'] * $item['quantity']; 
        }
        return number_format($total, 2) . ' $';
    }

    /**
     * بناء الـ HTML الخاص بالسلة
     */
    private function getCartHtml() {
        $cart = session()->get('cart', []);
        $html = "";
        
        if(empty($cart)) {
            return '<p style="color:#fff; text-align:center;">السلة فارغة حالياً</p>';
        }

        foreach($cart as $id => $details) {
            
            // ✅ بناء خيارات المنتج (مقاس، وزن، لون... إلخ) بشكل ديناميكي
            $optionsHtml = '';
            if (!empty($details['options']) && is_array($details['options'])) {
                foreach ($details['options'] as $optionName => $optionValue) {
                    $optionsHtml .= '<p style="color:#b8a07c; font-size:12px; margin:2px 0;">' . e($optionName) . ': ' . e($optionValue) . '</p>';
                }
            }

            $html .= '
            <div class="cart-item" style="display: flex; gap: 15px; margin-bottom: 20px; padding: 15px; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; background: rgba(255,255,255,0.05); position: relative;">
                <div style="width: 80px; height: 100px; border-radius: 5px; overflow: hidden; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <img src="'.(get_image_url($details['image'] ?? null, 'assets/images/default.jpg')).'" style="width:100%; height:100%; object-fit: cover;">
                </div>
                <div style="flex: 1;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <h5 style="margin:0; font-size:14px; color:#fff;">'.$details['name'].'</h5>
                        <span style="font-size:16px; color:#d4af37; font-weight:bold;">'.number_format($details['price'], 2).' $</span>
                    </div>
                    
                    '.$optionsHtml.'

                    <div style="display:flex; align-items:center; justify-content: space-between; margin-top:10px;">
                        <div style="display:flex; align-items:center; gap:5px; border:1px solid rgba(212, 175, 55, 0.5); border-radius:25px; padding:2px 5px; background:rgba(0,0,0,0.5);">
                            <button type="button" class="update-cart" data-id="'.$id.'" data-quantity="'.($details['quantity'] - 1).'" style="background:none; border:none; color:#d4af37; font-weight:bold; cursor:pointer; padding:0 10px;"> - </button>
                            <span style="color:#fff; font-weight:bold; font-size:13px; min-width:20px; text-align:center;">'.$details['quantity'].'</span>
                            <button type="button" class="update-cart" data-id="'.$id.'" data-quantity="'.($details['quantity'] + 1).'" style="background:none; border:none; color:#d4af37; font-weight:bold; cursor:pointer; padding:0 10px;"> + </button>
                        </div>
                        <button type="button" class="remove-from-cart" data-id="'.$id.'" style="background:none; border:none; color:#ff4d4d; cursor:pointer; font-size:14px;"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>';
        }
        return $html;
    }
}