<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
 public function index(Request $request)
{
    $query = Order::with(['user', 'products']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $orders = $query->latest()->paginate(15)->withQueryString();

    $variantIds = $orders->pluck('products')->flatten()->pluck('pivot.variant_id')->filter()->unique()->toArray();
    $variantsMap = !empty($variantIds) ? \App\Models\Variant::whereIn('id', $variantIds)->get()->keyBy('id') : collect();

    if ($request->ajax()) {
        return view('admin.orders.orders_table', compact('orders', 'variantsMap'))->render();
    }

    $users = User::select('id', 'name')->get();
    $products = Product::select('id', 'name')->get();
    
    return view('admin.orders.index', compact('orders', 'users', 'products', 'variantsMap'));
}

  public function store(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'السلة فارغة'
        ], 400);
    }

    $request->validate([
        'customer_name'   => 'required|string|max:255',
        'customer_phone'  => 'required|string',
        'shipping_method' => 'required|in:delivery,pickup',
        'city'            => 'nullable',
        'address'         => 'nullable',
        'notes'           => 'nullable|string|max:1000',
    ]);

    try {

        return DB::transaction(function () use ($request, $cart) {

            $subtotal = 0;
            $stockUpdates = [];

            foreach ($cart as $id => $details) {

                $qty = $details['quantity'];

                $variant = DB::table('variants')->where('id', $id)->first();

                if ($variant) {
                    $productId = $variant->product_id;
                } else {
                    $productId = $id;
                }

                // 🔒 LOCK مهم جداً لمنع بيع نفس القطعة مرتين
                $product = DB::table('products_tabel')
                    ->where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Product not found");
                }

                if ($product->stock < $qty) {
                    throw new \Exception("الكمية غير متوفرة");
                }

                // خصم الستوك
                DB::table('products_tabel')
                    ->where('id', $productId)
                    ->decrement('stock', $qty);

                $newStock = $product->stock - $qty;

                $stockUpdates[] = [
                    'product_id' => $productId,
                    'stock' => $newStock
                ];

                $subtotal += $details['price'] * $qty;
            }

            $shipping = $request->shipping_method == 'delivery' ? 5 : 0;

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'shipping_method' => $request->shipping_method,
                'city' => $request->city ?? null,
                'address' => $request->address ?? null,
                'total_price' => $subtotal + $shipping,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            session()->forget('cart');

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'stock_updates' => $stockUpdates
            ]);

        });

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}






    public function edit($id)
    {
        $order = Order::with('products')->find($id);
        if (!$order) {
            return response()->json(['error' => 'الطلب غير موجود'], 404);
        }
        return response()->json($order);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->products()->detach();
            $order->delete();
            return response()->json(['status' => 'success', 'message' => 'تم الحذف بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $orders = Order::with('user')->latest()->paginate(15)->withQueryString();
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.index', compact('orders', 'users', 'products'));
    }







    public function update(Request $request, $id)
{
    $request->validate([
        'customer_name'   => 'required|string|max:255',
        'customer_phone'  => 'required|string',
        'shipping_method' => 'required|in:delivery,pickup',
        'status'          => 'required|in:pending,completed,cancelled,processing', // أضف الحالات المستخدمة عندك
        'city'            => 'nullable',
        'address'         => 'nullable',
        'notes'           => 'nullable|string|max:1000',
    ]);

    try {
        $order = Order::findOrFail($id);

        // تحديث بيانات الطلب الأساسية دون المساس بالسلة
        $order->update([
            'customer_name'   => $request->customer_name,
            'customer_phone'  => $request->customer_phone,
            'shipping_method' => $request->shipping_method,
            'city'            => $request->city ?? null,
            'address'         => $request->address ?? null,
            'status'          => $request->status,
            'notes'           => $request->notes,
        ]);

        return response()->json([
            'status'  => 'success', // مطابق لما ينتظره الـ JavaScript عندك response.status === 'success'
            'success' => true,
            'message' => 'تم تحديث الطلب بنجاح'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function latestOrdersData()
    {
        try {
            $orders = Order::with('user')->latest()->take(10)->get()
                ->map(function($order) {
                    return [
                        'id'          => $order->id,
                        'user'        => $order->user->name ?? 'عميل غير معروف',
                        'phone'       => $order->user->phone_number ?? '', 
                        'date'        => $order->created_at ? $order->created_at->format('Y-m-d H:i') : '---',
                        'total_price' => $order->total_price,
                        'status'      => $order->status,
                        'status_raw'  => $order->getRawOriginal('status') ?? 'pending',
                    ];
                });
            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}