<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('admin.manage_users') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">



    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root {
        /* لوحة الألوان الذهبية */
        --gold-primary: #D4AF37;        /* ذهبي أساسي */
        --gold-secondary: #FFD700;      /* ذهبي فاتح */
        --gold-dark: #B8860B;          /* ذهبي داكن */
        --gold-light: #F5DEB3;         /* ذهبي فاتح جداً */
        --gold-glow: rgba(212, 175, 55, 0.3); /* توهج ذهبي */
        
        /* لوحة الألوان السوداء */
        --black-primary: #0a0a0a;      /* أسود أساسي */
        --black-secondary: #1a1a1a;    /* أسود ثانوي */
        --black-light: #2a2a2a;        /* أسود فاتح */
        --black-lighter: #3a3a3a;      /* أسود فاتح جداً */
        --black-glow: rgba(10, 10, 10, 0.8); /* توهج أسود */
        
        /* ألوان داعمة */
        --white: #ffffff;
        --gray: #888888;
        --gray-light: #aaaaaa;
    }
    
    body {
        background: linear-gradient(135deg, var(--black-primary) 0%, var(--black-secondary) 100%);
        color: var(--white);
        font-family: 'Cairo', 'Segoe UI', sans-serif;
        min-height: 100vh;
        background-attachment: fixed;
    }
    
    /* تدرجات ذهبية للعناوين */
    h2 {
        background: linear-gradient(45deg, var(--gold-primary), var(--gold-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        padding-bottom: 10px;
    }
    
    h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 50%;
        height: 3px;
        background: linear-gradient(to right, transparent, var(--gold-primary));
    }
    
    /* أزرار ذهبية متطورة */
    .btn-gold {
        background: linear-gradient(45deg, var(--gold-dark), var(--gold-primary), var(--gold-secondary));
        color: var(--black-primary);
        border: none;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--gold-glow);
        color: var(--black-primary);
    }
    
    .btn-gold::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-gold:hover::before {
        left: 100%;
    }
    
    /* أزرار الإطار الذهبي */
    .btn-outline-gold {
        color: var(--gold-primary);
        border: 2px solid var(--gold-primary);
        background: transparent;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-gold:hover {
        background: var(--gold-primary);
        color: var(--black-primary);
        box-shadow: 0 0 15px var(--gold-glow);
    }
    
    /* تنسيق البطاقات */
    .card {
        background: linear-gradient(145deg, var(--black-light), var(--black-secondary));
        border: 1px solid rgba(212, 175, 55, 0.1);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        border-color: var(--gold-primary);
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(212, 175, 55, 0.15);
    }
    
    .card-header {
        background: linear-gradient(to right, var(--black-primary), var(--black-light));
        border-bottom: 2px solid var(--gold-primary);
        color: var(--gold-primary);
        font-weight: 600;
        border-radius: 12px 12px 0 0 !important;
    }
    
    /* تنسيق الحقول */
    .form-control, .form-select {
        background: var(--black-light);
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: var(--white);
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        background: var(--black-light);
        border-color: var(--gold-primary);
        box-shadow: 0 0 0 3px var(--gold-glow);
        color: var(--white);
    }
    
    ::placeholder {
        color: rgba(212, 175, 55, 0.5) !important;
    }
    
    /* تنسيق الجداول */
    .table-dark {
        --bs-table-bg: var(--black-light);
        --bs-table-striped-bg: rgba(212, 175, 55, 0.03);
        --bs-table-striped-color: var(--white);
        --bs-table-hover-bg: rgba(212, 175, 55, 0.1);
        --bs-table-hover-color: var(--white);
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-dark th {
        background: linear-gradient(to bottom, var(--black-primary), var(--black-light));
        border-bottom: 2px solid var(--gold-primary);
        color: var(--gold-light);
        font-weight: 600;
        padding: 15px;
        position: sticky;
        top: 0;
    }
    
    .table-dark td {
        padding: 12px 15px;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
    }
    
    .table-hover tbody tr:hover {
        background: rgba(212, 175, 55, 0.1) !important;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* تنسيق أزرار الإجراءات الجديدة */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin-left: 5px;
        border: 2px solid;
        transition: all 0.3s ease;
        background: transparent;
        position: relative;
        overflow: hidden;
    }
    
    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-action:hover::before {
        left: 100%;
    }
    
    /* زر التعديل الذهبي */
    .btn-action-edit {
        color: var(--gold-primary);
        border-color: var(--gold-primary);
        background: rgba(212, 175, 55, 0.1);
    }
    
    .btn-action-edit:hover {
        background: var(--gold-primary);
        color: var(--black-primary);
        transform: rotate(15deg) scale(1.1);
        box-shadow: 0 0 15px var(--gold-glow);
    }
    
    /* زر الحذف مع لمسة ذهبية */
    .btn-action-delete {
        color: #ff6b6b;
        border-color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
    }
    
    .btn-action-delete:hover {
        background: #ff6b6b;
        color: var(--white);
        transform: rotate(-15deg) scale(1.1);
        box-shadow: 0 0 15px rgba(255, 107, 107, 0.4);
    }
    
    /* زر العرض مع لمسة ذهبية */
    .btn-action-view {
        color: var(--gold-light);
        border-color: var(--gold-light);
        background: rgba(245, 222, 179, 0.1);
    }
    
    .btn-action-view:hover {
        background: var(--gold-light);
        color: var(--black-primary);
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(245, 222, 179, 0.4);
    }
    
    /* تنسيق الباجات */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        min-width: 80px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .status-active {
        background: linear-gradient(45deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1));
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .status-inactive {
        background: linear-gradient(45deg, rgba(108, 117, 125, 0.2), rgba(108, 117, 125, 0.1));
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }
    
    .status-admin {
        background: linear-gradient(45deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.1));
        color: var(--gold-primary);
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    
    .status-user {
        background: linear-gradient(45deg, rgba(23, 162, 184, 0.2), rgba(23, 162, 184, 0.1));
        color: #17a2b8;
        border: 1px solid rgba(23, 162, 184, 0.3);
    }
    
    /* صورة المستخدم */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gold-primary);
        background: linear-gradient(45deg, var(--black-light), var(--black-lighter));
    }
    
    /* تنسيق المودال */
    .modal-content {
        background: linear-gradient(145deg, var(--black-secondary), var(--black-primary));
        border: 2px solid var(--gold-primary);
        border-radius: 15px;
        color: var(--white);
    }
    
    .modal-header {
        background: linear-gradient(to right, var(--black-primary), var(--black-light));
        border-bottom: 2px solid var(--gold-primary);
        border-radius: 13px 13px 0 0;
    }
    
    .modal-title {
        color: var(--gold-primary);
        font-weight: 600;
    }
    
    /* تنسيق خاص للبحث */
    .form-control#search {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    
    .form-control#search:focus {
        background: rgba(212, 175, 55, 0.1);
    }
    
  
    /* تنسيق الرسائل */
    #messageContainer .alert {
        background: linear-gradient(to right, var(--black-light), var(--black-lighter));
        border: 1px solid var(--gold-primary);
        color: var(--white);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    /* شريط التمرير المخصص */
    ::-webkit-scrollbar {
        width: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--black-light);
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(var(--gold-primary), var(--gold-secondary));
        border-radius: 5px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(var(--gold-secondary), var(--gold-primary));
    }
    
    /* تأثيرات إضافية */
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    /* تنسيق للموبايل */
    @media (max-width: 768px) {
        .btn-action {
            width: 32px;
            height: 32px;
            margin-left: 3px;
        }
        
        .status-badge {
            padding: 4px 8px;
            font-size: 0.8rem;
            min-width: 70px;
        }
        
        .table-dark th, .table-dark td {
            padding: 8px 10px;
        }


        

  

    }


    /* الكود المسؤول عن إزاحة المحتوى لحمايته من السايدبار */
.main-content-wrapper {
    /* افترضنا هنا أن عرض السايدبار عندك هو 260px، غير الرقم حسب عرض السايدبار الفعلي لديك */
    padding-right: 260px; 
    transition: all 0.3s ease;
    width: 100%;
}

/* لتفادي المشاكل على الشاشات الصغيرة (الموبايل والتابلت) حيث تختفي السايدبار أو تصبح علوية */
@media (max-width: 992px) {
    .main-content-wrapper {
        padding-right: 0 !important;
    }
}
</style>
</head>
<body>

     <body>

    @include('admin.sidebar')
    @include('admin.header')


    <!-- الغلاف الجديد لحماية المحتوى من السايدبار -->
    <div class="main-content-wrapper">
        <div class="container-fluid py-4 px-md-4">
        <h2 class="mb-4" style="color: var(--white);">
    <i class="bi bi-people me-2"></i>{{ __('admin.manage_users') }}
</h2>

            <!-- Search & Filters -->
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <input type="text" id="search" class="form-control" placeholder="{{ __('admin.search_user_placeholder') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select id="roleFilter" class="form-select">
                        <option value="">{{ __('admin.all_roles') }}</option>
                        <option value="admin">{{ __('admin.admin_role') }}</option>
                        <option value="user">{{ __('admin.customer_role') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="statusFilter" class="form-select">
                        <option value="">{{ __('admin.all_statuses') }}</option>
                        <option value="active">{{ __('admin.active') }}</option>
                        <option value="inactive">{{ __('admin.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-gold w-100" id="addUserBtn"><i class="bi bi-person-plus me-1"></i>{{ __('admin.add_new_user') }}</button>
                </div>
            </div>

            <!-- Messages -->
            <div id="messageContainer"></div>

            <!-- User Form -->
            <div class="card mb-4" id="userFormSection" style="display:none;">
                <div class="card-header d-flex justify-content-between">
                    <span id="formTitle">{{ __('admin.add_new_user') }}</span>
                    <button class="btn-close btn-close-white" id="closeFormBtn"></button>
                </div>
                <div class="card-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="userId" name="id">
                        <input type="hidden" id="formMethod" value="POST">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37" >{{ __('admin.name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.email_label') }}</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.phone_label') }}</label>
                                <input type="tel" name="phone_number" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" id="passwordField">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.permission') }}</label>
                                <select name="role" class="form-select" required>
                                    <option value="user" style="color: white">{{ __('admin.customer_role') }}</option>
                                    <option value="admin" style="color:white">{{ __('admin.admin_role') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.status') }}</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="status" value="active" class="form-check-input" checked>
                                    <label class="form-check-label">{{ __('admin.active') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="status" value="inactive" class="form-check-input">
                                    <label class="form-check-label">{{ __('admin.inactive') }}</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.address') }}</label>
                                <textarea name="address" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.birth_date') }}</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label style="color: #D4AF37">{{ __('admin.gender') }}</label>
                                <select name="gender" class="form-select">
                                    <option value="">{{ __('admin.select') }}</option>
                                    <option value="male">{{ __('admin.male') }}</option>
                                    <option value="female">{{ __('admin.female') }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i>{{ __('admin.save') }}</button>
                                <button type="button" class="btn btn-outline-gold" id="cancelFormBtn"><i class="bi bi-x-circle me-1"></i>{{ __('admin.cancel') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Container -->
            <div id="usersDataContainer"> 
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('admin.name') }}</th>
                                        <th>{{ __('admin.email') }}/{{ __('admin.phone') }}</th>
                                        <th>{{ __('admin.role') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th>{{ __('admin.created_at') }}</th>
                                        <th>{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    @include('admin.users.users_table')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="paginationContainer" class="mt-3">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            
            <!-- User Details Modal -->
            <div class="modal fade" id="userDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-dark bg-light">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('admin.user_details') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="userDetailsBody" style="color: #D4AF37"></div>
                    </div>
                </div>
            </div>

        </div>
    </div> <!-- إغلاق الغلاف -->
@include('admin.footer')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'edit_user_form_title'  => __('admin.edit_user_form_title'),
    'add_new_user'          => __('admin.add_new_user'),
    'full_name'             => __('admin.full_name'),
    'email_label'           => __('admin.email_label'),
    'phone_label'           => __('admin.phone_label'),
    'permission'            => __('admin.permission'),
    'status'                => __('admin.status'),
    'gender'                => __('admin.gender'),
    'address'               => __('admin.address'),
    'male'                  => __('admin.male'),
    'female'                => __('admin.female'),
    'admin_role'            => __('admin.admin_role'),
    'customer_role'         => __('admin.customer_role'),
    'active'                => __('admin.active'),
    'inactive'              => __('admin.inactive'),
    'delete_user_confirm'   => __('admin.delete_user_confirm'),
    'yes_delete_it'         => __('admin.yes_delete_it'),
    'go_back'               => __('admin.go_back'),
    'operation_success'     => __('admin.operation_success'),
    'save_error_title'      => __('admin.save_error_title'),
    'error_title'           => __('admin.error_title'),
    'edit'                  => __('admin.edit'),
    'delete'                => __('admin.delete'),
    'view'                  => __('admin.view'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────
$(function() {
    const csrf = $('meta[name="csrf-token"]').attr('content');

    // 1. دالة الفلترة (البحث)
    function filterUsers() {
        let search = $('#search').val();
        let role = $('#roleFilter').val();
        let status = $('#statusFilter').val();

        $.ajax({
            url: "{{ route('users.filter') }}",
            type: 'GET',
            data: { search: search, role: role, status: status },
            success: function(res) {
                $('#usersTableBody').html(res.table);
                $('#paginationContainer').html(res.pagination);
            },
            error: function(err) { console.log('خطأ في البحث:', err); }
        });
    }

    $('#search').on('keyup', filterUsers);
    $('#roleFilter, #statusFilter').on('change', filterUsers);

    // 2. إدارة النموذج (Form)
    function showForm(edit = false, user = null) {
        $('#userFormSection').slideDown();
        if (edit && user) {
            $('#formTitle').text(window.AppTrans.edit_user_form_title + user.name);
            $('#userId').val(user.id);
            $('#userForm input[name=name]').val(user.name);
            $('#userForm input[name=email]').val(user.email);
            $('#userForm input[name=phone_number]').val(user.phone_number);
            $('#userForm select[name=role]').val(user.role);
            $('#userForm input[name=status][value="' + user.status + '"]').prop('checked', true);
            $('#userForm textarea[name=address]').val(user.address);
            $('#userForm input[name=birth_date]').val(user.birth_date);
            $('#userForm select[name=gender]').val(user.gender);
            $('#passwordField').prop('required', false).val('');
        } else {
            $('#userForm')[0].reset();
            $('#formTitle').text(window.AppTrans.add_new_user);
            $('#userId').val('');
            $('#passwordField').prop('required', true);
        }
    }

    function hideForm() { $('#userFormSection').slideUp(); }
    $('#addUserBtn').click(() => showForm());
    $('#closeFormBtn,#cancelFormBtn').click(() => hideForm());

    // 3. أزرار الإجراءات (Event Delegation)
    
    // زر التعديل
    $(document).on('click', '.editUserBtn', function() {
        let row = $(this).closest('tr');
        let user = row.data('user') || JSON.parse(row.attr('data-user'));
        showForm(true, user);
    });

    // زر عرض التفاصيل (التنسيق الذهبي)
    $(document).on('click', '.viewUserBtn', function() {
        let row = $(this).closest('tr');
        let user = row.data('user') || JSON.parse(row.attr('data-user'));

        let html = `
            <div class="user-details-container text-white p-2">
                <div class="row g-3">
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.full_name}</label>
                        <span class="fw-bold">${user.name}</span>
                    </div>
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.email_label}</label>
                        <span class="fw-bold">${user.email}</span>
                    </div>
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.phone_label}</label>
                        <span class="fw-bold">${user.phone_number || '-'}</span>
                    </div>
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.permission}</label>
                        <span class="badge" style="background-color: #D4AF37; color: #1a1a1a;">${user.role === 'admin' ? window.AppTrans.admin_role : window.AppTrans.customer_role}</span>
                    </div>
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.status}</label>
                        ${user.status === 'active' ? `<span style="color: #28a745;">● ${window.AppTrans.active}</span>` : `<span style="color: #dc3545;">● ${window.AppTrans.inactive}</span>`}
                    </div>
                    <div class="col-md-6 border-bottom border-secondary pb-2">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.gender}</label>
                        <span>${user.gender === 'male' ? window.AppTrans.male : (user.gender === 'female' ? window.AppTrans.female : '-')}</span>
                    </div>
                    <div class="col-md-12">
                        <label style="color: #D4AF37;" class="small d-block mb-1">${window.AppTrans.address}</label>
                        <span>${user.address || '-'}</span>
                    </div>
                </div>
            </div>`;
        
        $('#userDetailsBody').html(html);
        $('#userDetailsModal .modal-content').css({'background-color': '#1a1a1a', 'color': '#fff', 'border': '1px solid #D4AF37'});
        new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
    });

    // زر الحذف (مع تراجع الذهبي)
    $(document).on('click', '.deleteUserBtn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: window.AppTrans.delete_user_confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#D4AF37',
            confirmButtonText: window.AppTrans.yes_delete_it,
            cancelButtonText: window.AppTrans.go_back,
            background: '#1a1a1a',
            color: '#fff',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/' + id,
                    type: 'DELETE',
                    data: { _token: csrf },
                    success: function() {
                        $('#user-' + id).fadeOut(400, function(){ $(this).remove(); });
                    }
                });
            }
        });
    });

    // 4. حفظ البيانات (Submit)
    $('#userForm').submit(function(e) {
        e.preventDefault();
        let id = $('#userId').val();
        let url = id ? '/users/' + id : '/users';
        let formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(res) {
                let user = res.user;
                let rowNumber = id ? $('#user-' + id).find('td:first').text() : $('#usersTableBody tr').length + 1;
                
                let statusBadge = user.status === 'active' 
                    ? `<span class="badge bg-soft-success text-success" style="border: 1px solid #28a745; padding: 5px 10px;"><i class="bi bi-patch-check-fill"></i> ${window.AppTrans.active}</span>` 
                    : `<span class="badge bg-soft-danger text-danger" style="border: 1px solid #dc3545; padding: 5px 10px;"><i class="bi bi-x-octagon-fill"></i> ${window.AppTrans.inactive}</span>`;

                let userDataAttr = JSON.stringify(user).replace(/'/g, "&apos;");

                let rowHtml = `
                <tr id="user-${user.id}" data-user='${userDataAttr}'>
                    <td class="align-middle">${rowNumber}</td>
                    <td class="align-middle fw-bold" style="color: #D4AF37;">${user.name}</td>
                    <td class="align-middle">
                        <div>${user.email}</div>
                        <small class="text-muted">${user.phone_number || '-'}</small>
                    </td>
                    <td class="align-middle"><span class="badge bg-secondary">${user.role === 'admin' ? window.AppTrans.admin_role : window.AppTrans.customer_role}</span></td>
                    <td class="align-middle">${statusBadge}</td>
                   
                    <td class="align-middle" style="font-size: 0.85rem; color: #d1c4a9;">
                  <i class="bi bi-calendar3 me-1" style="font-size: 0.8rem;"></i>
                                     ${user.created_at || '-'}
                  </td>
                    <td class="align-middle" style="width: 1%; white-space: nowrap;">
                        <div class="d-flex flex-nowrap gap-2 justify-content-center">
                            <button class="btn btn-action btn-action-edit editUserBtn" title="${window.AppTrans.edit}" style="color: #D4AF37; border: 1px solid #D4AF37;"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-action btn-action-delete deleteUserBtn" data-id="${user.id}" title="${window.AppTrans.delete}" style="color: #dc3545; border: 1px solid #dc3545;"><i class="bi bi-trash3"></i></button>
                            <button class="btn btn-action btn-action-view viewUserBtn" title="${window.AppTrans.view}" style="color: #0dcaf0; border: 1px solid #0dcaf0;"><i class="bi bi-eye"></i></button>
                        </div>
                    </td>
                </tr>`;

                // السطر المهم الذي كان ناقصاً: تحديث الواجهة فعلياً
                if (id) {
                    $('#user-' + id).replaceWith(rowHtml);
                } else {
                    $('#usersTableBody').prepend(rowHtml);
                }

                hideForm();
                Swal.fire({ icon: 'success', title: window.AppTrans.operation_success, background: '#1a1a1a', color: '#D4AF37', timer: 1500, showConfirmButton: false });
            },
            error: function(err) {
                let msg = window.AppTrans.error_check_data;
                if (err.responseJSON && err.responseJSON.errors) {
                    msg = Object.values(err.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                }
                Swal.fire({ icon: 'error', title: window.AppTrans.error_title, html: msg, background: '#1a1a1a', color: '#fff' });
            }
        });
    });
});
</script>
</body>
</html>
