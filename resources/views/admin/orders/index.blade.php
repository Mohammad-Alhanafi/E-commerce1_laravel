<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('admin.manage_orders') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
     <link rel="stylesheet" href="{{ asset('css/bootstrap.main.css') }}">
    
    <style>
    :root {
    --black-primary: #0a0a0a;
    --black-secondary: #1a1a1a;
    --black-light: #2a2a2a;
    --gold-primary: #D4AF37;
    --gold-secondary: #FFD700;
    --gold-light: #FFF8DC;
    --white: #ffffff;
    --gray: #888888;
    --success: #28a745;
    --warning: #ffc107;
    --info: #17a2b8;
    --danger: #dc3545;
}

body {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    color: var(--white);
    font-family: 'Segoe UI', 'Cairo', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    animation: fadeIn 0.8s ease-out;
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* يمنع ظهور شريط التمرير العرضي في الصفحة كاملة */
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInRight {
    from { transform: translateX(100px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes pulseGold {
    0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
    100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.card {
    background: linear-gradient(145deg, var(--black-secondary), var(--black-primary));
    border: 1px solid var(--black-light);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.2);
    border-color: var(--gold-primary);
}

.label-gold {
    background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary), var(--gold-light), var(--gold-primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% auto;
    animation: gradientLabel 3s ease infinite;
    font-weight: 700;
    font-size: 1.1rem;
    display: block;
    margin-bottom: 10px;
    position: relative;
    padding-right: 15px;
}

.label-gold::before {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 20px;
    background: linear-gradient(to bottom, var(--gold-primary), var(--gold-secondary));
    border-radius: 3px;
}

@keyframes gradientLabel {
    0% { background-position: 0% center; }
    50% { background-position: 100% center; }
    100% { background-position: 0% center; }
}

.btn-gold {
    background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
    color: var(--black-primary) !important;
    border: none;
    border-radius: 8px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    animation: pulseGold 2s infinite;
}

.btn-gold:hover {
    background: linear-gradient(45deg, var(--gold-secondary), var(--gold-primary));
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
}

.btn-outline-gold {
    color: var(--gold-primary);
    border: 2px solid var(--gold-primary);
    background: transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-outline-gold:hover {
    background: var(--gold-primary);
    color: var(--black-primary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

/* --- تنسيق الجدول الأصلي --- */
.table-dark {
    --bs-table-bg: var(--black-secondary) !important;
    --bs-table-hover-bg: rgba(212, 175, 55, 0.15) !important;
    table-layout: auto !important;
    width: 100% !important;
    margin-bottom: 0 !important;
}

.table th {
    background: linear-gradient(to bottom, var(--black-light), var(--black-secondary));
    border-bottom: 2px solid var(--gold-primary);
    color: var(--gold-light);
    font-weight: 600;
    padding: 12px 8px;
    font-size: 0.85rem;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    border-color: var(--black-light);
    padding: 10px 8px;
    transition: all 0.3s ease;
    word-break: break-word;
}

.form-control, .form-select {
    background: var(--black-light);
    border: 1px solid var(--gray);
    color: var(--white);
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    background: var(--black-light);
    color: var(--white);
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    transform: translateY(-2px);
}

.modal-content {
    background: linear-gradient(145deg, var(--black-secondary), var(--black-primary));
    color: var(--white);
    border: 1px solid var(--gold-primary);
    border-radius: 15px;
}

.modal-header {
    background: linear-gradient(to right, var(--black-primary), var(--black-light));
    border-bottom: 2px solid var(--gold-primary);
    border-radius: 15px 15px 0 0;
}

.modal-title {
    color: var(--gold-primary);
    font-weight: 600;
}

.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.status-pending {
    background: linear-gradient(45deg, rgba(255, 193, 7, 0.2), rgba(255, 193, 7, 0.1));
    color: var(--warning);
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-processing {
    background: linear-gradient(45deg, rgba(23, 162, 184, 0.2), rgba(23, 162, 184, 0.1));
    color: var(--info);
    border: 1px solid rgba(23, 162, 184, 0.3);
}

.status-completed {
    background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1));
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-cancelled {
    background: linear-gradient(45deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.1)) !important;
    color: #ff6b6b !important;
    border: 1px solid rgba(220, 53, 69, 0.5) !important;
    padding: 5px 12px;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.main-title {
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
    margin-bottom: 30px;
    animation: slideInRight 0.6s ease-out;
}

.main-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 50%;
    height: 3px;
    background: linear-gradient(to right, transparent, var(--gold-primary));
    animation: expandWidth 1s ease-out 0.5s both;
}

@keyframes expandWidth {
    from { width: 0; }
    to { width: 50%; }
}

.floating-icon {
    animation: float 4s ease-in-out infinite;
}

.search-box {
    position: relative;
}

.search-box .form-control {
    padding-right: 45px;
    background: rgba(212, 175, 55, 0.05);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: var(--white);
    transition: all 0.3s ease;
}

.search-box .form-control:focus {
    background: rgba(212, 175, 55, 0.1);
    border-color: var(--gold-primary);
    box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
}

.search-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold-primary);
    animation: searchIconPulse 2s ease-in-out infinite;
}

@keyframes searchIconPulse {
    0%, 100% { transform: translateY(-50%) scale(1); }
    50% { transform: translateY(-50%) scale(1.1); }
}

.text-danger {
    color: #ff6b6b !important;
    text-shadow: 0 0 5px rgba(255, 107, 107, 0.5);
}

::-webkit-scrollbar {
    width: 10px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--black-secondary);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(var(--gold-primary), var(--gold-secondary));
    border-radius: 5px;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(212, 175, 55, 0.3);
    border-radius: 50%;
    border-top-color: var(--gold-primary);
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.empty-state {
    animation: fadeIn 1s ease-out;
}

.empty-state i {
    color: var(--gold-primary);
    filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.5));
}

.form-check {
    padding-right: 2.5em;
    padding-left: 0;
}

.form-check-input {
    margin-right: -2.5em;
    margin-left: 0;
}

.form-check-input:checked + .status-cancelled, 
.status-cancelled:hover {
    background-color: rgba(220, 53, 69, 0.4) !important;
    box-shadow: 0 0 8px rgba(220, 53, 69, 0.3);
}

.pagination .page-item.active .page-link {
    background-color: #d4af37 !important; 
    border-color: #d4af37 !important;
    color: #000 !important; 
}

.pagination .page-link {
    background-color: #1a1a1a !important; 
    border-color: #d4af37 !important; 
    color: #d4af37 !important; 
}

.pagination .page-link:hover {
    background-color: #d4af37 !important;
    color: #000 !important;
}

.pagination .page-item.disabled .page-link {
    background-color: #111 !important;
    border-color: #444 !important;
    color: #666 !important;
}

::placeholder {
    color: rgba(212, 175, 55, 0.6) !important;
    opacity: 1;
}

:-ms-input-placeholder {
    color: rgba(212, 175, 55, 0.6) !important;
}

::-ms-input-placeholder {
    color: rgba(212, 175, 55, 0.6) !important;
}

.form-control::placeholder {
    color: rgba(212, 175, 55, 0.6) !important;
    font-weight: 500;
    transition: all 0.3s ease;
}

.form-control:focus::placeholder {
    color: rgba(212, 175, 55, 0.4) !important;
    transform: translateX(-5px);
}

.gold-header {
    background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary), var(--gold-light), var(--gold-primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% auto;
    animation: gradientHeader 3s ease infinite;
    font-weight: 700;
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
}

.text-gold {
    color: #c5a059 !important;
}

.gold-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
}

@keyframes gradientHeader {
    0% { background-position: 0% center; }
    50% { background-position: 100% center; }
    100% { background-position: 0% center; }
}


/* ==========================================================================
   الحل الجذري الشامل: دفع كل ما هو خارج السايد بار تلقائياً دون تعديل الـ HTML
   ========================================================================== */

/* 1. تثبيت كود السايد بار لمنعه من القفز أو التحرك */


/* 2. استهداف وإجبار الحاويات الرئيسية على الابتعاد عن اليمين بمقدار مساحة السايد بار */
/* Desktop */
@media (min-width: 992px) {

main,
.main,
.main-content,
.content-wrapper,
.container,
.container-fluid,
body > div:not(.sidebar):not([class*="sidebar"]):not(.modal){

    margin-right:280px !important;
    width:calc(100% - 280px) !important;
    padding:30px !important;
}

}

/* Tablet & Mobile */
@media (max-width:991px){

main,
.main,
.main-content,
.content-wrapper,
.container,
.container-fluid,
body > div:not(.sidebar):not([class*="sidebar"]):not(.modal){

    margin-right:0 !important;
    width:100% !important;
    padding:15px !important;
}

}

/* 3. حماية الجداول داخل التصميم من الخروج عن الحدود المتاحة */
table {
    table-layout: auto !important;
    width: 100% !important;
}
    </style>
</head>
<body>

    @include('admin.sidebar')
    @include('admin.header')


    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 main-title">
                    <i class="bi bi-cart-check me-2 floating-icon"></i>{{ __('admin.manage_orders') }}
                </h1>
            </div>
        </div>

        <div class="row mb-4 animate__animated animate__fadeInUp">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="search-box flex-grow-1 me-3">
                                <input type="text" id="search" class="form-control search-gold" 
                                    placeholder="{{ __('admin.search_order_placeholder') }}">
                                <i class="bi bi-search"></i>
                            </div>
                            <button class="btn btn-gold" id="addOrderBtn">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('admin.add_new_order') }}
                            </button>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-3 mb-2">
                                <select class="form-select" id="statusFilter">
                                    <option value="">{{ __('admin.all_statuses') }}</option>
                                    <option value="pending">{{ __('admin.pending') }}</option>
                                    <option value="processing">{{ __('admin.processing') }}</option>
                                    <option value="completed">{{ __('admin.completed') }}</option>
                                    <option value="cancelled">{{ __('admin.cancelled') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="date" class="form-control" id="dateFilter" placeholder="{{ __('admin.order_date') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 gold-header">
                            <i class="bi bi-table me-2"></i>{{ __('admin.orders_list') }}
                        </h5>
                        <span class="badge bg-dark text-gold border border-gold" id="orders-count">
                            <i class="bi bi-cart me-1"></i> {{ __('admin.orders_count_label') }}: {{ $orders->total() }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div id="ordersWrapper">
                            @include('admin.orders.orders_table', ['orders' => $orders])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-cart-plus me-2"></i> {{ __('admin.order_management') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="orderForm">
                        @csrf
                        <input type="hidden" id="orderId" name="order_id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="label-gold"><i class="bi bi-person me-1"></i> {{ __('admin.customer') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="userId" name="user_id" required>
                                    <option value="" selected disabled>{{ __('admin.choose_customer') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} (ID: {{ $user->id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="label-gold"><i class="bi bi-currency-dollar me-1"></i> {{ __('admin.total_price') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="totalPrice" name="total_price" step="0.01" required>
                            </div>

                            <div class="col-12"><h6 class="text-gold mb-3 border-bottom pb-2">{{ __('admin.delivery_data') }}</h6></div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="label-gold">{{ __('admin.recipient_name') }}</label>
                                <input type="text" class="form-control" name="customer_name" id="modalCustomerName">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="label-gold">{{ __('admin.phone_number') }}</label>
                                <input type="tel" class="form-control" name="customer_phone" id="modalCustomerPhone">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="label-gold">{{ __('admin.city') }}</label>
                                <input type="text" class="form-control" name="city" id="modalCity">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="label-gold">{{ __('admin.detailed_address') }}</label>
                                <input type="text" class="form-control" name="address" id="modalAddress">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="label-gold mb-2 d-block">{{ __('admin.shipping_method_label') }}</label>
                                <div class="d-flex gap-3">
                                    <input type="radio" class="btn-check" name="shipping_method" id="method_delivery" value="delivery" checked>
                                    <label class="btn btn-outline-gold flex-fill py-3" for="method_delivery">
                                        <i class="bi bi-truck fs-4 d-block mb-1"></i>
                                        <span>{{ __('admin.delivery_service') }}</span>
                                    </label>

                                    <input type="radio" class="btn-check" name="shipping_method" id="method_pickup" value="pickup">
                                    <label class="btn btn-outline-warning flex-fill py-3" for="method_pickup">
                                        <i class="bi bi-shop fs-4 d-block mb-1"></i>
                                        <span>{{ __('admin.personal_pickup') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="label-gold">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="pending" id="pendingStatus" checked>
                                        <label class="form-check-label status-badge status-pending" for="pendingStatus">{{ __('admin.pending') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="processing" id="processingStatus">
                                        <label class="form-check-label status-badge status-processing" for="processingStatus">{{ __('admin.processing') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="completed" id="completedStatus">
                                        <label class="form-check-label status-badge status-completed" for="completedStatus">{{ __('admin.completed') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="cancelled" id="cancelledStatus">
                                        <label class="form-check-label status-badge status-cancelled" for="cancelledStatus">{{ __('admin.cancelled') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="label-gold">{{ __('admin.extra_notes') }}</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="{{ __('admin.notes_placeholder') }}"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="text-gold mb-2">{{ __('admin.products') }}:</label>
                                <table class="table table-dark table-bordered">
                                    <tbody id="modalProductsList"></tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-warning" id="addNewProductRow">
                                    <i class="bi bi-plus-circle"></i> {{ __('admin.add_product_row') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" form="orderForm" class="btn btn-gold" id="saveOrderBtn">{{ __('admin.save_order') }}</button>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')




<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'add_new_order'         => __('admin.add_new_order'),
    'edit_order_title'      => __('admin.edit_order_title'),
    'choose_product'        => __('admin.choose_product'),
    'save_success_msg'      => __('admin.save_success_msg'),
    'save_error_title'      => __('admin.save_error_title'),
    'error_title'           => __('admin.error_title'),
    'delete_order_confirm'  => __('admin.delete_order_confirm'),
    'delete_confirm'        => __('admin.delete_confirm'),
    'delete_success'        => __('admin.delete_success'),
    'delete_error'          => __('admin.delete_error'),
    'yes_delete'            => __('admin.yes_delete'),
    'cancel'                => __('admin.cancel'),
    'error_save_fields'     => __('admin.error_save_fields'),
    'new_order_notification'=> __('admin.new_order_notification'),
    'order_value'           => __('admin.order_value'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────

  





















$(document).ready(function() {
    // 1. إعدادات الأمان (CSRF Token)
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ---------------------------------------------------------
    // 2. وظائف الفلترة والبحث والترقيم (Pagination & Filtering)
    // ---------------------------------------------------------
    function filterOrders(page = 1) {
        let search = $('#search').val();
        let status = $('#statusFilter').val();
        let date = $('#dateFilter').val();

        $('#ordersWrapper').css('opacity', '0.5');

        $.ajax({
            url: "{{ route('orders.index') }}?page=" + page,
            type: "GET",
            data: { search: search, status: status, date: date },
            success: function(data) {
                $('#ordersWrapper').html(data);
                $('#ordersWrapper').css('opacity', '1');
            },
            error: function() {
                $('#ordersWrapper').css('opacity', '1');
                console.error("حدث خطأ في تحديث الجدول");
            }
        });
    }

    // تشغيل الفلترة عند أي تغيير
    $('#search').on('keyup', function() { filterOrders(); });
    $('#statusFilter, #dateFilter').on('change', function() { filterOrders(); });

    // تفعيل أزرار الترقيم عبر AJAX
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        filterOrders(page);
    });

    // ---------------------------------------------------------
    // 3. وظائف المنتجات الديناميكية داخل المودال
    // ---------------------------------------------------------
    function appendProductRow(productId = '', quantity = 1) {
        let row = `
        <tr class="product-row align-middle">
            <td>
                <select name="products[]" class="form-select bg-dark text-white border-secondary" required>
                    <option value="" selected disabled>{{ __('admin.choose_product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" ${productId == {{ $product->id }} ? 'selected' : ''}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantities[]" class="form-control bg-dark text-white border-secondary" value="${quantity}" min="1">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
        $('#modalProductsList').append(row);
    }

    $('#addNewProductRow').click(function() { appendProductRow(); });
    $(document).on('click', '.remove-row', function() { $(this).closest('tr').remove(); });

    // ---------------------------------------------------------
    // 4. فتح المودال (إضافة / تعديل)
    // ---------------------------------------------------------
    
    // عند الضغط على زر "إضافة طلب جديد"
    $('#addOrderBtn').click(function() {
        $('#orderForm')[0].reset();
        $('#orderId').val('');
        $('#modalProductsList').empty();
        appendProductRow(); // إضافة سطر فارغ للبدء
        $('#modalTitle').text(window.AppTrans.add_new_order);
        $('#orderModal').modal('show');
    });

    // عند الضغط على زر "تعديل" في الجدول
   $(document).on('click', '.editOrderBtn', function() {
    const id = $(this).data('id');
    
    // تأكد من أن المسار هنا يطابق الـ Route الخاص بلوحة التحكم عندك (إذا كان بحاجة لـ /admin ضعه، وإلا اتركه كما هو)
    $.get(`/orders/${id}/edit`, function(order) {
        // تعبئة الحقول الأساسية
        $('#orderId').val(order.id);
        $('#userId').val(order.user_id).trigger('change');
        $('#totalPrice').val(order.total_price);
        $('#notes').val(order.notes);
        $('#modalCustomerName').val(order.customer_name);
        $('#modalCustomerPhone').val(order.customer_phone);
        $('#modalCity').val(order.city);
        $('#modalAddress').val(order.address);
        
        // تحديد طريقة الشحن
        if (order.shipping_method) {
            $(`input[name="shipping_method"][value="${order.shipping_method}"]`).prop('checked', true);
        }
        
        // تحديد حالة الراديو بوتون (قيد الانتظار، ملغى، إلخ)
        $(`input[name="status"][value="${order.status}"]`).prop('checked', true);
        
        // 🔒 الحركة القاضية: تغيير الـ action الخاص بالفورم ليوجه إلى دالة الـ update الجديدة
        // إذا كانت روابط لوحة التحكم عندك تبدأ بـ /admin/orders قم بتعديلها هنا أيضاً
        $('#orderForm').attr('action', `/orders/${order.id}`);

        // تعبئة المنتجات
        $('#modalProductsList').empty();
        if (order.products && order.products.length > 0) {
            order.products.forEach(p => {
                appendProductRow(p.id, p.pivot ? p.pivot.quantity : 1);
            });
        } else {
            appendProductRow();
        }

        $('#modalTitle').text(window.AppTrans.edit_order_title + order.id);
        $('#orderModal').modal('show');
    });
});
    // ---------------------------------------------------------
    // 5. حفظ البيانات (Store & Update)
    // ---------------------------------------------------------
 $(document).on('submit', '#orderForm', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    
    // تأمين جلب قيمة الحالة
    let statusValue = $('input[name="status"]:checked').val();
    formData.set('status', statusValue);

    // 1. جلب الـ ID من الحقل المخفي داخل الفورم
    let orderId = $('#orderId').val(); 
    
    let actionUrl = "/orders"; 
    if (orderId && orderId !== '') {
        actionUrl = `/orders/${orderId}`;
        formData.append('_method', 'PUT'); // لإخبار لارافل أننا نريد الـ update
    }

    console.log("رابط الإرسال الفعلي هو: ", actionUrl); // لمراقبة الكود في الـ Console

    $.ajax({
        url: actionUrl,
        method: 'POST', // نرسل كـ POST ولارافل يترجمها لـ PUT بسبب الـ _method
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // التحقق من النجاح بكافة الأشكال الممكنة
            if(response.status === 'success' || response.success === true) {
                $('#orderModal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: window.AppTrans.save_success_msg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                setTimeout(function() {
                    window.location.reload(); 
                }, 1000);
            } else {
                Swal.fire({ icon: 'error', title: window.AppTrans.error_title, text: response.message });
            }
        },
        error: function(xhr) {
            console.error("تفاصيل الخطأ كاملة: ", xhr.responseText);
            let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : window.AppTrans.error_save_fields;
            Swal.fire({
                icon: 'error',
                title: window.AppTrans.save_error_title,
                text: errorMsg,
                background: '#1a1a1a',
                color: '#fff'
            });
        }
    });
});





$(document).on('click', '#createNewOrderBtn', function() {
    $('#orderForm')[0].reset();
    $('#orderId').val(''); // تصفير الحقل ليعرف الأياكس أنه طلب جديد
    $('#orderModal').modal('show');
});








    // ---------------------------------------------------------
    // 6. حذف الطلب
    // ---------------------------------------------------------
    $(document).on('click', '.deleteOrderBtn', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: window.AppTrans.delete_confirm,
            text: window.AppTrans.delete_order_confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: window.AppTrans.yes_delete,
            cancelButtonText: window.AppTrans.cancel,
            background: '#1a1a1a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/orders/" + id,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    success: function() {
                        Swal.fire(window.AppTrans.delete_success, '', 'success');
                        filterOrders();
                    },
                    error: function() {
                        Swal.fire(window.AppTrans.error_title, window.AppTrans.delete_error, 'error');
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>