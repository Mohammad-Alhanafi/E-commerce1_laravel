<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('admin.manage_categories') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
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
            background: var(--bg-color, #0a0a0a) !important;
            color: var(--text-color, #ffffff);
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from { transform: translateX(-100px); opacity: 0; }
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
            animation: slideInUp 0.6s ease-out;
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.2);
            border-color: var(--gold-primary);
        }
        
        .stat-card {
            background: linear-gradient(145deg, var(--card-bg), var(--bg-color));
            border-left: 4px solid var(--gold-primary);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(212, 175, 55, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .stat-card:hover::before {
            transform: translateX(100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.15);
        }
        
        .btn-gold {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--black-primary);
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
            color: var(--black-primary);
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
        }
        
        .btn-gold::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-gold:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% { transform: scale(0, 0); opacity: 0.5; }
            100% { transform: scale(20, 20); opacity: 0; }
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
    
        
      
        
        .table {
            color: var(--white);
            border-color: var(--black-light);
            animation: slideInRight 0.8s ease-out;
        }
        
        .table-dark {
            --bs-table-bg: var(--black-secondary);
            --bs-table-striped-bg: rgba(212, 175, 55, 0.05);
            --bs-table-striped-color: var(--white);
            --bs-table-active-bg: rgba(212, 175, 55, 0.1);
            --bs-table-hover-bg: rgba(212, 175, 55, 0.15);
        }
        
        .table th {
            background: linear-gradient(to bottom, var(--black-light), var(--black-secondary));
            border-bottom: 2px solid var(--gold-primary);
            color: var(--gold-light);
            font-weight: 600;
            padding: 15px;
        }
        
        .table td {
            vertical-align: middle;
            border-color: var(--black-light);
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        

        
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .status-active {
            background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1));
            color: var(--success-color, #28a745);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .status-inactive {
            background: linear-gradient(45deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.1));
            color: var(--danger-color, #dc3545);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .featured-badge {
            background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
            color: var(--black-primary);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            animation: float 3s ease-in-out infinite;
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
            animation: slideInUp 0.5s ease-out;
        }
        
        .modal-header {
            background: linear-gradient(to right, var(--black-primary), var(--black-light));
            border-bottom: 2px solid var(--gold-primary);
            border-radius: 15px 15px 0 0;
        }
        
        .modal-title {
            color: var(--gold-primary);
        }
        
        .modal-footer {
            border-top: 1px solid var(--black-light);
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            animation: slideInLeft 0.5s ease-out;
        }
        
        .alert-success {
            background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1));
            color: var(--success-color, #28a745);
            border-left: 4px solid var(--success-color, #28a745);
        }
        
        .alert-danger {
            background: linear-gradient(45deg, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.1));
            color: var(--danger-color, #dc3545);
            border-left: 4px solid var(--danger-color, #dc3545);
        }
        
        .search-box {
            position: relative;
            animation: slideInLeft 0.6s ease-out 0.2s both;
        }
        
        .search-box .form-control {
            padding-right: 45px;
            background: var(--black-light);
            border: 1px solid var(--gray);
        }
        
        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-primary);
        }
        
        .page-title {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            animation: slideInRight 0.6s ease-out;
        }
        
        .page-title::after {
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
        
        .empty-state {
            animation: fadeIn 1s ease-out;
        }
        
        .empty-state i {
            color: var(--gold-primary);
            filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.5));
        }
        
        /* RTL Support */
        .form-check {
            padding-right: 2.5em;
            padding-left: 0;
        }
        
        .form-check-input {
            margin-right: -2.5em;
            margin-left: 0;
        }
        
        .form-switch .form-check-input {
            width: 3em;
            margin-right: -3.5em;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--black-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--gold-primary), var(--gold-secondary));
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(var(--gold-secondary), var(--gold-primary));
        }
        
        /* Loading Animation */
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
        
        /* Glow Effect */
        .glow {
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
                animation: slideInUp 0.5s ease-out;
            }
            
            .action-buttons .btn {
                margin-bottom: 5px;
                display: block;
                width: 100%;
            }



            
#categoriesTable th, #categoriesTable td {
    padding: 8px 10px; 
    font-size: 0.85rem; 
    vertical-align: middle;
}

.text-truncate-custom {
    max-width: 150px; 
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* تصغير حجم الأزرار */
.action-buttons .btn {
    padding: 2px 6px;
    font-size: 0.75rem;
}

.table-responsive {
    max-width: 100%;
    overflow-x: auto;
}




.bg-black {
    background-color: var(--bg-color) !important;
}

.rounded-4 {
    border-radius: 15px !important;
}

.border-gold {
    border-color: var(--primary-color) !important;
}

.btn-gold {
    background-color: var(--primary-color);
    color: var(--bg-color);
    font-weight: bold;
    border: none;
}

.btn-gold:hover {
    background-color: var(--hover-color);
    color: var(--text-color);
}




    .pagination-gold .page-link {
        background-color: var(--bg-color);
        border-color: var(--border-color);
        color: var(--primary-color);
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .pagination-gold .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--bg-color);
        font-weight: bold;
    }

    .pagination-gold .page-link:hover {
        background-color: var(--card-bg);
        color: var(--accent-color, var(--primary-color));
        border-color: var(--primary-color);
    }

    .pagination-gold .page-item.disabled .page-link {
        background-color: var(--bg-color);
        color: var(--text-muted);
        border-color: var(--border-color);
    }



.custom-pagination .pagination {
    gap: 8px;
    margin-bottom: 0;
}

.custom-pagination .page-item {
    border: none;
}

.custom-pagination .page-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px !important;
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    transition: all 0.3s ease;
}

/* المربع النشط (الصفحة الحالية) */
.custom-pagination .page-item.active .page-link {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--bg-color) !important;
    font-weight: bold;
    box-shadow: 0 4px 10px color-mix(in srgb, var(--primary-color) 30%, transparent);
}

.custom-pagination .page-link:hover {
    background-color: var(--primary-color);
    color: var(--bg-color);
    border-color: var(--primary-color);
    transform: translateY(-3px);
}

.custom-pagination .page-item.disabled .page-link {
    background-color: var(--bg-color);
    color: var(--text-muted);
    border-color: var(--border-color);
}


        }


       

    </style>
</head>
<body>

@include('admin.sidebar')

@include('admin.header')

    <!-- Main Container -->
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
                    <div>
                        <h1 class="h2 page-title">
                            <i class="bi bi-tags me-2 floating-icon"></i>إدارة الفئات
                        </h1>
                       
                    </div>
                    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-circle me-2"></i>إضافة فئة جديدة
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="row mb-4 animate__animated animate__slideInLeft">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-0">تمت العملية بنجاح!</h6>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
        <div class="row mb-4 animate__animated animate__slideInLeft">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-0">حدث خطأ!</h6>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2" style="color: var(--secondary-color);">{{ __('admin.categories') }}</h6>

                                <h2 class="mb-0 glow" style="color: var(--primary-color); text-shadow: 0 0 10px color-mix(in srgb, var(--primary-color) 50%, transparent);">
                                    {{ $categories->count() }}
                                   </h2>
                            </div>

                            <i class="bi bi-tag-fill fs-1" style="color: var(--primary-color); opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-gold mb-2"  style="color: var(--secondary-color);">{{ __('admin.active') }}</h6>
                                <h2 class="mb-0 glow" style="color: var(--success-color)">{{ $categories->where('is_active', 1)->count() }}</h2>
                            </div>
                            <i class="bi bi-check-circle-fill fs-1" style="color: var(--success-color)"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-gold mb-2" style="color: var(--secondary-color);">{{ __('admin.requested_products') }}</h6>
                                <h2 class="mb-0 glow" style="color: var(--info-color)">{{ $totalProducts ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-box-fill fs-1" style="color: var(--info-color)"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-gold mb-2" style="color: var(--secondary-color);">{{ __('admin.featured') }}</h6>
                                <h2 class="mb-0 glow" style="color: var(--primary-color)">{{ $categories->where('is_featured', 1)->count() }}</h2>
                            </div>
                            <i class="bi bi-star-fill fs-1" style="color: var(--primary-color)"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="row animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                     <h5 class="mb-0 d-flex align-items-center">
    <span class="text-warning fw-bold d-inline-flex align-items-center animate__animated animate__pulse animate__infinite">
        <i class="bi bi-list-ul me-2 shadow-sm"></i>
        <span style="text-shadow: 0 0 8px rgba(255, 193, 7, 0.5);">{{ __('admin.categories') }}</span>
    </span>
</h5>

                        <div class="d-flex">
                          <div class="search-box me-3">
                          <input type="text" 
                         class="form-control form-control-sm" 
                         placeholder="{{ __('admin.search') }}" 
                          id="searchInput">
                       <i class="bi bi-search"></i>
                                 </div>

                            </div>
                            <select class="form-select form-select-sm" style="width: auto;" id="statusFilter">
                                <option value="">{{ __('admin.all_statuses') }}</option>
                                <option value="active">{{ __('admin.active') }}</option>
                                <option value="inactive">{{ __('admin.inactive') }}</option>
                                <option value="featured">{{ __('admin.featured') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0" id="categoriesTable">
                                 <thead>
                                     <tr>
                                         <th width="50">#</th>
                                         <th>{{ __('admin.category') }}</th>
                                         <th>{{ __('admin.description') }}</th>
                                         <th width="120">{{ __('admin.status') }}</th>
                                         <th width="100">{{ __('admin.featured') }}</th>
                                         <th width="120">{{ __('admin.created_at') }}</th>
                                         <th width="180">{{ __('admin.actions') }}</th>
                                     </tr>
                                 </thead>
                                                                        <tbody>
    @forelse($categories as $category)
    <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
        <td class="fw-bold">{{ $loop->iteration }}</td>

        <td>
            <div class="d-flex align-items-center">
                @if($category->image)
                    <img src="{{ $category->image_url }}" class="rounded me-2" width="35" height="35" style="object-fit: cover;">
                @endif
                <span class="text-gold">{{ $category->name }}</span>
            </div>
        </td>

        <td>
            <p class="mb-0 text-truncate" style="max-width: 200px;" title="{{ $category->description }}">
                {{ $category->description ?? 'لا يوجد وصف' }}
            </p>
        </td>

        <td>
            <span class="status-badge {{ $category->is_active ? 'status-active' : 'status-inactive' }}">
                {{ $category->is_active ? __('admin.active') : __('admin.inactive') }}
            </span>
        </td>

        
         <td class="featured-status text-center" style="vertical-align: middle;">
    @if($category->is_featured)
        <div class="featured-star animate__animated animate__zoomIn">
            <i class="bi bi-star-fill text-warning fs-3" 
               style="filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0.6)); cursor: help;"
               data-bs-toggle="tooltip"
               title="{{ __('admin.featured_tooltip') }}"></i>
        </div>
    @else
        <i class="bi bi-star text-muted opacity-25 fs-5"></i>
    @endif


        <td>
            <small class="text-gold">{{ $category->created_at->format('Y/m/d') }}</small>
        </td>

        
        <td style="vertical-align: middle;">
    <div class="d-flex align-items-center justify-content-center gap-2">
        
        <button class="btn btn-sm btn-outline-gold edit-category" 
            data-bs-toggle="modal" 
            data-bs-target="#editCategoryModal"
            data-id="{{ $category->id }}"
            data-name="{{ $category->name }}"
            data-description="{{ $category->description }}"
            data-is-active="{{ $category->is_active ? 1 : 0 }}" 
            data-is-featured="{{ $category->is_featured ? 1 : 0 }}"
            data-sort-order="{{ $category->sort_order ?? 0 }}"
            data-image="{{ $category->image }}"
            title="{{ __('admin.edit') }}">
            <i class="bi bi-pencil"></i>
        </button>
        
        <form method="POST" action="{{ route('category.destroy', $category->id) }}" class="d-inline delete-form m-0">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-name="{{ $category->name }}" title="{{ __('admin.delete') }}">
                <i class="bi bi-trash"></i>
            </button>
        </form>
        
       <button type="button" class="btn btn-sm btn-outline-info view-variants" 
    data-id="{{ $category->id }}"
    data-name="{{ $category->name }}"
    data-description="{{ $category->description }}"
    data-is-active="{{ $category->is_active ? 1 : 0 }}" 
    data-is-featured="{{ $category->is_featured ? 1 : 0 }}"
    data-image="{{ $category->image }}"
    title="{{ __('admin.view') }}">
    <i class="bi bi-eye"></i>
</button>
        
    </div>
</td>
        
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center py-5">{{ __('admin.no_categories') }}</td>
    </tr>
    @endforelse
</tbody>
                            </table>
                        </div>

                        {{-- Pagination موحّد ونظيف --}}
                        @if($categories->hasPages())
                        <div class="d-flex justify-content-center py-3">
                            <nav class="admin-pagination">
                                {{ $categories->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

   <!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('admin.add_category') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- اسم الفئة -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                       

                        <!-- الوصف -->
                        <div class="col-12 mb-3">
                            <label class="form-label">{{ __('admin.description') }}</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                                <label class="form-check-label" for="isActive">{{ __('admin.active') }}</label>
                            </div>
                        </div>

                        <!-- الفئة المميزة -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                                <label class="form-check-label" for="isFeatured">{{ __('admin.featured') }}</label>
                            </div>
                        </div>

                        <!-- ترتيب العرض -->
                        <div class="col-12">
                            <label class="form-label">{{ __('admin.order') }}</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-save me-2"></i>{{ __('admin.add_category') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">


                <form method="POST" id="editCategoryForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>{{ __('admin.edit') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editFormContent">
                          
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-gold">
                            <i class="bi bi-save me-2"></i>{{ __('admin.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




<div class="modal fade" id="variantsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list-ul me-2"></i> Variants
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Loader -->
                <div id="variantsLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary"></div>
                </div>

                <!-- Variants Table -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.additional_price') }}</th>
                                <th>{{ __('admin.quantity') }}</th>
                                <th>{{ __('admin.status') }}</th>
                            </tr>
                        </thead>
                        <tbody id="variantsTableBody">
                            <tr>
                                <td colspan="5" class="text-center">اختر عنصر لعرض الـ variants</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
            </div>
        </div>
    </div>
</div>




<!-- view category modal  -->

<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center mb-4">
                    <div id="view_image_container" class="position-relative d-inline-block">
                        </div>
                    <h3 id="view_name" class="text-gold mt-3 fw-bold mb-1"></h3>
                    <div id="view_main_badge"></div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-4 card h-100 text-center">
                            <i class="bi bi-shield-check text-gold fs-4"></i>
                            <small class="d-block text-muted mt-1" style="color: var(--primary-color) !important;">{{ __('admin.status') }}</small>
                            <div id="view_status_val" class="fw-bold mt-1"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 card h-100 text-center">
                            <i class="bi bi-star text-warning fs-4"></i>
                            <small class="d-block mt-1 text-gold" style="color: var(--primary-color) !important;">{{ __('admin.featured') }}</small>
                            <div id="view_featured_val" class="fw-bold mt-1"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-4 card">
                            <small class="d-block mb-2 text-end" style="color: var(--text-muted); font-weight: 500;">
                             <i class="bi bi-card-text me-1" style="color: var(--primary-color);"></i> {{ __('admin.description') }}</small>


         
                            <p id="view_desc" class="m-0 text-end" style="font-size: 0.95rem; color: var(--text-color);"></p>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>
@include('admin.footer')

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'name'                  => __('admin.name'),
    'description'           => __('admin.description'),
    'order'                 => __('admin.order'),
    'image'                 => __('admin.image'),
    'active'                => __('admin.active'),
    'inactive'              => __('admin.inactive'),
    'featured'              => __('admin.featured'),
    'cancel'                => __('admin.cancel'),
    'save_changes'          => __('admin.save_changes'),
    'confirm_delete_title'  => __('admin.confirm_delete_title'),
    'delete_confirm'        => __('admin.delete_confirm'),
    'delete_success'        => __('admin.delete_success'),
    'update_success'        => __('admin.update_success'),
    'operation_success'     => __('admin.operation_success'),
    'yes_delete'            => __('admin.yes_delete'),
    'save_error_title'      => __('admin.save_error_title'),
    'error_title'           => __('admin.error_title'),
    'no_description'        => __('admin.no_data'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            

        });
        
$('.edit-category').on('click', function() {
    var categoryId = $(this).data('id');
    var categoryName = $(this).data('name');
    var description = $(this).data('description');
    var isActive = parseInt($(this).data('is-active'));
    var isFeatured = parseInt($(this).data('is-featured'));
    var sortOrder = $(this).data('sort-order') || 0;
    var image = $(this).data('image');

    $('#editCategoryForm').attr('action', '/category/' + categoryId);

    var formContent = `
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">${window.AppTrans.name} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="${categoryName}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">${window.AppTrans.order}</label>
                    <input type="number" name="sort_order" class="form-control" value="${sortOrder}" min="0">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">${window.AppTrans.image}</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    ${image ? `<div class="mt-2"><small class="text-gold">${image}</small></div>` : ''}
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">${window.AppTrans.description}</label>
                    <textarea name="description" class="form-control" rows="3">${description || ''}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch p-3 rounded border shadow-sm">
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="editIsActive" ${isActive == 1 ? 'checked' : ''}>
                        <label class="form-check-label me-4" for="editIsActive"> ${window.AppTrans.active}</label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch p-3 rounded border shadow-sm">
                        <input class="form-check-input ms-0" type="checkbox" name="is_featured" value="1" id="editIsFeatured" ${isFeatured == 1 ? 'checked' : ''}>
                        <label class="form-check-label me-4" for="editIsFeatured">${window.AppTrans.featured}</label>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#editFormContent').html(formContent);
});
            
            // Delete Confirmation with Sweet Animation
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var categoryName = $(this).data('name');
                var row = $(this).closest('tr');
                
                // Create custom confirmation modal
                var confirmModal = `
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
                                    <h5>${window.AppTrans.delete_confirm}</h5>
                                    <h4 class="text-gold mb-4">"${categoryName}"</h4>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${window.AppTrans.cancel}</button>
                                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                                        <i class="bi bi-trash-fill me-2"></i>${window.AppTrans.yes_delete}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(confirmModal);
                var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
                
                $('#confirmDeleteBtn').on('click', function() {
                    // Add delete animation
                    row.addClass('animate__animated animate__fadeOutLeft');
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                });
                
                // Remove modal on hide
                $('#confirmDeleteModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            });
            
            // Search Filter
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#categoriesTable tbody tr').each(function() {
                    var text = $(this).text().toLowerCase();
                    if (text.indexOf(value) > -1) {
                        $(this).show().addClass('animate__animated animate__fadeIn');
                    } else {
                        $(this).hide().removeClass('animate__animated animate__fadeIn');
                    }
                });
            });
            
            // Status Filter
            $('#statusFilter').on('change', function() {
                var value = $(this).val();
                $('#categoriesTable tbody tr').each(function() {
                    var status = $(this).find('.status-badge').text().trim();
                    var isFeatured = $(this).find('.featured-badge').length > 0;
                    
                    if (value === '' || 
                        (value === 'active' && status === 'نشط') ||
                        (value === 'inactive' && status === 'غير نشط') ||
                        (value === 'featured' && isFeatured)) {
                        $(this).show().addClass('animate__animated animate__fadeIn');
                    } else {
                        $(this).hide().removeClass('animate__animated animate__fadeIn');
                    }
                });
            });



$(document).on('click', '.view-variants', function() {
    let btn = $(this);
    let name = btn.data('name');
    let desc = btn.data('description') || 'لا يوجد وصف متاح حالياً';
    let isActive = btn.data('is-active');
    let isFeatured = btn.data('is-featured');
    let image = btn.data('image');

    // الاسم والوصف
    $('#view_name').text(name);
    $('#view_desc').text(desc);

    // الحالة (نص وألوان داخل الكرت)
    if (isActive == 1) {
        $('#view_status_val').html(`<span class="text-success">${window.AppTrans.active}</span>`);
    } else {
        $('#view_status_val').html(`<span class="text-danger">${window.AppTrans.inactive}</span>`);
    }

    // التميز
    if (isFeatured == 1) {
        $('#view_featured_val').html(`<span class="text-warning">${window.AppTrans.featured} ★</span>`);
    } else {
        $('#view_featured_val').html('<span style="color: #dee2e6; font-weight: 500; opacity: 0.9;">---</span>');
    }

    // الصورة بشكل دائري فخم مع إطار
  

    $('#viewDetailsModal').modal('show');
});







            
        $('#editCategoryForm').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);
    let actionUrl = form.attr('action');
    let formData = new FormData(this);

    $.ajax({
        url: actionUrl,
        type: 'POST', 
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {
            $('#editCategoryModal').modal('hide');

            let cat = response.category; 
            let row = $('button[data-id="' + cat.id + '"]').closest('tr');
            let editBtn = row.find('.edit-category'); // زر التعديل الخاص بهذا الصف

            // 1. تحديث النصوص في الجدول
            row.find('h6').text(cat.name);
            row.find('p').text(cat.description ?? 'لا يوجد وصف');

            // 2. تحديث حالة النشاط (Badge)
            let statusBadge = row.find('.status-badge');
            statusBadge.text(cat.is_active ? window.AppTrans.active : window.AppTrans.inactive);

            let featuredCell = row.find('.featured-status');
            if (cat.is_featured == 1 || cat.is_featured == true) {
                featuredCell.html(`
                    <div class="featured-star animate__animated animate__zoomIn">
                        <i class="bi bi-star-fill text-warning fs-3" style="filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0.6));"></i>
                    </div>
                `);
            } else {
                featuredCell.html('<i class="bi bi-star text-muted opacity-25 fs-5"></i>');
            }

            editBtn.data('name', cat.name);
            editBtn.data('description', cat.description);
            editBtn.data('is-active', cat.is_active ? 1 : 0);
            editBtn.data('is-featured', cat.is_featured ? 1 : 0);
            editBtn.data('sort-order', cat.sort_order);
            if(cat.image) editBtn.data('image', cat.image);

            // 5. تنبيه النجاح
            Swal.fire({
                icon: 'success',
                title: window.AppTrans.update_success,
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#d4af37',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: window.AppTrans.error_title,
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#dc3545'
            });
            console.log(xhr.responseText);
        }
    });
});

document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    fetch("{{ route('products.store') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('تم إضافة المنتج بنجاح ✅');

           
            form.reset();

    
             addProductToTable(data.product);

        } else {
            alert('حصل خطأ');
        }
    })
    .catch(err => {
        console.error(err);
        alert('خطأ في الإرسال');
    });
});
$(document).ready(function () {

    $('.view-variants').on('click', function () {

        let productId = $(this).data('id');

        // فتح المودال
        let modal = new bootstrap.Modal(document.getElementById('variantsModal'));
        modal.show();

        // loader
        $('#variantsLoading').removeClass('d-none');
        $('#variantsTableBody').html('');

        $.ajax({
            url: '/products/' + productId + '/variants',
            method: 'GET',
            success: function (response) {

                let tbody = $('#variantsTableBody');
                tbody.empty();

                if (response.variants && response.variants.length > 0) {
                    response.variants.forEach((variant, index) => {
                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${variant.name}</td>
                                <td>${variant.additional_price ?? '-'}</td>
                                <td>${variant.stock ?? 0}</td>
                                <td>${variant.is_active ? 'نشط' : 'غير نشط'}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.html(`
                        <tr>
                            <td colspan="5" class="text-center">
                                لا توجد Variants
                            </td>
                        </tr>
                    `);
                }

                $('#variantsLoading').addClass('d-none');
            },
            error: function () {
                alert('خطأ أثناء جلب الـ variants');
                $('#variantsLoading').addClass('d-none');
            }
        });
    });



//Pagination

    $(document).ready(function() {
    $(document).on('click', '#variantsPagination a', function(e) {
        e.preventDefault();

        let url = $(this).attr('href'); 

        $('#categoriesTable').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: "get",
            success: function(response) {
                let newTable = $(response).find('#categoriesTable').html();
                let newPagination = $(response).find('#variantsPagination').html();

                $('#categoriesTable').html(newTable);
                $('#variantsPagination').html(newPagination);

                $('#categoriesTable').css('opacity', '1');
                
                $('html, body').animate({ scrollTop: $('#categoriesTable').offset().top - 100 }, 300);
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                $('#categoriesTable').css('opacity', '1');
            }
        });
    });
});


    });

</script>

</body>
</html>