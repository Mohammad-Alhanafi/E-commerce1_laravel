<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Tajawal:wght@400;700&family=Almarai:wght@400;700&family=Amiri:wght@400;700&family=Changa:wght@400;700&family=Lalezar&family=Reem+Kufi:wght@400;700&family=Marhey:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=El+Messiri:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">


@php
    // حماية تلقائية: إذا لم يكن المتغير $settings موجوداً، يتم إنشاؤه مصفوفة فارغة لتجنب كسر الصفحات
    if (!isset($settings)) {
        $settings = [];
    }
@endphp

<header class="site-header">
    <div class="container">
        <div class="header-content">

            {{-- LOGO (يفتح المودال فقط إذا كان المستخدم آدمن) --}}
            <div class="logo" id="adminLogo" style="{{ (Auth::check() && Auth::user()->role === 'admin') ? 'cursor: pointer;' : '' }}">
                <span class="logo-icon-wrap" id="headerLogoWrap">
                    @if(!empty($settings['logo_path']))
                        <img 
                            src="{{ asset('storage/' . $settings['logo_path']) }}"
                            id="headerLogoImg"
                            alt="Logo"
                            style="
                                height: {{ $settings['logo_size'] ?? '50' }}px;
                                border-radius: {{ $settings['logo_shape'] ?? '0%' }};
                                object-fit: contain;
                            "
                        >
                    @else
                        <i 
                            id="headerLogoIcon"
                            class="fas fa-crown logo-icon"
                            style="
                                color: {{ $settings['text_color'] ?? '#D4AF37' }};
                                font-size: {{ ($settings['logo_size'] ?? 50) / 1.5 }}px;
                            ">
                        </i>
                    @endif
                </span>
                
                <div class="logo-text" id="headerStoreName" style="color: {{ $settings['text_color'] ?? '#D4AF37' }}; font-family: {{ $settings['font_family'] ?? "'Cairo', sans-serif" }}; font-size: {{ $settings['text_size'] ?? 24 }}px;">
                    {{ $settings['store_name'] ?? 'الوقار' }}
                </div>
            </div>

            {{-- NAV --}}
            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active-gold' : 'nav-link-white' }}">
                             <i class="fas fa-home"></i> {{ __('navbar.home') }}
                        </a>
                    </li>
                    <li class="custom-dropdown">
                        <a href="#" class="nav-link-white" id="categoriesBtn">
                            <i class="fas fa-layer-group"></i> {{ __('navbar.categories') }}
                        </a>
                        <div class="custom-dropdown-menu d-none" id="categoriesMenu">
                            @php
                                if (!isset($categories)) {
                                    // جلب آمن للأقسام لضمان عملها في كل الصفحات
                                    $categories = \App\Models\Category::with('products')->get();
                                }
                            @endphp
                            @foreach($categories as $cat)
                                <div class="category-box">
                                    <button type="button" class="category-toggle" data-target="cat-{{ $cat->id }}">{{ $cat->name }}</button>
                                    <div class="products-list d-none" id="cat-{{ $cat->id }}">
                                        @foreach($cat->products as $product)
                                            <a href="{{ route('products.show', $product->id) }}" class="product-link"> - {{ $product->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </nav>

            {{-- ICONS --}}
            <div class="header-icons">
                {{-- USER --}}




                @include('components.language-switcher')


                <div class="user-dropdown">
                    @php
                        $avatarUrl = null;
                    @endphp
                    @auth
                        @php
                            $user = auth()->user();
                            $avatarPath = $user->avatar ?? $user->profile_image ?? $user->profile_photo_path ?? $user->image ?? null;
                            if ($avatarPath && str_starts_with($avatarPath, 'http')) { $avatarUrl = $avatarPath; }
                            elseif ($avatarPath && str_starts_with($avatarPath, 'uploads/')) { $avatarUrl = asset($avatarPath); }
                            elseif ($avatarPath) { $avatarUrl = asset('storage/' . $avatarPath); }
                        @endphp
                    @endauth

                    <button type="button" class="header-icon-btn user-avatar-btn" id="userBtn" aria-label="حساب المستخدم">
                        @auth
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="header-avatar" onerror="this.remove(); this.parentElement.classList.add('show-initials');">
                            @endif
                            <span class="avatar-initials">{{ mb_substr($user->name ?? 'U', 0, 1) }}</span>
                        @else
                            <i class="fas fa-user"></i>
                        @endauth
                    </button>

                    <div class="user-menu d-none" id="userMenu">
                        @auth
                            <div class="user-item-header"><span class="status-dot"></span> {{ __('navbar.welcome') }} {{ auth()->user()->name }}</div>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ url('/admin') }}" class="user-item"><i class="fas fa-gem me-2"></i> {{ __('navbar.admin_panel') }}</a>
                            @endif
                            <a href="{{ route('account') }}" class="user-item"><i class="fas fa-user-circle me-2"></i> {{ __('navbar.account') }}</a>
                            <a href="#" class="user-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2"></i> {{ __('navbar.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        @else
                            <a href="{{ route('login.page') }}" class="user-item"><i class="fas fa-sign-in-alt me-2"></i> {{ __('navbar.login') }}</a>
                            <a href="{{ route('register.page') }}" class="user-item"><i class="fas fa-user-plus me-2"></i> {{ __('navbar.register') }}</a>
                        @endauth
                    </div>
                </div>

                {{-- CART --}}
                <a href="#" id="open-cart" class="header-icon-btn cart-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <span id="cart-count">{{ count(session('cart', [])) }}</span>
                </a>
            </div>

        </div>
    </div>
</header>   

{{-- نافذة إعدادات الهوية البصرية للمشرف --}}
@if(Auth::check() && Auth::user()->role === 'admin')
<div id="logoModal" class="modal">
    <div class="modal-content logo-modal-pro">

        <div class="modal-header-pro">
            <h3><i class="fas fa-palette"></i> {{ __('settings.visual_identity') }}</h3>
            <span class="close">&times;</span>
        </div>

        <div class="preview-container-pro">
            <div id="livePreview" class="live-preview-box">
                <i id="previewIcon" class="fas fa-crown" style="{{ !empty($settings['logo_path']) ? 'display:none;' : '' }}"></i>
                <img id="previewLogoImg"
                     src="{{ !empty($settings['logo_path']) ? asset('storage/'.$settings['logo_path']) : '' }}"
                     style="{{ empty($settings['logo_path']) ? 'display:none;' : '' }} height:{{ $settings['logo_size'] ?? 50 }}px; border-radius:{{ $settings['logo_shape'] ?? '0%' }};">
                <span id="previewText" style="font-size: {{ $settings['text_size'] ?? 24 }}px;">{{ $settings['store_name'] ?? 'الوقار' }}</span>
            </div>
        </div>

        <div class="modal-body-pro">

            <div class="setting-group-pro">
                <label><i class="fas fa-image"></i> {{ __('settings.logo') }}</label>
                <div class="upload-box">
                    <input type="file" id="logoInput" accept="image/*" hidden>
                    <label for="logoInput" class="upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ __('settings.upload_logo') }}</span>
                    </label>
                </div>
            </div>

            <div class="setting-group-pro">
                <label><i class="fas fa-expand-arrows-alt"></i> {{ __('settings.logo_size') }}</label>
                <input type="range" id="logoSize" min="30" max="150" value="{{ $settings['logo_size'] ?? 50 }}">
            </div>

            <div class="setting-group-pro">
                <label><i class="fas fa-store"></i> {{ __('settings.store_name') }}</label>
                <input type="text" id="storeNameInput" value="{{ $settings['store_name'] ?? 'الوقار' }}">
            </div>

            <div class="row-settings">
                <div class="setting-group-pro small">
                    <label><i class="fas fa-fill-drip"></i> {{ __('settings.text_color') }}</label>
                    <input type="color" id="textColorInput" value="{{ $settings['text_color'] ?? '#D4AF37' }}">
                </div>
                <div class="setting-group-pro">
                    <label><i class="fas fa-font"></i> {{ __('settings.font_family') }}</label>
                    <select id="fontFamilyInput">
                        <option value="'Cairo', sans-serif" {{ ($settings['font_family'] ?? '') === "'Cairo', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_cairo') }}
</option>
                        <option value="'Tajawal', sans-serif" {{ ($settings['font_family'] ?? '') === "'Tajawal', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_tajawal') }}
</option>
                        <option value="'Almarai', sans-serif" {{ ($settings['font_family'] ?? '') === "'Almarai', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_almarai') }}
</option>
                        <option value="'Amiri', serif" {{ ($settings['font_family'] ?? '') === "'Amiri', serif" ? 'selected' : '' }}>            {{ __('settings.font_amiri') }}
</option>
                        <option value="'Changa', sans-serif" {{ ($settings['font_family'] ?? '') === "'Changa', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_changa') }}
</option>
                        <option value="'Lalezar', display" {{ ($settings['font_family'] ?? '') === "'Lalezar', display" ? 'selected' : '' }}>            {{ __('settings.font_lalezar') }}
</option>
                        <option value="'Reem Kufi', sans-serif" {{ ($settings['font_family'] ?? '') === "'Reem Kufi', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_reem_kufi') }}
</option>
                        <option value="'Marhey', display" {{ ($settings['font_family'] ?? '') === "'Marhey', display" ? 'selected' : '' }}>            {{ __('settings.font_marhey') }}
</option>
                        <option value="'Aref Ruqaa', serif" {{ ($settings['font_family'] ?? '') === "'Aref Ruqaa', serif" ? 'selected' : '' }}>            {{ __('settings.font_aref_ruqaa') }}
</option>
                        <option value="'El Messiri', sans-serif" {{ ($settings['font_family'] ?? '') === "'El Messiri', sans-serif" ? 'selected' : '' }}>            {{ __('settings.font_el_messiri') }}
</option>
                    </select>
                </div>
            </div>

            <div class="setting-group-pro">
                <label><i class="fas fa-text-height"></i>  {{ __('settings.text_size') }}</label>
                <input type="range" id="textSizeInput" min="14" max="48" value="{{ $settings['text_size'] ?? 24 }}">
            </div>

            <div class="setting-group-pro">

    <label>
        <i class="fas fa-shapes"></i>
        {{ __('settings.logo_shape') }}
    </label>

    <select id="logoShapeInput">

        <option value="0%" {{ ($settings['logo_shape'] ?? '') === '0%' ? 'selected' : '' }}>
            {{ __('settings.logo_square') }}
        </option>

        <option value="15px" {{ ($settings['logo_shape'] ?? '') === '15px' ? 'selected' : '' }}>
            {{ __('settings.logo_rounded') }}
        </option>

        <option value="50%" {{ ($settings['logo_shape'] ?? '') === '50%' ? 'selected' : '' }}>
            {{ __('settings.logo_circle') }}
        </option>

    </select>

</div>

        </div>

        <div class="modal-footer-pro">
            <button id="saveLogo" class="btn-save-pro">
                <i class="fas fa-check"></i>             {{ __('settings.save') }}

            </button>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@vite(['resources/js/app.js'])
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('logoModal');
    const btn = document.getElementById('adminLogo');
    const span = document.querySelector('#logoModal .close');

    if (!btn || !modal) return;

    const storeNameInput = document.getElementById('storeNameInput');
    const textColorInput = document.getElementById('textColorInput');
    const fontFamilyInput = document.getElementById('fontFamilyInput');
    const logoSize = document.getElementById('logoSize');
    const textSizeInput = document.getElementById('textSizeInput');
    const logoShapeInput = document.getElementById('logoShapeInput');
    const logoInput = document.getElementById('logoInput');

    const previewText = document.getElementById('previewText');
    const previewIcon = document.getElementById('previewIcon');
    const previewLogoImg = document.getElementById('previewLogoImg');
    const saveBtn = document.getElementById('saveLogo');

    let newImageSelected = false;

    btn.onclick = function(e) {
        e.preventDefault();
        modal.style.display = "flex";
        updateLivePreview();
    }

    if (span) { span.onclick = function() { modal.style.display = "none"; } }
    window.addEventListener('click', function(event) {
        if (event.target == modal) { modal.style.display = "none"; }
    });

    // معاينة الصورة فوراً عند الاختيار
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(event) {
                previewLogoImg.src = event.target.result;
                previewLogoImg.style.display = 'inline-block';
                previewIcon.style.display = 'none';
                newImageSelected = true;
                updateLivePreview();
            };
            reader.readAsDataURL(file);
        });
    }

    function updateLivePreview() {
        if (previewText && storeNameInput) previewText.innerText = storeNameInput.value;
        if (previewText && textColorInput) previewText.style.color = textColorInput.value;
        if (previewText && fontFamilyInput) previewText.style.fontFamily = fontFamilyInput.value;
        if (previewText && textSizeInput) previewText.style.fontSize = textSizeInput.value + 'px';

        const size = logoSize ? logoSize.value : 50;
        const shape = logoShapeInput ? logoShapeInput.value : '0%';

        if (previewIcon) {
            previewIcon.style.color = textColorInput ? textColorInput.value : '#D4AF37';
            previewIcon.style.fontSize = (size * 0.7) + 'px';
        }
        if (previewLogoImg) {
            previewLogoImg.style.height = size + 'px';
            previewLogoImg.style.borderRadius = shape;
        }
    }

    if (storeNameInput) storeNameInput.oninput = updateLivePreview;
    if (textColorInput) textColorInput.oninput = updateLivePreview;
    if (fontFamilyInput) fontFamilyInput.onchange = updateLivePreview;
    if (logoSize) logoSize.oninput = updateLivePreview;
    if (textSizeInput) textSizeInput.oninput = updateLivePreview;
    if (logoShapeInput) logoShapeInput.onchange = updateLivePreview;

    if (saveBtn) {
        saveBtn.onclick = function() {
            let formData = new FormData();
            formData.append('store_name', storeNameInput.value);
            formData.append('text_color', textColorInput.value);
            formData.append('font_family', fontFamilyInput.value);
            formData.append('logo_size', logoSize.value);
            formData.append('text_size', textSizeInput.value);
            formData.append('logo_shape', logoShapeInput.value);

            if (logoInput && logoInput.files[0]) {
                formData.append('logo_image', logoInput.files[0]);
            }

            let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // تعطيل الزر مؤقتاً وإظهار حالة تحميل بسيطة عليه
            const originalBtnHTML = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('admin.saving') }}';

            let targetUrl = "";
            try {
                targetUrl = "{{ Route::has('admin.settings.update') ? route('admin.settings.update') : '/admin/settings/update' }}";
            } catch(e) {
                targetUrl = "/admin/settings/update";
            }

            const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            }) : null;

            if (typeof axios !== 'undefined') {
                axios.post(targetUrl, formData, {
                    headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'multipart/form-data' }
                })
                .then(response => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalBtnHTML;
                    modal.style.display = "none";

                    if (Toast) {
                        Toast.fire({ icon: 'success', title: '{{ __('admin.save_success_msg') }}' });
                    }
                    setTimeout(() => { window.location.reload(); }, 1200);
                })
                .catch(error => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalBtnHTML;

                    if (Toast) {
                        Toast.fire({ icon: 'error', title: '{{ __('admin.save_error_msg') }}' });
                    } else {
                        alert('{{ __('admin.save_error_msg') }}');
                    }
                });
            } else {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnHTML;
                alert('مكتبة Axios غير معرفة في هذه الصفحة!');
            }
        }
    }
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriesBtn = document.getElementById('categoriesBtn');
    const categoriesMenu = document.getElementById('categoriesMenu');
    if (categoriesBtn && categoriesMenu) {
        categoriesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            categoriesMenu.classList.toggle('d-none');
        });
    }

    const toggles = document.querySelectorAll('.category-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetList = document.getElementById(targetId);
            if (targetList) {
                targetList.classList.toggle('d-none');
            }
        });
    });

    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');
    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.preventDefault();
            userMenu.classList.toggle('d-none');
        });
    }

    window.addEventListener('click', function(e) {
        if (categoriesMenu && categoriesBtn && !categoriesBtn.contains(e.target) && !categoriesMenu.contains(e.target)) {
            categoriesMenu.classList.add('d-none');
        }
        if (userMenu && userBtn && !userBtn.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.add('d-none');
        }
    });
});







document.addEventListener('DOMContentLoaded', function(){

    let theme = localStorage.getItem('theme') || 'dark';

    document.documentElement.setAttribute(
        'data-theme',
        theme
    );


    const btn = document.getElementById('themeToggle');

    if(btn){

        btn.addEventListener('click', function(){

            let current =
            document.documentElement.getAttribute('data-theme');


            let newTheme =
            current === 'dark'
            ? 'light'
            : 'dark';


            document.documentElement.setAttribute(
                'data-theme',
                newTheme
            );


            localStorage.setItem(
                'theme',
                newTheme
            );


            // تغيير الأيقونة
            const icon = btn.querySelector('i');

            if(icon){

                if(newTheme === 'dark'){
                    icon.className = 'fas fa-moon';
                }
                else{
                    icon.className = 'fas fa-sun';
                }

            }


        });

    }

});









document.getElementById('langBtn').onclick = function(e){

    e.stopPropagation();

    document.getElementById('langList')
    .classList.toggle('active');

};


document.onclick = function(){

    document.getElementById('langList')
    .classList.remove('active');

};
</script>