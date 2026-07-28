<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.title') ?? 'لوحة التحكم' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="card card-custom">
                <div class="card-header card-header-custom"><i class="bi bi-bar-chart me-2"></i> {{ __('dashboard.sales_chart') }}</div>
                <div class="card-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="salesChart"></canvas>
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
            <table class="table table-hover table-custom">
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
                            <td class="text-white">#{{ $order->id }}</td>
            <td class="text-white">{{ $order->user->name ?? __('admin.external_customer') }}</td>
                           <td class="text-warning small">
                                 {{ $order->created_at ? $order->created_at->format('Y-m-d') : '---' }}
                            </td>
                            <td class="text-white">{{ $order->total_price }} $</td>
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
                                    <div class="card-body" style="background-color: #D4AF37;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div style="width:50px;height:50px;background:#e9ecef;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                    @if(str_contains($product->name,'عباية'))
                                                        <i class="bi bi-bag" style="font-size:1.5rem;color:var(--primary-color);"></i>
                                                    @else
                                                        <i class="bi bi-droplet" style="font-size:1.5rem;color:var(--accent-color);"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0" style="color: #000;">
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
    refreshDashboard();
    // تحديث كل شيء كل 30 ثانية تلقائياً
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
                <li class="list-group-item d-flex justify-content-between align-items-center" style="background:transparent; color:gold; border-color: #444;">
                    <div><h6 class="mb-0 text-white">${p.name}</h6><small class="text-muted">${p.sales} ${window.AppTrans.sold_count}</small></div>
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
                    <td class="text-white">#${order.id}</td>
                    <td class="text-white">${order.user}</td>
                    <td class="text-warning small">${order.date}</td>
                    <td class="text-white">${order.total_price} $</td>
                    <td><span class="badge ${badgeClass}">${order.status}</span></td>
                    <td><div class="btn-group">
                            <a href="${urlP1}" target="_blank" class="btn btn-sm btn-success" title="${window.AppTrans.wa_partner1}"> (1) </a>
                            <a href="${urlP2}" target="_blank" class="btn btn-sm btn-info text-white" title="${window.AppTrans.wa_partner2}"> (2) </a>
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
                    <div class="card mb-3 bg-dark border-danger shadow-sm">
                        <div class="card-body p-2 text-center">
                            <h6 class="text-white mb-1">${p.name}</h6>
                            <small class="badge bg-danger">${window.AppTrans.low_stock_badge}: ${p.stock}</small>
                        </div>
                    </div>
                </div>`;
        });
    });
}

function loadSalesChart() {
    fetch('/admin/ajax/sales-chart')
    .then(res => res.json())
    .then(chartData => {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        if (salesChartInstance) {
            salesChartInstance.destroy();
        }

        const isArabic = (document.documentElement.lang === 'ar') || (document.documentElement.dir === 'rtl');

        const labelsMap = {
            'pending':    window.AppTrans.pending,
            'processing': window.AppTrans.processing,
            'completed':  window.AppTrans.completed,
            'shipped':    window.AppTrans.shipped,
            'canceled':   window.AppTrans.cancelled,
            'cancelled':  window.AppTrans.cancelled
        };

        const labelsFormatted = (chartData.labels || []).map(label => labelsMap[label] || label);

        salesChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsFormatted, 
                datasets: [{
                    label: window.AppTrans.orders_count || '{{ __('dashboard.orders_count') }}',
                    data: chartData.data || [],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.85)',
                        'rgba(23, 162, 184, 0.85)',
                        'rgba(40, 167, 69, 0.85)',
                        'rgba(13, 110, 253, 0.85)',
                        'rgba(220, 53, 69, 0.85)'
                    ],
                    borderColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#28a745',
                        '#0d6efd',
                        '#dc3545'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 6,
                    barThickness: 28, 
                    maxBarThickness: 35 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleColor: '#ffd700',
                        bodyColor: '#fff',
                        borderColor: 'rgba(212, 160, 23, 0.4)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { color: "#ccc", stepSize: 1, precision: 0 },
                        grid: { color: "rgba(255, 255, 255, 0.08)" }
                    },
                    x: { 
                        ticks: { color: "#ccc", font: { size: 12 } },
                        grid: { display: false } 
                    }
                }
            }
        });
    }).catch(err => console.error('Chart Error:', err));
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
