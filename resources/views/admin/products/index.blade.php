<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.manage_products') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">


    <style>



/* رفع المودال فوق أي طبقة أخرى */
.modal {
    z-index: 9999 !important;
}
.modal-backdrop {
    z-index: 9998 !important;
}
.modal-backdrop {
    display: none !important; /* هذا سيخفي السواد تماماً ويجعلك تضغط على الفورم */
}
      
        :root {
            --black-primary: var(--bg-color, #0a0a0a);
            --black-secondary: var(--card-bg, #1a1a1a);
            --black-light: var(--border-color, #2a2a2a);
            --gold-primary: var(--primary-color, #D4AF37);
            --gold-secondary: var(--secondary-color, #FFD700);
            --gold-light: var(--accent-color, #FFF8DC);
            --white: var(--text-color, #ffffff);
            --gray: var(--text-muted, #888888);
            --success: var(--success-color, #28a745);
            --danger: var(--danger-color, #dc3545);
            --warning: var(--warning-color, #ffc107);
            --info: var(--info-color, #17a2b8);
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', 'Cairo', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            animation: fadeIn 0.8s ease-out;
        }

        /* --- Animations --- */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInRight { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideInLeft { from { transform: translateX(-100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes pulseGold { 0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); } 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        /* --- Elements --- */
        .card {
            background: linear-gradient(145deg, var(--black-secondary), var(--black-primary));
            border: 1px solid var(--black-light);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--gold-primary);
        }

        .btn-gold {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--black-primary) !important;
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            animation: pulseGold 2s infinite;
        }

        /* --- Forms & Inputs (حل مشكلة "ثالثاً") --- */
        .form-control, .form-select {
            background-color: var(--black-light) !important;
            border: 1px solid var(--gray) !important;
            color: var(--white) !important; /* لون النص أبيض ليظهر بوضوح */
            border-radius: 8px;
            padding: 12px 15px;
            pointer-events: auto !important; /* ضمان التفاعل */
        }

        /* لضمان لون الكتابة داخل الفورم */
        #variantForm input, 
        #variantForm select, 
        #variantForm textarea {
            color: var(--text-color) !important;
            background-color: var(--input-bg) !important;
            pointer-events: auto !important;
            cursor: text;
        }

        #variantForm label {
            color: var(--gold-primary) !important;
            font-weight: 600;
        }

        /* --- Modal Corrections (حل مشكلة "ثانياً") --- */
        .modal {
            z-index: 1060 !important;
        }

        .modal-content {
            background: linear-gradient(145deg, var(--black-secondary), var(--black-primary));
            border: 1px solid var(--gold-primary);
            pointer-events: auto !important; /* تجعل المودال يستجيب للنقر */
        }

        .modal-backdrop {
            opacity: 0.6 !important; /* تجنب الحذف الكامل لعدم حدوث أخطاء التركيز */
        }

        /* --- Tables --- */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--white);
        }

        .products-table th {
            background-color: var(--bg-color);
            color: var(--primary-color);
            padding: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .products-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        /* --- Status Badges --- */
        .status-active {
            background-color: var(--success);
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .status-inactive {
            background-color: var(--danger);
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        /* --- Labels Styling --- */
        .label-gold {
            color: var(--gold-primary);
            font-weight: 600;
            border-right: 4px solid var(--gold-primary);
            padding-right: 10px;
            margin-bottom: 10px;
            display: block;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--black-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--gold-primary); border-radius: 5px; }




        /* إعادة اللون الذهبي المتدرج للـ Labels */
.label-gradient {
    background: linear-gradient(45deg, #D4AF37, #FFD700, #FFF8DC, #D4AF37);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% auto;
    animation: gradientLabel 3s ease infinite;
    font-weight: 700 !important;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 8px;
}

/* حركة التدرج الذهبي */
@keyframes gradientLabel {
    0% { background-position: 0% center; }
    50% { background-position: 100% center; }
    100% { background-position: 0% center; }
}

/* تنسيق الأيقونات بجانب الذهب لتبدو متناسقة */
.label-gradient i {
    -webkit-text-fill-color: var(--primary-color);
    font-size: 1.2rem;
}

#productForm .form-control, 
#variantForm .form-control {
    border: 1px solid var(--border-color) !important;
    background-color: var(--input-bg) !important;
    color: var(--input-text) !important;
}

#productForm .form-control:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 8px color-mix(in srgb, var(--primary-color) 40%, transparent) !important;
}





/* تعديل عنوان إضافة منتج جديد */
.card-header {
    background: var(--card-bg) !important;
    border-bottom: 2px solid var(--primary-color) !important;
    padding: 15px 20px;
}

/* apply gradient gold on text & icon inside header */
.card-header, 
.card-header i {
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color, #FFD700), var(--primary-color)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
    background-size: 200% auto;
    animation: gradientLabel 3s ease infinite;
    font-weight: 800 !important;
}


.card-header i {
    display: inline-block;
    margin-left: 8px;
}


.badge.text-gold {
    background: var(--bg-color) !important;
    border: 1px solid var(--primary-color) !important;
    padding: 8px 15px !important;
    font-size: 1rem !important;
    font-weight: 800 !important;
    
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color, #FFD700), var(--primary-color)) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
    
    /* حركة الوميض */
    background-size: 200% auto;
    animation: gradientLabel 3s ease infinite;
    
    display: inline-block;
    box-shadow: 0 0 10px color-mix(in srgb, var(--primary-color) 20%, transparent);
}

/* لضمان أن الحدود تظل ذهبية ولا تختفي مع التدرج */
.badge.border-gold {
    border-color: var(--primary-color) !important;
}





/* تنسيق زر التفاصيل الذهبي */
.btn-details, 
.btn-outline-gold {
    background: transparent !important;
    color: var(--primary-color) !important;
    border: 2px solid var(--primary-color) !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    padding: 6px 15px !important;
    transition: all 0.3s ease-in-out !important;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.btn-details:hover, 
.btn-outline-gold:hover {
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color, var(--primary-color))) !important;
    color: var(--bg-color) !important;
    box-shadow: 0 0 15px color-mix(in srgb, var(--primary-color) 50%, transparent) !important;
    transform: translateY(-2px);
}

.btn-details i {
    margin-left: 5px;
    color: inherit; 
}





.table thead tr th {
    color: var(--primary-color) !important;
    border-bottom: 2px solid var(--primary-color) !important;
    text-align: center;
    font-weight: bold;
    text-transform: uppercase;
    background-color: var(--bg-color) !important;
}





.table tbody tr td:nth-child(4), 
.table tbody tr td:nth-child(5) { 
    color: var(--accent-color, var(--primary-color)) !important;
    font-weight: bold;
}




.table thead th, 
.table tbody td {
    text-align: center !important; 
    vertical-align: middle !important; 
}


.table tbody td:last-child {
    display: table-cell;
    text-align: center !important;
}


.table tbody td {
    color: var(--text-color);
    padding: 12px 8px !important;
}







.table thead th:nth-child(1), 
.table tbody td:nth-child(1) {
    width: 50px !important;
}




/* هذا هو البديل الصحيح */
.table td:nth-child(2) { 
    width: 60px !important; /* مساحة على قد الصورة بس */
    padding-left: 0 !important;
    text-align: center !important;
}

.table td:nth-child(3) { 
    padding-right: 5px !important; /* يقرب الاسم من الصورة */
    text-align: right !important;
}

/* باقي الأعمدة (الفئة، السعر، الكمية، الحالة) */
.table thead th:nth-child(3), .table tbody td:nth-child(3), /* الفئة */
.table thead th:nth-child(4), .table tbody td:nth-child(4), /* السعر */
.table thead th:nth-child(5), .table tbody td:nth-child(5), /* الكمية */
.table thead th:nth-child(6), .table tbody td:nth-child(6)  /* الحالة */
{
    width: 12% !important;
}


.table thead th:last-child, 
.table tbody td:last-child {
    width: 180px !important;
}


.table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; 
}




/* توسيط محتوى خانة الإجراءات (الأزرار) */
.table tbody td:last-child {
    text-align: center !important;
    vertical-align: middle !important;
}

/* تنسيق الحاوية داخل الخلية لضمان اصطفاف الأزرار */
.table tbody td:last-child .btn-group, 
.table tbody td:last-child div {
    display: flex !important;
    justify-content: center !important; /* التوسط الأفقي */
    align-items: center !important;     /* التوسط العمودي */
    gap: 8px !important;                /* مسافة بين الأزرار */
}

/* تحسين شكل الأزرار داخل الإجراءات */
.table tbody td:last-child .btn {
    margin: 0 !important; /* إلغاء الهوامش الافتراضية */
    display: inline-flex;
    align-items: center;
    justify-content: center;
}




/* تنسيق كپسولات الحالة */
.status-badge {
    padding: 5px 12px !important;
    border-radius: 50px !important; /* شكل بيضاوي كامل */
    font-size: 12px !important;
    font-weight: 700 !important;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: 1px solid transparent;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* حالة: نشط (Active) - توهج أخضر */
.status-active {
    background: color-mix(in srgb, var(--success-color) 10%, transparent) !important;
    color: var(--success-color) !important;
    border-color: color-mix(in srgb, var(--success-color) 50%, transparent) !important;
    box-shadow: 0 0 8px color-mix(in srgb, var(--success-color) 20%, transparent);
}

/* حالة: غير نشط (Inactive) - توهج رمادي */
.status-inactive {
    background: color-mix(in srgb, var(--text-muted) 10%, transparent) !important;
    color: var(--text-muted) !important;
    border-color: color-mix(in srgb, var(--text-muted) 50%, transparent) !important;
}

/* حالة: نفذ من المخزون (Out of Stock) - توهج أحمر */
.status-outofstock {
    background: color-mix(in srgb, var(--danger-color) 10%, transparent) !important;
    color: var(--danger-color) !important;
    border-color: color-mix(in srgb, var(--danger-color) 50%, transparent) !important;
    box-shadow: 0 0 8px color-mix(in srgb, var(--danger-color) 20%, transparent);
}

/* إضافة نقطة صغيرة مضيئة بجانب الكلمة */
.status-badge::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: currentColor; /* تأخذ نفس لون النص */
    display: inline-block;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
}



.table td .sku-text {
    background: linear-gradient(to right, #BF953F, #FCF6BA, #B38728, #FBF5B7, #AA771C) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
    background-size: 200% auto !important;
    
    font-family: 'Courier New', Courier, monospace !important;
    font-weight: 900 !important;
    font-size: 1rem !important;
    display: inline-block !important;
    
    animation: goldShine 3s linear infinite !important;
}

@keyframes goldShine {
    to {
        background-position: 200% center;
    }
}


.pagination-gold .pagination {
    margin-bottom: 0;
    gap: 5px;
}

.pagination-gold .page-link {
    background-color: var(--card-bg) !important;
    border: 1px solid var(--primary-color) !important;
    color: var(--primary-color) !important;
    border-radius: 4px;
    padding: 5px 12px;
}

.pagination-gold .page-item.active .page-link {
    background-color: var(--primary-color) !important;
    color: var(--bg-color) !important;
}

.pagination-gold .page-link:hover {
    background-color: var(--primary-color);
    color: var(--bg-color) !important;
}

.pagination-gold .page-item.disabled .page-link {
    border-color: var(--border-color) !important;
    color: var(--text-muted) !important;
}




.pagination-gold .page-link {
    background-color: var(--card-bg) !important;
    border: 1px solid var(--primary-color) !important;
    color: var(--primary-color) !important;
    margin: 0 2px;
}

.pagination-gold .page-item.active .page-link {
    background-color: var(--primary-color) !important;
    color: var(--bg-color) !important;
    border-color: var(--primary-color) !important;
}

.pagination-gold .page-item.disabled .page-link {
    border-color: var(--border-color) !important;
    color: var(--text-muted) !important;
    background-color: var(--bg-color) !important;
}



/* تغيير لون الـ Placeholder لكل الحقول */
::placeholder {
    color: var(--text-muted) !important;
    opacity: 1; /* المتصفحات مثل Firefox بتقلل الشفافية تلقائياً، هيك بنثبتها */
}

/* للمتصفحات القديمة مثل Internet Explorer */
:-ms-input-placeholder {
    color: var(--text-muted) !important;
}

/* لمتصفح Microsoft Edge */
::-ms-input-placeholder {
    color: var(--text-muted) !important;
}



/* تنسيق الحاوية الأساسية للترقيم */
.custom-pagination .pagination {
    margin-bottom: 0;
    gap: 5px; /* المسافة بين المربعات */
}

.custom-pagination .page-item .page-link {
    background-color: var(--bg-color); /* أسود غامق */
    border: 1px solid var(--primary-color);  /* إطار ذهبي */
    color: var(--primary-color);             /* نص ذهبي */
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px !important; /* شكل مربع */
    box-shadow: none !important;
    transition: all 0.3s ease;
}

/* حالة المرور بالماوس (Hover) */
.custom-pagination .page-item .page-link:hover {
    background-color: var(--primary-color);
    color: var(--btn-text-color, #000);
}

/* الصفحة الحالية (Active) */
.custom-pagination .page-item.active .page-link {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--btn-text-color, #000) !important;
    font-weight: bold;
}

/* الصفحات المعطلة (Disabled) */
.custom-pagination .page-item.disabled .page-link {
    background-color: var(--bg-color);
    border-color: var(--border-color);
    color: var(--text-muted);
}

.custom-pagination nav .flex.items-center.justify-between,
.custom-pagination nav p.text-sm.text-gray-700 {
    display: none !important;
}


.pulse-stock {
    animation: stock-shadow-pulse 1.5s infinite;
}

@keyframes stock-shadow-pulse {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}


.table {
    table-layout: auto !important; 
    width: 100% !important;
}

.table td, .table th {
    vertical-align: middle;
    padding: 10px 5px !important; 
    white-space: nowrap; 
}

.table td:nth-child(2) {
    text-align: center;
    padding-right: 0 !important;
}

.table td:nth-child(3) {
    padding-left: 0 !important;
    text-align: right;
}







/* --- إعدادات الحاوية الرئيسية والتجاوب --- */

/* الافتراضي للشاشات الكبيرة (الكمبيوتر والمكتبية) */
.main-content-wrapper {
    margin-right: 260px; /* يعتمد على عرض السايدبار لديك، إذا كان عرضه مختلفاً غير الرقم هنا */
    margin-left: 0;
    padding: 15px;
    transition: all 0.3s ease-in-out;
    min-height: 100vh;
}

/* عندما تكون الشاشة متوسطة أو صغيرة (تابلت وهواتف ذكية) */
@media (max-width: 991.98px) {
    .main-content-wrapper {
        margin-right: 0 !important; /* إلغاء الهامش لأن السايدبار غالباً يصبح مخفياً أو علوياً */
        padding: 10px;
    }
}

/* تحسينات إضافية لتجاوب الجداول والفورم */
.card {
    max-width: 100%;
    overflow: hidden;
}

/* جعل الجدول مرناً ولا يخرج عن حدود الشاشة في الهواتف */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
}

    </style>




    
</head>

<body >

  

    @include('admin.sidebar')
    @include('admin.header')





    


    <!-- Main Container -->
    <div class="main-content-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
   
        <h1 class="h2 page-title mb-0">
            <i class="bi bi-box-seam me-2 floating-icon"></i>{{ __('admin.manage_products') }}
        </h1>
    </div>
</div>

        <!-- Success/Error Messages -->
        <div id="messageContainer"></div>


        <!-- Form Section -->
        <div class="row mb-4 animate__animated animate__fadeInUp">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-plus-circle me-2" ></i>{{ __('admin.add_new_product') }}
                    </div>
                    <div class="card-body">
                        <form id="productForm" action="{{ route('products.store') }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-box me-1"></i>
                                        {{ __('admin.product_name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-tags me-1"></i>
                                        {{ __('admin.category') }} <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" class="form-control" required>
                                        @if($categories->count() > 0)
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">{{ __('admin.all_categories_option') }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-card-text me-1"></i>
                                        {{ __('admin.description') }}
                                    </label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-currency-dollar me-1"></i>
                                        {{ __('admin.price') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text text-gold" style="background-color: var(--input-bg); border-color: var(--border-color);">$</span>
                                        <input type="number" step="0.01" name="price" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-box-seam me-1"></i>
                                        {{ __('admin.stock') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" class="form-control" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-circle me-1"></i>
                                        {{ __('admin.status') }} <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-control" required>
                                        <option value="active">{{ __('admin.active') }}</option>
                                        <option value="inactive">{{ __('admin.inactive') }}</option>
                                        <option value="outofstock">{{ __('admin.outofstock') }}</option>
                                    </select>
                                </div>



                                  <div class="col-12 mb-3">
                              <label class="form-label label-gradient">
                             <i class="bi bi-stars me-1"></i> 
                                      {{ __('admin.instructions') }}
                                  </label>
                         <textarea name="care_instructions" id="care_instructions" class="form-control" rows="3" ></textarea>
                                   </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label label-gradient">
                                        <i class="bi bi-upc-scan me-1"></i>
                                        {{ __('admin.sku') }}
                                    </label>
                                    <input type="text" name="sku" class="form-control" placeholder="SKU001">
                                </div>

     <div class="mb-3">

        <label class="form-label label-gradient">
                                        <i class="bi bi-upc-scan me-1"></i>
                                        {{ __('admin.image') }}
                                    </label>
    <input type="file" name="image" id="productImage" 
           class="form-control shadow-none">
    <div class="form-text" style="font-size: 0.8rem; color: var(--text-muted);">{{ __('admin.image_optional_hint2') }}</div>
</div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-gold">
                                        <i class="bi bi-plus-circle me-2"></i>{{ __('admin.add_product') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" id="searchName" name="name" value="{{ request('name') }}" 
                           class="form-control" 
                           placeholder="{{ __('admin.search_product_placeholder') }}">
                </div>
                <div class="col-md-4">
                    <select id="searchCategory" name="category_id" class="form-control">
                        <option value="">{{ __('admin.all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
            </div>
        </form>
    </div>
</div>
<div id="alert-container" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;"></div>

         <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2 text-gold"></i>{{ __('admin.products_list') }}
                </h5>
                <span class="badge text-gold border border-gold total-products-count" style="background-color: color-mix(in srgb, var(--primary-color) 12%, var(--card-bg));">
                    <i class="bi bi-box me-1"></i> {{ __('admin.total_count') }}: {{ $products->total() }}
                </span>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto !important; -webkit-overflow-scrolling: touch;">
                    <table class="table mb-0" id="productsTable" style="min-width: 900px; table-layout: fixed;">
                       <thead>
                       <tr class="text-gold">
                       <th width="30" class="text-center">#</th>
                       <th width="70" class="text-center">{{ __('admin.image') }}</th>
                       <th width="200">{{ __('admin.product') }}</th>
                       <th width="100">{{ __('admin.category') }}</th>
                       <th width="80">{{ __('admin.price') }}</th>
                       <th width="60" class="text-center">{{ __('admin.stock') }}</th>
                       <th width="120">{{ __('admin.description') }}</th>
                       <th width="120">{{ __('admin.instructions') }}</th>
                       <th width="80" class="text-center">{{ __('admin.status') }}</th>
                       <th width="150" class="text-center">{{ __('admin.actions') }}</th>
                       </tr>
                       </thead>
                       <tbody id="productsTableBody">
                           @foreach($products as $product)
                               <tr class="animate__animated animate__fadeInRight"
                                   style="animation-delay: {{ $loop->index * 0.05 }}s; vertical-align: middle;"
                                   data-id="{{ $product->id }}">
                                   <td class="text-center fw-bold">{{ $products->firstItem() + $loop->index }}</td>
                                   <td class="text-center">
                                        @if($product->image && Storage::disk('public')->exists($product->image))
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded-circle border border-gold shadow-sm"
                                                 style="width: 45px; height: 45px; object-fit: cover; background-color: var(--input-bg);">
                                        @elseif($product->image && (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')))
                                            <img src="{{ $product->image }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded-circle border border-gold shadow-sm"
                                                 style="width: 45px; height: 45px; object-fit: cover; background-color: var(--input-bg);">
                                        @else
                                            <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto"
                                                 style="width: 45px; height: 45px; background-color: var(--input-bg); border-color: var(--border-color) !important;">
                                                <i class="bi bi-image text-muted" style="font-size: 0.8rem;"></i>
                                            </div>
                                        @endif

                                    <td>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0 text-truncate" style="max-width: 180px; color: var(--text-color);">{{ $product->name }}</h6>
                                            <small class="sku-golden">{{ $product->sku ?? '-' }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge text-gold border border-gold-subtle" style="background-color: var(--input-bg);">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                    </td>

                                    <td class="text-center">
                                        <span class="fw-bold {{ $product->stock > 10 ? 'text-success' : 'text-warning' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="description-text text-gold small">
                                            {{ Str::limit($product->description, 30, '...') ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="care-text text-info small">
                                            {{ Str::limit($product->care_instructions, 20, '...') ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $statusClass = [
                                                'active'     => 'status-active',
                                                'inactive'   => 'status-inactive',
                                                'outofstock' => 'status-outofstock',
                                            ][$product->status] ?? 'status-inactive';
                                            $statusText = [
                                                'active'     => __('admin.active'),
                                                'inactive'   => __('admin.inactive'),
                                                'outofstock' => __('admin.outofstock'),
                                            ][$product->status] ?? $product->status;
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>

                                    <td>
                                        <div class="action-buttons d-flex justify-content-center">
                                            <button class="btn btn-sm btn-outline-gold manageVariantsBtn me-2"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    title="{{ __('admin.view') }}">
                                                <i class="bi bi-list-nested me-1"></i>
                                                <span>{{ __('admin.view') }}</span>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info editProductBtn me-2"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    data-category="{{ $product->category_id }}"
                                                    data-price="{{ $product->price }}"
                                                    data-stock="{{ $product->stock }}"
                                                    data-status="{{ $product->status }}"
                                                    data-sku="{{ $product->sku }}"
                                                    data-description="{{ $product->description }}"
                                                    data-care="{{ $product->care_instructions }}"
                                                    title="{{ __('admin.edit') }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger deleteProductBtn"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    title="{{ __('admin.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                               </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-center py-3">
                <nav class="admin-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
</div>

    <!-- Variants Modal -->
    <div class="modal fade" id="variantsModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-list-nested me-2"></i>
                        {{ __('admin.manage_variants') }}: <span id="variantProductName" class="text-gold"></span>
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- Add Variant Form -->
                    <div class="card mb-4">
                        <div class="card-header">{{ __('admin.add_variant_title') }}</div>
                        <div class="card-body">
            
         <form id="variantForm">
         @csrf
        <input type="hidden" name="product_id" id="variantProductId">
        <input type="hidden" name="variant_id" id="edit_variant_id">
        <input type="hidden" name="variant_id" id="variant_id_input" value="">

         <div class="row g-3">

    <!-- نوع التفصيل -->
    <div class="col-md-4">
        <label class="form-label text-gold" >{{ __('admin.variant_type') }}</label>
        <input type="text" name="name" class="form-control" required placeholder="">
    </div>

    <!-- السعر الإضافي -->
    <div class="col-md-4">
        <label class="form-label text-gold">{{ __('admin.additional_price') }}</label>
        <input type="number" step="0.01" min="0" name="additional_price" class="form-control">
    </div>

    <!-- 👇 col يحتوي row -->
    <div class="col-12">
        <div class="row g-3">

            <!-- الكمية -->
            <div class="col-md-6">
                <label class="form-label text-gold">{{ __('admin.quantity') }}</label>
                <input type="number" min="0" name="stock" class="form-control" placeholder="0" required>
            </div>

            <!-- الحالة -->
            <div class="col-md-6">
                <label class="form-label text-gold">{{ __('admin.status') }}</label>
                <select name="status" class="form-control">
                    <option value="active">{{ __('admin.active') }}</option>
                    <option value="inactive">{{ __('admin.inactive') }}</option>
                </select>
            </div>

            <div class="col-md-4">
            <label class="form-label text-gold">{{ __('admin.optional_image') }}</label>
            <input type="file" name="variant_image" class="form-control" accept="image/*">
        </div>

        </div>
    </div>

<div class="mb-3" id="color-picker-container">
    <label for="color" class="form-label text-gold">{{ __('admin.optional_color') }}:</label>
    <div class="d-flex align-items-center gap-2">
        <input type="color" name="color" id="color_input" class="form-control form-control-color" value="#000000" title="{{ __('admin.choose_color') }}">
        
        <input type="text" id="color_text" class="form-control" placeholder="#000000">
        
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearColor()">{{ __('admin.no_color') }}</button>
    </div>
</div>


    <!-- الخصائص -->
    <div class="col-12">
        <label class="form-label text-gold">{{ __('admin.properties') }}</label>
        <div id="attributesContainer">
            <div class="row mb-2 attribute-row">
                <div class="col-md-5">
                    <input type="text" name="attribute_name[]" class="form-control" placeholder="{{ __('admin.property_name') }}">
                </div>
                <div class="col-md-5">
                    <input type="text" name="attribute_value[]" class="form-control" placeholder="{{ __('admin.property_value') }}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeAttributeBtn">×</button>
                </div>
            </div>
        </div>
        <button type="button" id="addAttributeBtn" class="btn btn-gold mt-2">
            {{ __('admin.add_new_property') }}
        </button>
    </div>

    <!-- ملاحظات -->
    <div class="col-12">
        <label class="form-label text-gold">{{ __('admin.notes') }}</label>
        <textarea name="notes" class="form-control" rows="2"></textarea>
    </div>

    <!-- زر الإضافة -->
    <div class="col-12">
        <button type="submit" class="btn btn-gold mt-3" id="submitBtn">
            <i class="bi bi-plus-circle me-2"></i><span>{{ __('admin.add') }}</span>
        </button>
    </div>

</div>

</form>




                   


<!-- Variants List -->


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('admin.variants_list') }}</span>
        <span class="badge text-gold border border-gold" id="variantsCount" style="background-color: color-mix(in srgb, var(--primary-color) 12%, var(--card-bg));">0</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="overflow-x: auto !important; -webkit-overflow-scrolling: touch;">
            <table class="table mb-0" id="variantsTable" style="min-width: 850px; table-layout: fixed;">
                <thead>
                    <tr class="text-gold">
                        <th style="width: 80px;">{{ __('admin.image') }}</th> 
                        <th style="width: 200px;">{{ __('admin.name') }}</th>
                        <th style="width: 120px;">{{ __('admin.color') }}</th> 
                        <th style="width: 130px;">{{ __('admin.additional_price') }}</th>
                        <th style="width: 100px;">{{ __('admin.quantity') }}</th>
                        <th style="width: 300px;">{{ __('admin.notes') }}</th>
                        <th style="width: 120px;" class="text-center">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="noVariantsMessage" class="text-center py-4 d-none">
            <i class="bi bi-inbox text-gold fs-4 d-block mb-2"></i>
            <p class="text-muted mb-0">{{ __('admin.no_variants') }}</p>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center" id="variantsPagination">
        </div>
</div>

   <!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>{{ __('admin.edit_product_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editProductId">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.product_name') }}</label>
                            <input type="text" id="editProductName" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin.category') }}</label>
                            <select id="editProductCategory" name="category_id" class="form-control" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.price') }}</label>
                            <input type="number" step="0.01" id="editProductPrice" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.stock') }}</label>
                            <input type="number" id="editProductStock" name="stock" class="form-control" required>
                        </div>

                        <div class="mb-3">
    <label class="form-label text-gold">{{ __('admin.sku') }}</label>
    <input type="text" name="sku" id="editProductSku" class="form-control">
</div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('admin.status') }}</label>
                            <select id="editProductStatus" name="status" class="form-control" required>
                                <option value="active">{{ __('admin.active') }}</option>
                                <option value="inactive">{{ __('admin.inactive') }}</option>
                                <option value="outofstock">{{ __('admin.outofstock') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.description') }}</label>
                            <textarea id="editProductDescription" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                     <div class="col-12">
                            <label class="form-label">{{ __('admin.instructions') }}</label>
                            <textarea name="care_instructions" id="editProductCareInstructions" class="form-control" rows="3"></textarea>
                        </div>

                    <div class="mb-3">
    <label for="productImage" class="form-label text-gold">{{ __('admin.product_image') }}</label>
    <input type="file" name="image" id="productImage" 
           class="form-control shadow-none">
    <div class="form-text" style="font-size: 0.8rem; color: var(--text-muted);">{{ __('admin.image_optional_hint') }}</div>
</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                <button type="submit" form="editProductForm" class="btn btn-gold">{{ __('admin.save_changes') }}</button>
            </div>
        </div>
    </div>




</div>

    </div>


    @include('admin.footer')


    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'save_success_msg'      => __('admin.save_success_msg'),
    'save_error_title'      => __('admin.save_error_title'),
    'error_title'           => __('admin.error_title'),
    'data_error_title'      => __('admin.data_error_title'),
    'unexpected_error'      => __('admin.unexpected_error'),
    'confirm_delete_title'  => __('admin.confirm_delete_title'),
    'delete_product_confirm'=> __('admin.delete_product_confirm'),
    'delete_product_detail' => __('admin.delete_product_detail'),
    'delete_confirm'        => __('admin.delete_confirm'),
    'delete_success'        => __('admin.delete_success'),
    'delete_error'          => __('admin.delete_error'),
    'add_success'           => __('admin.add_success'),
    'update_success'        => __('admin.update_success'),
    'operation_success'     => __('admin.operation_success'),
    'yes_delete'            => __('admin.yes_delete'),
    'yes_delete_it'         => __('admin.yes_delete_it'),
    'delete_btn'            => __('admin.delete_btn'),
    'cancel'                => __('admin.cancel'),
    'total_count'           => __('admin.total_count'),
    'variant_update'        => __('admin.variant_update'),
    'variant_add'           => __('admin.variant_add'),
    'no_care_instructions'  => __('admin.no_care_instructions'),
    'without_sku'           => __('admin.without_sku'),
    'general_category'      => __('admin.general_category'),
    'processing_msg'        => __('admin.processing_msg'),
    'adding'                => __('admin.adding'),
    'save_product'          => __('admin.save_product'),
    'error_save_fields'     => __('admin.error_save_fields'),
    'error_check_data'      => __('admin.error_check_data'),
    'active'                => __('admin.active'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    /* =========================
       SHOW MESSAGE
    ========================== */
    function showMessage(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        const messageHtml = `
            <div class="row mb-4 alert-animation">
                <div class="col-12">
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi ${icon} me-2 fs-4"></i>
                            <div>${message}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        `;
        $('#messageContainer').html(messageHtml);
        setTimeout(() => { $('.alert').alert('close'); }, 5000);
    }

    /* =========================
       SET LOADING BUTTON
    ========================== */
    function setLoading(button, isLoading) {
        if (isLoading) {
            button.data('original-text', button.html());
            button.html('<span class="loading-spinner"></span> جاري المعالجة...');
            button.prop('disabled', true);
        } else {
            button.html(button.data('original-text'));
            button.prop('disabled', false);
        }
    }

let currentProductIdForVariants = null;

// 1. دالة التحميل الرئيسية (تدعم الترقيم)
//LOAD VARIANT
function loadVariants(productId, page = 1) {
    currentProductIdForVariants = productId;
    $.ajax({
        url: `/variants/product/${productId}?page=${page}`,
        type: 'GET',
        success: function(response) {
            let tbody = $('#variantsTable tbody');
            tbody.empty();

            // 💡 الحل السحري هنا:
            // إذا كان response هو المصفوفة مباشرة نأخذه، وإذا كان pagination نأخذ response.data
            let variants = Array.isArray(response) ? response : (response.data || []);

            if (variants.length === 0) {
                $('#variantsCount').text(0);
                $('#noVariantsMessage').removeClass('d-none');
                $('#variantsPagination').empty();
                return;
            }

            $('#noVariantsMessage').addClass('d-none');
            
            // تحديث العدد الكلي
            let total = response.total !== undefined ? response.total : variants.length;
            $('#variantsCount').text(total);

            let rows = '';
            variants.forEach(v => { rows += buildVariantRow(v); });
            tbody.html(rows);

            // تشغيل الترقيم فقط إذا كان السيرفر يرسل بيانات الترقيم
            if (response.current_page) {
                renderVariantsPagination(response, productId);
            } else {
                $('#variantsPagination').empty();
            }
        },
        error: function(xhr) {
            console.error("خطأ في جلب البيانات:", xhr.responseText);
        }
    });
}

// 2. دالة بناء السطر المحدثة لعرض الصورة (تم التصحيح والربط المباشر)
function buildVariantRow(v) {
    // ✅ الحل هنا: نستخدم الرابط الجاهز مباشرة بدون إضافة مجلد storage يدوياً
    let imgSrc = v.variant_image 
                ? v.variant_image 
                : 'https://via.placeholder.com/50?text=No+Img';

    // 1. معالجة عرض اللون
    let colorDisplay = v.color 
        ? `<div class="d-flex align-items-center gap-2">
             <span style="background-color: ${v.color}; width: 20px; height: 20px; display: inline-block; border-radius: 50%; border: 1px solid #d4af37;"></span>
             <small style="color: var(--text-muted);">${v.color}</small>
           </div>` 
        : '<span class="text-muted small">---</span>';

    // 2. معالجة "الخصائص الإضافية"
    let extraAttributes = '';
    if (v.attributes && v.attributes.length > 0) {
        extraAttributes = '<div class="mt-1">';
        v.attributes.forEach(attr => {
            extraAttributes += `<span class="badge me-1" style="font-size: 10px; background-color: var(--primary-color); color: var(--btn-text-color, #000);">
                                 ${attr.name}: ${attr.value}
                               </span>`;
        });
        extraAttributes += '</div>';
    }

    // --- 3. منطق التنبيه للمخزن (لو 5 أو أقل) ---
    let stockStatusClass = "border-info text-info"; // الحالة العادية
    let alertIcon = ""; 

    if (parseInt(v.stock) <= 5) {
        stockStatusClass = "border-danger text-danger fw-bold pulse-stock"; // أحمر وينبض
        alertIcon = '<i class="bi bi-exclamation-triangle-fill me-1"></i>';
    }

    return `
        <tr id="variant-row-${v.id}" style="vertical-align: middle;">
            <td style="width: 80px;">
                <img src="${imgSrc}" class="rounded border border-secondary" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            
            <td style="width: 200px;">
                <strong class="text-gold">${v.name}</strong>
                ${extraAttributes}
            </td>
            
            <td style="width: 150px;">${colorDisplay}</td>
            
            <td style="width: 130px;"><span style="color: var(--text-color);">${parseFloat(v.additional_price).toFixed(2)} $</span></td>
            
            <td style="width: 100px;">
                <span class="badge border ${stockStatusClass}" style="font-size: 0.85rem; background-color: var(--input-bg);">
                    ${alertIcon} ${v.stock}
                </span>
            </td>
            
            <td style="width: 300px;"><small class="text-muted">${v.notes || '-'}</small></td>
            
            <td class="text-center" style="width: 120px; white-space: nowrap;">
                <button type="button" class="btn btn-sm btn-outline-warning edit-v" 
                        data-id="${v.id}" 
                        data-name="${v.name}" 
                        data-color="${v.color || ''}" 
                        data-price="${v.additional_price}" 
                        data-stock="${v.stock}" 
                        data-notes="${v.notes || ''}"
                        data-image="${imgSrc}"> <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger delete-v" data-id="${v.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
}
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    //OPEN MODAL VARIANT
    // 3. فتح المودال
    $(document).on('click', '.manageVariantsBtn', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        currentProductIdForVariants = productId;
        $('#variantProductId').val(productId);
        $('#variantProductName').text(productName);
        
        // تصفير الفورم والـ ID عند فتح مودال جديد
        resetVariantForm();
        
        loadVariants(productId); 
        $('#variantsModal').modal('show');
    });

    // 4. دالة معالجة الفورم (إضافة وتعديل مدمج)
   $('#variantForm').on('submit', function(e) {
    e.preventDefault();
    
    const vId = $('#edit_variant_id').val();
    const url = vId ? `/variants/${vId}` : '/variants';
    
    // 1. استخدم FormData بدلاً من serialize لإرسال الصور
    let formData = new FormData(this);

    // 2. حيلة لارافل: عند التعديل، نرسل POST ونضيف حقل _method بقيمة PUT
    if (vId) {
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        method: 'POST', // دائماً POST عند إرسال الصور
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(res) {
    if (res.status === 'success') {
        resetVariantForm();
        
        let pId = $('#variantProductId').val() || currentProductIdForVariants;
        
        console.log("إعادة تحميل الجدول للمنتج رقم: " + pId);
        
        if(pId) {
            loadVariants(pId);
        } else {
            location.reload(); 
        }
        
        Swal.fire({ 
            icon: 'success', 
            title: window.AppTrans.operation_success, 
            timer: 800, 
            showConfirmButton: false 
        });
                
                // تنظيف حقل الصورة بعد النجاح
                $('input[name="variant_image"]').val('');
            }
        },
        error: function(xhr) {
            let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : window.AppTrans.save_error_msg;
            Swal.fire(window.AppTrans.error_title, errorMsg, 'error');
        }
    });
});
   // 5. عند الضغط على زر التعديل
// 5. عند الضغط على زر التعديل
$(document).on('click', '.edit-v', function() {
    const btn = $(this);
    
    $('#edit_variant_id').val(btn.data('id'));
    $('input[name="name"]').val(btn.data('name'));
    $('input[name="additional_price"]').val(btn.data('price'));
    $('input[name="stock"]').val(btn.data('stock'));
    $('select[name="status"]').val(btn.data('status'));
    $('textarea[name="notes"]').val(btn.data('notes'));

    // --- تحديث اللون (دائرة اللون وحقل النص) ---
    let colorValue = btn.data('color'); 
    if (colorValue && colorValue !== "") {
        $('#color_input').val(colorValue); // تعبئة الدائرة
        $('#color_text').val(colorValue);  // تعبئة حقل النص (#xxxxxx)
    } else {
        // حالة العسل أو حبوب اللقاح (بدون لون)
        $('#color_input').val('#000000'); 
        $('#color_text').val(''); // نترك النص فارغاً لنميز أنه بدون لون
    }

    // عرض الصورة إذا وجدت
    let imgPath = btn.data('image');
    if (imgPath) {
        $('#variantImagePreview').attr('src', '/storage/' + imgPath).show();
    } else {
        $('#variantImagePreview').hide();
    }

    $('#submitBtn').find('span').text(window.AppTrans.variant_update);
    $('#submitBtn').removeClass('btn-gold').addClass('btn-warning');
    $('.modal-body').animate({ scrollTop: 0 }, 'slow');
});

/* --- أضف هدول تحت الدالة مباشرة عشان المزامنة المستمرة --- */
$(document).on('input', '#color_input', function() {
    $('#color_text').val($(this).val());
});

$(document).on('keyup', '#color_text', function() {
    let val = $(this).val();
    if(/^#[0-9A-F]{6}$/i.test(val)) {
        $('#color_input').val(val);
    }
});

//DELETE VARIANT
    // 6. الحذف
    $(document).on('click', '.delete-v', function() {
        const vId = $(this).data('id');
        Swal.fire({
            title: window.AppTrans.delete_confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.AppTrans.yes_confirm,
            cancelButtonText: window.AppTrans.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/variants/' + vId,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    success: function() {
                        loadVariants(currentProductIdForVariants);
                        Swal.fire({ icon: 'success', title: window.AppTrans.delete_success, timer: 700, showConfirmButton: false });
                    }
                });
            }
        });
    });
//DELETE 
    // 7. إضافة وحذف الخصائص (Attributes)
    $('#addAttributeBtn').on('click', function() {
        const newRow = `
            <div class="row mb-2 attribute-row">
                <div class="col-md-5">
                    <input type="text" name="attribute_name[]" class="form-control" placeholder="اسم الخاصية">
                </div>
                <div class="col-md-5">
                    <input type="text" name="attribute_value[]" class="form-control" placeholder="القيمة">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeAttributeBtn">×</button>
                </div>
            </div>`;
        $('#attributesContainer').append(newRow);
    });

    $(document).on('click', '.removeAttributeBtn', function() {
        $(this).closest('.attribute-row').remove();
    });
});

// دالة مساعدة لتصفير الفورم بالكامل
function resetVariantForm() {
    $('#variantForm')[0].reset();
    $('#edit_variant_id').val('');
    $('#variant_id_input').val(''); 
    $('#color_text').val('');
    $('#attributesContainer').empty();
    
    $('#variantImagePreview').attr('src', '').hide(); 
    
    $('#submitBtn').html('<i class="bi bi-plus-circle me-2"></i>' + window.AppTrans.variant_add);
    $('#submitBtn').removeClass('btn-warning').addClass('btn-gold');
}



//LOADING Product

function loadProducts(page = 1) {
    // 1. نجلب القيم من الحقول التي أضفناها فوق الجدول
    let name = $('#filter_name').val() || '';
    let category = $('#filter_category').val() || '';

    // 2. نرسل القيم كـ Query Parameters في الرابط
    $.ajax({
        url: `/products/data?page=${page}&name=${name}&category_id=${category}`,
        type: 'GET',
        success: function(response) {
            let tbody = $('#productsTable tbody');
            tbody.empty();

            // رسم الأسطر (استخدم دالتك الخاصة برسم سطر المنتج هنا)
            response.data.forEach(product => {
                tbody.append(buildProductRow(product));
            });

            // تحديث الترقيم
            renderProductsPagination(response);
        }
    });
}

// تشغيل الفلترة عند الكتابة أو تغيير القسم (Live Search)
$(document).on('keyup', '#filter_name', function() { loadProducts(1); });
$(document).on('change', '#filter_category', function() { loadProducts(1); });


    /* =========================
       ADD PRODUCT
    ========================== */
$('#productForm').on('submit', function(e) {
    e.preventDefault(); 
    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('products.store') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success) {
                let p = response.product;

                let imgHtml = p.image 
                    ? `<img src="/storage/${p.image}" class="rounded-circle border border-gold shadow-sm" style="width: 45px; height: 45px; object-fit: cover; background-color: var(--input-bg);">`
                    : `<div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto" style="width: 45px; height: 45px; background-color: var(--input-bg); border-color: var(--border-color) !important;"><i class="bi bi-image text-muted"></i></div>`;

                let newRow = `
                <tr class="animate__animated animate__fadeInRight" style="vertical-align: middle;" data-id="${p.id}">
                    <td class="text-center fw-bold text-gold">NEW</td>
                    <td class="text-center">${imgHtml}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <h6 class="mb-0" style="color: var(--text-color);">${p.name}</h6>
                            <small class="sku-golden">${p.sku}</small>
                        </div>
                    </td>
                    <td><span class="badge text-gold border border-gold-subtle" style="background-color: color-mix(in srgb, var(--primary-color) 12%, var(--card-bg));">${response.category_name || 'عام'}</span></td>
                    <td><span class="fw-bold text-success">$${parseFloat(p.price).toFixed(2)}</span></td>
                    <td><span class="fw-bold text-success">${p.stock}</span></td>
                    <td class="text-center"><span class="status-badge status-active">نشط</span></td>
                    <td>
                        <div class="action-buttons d-flex justify-content-center">
                            <button class="btn btn-sm btn-outline-info me-2 editProductBtn" 
                                data-id="${p.id}" 
                                data-name="${p.name}" 
                                data-sku="${p.sku}" 
                                data-price="${p.price}" 
                                data-stock="${p.stock}" 
                                data-category="${p.category_id}" 
                                data-description="${p.description}" 
                                data-status="${p.status}">
                                data-care="${p.care_instructions}"
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger deleteProductBtn" data-id="${p.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;

                $('#productsTableBody').prepend(newRow);

                let counterBadge = $('.badge.text-gold.border.border-gold');
                let currentCount = parseInt(counterBadge.text().replace(/[^0-9]/g, '')) || 0;
                counterBadge.html(`<i class="bi bi-table me-2 text-gold"></i>العدد الكلي: ${currentCount + 1}`);

                $('#productModal').modal('hide');
                $('#productForm')[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: window.AppTrans.add_success,
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = Object.values(errors).flat().join('\n');
                
                Swal.fire({
                    icon: 'warning',
                    title: window.AppTrans.data_error_title,
                    text: errorMsg
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: window.AppTrans.unexpected_error
                });
            }
        }
    });
});




    $(document).on('submit', '#addProductForm', function (e) {
    e.preventDefault();

    let formData = new FormData(this); // استخدمنا FormData عشان لو في صور مستقبلاً
    let submitBtn = $(this).find('button[type="submit"]');

    submitBtn.prop('disabled', true).text(window.AppTrans.adding);

    $.ajax({
        url: '/products', // تأكد أن هذا رابط الإضافة عندك (Store)
        type: 'POST',
        data: formData,
        processData: false, // ضروري مع FormData
        contentType: false, // ضروري مع FormData
        success: function (response) {
            $('#addProductModal').modal('hide'); // إغلاق المودال
            $('#addProductForm')[0].reset(); // تفريغ الفورم

            Swal.fire({
                icon: 'success',
                title: window.AppTrans.add_success,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            // --- السحر هنا: إضافة السطر الجديد للجدول ---
            // نأخذ اسم الفئة من الـ Select المختار في فورم الإضافة
            let catName = $('#addProductForm select[name="category_id"] option:selected').text();
            
            let newRow = `
                <tr>
                    <td>${response.id}</td>
                    <td>${formData.get('name')}</td>
                    <td>${catName}</td>
                    <td>${formData.get('price')}</td>
                    <td>${formData.get('stock')}</td>
                    <td>
                        <span class="badge bg-success">نشط</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info editProductBtn" 
                                data-id="${response.id}" 
                                data-name="${formData.get('name')}"
                                data-category="${formData.get('category_id')}"
                                data-price="${formData.get('price')}"
                                data-stock="${formData.get('stock')}">
                                
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                </tr>`;

            // إضافة السطر في أول الجدول
            $('table tbody').prepend(newRow);
            
            submitBtn.prop('disabled', false).text('حفظ المنتج');
        },
        error: function (xhr) {
            submitBtn.prop('disabled', false).text('حفظ المنتج');
            alert('حدث خطأ، تأكد من ملء الحقول المطلوبة');
        }
    });
});

    /* =========================
       DELETE PRODUCT
    ========================== */
    $(document).on('click', '.deleteProductBtn', function () {
        const button = $(this);
        const productId = button.data('id');
        const productName = button.data('name');
        const row = button.closest('tr');

        const confirmModal = `
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title text-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>${window.AppTrans.confirm_delete_title}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="bi bi-trash-fill text-danger fs-1 mb-3 d-block"></i>
                            <h5>${window.AppTrans.delete_product_confirm}</h5>
                            <h4 class="text-gold mb-4">"${productName}"</h4>

                            <p class="text-danger">${window.AppTrans.delete_product_detail}</p>
                        </div>
                        <div class="modal-footer border-top-0 justify-content-center">
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                                <i class="bi bi-trash-fill me-2"></i> ${window.AppTrans.delete_btn}
                            </button>
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${window.AppTrans.cancel}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(confirmModal);
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();

        $('#confirmDeleteBtn').on('click', function () {
            row.addClass('animate__animated animate__fadeOutLeft');
            $.ajax({
                url: `/products/${productId}`,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function () {
                    setTimeout(() => { row.remove(); showMessage('تم حذف المنتج بنجاح'); }, 500);
                },
                error: function () {
                    showMessage('حدث خطأ أثناء الحذف', 'error');
                    row.removeClass('animate__animated animate__fadeOutLeft');
                }
            });
            modal.hide();
        });

        $('#confirmDeleteModal').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    });

 


$(document).on('click', '.editProductBtn', function (e) {
    e.preventDefault();
    
    // تعبئة البيانات
    $('#editProductId').val($(this).data('id'));
    $('#editProductName').val($(this).data('name'));
    $('#editProductCategory').val($(this).data('category'));
    $('#editProductPrice').val($(this).data('price'));
    $('#editProductStock').val($(this).data('stock'));
    $('#editProductStatus').val($(this).data('status'));
    $('#editProductDescription').val($(this).data('description'));
        $('#editProductImage').val($(this).data('image'));
        $('#editProductSku').val($(this).data('sku'));
        $('#editProductCareInstructions').val($(this).data('care'));


    var modalEl = document.getElementById('editProductModal');
    document.body.appendChild(modalEl); // 

    var myModal = new bootstrap.Modal(modalEl);
    myModal.show();
});

$(document).on('submit', '#editProductForm', function (e) {
    e.preventDefault();
    
    let id = $('#editProductId').val();
    let formData = new FormData(this); 
    
    // إخبار لارافيل أننا نقوم بعملية تحديث (PUT)
    formData.append('_method', 'PUT'); 

    $.ajax({
        url: '/products/' + id,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status === 'success') {
                // 1. إغلاق المودال
                $('#editProductModal').modal('hide');

                // 2. إشعار النجاح (SweetAlert)
                Swal.fire({
                    icon: 'success',
                    title: window.AppTrans.update_success,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });

                // 3. تحديد السطر المستهدف في الجدول
                let row = $('tr[data-id="' + id + '"]');
                
                // 4. تحديث الصورة إذا تم رفع صورة جديدة
                if (response.image_url) {
                    row.find('td img').attr('src', response.image_url);
                }

                row.find('h6').text($('#editProductName').val());
                row.find('.sku-golden').text($('#editProductSku').val() || window.AppTrans.without_sku);
                row.find('td:eq(3) span').text($('#editProductCategory option:selected').text());
                row.find('td:eq(4) span').text('$' + parseFloat($('#editProductPrice').val()).toFixed(2));
                row.find('td:eq(5) span').text($('#editProductStock').val());

                let newDescription = $('#editProductDescription').val() || ''; 
                let shortDesc = newDescription.length > 30 
                                ? newDescription.substring(0, 30) + '...' 
                                : newDescription;

                row.find('.description-text').fadeOut(200, function() {
                    $(this).text(shortDesc).fadeIn(200);
                });

                let newCare = $('#editProductCareInstructions').val() || ''; 
                let shortCare = newCare.length > 30 
                                ? newCare.substring(0, 30) + '...' 
                                : newCare;

                row.find('.care-text').fadeOut(200, function() {
                    $(this).text(shortCare || window.AppTrans.no_care_instructions).fadeIn(200);
                });

                let btn = row.find('.editProductBtn');
                btn.data('name', $('#editProductName').val());
                btn.data('price', $('#editProductPrice').val());
                btn.data('care', newCare);
                btn.data('stock', $('#editProductStock').val());
                btn.data('description', newDescription); 
                btn.data('category', $('#editProductCategory').val());
                btn.data('sku', $('#editProductSku').val());
                
                if (response.image_path) {
                    btn.data('image', response.image_path);
                }

                // حركة إضافية: وميض للسطر عشان يبين التغيير
                row.css('transition', 'background-color 0.5s ease');
                row.css('background-color', 'rgba(212, 175, 55, 0.1)');
                setTimeout(() => row.css('background-color', 'transparent'), 2000);
            }
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: window.AppTrans.save_error_title,
                text: xhr.responseJSON ? xhr.responseJSON.message : window.AppTrans.error_check_data
            });
        }
    });
});






$(document).ready(function() {
    let debounceTimer;

    // دالة جلب البيانات
    function fetchProducts() {
        let name = $('#searchName').val();
        let categoryId = $('#searchCategory').val();

        $.ajax({
            url: "{{ route('products.index') }}",
            method: 'GET',
            data: { 
                name: name, 
                category_id: categoryId 
            },
            beforeSend: function() {
                // تقليل الشفافية ليعرف المستخدم أن البحث جارٍ
                $('#productsTableBody').css('opacity', '0.5');
            },
            success: function(response) {
                // سنقوم باستخراج محتوى الجدول فقط من الرد
                let newTable = $(response).find('#productsTableBody').html();
                $('#productsTableBody').html(newTable).css('opacity', '1');

                // تحديث الـ Pagination إذا كان موجوداً لديك
                let newPagination = $(response).find('.pagination-container').html();
                $('.pagination-container').html(newPagination);
            },
            error: function() {
                $('#productsTableBody').css('opacity', '1');
            }
        });
    }

    // الفلترة عند الكتابة (البحث بالاسم)
    $('#searchName').on('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchProducts, 500); // ينتظر نصف ثانية بعد آخر حرف
    });

    // الفلترة عند تغيير القسم فوراً
    $('#searchCategory').on('change', function() {
        fetchProducts();
    });

    // منع الفورم من إرسال نفسه إذا ضغط المستخدم Enter
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
    });
});
});





function clearColor() {
    const colorInput = document.getElementById('color_input');
    if(colorInput) colorInput.value = "#000000"; 
    
    const colorText = document.getElementById('color_text');
    if(colorText) colorText.value = ""; 

    console.log("Color has been cleared");
}






</script>



</body>

</html>