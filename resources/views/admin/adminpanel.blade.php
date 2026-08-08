<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.title') ?? 'لوحة التحكم' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        if (typeof Chart === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"><\/script>');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('admin.sidebar')
@include('admin.header')















<!-- nav bar -->
<div class="main-content">
    
    

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        

        <div class="col-md-3">

            <div class="card card-custom">
                <div class="card-body stat-card">

                    

                    <div class="icon"><i class="bi bi-currency-dollar"></i></div>
                    <div class="number" id="orderCount" >{{ $orderCount ?? 0 }}</div>
                    <div class="label">{{ __('dashboard.total_sales') }}</div>
                    <div class="mt-2">
                        <span class="badge bg-success">{{ $salesPercent ?? '+0%' }}</span>
                     <small class="small-golden">{{ $salesPeriod ?? __('dashboard.from_last_month') }}</small>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body stat-card">
                    <div class="icon"><i class="bi bi-cart"></i></div>
                    <div class="number" id="newOrders">{{ $newOrders ?? 0 }}</div>
                    <div class="label">{{ __('dashboard.new_orders') }}</div>
                    <div class="mt-2">
                        <span class="badge bg-warning">{{ $pendingOrders ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body stat-card">
                    <div class="icon"><i class="bi bi-people"></i></div>
                    <div class="number" id="numOfNweCustomers">{{ $numOfNewCustomers ?? 0 }}</div>
                    <div class="label">{{ __('dashboard.new_customers') }}</div>
                    <div class="mt-2">
                        <span class="badge bg-success">{{ $percetnofCustomer ?? '+0%' }}</span>
                        <small class="small-golden">{{ $cutomersPeriod ?? __('dashboard.from_last_month') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom">
                <div class="card-body stat-card">
                    <div class="icon"><i class="bi bi-box"></i></div>
                    <div class="number"  id="totalProducts">{{ $totalProducts ?? 0 }}</div>
                    <div class="label">{{ __('dashboard.total_products') }}</div>
                    <div class="mt-2">
                        <span class="badge bg-danger">{{ $lowStockProductsCount ?? 0 }} {{ __('dashboard.low_stock') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    








    <!-- الرسم البياني للمبيعات -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card card-custom shadow-sm">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <div><i class="bi bi-bar-chart-line-fill me-2"></i> {{ __('dashboard.sales_chart') }}</div>
                    @php
                    $initLabels = $salesChartData['labels'] ?? [
                                            __('admin.pending'),
                                             __('admin.processing'),
                                       __('admin.completed'),
                                       __('admin.shipped'),
                                         __('admin.cancelled')
                                          ];
                        $initData   = $salesChartData['data']   ?? [0, 0, 0, 0, 0];
                        $initTotal  = array_sum($initData);
                    @endphp
                    <span class="badge fw-bold px-3 py-2" id="salesChartTotalBadge"
                          style="font-size:13px; border-radius:8px;
                                 background: var(--primary-color);
                                 color: var(--btn-text-color, #000);">
                        إجمالي الطلبات: {{ $initTotal }}
                    </span>
                </div>
                <div class="card-body p-3">
                    <div id="salesChartContainer"
                         style="position:relative; height:260px; width:100%;
                                display:flex; flex-direction:column; justify-content:flex-end;
                                background:color-mix(in srgb, var(--card-bg,#111) 85%, var(--primary-color,#D4AF37) 15%);
                                border-radius:12px; padding:15px 12px 10px 12px;
                                border:1px solid var(--border-color, rgba(255,255,255,0.15));">

                        <!-- خطوط الشبكة مرتبطة بلون الحدود من الثيم -->
                        <div style="position:absolute; top:15px; left:15px; right:15px; bottom:45px;
                                    display:flex; flex-direction:column; justify-content:space-between;
                                    pointer-events:none; opacity:0.25;">
                            <div style="border-top:1px dashed var(--border-color,#444); width:100%;"></div>
                            <div style="border-top:1px dashed var(--border-color,#444); width:100%;"></div>
                            <div style="border-top:1px dashed var(--border-color,#444); width:100%;"></div>
                            <div style="border-top:1px dashed var(--border-color,#444); width:100%;"></div>
                        </div>

                        <!-- صفوف الأعمدة — كل لون مربوط بمتغير حالة الثيم -->
                        <div id="salesChartBarsRow"
                             class="d-flex align-items-end justify-content-around w-100"
                             style="height:200px; position:relative; z-index:2;">
                            @php
                                $maxVal = max(array_merge($initData, [1]));
                                /*
                                 * ربط كل حالة طلب بمتغير CSS خاص من الثيم:
                                 *   pending    → --warning-color
                                 *   processing → --info-color
                                 *   completed  → --success-color
                                 *   shipped    → --primary-color
                                 *   canceled   → --danger-color
                                 */
                                $statusBarColors = [
                                    ['var' => '--warning-color', 'fb' => '#FFC107'],
                                    ['var' => '--info-color',    'fb' => '#17A2B8'],
                                    ['var' => '--success-color', 'fb' => '#28A745'],
                                    ['var' => '--primary-color', 'fb' => '#D4AF37'],
                                    ['var' => '--danger-color',  'fb' => '#DC3545'],
                                ];
                            @endphp

                            @foreach($initLabels as $idx => $label)
                                @php
                                    $val = (int)($initData[$idx] ?? 0);
                                    $pct = max(12, min(100, round(($val / $maxVal) * 82)));
                                    $sc  = $statusBarColors[$idx % count($statusBarColors)];
                                    $cv  = 'var(' . $sc['var'] . ', ' . $sc['fb'] . ')';
                                @endphp
                                <div class="sales-bar-item d-flex flex-column align-items-center flex-fill"
                                     style="height:100%; justify-content:flex-end; padding:0 4px;">

                                    <!-- شارة القيمة — لون خلفيتها من الثيم -->
                                    <span class="badge mb-2 fw-bold bar-value-badge"
                                          style="background: {{ $cv }};
                                                 color: var(--btn-text-color, #000);
                                                 font-size:13px; min-width:28px;
                                                 transition: all 0.3s ease;
                                                 box-shadow: 0 3px 8px color-mix(in srgb, {{ $cv }} 50%, transparent);">
                                        {{ $val }}
                                    </span>

                                    <!-- العمود — تدرج من لون الحالة إلى فاتح منه -->
                                    <div class="bar-column"
                                         style="width:70%; max-width:52px; height:{{ $pct }}%;
                                                background: linear-gradient(180deg,
                                                    {{ $cv }} 0%,
                                                    color-mix(in srgb, {{ $cv }} 55%, white) 100%);
                                                border-radius:8px 8px 0 0; min-height:14px;
                                                box-shadow: 0 4px 18px color-mix(in srgb, {{ $cv }} 45%, transparent);
                                                border: 1px solid color-mix(in srgb, {{ $cv }} 75%, white);
                                                transition: height 0.6s cubic-bezier(0.4,0,0.2,1);">
                                    </div>

                                    <!-- اسم الحالة — لونه من متغير الثيم -->
                                    <span class="mt-2 fw-bold text-center text-truncate w-100"
                                          style="font-size:12px;
                                                 color: {{ $cv }};
                                                 text-shadow: 0 1px 4px rgba(0,0,0,0.8);">
                                        {{ $label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>










        <!-- المنتجات الأكثر مبيعاً -->
        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-header card-header-custom"><i class="bi bi-star me-2"></i> {{ __('dashboard.top_products') }}</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush" id="topProductList" style="color: gold">
                        @forelse($topProducts ?? [] as $product)
                           <li class="list-group-item d-flex justify-content-between align-items-center gold-item">

                                <div>
                                    <h6 class="mb-0">{{ $product['name'] }}</h6>
                                    <small class="text-muted" style="color:rgb(244, 144, 13)">{{ $product['sales'] ?? 0 }} {{ __('admin.view') }}</small>
                                </div>
                                @if($loop->first)<span class="badge bg-primary">{{ __('dashboard.top_products') }}</span>@endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted" >{{ __('admin.no_data') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>








    <!-- الطلبات الأخيرة -->
  <div class="card card-custom">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-list-check me-2"></i> {{ __('dashboard.recent_orders') }}
        </div>
        <div class="d-flex gap-2"> 
            <button onclick="loadLatestOrders()" class="btn btn-warning btn-sm fw-bold text-dark">
                <i class="bi bi-arrow-clockwise"></i> {{ __('admin.refresh') }}
            </button>

            <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm">{{ __('dashboard.view_all') }}</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.name') }}</th>
                        <th>{{ __('dashboard.today') }}</th>
                        <th>{{ __('admin.price') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="latestOrdersTable">
                    @forelse($latestOrders ?? [] as $order)
                        <tr class="align-middle">
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? __('admin.external_customer') }}</td>
                            <td class="small" style="color: var(--primary-color);">
                                 {{ $order->created_at ? $order->created_at->format('Y-m-d') : '---' }}
                            </td>
                            <td>{{ $order->total_price }} $</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'completed' || $order->status == 'مكتمل') bg-success
                                    @elseif($order->status == 'pending' || $order->status == 'قيد المعالجة') bg-warning text-dark
                                    @elseif($order->status == 'processing' || $order->status == 'شحن') bg-info
                                    @else bg-danger @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-warning">{{ __('admin.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>









    <!-- {{ __('dashboard.low_stock') }} -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header card-header-custom"><i class="bi bi-exclamation-triangle me-2"></i> {{ __('dashboard.low_stock') }}</div>
                <div class="card-body">
                    <div class="row" id="lowStockProductRow">
                        @foreach($lowStockProducts ?? [] as $product)
                            <div class="col-md-3">
                                <div class="card mb-3">
                                    <div class="card-body" style="background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div style="width:50px;height:50px;background:color-mix(in srgb, var(--primary-color) 12%, transparent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                    @if(str_contains($product->name,'عباية'))
                                                        <i class="bi bi-bag" style="font-size:1.5rem;color:var(--primary-color);"></i>
                                                    @else
                                                        <i class="bi bi-droplet" style="font-size:1.5rem;color:var(--accent-color);"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0" style="color: var(--text-color);">
                                           {{ $product->name }}
                                                  </h6>
                                                <small class="text-muted">{{ __('admin.stock') }}: <span class="text-danger">{{ $product->stock }}</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if(empty($lowStockProducts) || count($lowStockProducts) == 0)
                            <div class="col-12 text-center text-gold">{{ __('admin.no_data') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>






@include('admin.footer')



   


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// 1. تعريف المتغيرات العالمية في البداية
let salesChartInstance = null;
const initialChartData = {!! json_encode($salesChartData ?? ['labels' => ['قيد الانتظار', 'جاري المعالجة', 'مكتمل', 'تم الشحن', 'ملغي'], 'data' => [0,0,0,0,0]]) !!};

// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'best_seller_badge'     => __('admin.best_seller_badge'),
    'sold_count'            => __('admin.sold_count'),
    'enough_stock'          => __('admin.enough_stock'),
    'low_stock_badge'       => __('admin.low_stock_badge'),
    'no_data'               => __('admin.no_data'),
    'new_order_notification'=> __('admin.new_order_notification'),
    'order_value'           => __('admin.order_value'),
    'wa_partner1'           => __('admin.wa_partner1'),
    'wa_partner2'           => __('admin.wa_partner2'),
    'view'                  => __('admin.view'),
    'pending'               => __('admin.pending'),
    'processing'            => __('admin.processing'),
    'completed'             => __('admin.completed'),
    'shipped'               => __('admin.shipped'),
    'cancelled'             => __('admin.cancelled'),
    'orders_count'          => __('dashboard.orders_count'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────

// 2. تشغيل المحرك الرئيسي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function () {
    if (typeof initialChartData !== 'undefined' && initialChartData) {
        updateSalesChartUI(initialChartData);
    }
    refreshDashboard();
    // تحديث كل شيء كل 10 ثواني تلقائياً
    setInterval(refreshDashboard, 10000); 
});

// 3. الدالة الأم التي تنادي على الجميع
function refreshDashboard() {
    loadStatus();
    loadTopProducts();
    loadLatestOrders();
    loadLowStockProducts();
    loadSalesChart();
}

// --- الدوال الفرعية ---

function loadStatus(){
    fetch('/admin/ajax/status')
    .then(res => res.json())
    .then(data => {
        if(document.getElementById('orderCount')) document.getElementById('orderCount').innerText = data.orderCount;
        if(document.getElementById('newOrders')) document.getElementById('newOrders').innerText = data.newOrders;
        if(document.getElementById('numOfNewCustomers')) document.getElementById('numOfNewCustomers').innerText = data.numOfNewCustomers;
        if(document.getElementById('totalProducts')) document.getElementById('totalProducts').innerText = data.totalProducts;
    }).catch(err => console.error('Status Error:', err));
}

function loadTopProducts(){
    fetch('/admin/ajax/top-products')
    .then(res => res.json())
    .then(data => {
        const ul = document.getElementById('topProductList');
        if(!ul) return;
        ul.innerHTML = ''; 
        data.forEach((p, i) => {
        const badge = i === 0 ? `<span class="badge bg-primary">${window.AppTrans.best_seller_badge}</span>` : '';
            ul.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center" style="background:transparent; color:var(--primary-color); border-color: var(--border-color);">
                    <div><h6 class="mb-0" style="color:var(--text-color);">${p.name}</h6><small class="text-muted">${p.sales} ${window.AppTrans.sold_count}</small></div>
                    ${badge}
                </li>`;
        });
    });
}


let lastOrderId = null; 

const partner1_phone = "96181025201"; 
const partner2_phone = "96170092963"; 

function loadLatestOrders() {
    console.log("جاري التحديث..."); // عشان تتأكد إن الزر شغال
    fetch('/admin/ajax/latest-orders')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('latestOrdersTable');
        if (!tbody || data.length === 0) return;

        const newestOrder = data[0]; 

        // منطق التنبيه الصوتي والإشعارات (تم تصحيح الأقواس هنا)
        if (lastOrderId !== null && newestOrder.id > lastOrderId) {
            let audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); 
            audio.play().catch(e => console.log("تفاعل مع الصفحة لتفعيل الصوت"));

            if (typeof notify === "function") {
                notify(`${window.AppTrans.new_order_notification} ${newestOrder.user} — ${window.AppTrans.order_value} ${newestOrder.total_price}$`, 'success');
            }
        }
        
        lastOrderId = newestOrder.id;

        // بناء الجدول
        tbody.innerHTML = '';
        data.forEach(order => {
            let badgeClass = 'bg-secondary';
            if (order.status_raw === 'completed' || order.status === 'مكتمل') badgeClass = 'bg-success';
            if (order.status_raw === 'pending' || order.status === 'قيد المعالجة') badgeClass = 'bg-warning text-dark';
            if (order.status_raw === 'processing' || order.status === 'شحن') badgeClass = 'bg-info text-white';
            if (order.status_raw === 'cancelled' || order.status === 'ملغي') badgeClass = 'bg-danger';

            const msg = encodeURIComponent(`${window.AppTrans.new_order_notification} #${order.id}\n${order.user}\n${order.total_price}$`);
            const urlP1 = `https://wa.me/${partner1_phone}?text=${msg}`;
            const urlP2 = `https://wa.me/${partner2_phone}?text=${msg}`;
            const clientPhoneClean = order.phone ? order.phone.replace(/\D/g, '') : '';
            const urlClient = clientPhoneClean ? `https://wa.me/${clientPhoneClean}` : '#';

            tbody.innerHTML += `
                <tr class="align-middle">
                    <td>#${order.id}</td>
                    <td>${order.user}</td>
                    <td class="small" style="color: var(--primary-color);">${order.date}</td>
                    <td>${order.total_price} $</td>
                    <td><span class="badge ${badgeClass}">${order.status}</span></td>
                    <td><div class="btn-group">
                            <a href="${urlP1}" target="_blank" class="btn btn-sm btn-success" title="${window.AppTrans.wa_partner1}"> (1) </a>
                            <a href="${urlP2}" target="_blank" class="btn btn-sm btn-info" title="${window.AppTrans.wa_partner2}"> (2) </a>
                            ${clientPhoneClean ? `
                            <a href="${urlClient}" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fab fa-whatsapp"></i>
                            </a>` : ''}
                            <a href="/orders/${order.id}" class="btn btn-sm btn-outline-warning">${order.view_text || window.AppTrans.view}</a>
                        </div></td>
                </tr>`;
        });
    })
    .catch(err => console.error('Error:', err));
}

// تشغيل عند التحميل وعند التوقيت
document.addEventListener('DOMContentLoaded', function() {
    loadLatestOrders();
    setInterval(loadLatestOrders, 10000); 
});

function notify(message, type) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        icon: type,
        title: message
    });
}



function loadLowStockProducts(){
    fetch('/admin/ajax/low-stock')
    .then(res => res.json())
    .then(data => {
        const row = document.getElementById('lowStockProductRow');
        if(!row) return;
        row.innerHTML = '';
        if(data.length === 0) {
            row.innerHTML = `<div class="col-12 text-center text-muted">${window.AppTrans.enough_stock}</div>`;
            return;
        }
        data.forEach(p => {
            row.innerHTML += `
                <div class="col-md-3">
                    <div class="card mb-3 border-danger shadow-sm" style="background-color: var(--card-bg);">
                        <div class="card-body p-2 text-center">
                            <h6 class="mb-1" style="color:var(--text-color);">${p.name}</h6>
                            <small class="badge bg-danger">${window.AppTrans.low_stock_badge}: ${p.stock}</small>
                        </div>
                    </div>
                </div>`;
        });
    });
}

function loadSalesChart(period = 'monthly') {
    fetch('/admin/ajax/sales-chart?period=' + period)
    .then(res => {
        if (!res.ok) throw new Error('HTTP error ' + res.status);
        return res.json();
    })
    .then(chartData => {
        updateSalesChartUI(chartData);
    })
    .catch(err => {
        console.error('Sales Chart AJAX Error:', err);
    });
}

function updateSalesChartUI(chartData) {
    if (!chartData || !chartData.labels || !chartData.data) return;

    const labels = chartData.labels;
    const data   = chartData.data;
    const maxVal = Math.max(...data.map(Number), 1);
    const totalOrders = data.reduce((a, b) => a + Number(b), 0);

    // تحديث شارة الإجمالي
    const totalBadge = document.getElementById('salesChartTotalBadge');
    if (totalBadge) {
        totalBadge.innerText = 'إجمالي الطلبات: ' + totalOrders;
    }

    const barsRow = document.getElementById('salesChartBarsRow');
    if (!barsRow) return;

    // قراءة ألوان الحالات مباشرة من متغيرات الثيم النشط
    const cs = getComputedStyle(document.documentElement);
    const getVar = (name, fallback) => cs.getPropertyValue(name).trim() || fallback;

    /*
     * ربط كل حالة طلب بمتغير CSS من الثيم — نفس الترتيب الموجود في PHP:
     *   pending    → --warning-color
     *   processing → --info-color
     *   completed  → --success-color
     *   shipped    → --primary-color
     *   canceled   → --danger-color
     */
    const statusColors = [
        getVar('--warning-color', '#FFC107'),   // قيد الانتظار
        getVar('--info-color',    '#17A2B8'),   // جاري المعالجة
        getVar('--success-color', '#28A745'),   // مكتمل
        getVar('--primary-color', '#D4AF37'),   // تم الشحن
        getVar('--danger-color',  '#DC3545'),   // ملغي
    ];
    const btnTextColor = getVar('--btn-text-color', '#000');

    let html = '';
    labels.forEach((label, idx) => {
        const val   = Number(data[idx]) || 0;
        const pct   = Math.max(12, Math.min(100, Math.round((val / maxVal) * 82)));
        const color = statusColors[idx % statusColors.length];

        html += `
            <div class="sales-bar-item d-flex flex-column align-items-center flex-fill"
                 style="height:100%; justify-content:flex-end; padding:0 4px;">

                <span class="badge mb-2 fw-bold bar-value-badge"
                      style="background:${color};
                             color:${btnTextColor};
                             font-size:13px; min-width:28px;
                             transition:all 0.3s ease;
                             box-shadow:0 3px 8px ${color}66;">
                    ${val}
                </span>

                <div class="bar-column"
                     style="width:70%; max-width:52px; height:${pct}%;
                            background:linear-gradient(180deg, ${color} 0%, ${color}88 100%);
                            border-radius:8px 8px 0 0; min-height:14px;
                            box-shadow:0 4px 18px ${color}77;
                            border:1px solid ${color}BB;
                            transition:height 0.6s cubic-bezier(0.4,0,0.2,1);">
                </div>

                <span class="mt-2 fw-bold text-center text-truncate w-100"
                      style="font-size:12px;
                             color:${color};
                             text-shadow:0 1px 4px rgba(0,0,0,0.8);">
                    ${label}
                </span>
            </div>
        `;
    });

    barsRow.innerHTML = html;
}


//NOTIFICATION
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end', // سيظهر في أعلى اليمين
    showConfirmButton: false,
    timer: 5000, // يختفي بعد 3 ثواني
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// وظيفة جاهزة للاستخدام
function notify(title, icon = 'success') {
    Toast.fire({
        icon: icon, // success, error, warning, info
        title: title
    });
}






@include('components.theme-toggle')
</body>
</html>
