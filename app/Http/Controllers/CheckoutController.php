<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
   public function index()
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('home')->with('error', 'سلتك فارغة!');
    }

    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $shipping_type = DB::table('settings')->where('key', 'shipping_type')->value('value');

    $regions = [];
    if ($shipping_type === 'region') {
        $regionsData = DB::table('settings')->where('key', 'shipping_regions')->value('value');
        $regions = $regionsData ? json_decode($regionsData, true) : [];
    }

    // لو النظام "حسب المنطقة"، السعر الابتدائي بيصير 0 لحد ما يختار العميل منطقته (بيتحدث عبر JS أو عند الإرسال)
    $ship_fee = ($shipping_type === 'region') ? 0 : $this->calculateShippingFee('delivery');

    return view('frontend.checkout', compact('cart', 'subtotal', 'ship_fee', 'shipping_type', 'regions'));
}

 public function store(Request $request)
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return response()->json(['success' => false, 'message' => 'السلة فارغة!'], 400);
    }

    $request->validate([
        'customer_name'   => 'required|string|max:255',
        'customer_phone'  => 'required|string|max:50',
        'shipping_method' => 'required|in:delivery,pickup',
        'city'            => 'nullable|string|max:100',
        'address'         => 'nullable|string',
        'notes'           => 'nullable|string|max:1000',
    ]);

    try {
        $result = DB::transaction(function () use ($request, $cart) {
            $ship_fee = $this->calculateShippingFee($request->shipping_method, $request->city);
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $variantId => $item) {
                $qty = (int) $item['quantity'];

                $variant = DB::table('variants')
                    ->where('id', $variantId)
                    ->lockForUpdate()
                    ->first();

                if (!$variant) {
                    throw new \Exception('المنتج غير موجود');
                }

                if ((int) $variant->stock < $qty) {
                    throw new \Exception('الكمية غير متوفرة من المنتج: ' . $item['name']);
                }

                DB::table('variants')
                    ->where('id', $variantId)
                    ->decrement('stock', $qty);

                $product = DB::table('products_tabel')
                    ->where('id', $variant->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    DB::table('products_tabel')
                        ->where('id', $variant->product_id)
                        ->update(['stock' => max(0, (int) $product->stock - $qty)]);
                }

                $price = (float) $variant->variant_price;
                $subtotal += $price * $qty;

                $orderItems[] = [
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $total_final = $subtotal + $ship_fee;

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'shipping_method' => $request->shipping_method,
                'city' => $request->shipping_method == 'delivery' ? $request->city : null,
                'address' => $request->shipping_method == 'delivery' ? $request->address : null,
                'total_price' => $total_final,
                'notes' => $request->notes,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $orderId;
                DB::table('order_items')->insert($orderItem);
            }

            return [
                'order_id' => $orderId,
                'subtotal' => $subtotal,
                'ship_fee' => $ship_fee,
                'total_final' => $total_final,
            ];
        });
    } catch (Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 422);
    }

    $whatsapp_data = DB::table('settings')->where('key', 'whatsapp_numbers')->value('value');
    $numbers = json_decode($whatsapp_data, true);
    $adminPhone = !empty($numbers) ? $numbers[0] : '96100000000';

    $message = "*طلب جديد -  *\n";
    $message .= "--------------------------\n\n";

    $message .= "*معلومات الزبون:*\n";
    $message .= "*الاسم:* " . $request->customer_name . "\n";
    $message .= "*الهاتف:* " . $request->customer_phone . "\n";
    $message .= "*طريقة الاستلام:* " . ($request->shipping_method == 'delivery' ? 'خدمة التوصيل' : 'استلام من المحل') . "\n";

    if ($request->shipping_method == 'delivery') {
        $message .= "*المدينة:* " . $request->city . "\n";
        $message .= "*العنوان:* " . $request->address . "\n";
    }

    $message .= "\n*تفاصيل الطلبية:*\n";
    $message .= "--------------------------\n";

    foreach ($cart as $item) {
        $message .= "* " . $item['name'] . "\n";
        $message .= "   الكمية: " . $item['quantity'] . " × " . $item['price'] . "$\n";
    }

    $message .= "--------------------------\n";
    $message .= "*ملخص الفاتورة:*\n";
    $message .= "*سعر المنتجات:* " . $result['subtotal'] . "$\n";
    $message .= "*أجور التوصيل:* " . ($result['ship_fee'] > 0 ? $result['ship_fee'] . "$" : "مجاني") . "\n";
    $message .= "*الإجمالي النهائي:* " . $result['total_final'] . "$\n\n";

    if ($request->notes) {
        $message .= "*ملاحظة:* " . $request->notes . "\n";
    }

    $message .= "\nشكراً لاختياركم متجرنا";

    $whatsappUrl = "https://wa.me/" . $adminPhone . "?text=" . urlencode($message);

    session()->forget('cart');

    return response()->json([
        'success' => true,
        'order_id' => $result['order_id'],
        'whatsapp_url' => $whatsappUrl,
    ]);
}

private function calculateShippingFee(string $shippingMethod, ?string $city = null): float
{
    // استلام من المحل = بدون شحن دائماً
    if ($shippingMethod !== 'delivery') {
        return 0;
    }

    $shipping_type = DB::table('settings')->where('key', 'shipping_type')->value('value');

    if ($shipping_type === 'free') {
        return 0;
    }

    if ($shipping_type === 'region') {
        $regionsData = DB::table('settings')->where('key', 'shipping_regions')->value('value');
        $regions = $regionsData ? json_decode($regionsData, true) : [];

        if (!$city) {
            return 0;
        }

        $match = collect($regions)->first(function ($r) use ($city) {
            return isset($r['name']) && mb_strtolower(trim($r['name'])) === mb_strtolower(trim($city));
        });

        return $match ? (float) $match['fee'] : 0;
    }

    // fixed (أو أي قيمة افتراضية أخرى)
    return (float) (DB::table('settings')->where('key', 'shipping_fee')->value('value') ?? 0);
}
}
