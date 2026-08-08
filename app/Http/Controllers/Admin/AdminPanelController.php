<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;

class AdminPanelController extends Controller
{
    public function dashboardStatus()
    {
        try {
            // ===== العدادات =====
            $orderCount = Order::count();
            $newOrders = Order::where('status', 'pending')->count();
            $numOfNewCustomers = User::where('role', 'customer')->count();
            $totalProducts = Product::count();

            // ===== المنتجات منخفضة المخزون =====
            $lowStockProducts = Product::select('id', 'name', 'stock', 'image')
                ->where('stock', '<=', 5)
                ->take(10)
                ->get();

            // ===== آخر الطلبات =====
            $latestOrders = Order::select('id', 'user_id', 'customer_name', 'customer_phone', 'total_price', 'status', 'created_at')
                ->with('user:id,name,email')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // ===== أفضل المنتجات مبيعاً =====
            $topProducts = Product::select('id', 'name')
                ->withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'name' => $p->name,
                        'sales' => $p->orders_count
                    ];
                });

            // ===== الرسم البياني للمبيعات حسب الحالة =====
            $salesChartData = $this->getChartDataFormatted();
            $salesChart = $salesChartData;

        } catch (Exception $e) {
            // قيم افتراضية في حال فشل قاعدة البيانات حتى لا تنهار الصفحة
            $orderCount = $newOrders = $numOfNewCustomers = $totalProducts = 0;
            $lowStockProducts = $latestOrders = $topProducts = collect([]);
            $salesChartData = [
                'labels' => ['قيد الانتظار', 'جاري المعالجة', 'مكتمل', 'تم الشحن', 'ملغي'],
                'data'   => [0, 0, 0, 0, 0]
            ];
            $salesChart = $salesChartData;
        }

        // ===== بيانات واجهة المستخدم الإضافية =====
        $percetnofCustomer = '+0%';
        $cutomersPeriod = 'من الشهر الماضي';
        $salesPercent = '+0%';
        $salesPeriod = 'من الشهر الماضي';

        // ===== إرسال البيانات إلى الـ Blade =====
        return view('admin.adminpanel', compact(
            'orderCount',
            'newOrders',
            'numOfNewCustomers',
            'totalProducts',
            'lowStockProducts',
            'latestOrders',
            'topProducts',
            'salesChart',
            'salesChartData',
            'percetnofCustomer',
            'cutomersPeriod',
            'salesPercent',
            'salesPeriod'
        ));
    }

    public function ajaxStatus()
    {
        try {
            return response()->json([
                'orderCount' => Order::count(),
                'newOrders' => Order::where('status', 'pending')->count(),
                'numOfNewCustomers' => User::where('role', 'customer')->count(),
                'totalProducts' => Product::count(),
            ]);
        } catch (Exception $e) {
            return response()->json(['orderCount' => 0, 'newOrders' => 0, 'numOfNewCustomers' => 0, 'totalProducts' => 0]);
        }
    }

    // Helper formatting function for Chart Data
    private function getChartDataFormatted()
    {
        try {
            $statusLabelsMap = [
                'pending'    => 'قيد الانتظار',
                'processing' => 'جاري المعالجة',
                'completed'  => 'مكتمل',
                'shipped'    => 'تم الشحن',
                'canceled'   => 'ملغي',
            ];

            $allStatuses = [
                'pending'    => 0,
                'processing' => 0,
                'completed'  => 0,
                'shipped'    => 0,
                'canceled'   => 0,
            ];

            $salesChart = Order::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            foreach ($salesChart as $status => $count) {
                $statusKey = strtolower(trim($status));
                if (array_key_exists($statusKey, $allStatuses)) {
                    $allStatuses[$statusKey] += (int)$count;
                } else {
                    $allStatuses[$statusKey] = (int)$count;
                }
            }

            $translatedLabels = [];
            foreach (array_keys($allStatuses) as $key) {
                $translatedLabels[] = $statusLabelsMap[$key] ?? ucfirst($key);
            }

            return [
                'labels' => $translatedLabels,
                'data'   => array_values($allStatuses),
            ];
        } catch (Exception $e) {
            return [
                'labels' => ['قيد الانتظار', 'جاري المعالجة', 'مكتمل', 'تم الشحن', 'ملغي'],
                'data'   => [0, 0, 0, 0, 0]
            ];
        }
    }

    // function Ajax for a Chart
    public function ajaxChart()
    {
        return response()->json($this->getChartDataFormatted());
    }

    public function ajaxLatestOrders() {
        try {
            $latestOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function($or) {
                    $statusKey = strtolower(trim($or->status));
                    $translatedStatus = __('admin.' . $statusKey);
                    if ($translatedStatus === 'admin.' . $statusKey) {
                        $translatedStatus = $or->status;
                    }
                    
                    return [
                        'id'          => $or->id,
                        'user'        => $or->user->name ?? __('admin.client'),
                        'phone'       => $or->user->phone ?? '',
                        'date'        => $or->created_at ? $or->created_at->format('Y-m-d') : '',
                        'total_price' => $or->total_price,
                        'status'      => $translatedStatus,
                        'status_raw'  => $statusKey,
                        'view_text'   => __('admin.view')
                    ];
                });

            return response()->json($latestOrders);
        } catch (Exception $e) {
            return response()->json([]);
        }
    }

    public function ajaxTopProducts() {
        try {
            $topProducts = Product::withCount('orders')
                ->orderBy('orders_count','desc')
                ->take(5)
                ->get()
                ->map(function($p){
                    return [
                        'name' => $p->name,
                        'sales'=> $p->orders_count
                    ];
                });

            return response()->json($topProducts);
        } catch (Exception $e) {
            return response()->json([]); // يرجع مصفوفة فارغة بدلاً من كسر الجافاسكريبت والصفحة
        }
    }

    public function ajaxLowStock() {
        try {
            $lowStockProducts = Product::where('stock','<=',5)->get()
                ->map(function($p){
                    return [
                        'name' => $p->name,
                        'stock'=> $p->stock
                    ];
                });
            return response()->json($lowStockProducts);
        } catch (Exception $e) {
            return response()->json([]);
        }
    }
}