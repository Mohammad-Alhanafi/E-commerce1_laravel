<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('profile.title') ?? 'حسابي' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
</head>
<body>
    @include('admin.header')

<main class="account-page" style="background: var(--bg-color); color: var(--text-color); padding: 40px 0; font-family: sans-serif;">
    <div class="container">
        {{-- رسائل النجاح والأخطاء --}}
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 mb-4 text-center" style="background: var(--success-color, #198754); color: #fff;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mb-4" style="background: var(--danger-color, #dc3545); color: #fff; list-style: none;">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="account-card" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden;">

            {{-- هيدر الحساب مع الصورة الشخصية وقلم التعديل الفوري لها --}}
            <div class="account-hero d-flex flex-column flex-md-row align-items-center gap-4 p-4" style="background: var(--section-bg); border-bottom: 1px solid var(--border-color);">
                <div class="account-avatar" style="position: relative; width: 120px; height: 120px; border-radius: 50%; background: var(--section-bg); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--primary-color); border: 2px solid var(--primary-color); overflow: hidden;">
                    @if($user->profile_image)
                        <img src="{{ asset($user->profile_image) }}" id="avatarPreview" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span id="avatarInitials">{{ mb_substr($user->name ?? 'U', 0, 1) }}</span>
                        <img src="" id="avatarPreview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    @endif

                    {{-- فورم مستقل خاص فقط برفع الصورة، عشان ما يصطدم مع التحقق (validation) تبع باقي حقول الحساب --}}
                    <form action="{{ route('account.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <label for="profileImageInput" style="position: absolute; bottom: 0; right: 0; background: var(--primary-color); color: var(--btn-text-color, #000); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.9rem; border: 2px solid var(--bg-color); z-index: 2;">
                            <i class="fas fa-pen"></i>
                        </label>
                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit();">
                    </form>
                </div>

                <div class="text-center text-md-start flex-grow-1">
                    <h1 class="account-title" style="font-size: 1.8rem; font-weight: bold; color: var(--text-color); margin-bottom: 5px;">{{ $user->name }}</h1>
                    <div style="color: var(--text-muted); font-size: 0.95rem;">
                        <span style="color: var(--primary-color);"><i class="fas fa-user-shield me-1"></i> {{ __('profile.membership') }}</span> · {{ __('profile.member_since') }} {{ $user->created_at?->format('Y-m-d') }}
                    </div>
                </div>

                <div>
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editProfileModal" style="background: transparent; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 8px 20px; border-radius: 30px; font-weight: bold;">
                        <i class="fas fa-pen me-2"></i> {{ __('profile.edit_account') }}
                    </button>
                </div>
            </div>

            {{-- عرض البيانات بطريقة عرض البطاقات النظيفة والأنيقة الفردية --}}
            <div class="p-4">
                <h4 class="mb-4" style="color: var(--primary-color); font-size: 1.2rem; border-inline-start: 3px solid var(--primary-color); padding-inline-start: 10px;"><i class="fas fa-info-circle ms-2"></i> {{ __('profile.personal_account_details') }}</h4>

                <div class="row g-4">
                    {{-- حقل الاسم --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.name') }}</small>
                                <span style="font-weight: 500;">{{ $user->name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- حقل البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.email') }}</small>
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- حقل رقم الهاتف --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.phone') }}</small>
                                <span>{{ $user->phone_number ?? __('profile.no_phone') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- حقل تاريخ الميلاد --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.birthday') }}</small>
                                <span>{{ $user->birth_date ?? __('profile.not_set_yet') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- حقل الجنس --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.gender') }}</small>
                                <span>{{ $user->gender == 'male' ? __('profile.male') : ($user->gender == 'female' ? __('profile.female') : __('profile.not_set_yet')) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- حقل العنوان --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: var(--section-bg); border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <small style="color: var(--text-muted); display: block; margin-bottom: 4px;">{{ __('profile.address') }}</small>
                                <span>{{ $user->address ?? __('profile.no_address') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قسم الطلبات المعتاد بأسفل الصفحة --}}
            <div class="p-4 border-top" style="border-color: #333 !important;">
                <h4 class="mb-4" style="color: var(--primary-color); font-size: 1.2rem; border-inline-start: 3px solid var(--primary-color); padding-inline-start: 10px;"><i class="fas fa-box-open ms-2"></i>{{ __('profile.previous_orders') }}</h4>

                @forelse($orders as $order)
                    <div class="order-row mb-3 p-3 rounded" style="background: var(--section-bg); border: 1px solid var(--border-color);">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                            <div>
                                <strong>{{ __('profile.order_number') }} #{{ $order->id }}</strong>
                                <div style="color: var(--text-muted); font-size: 0.85rem;">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="text-md-end">
                                <div class="fw-bold" style="color: var(--success-color);">{{ number_format($order->total_price, 2) }} $</div>
                                <span class="badge" style="background: var(--primary-color); color: var(--btn-text-color, #000); margin-top: 4px;">{{ $order->status }}</span>
                            </div>
                        </div>
                        @if($order->products->count())
                            <div class="mt-3 small border-top pt-2" style="border-color: var(--border-color) !important; color: var(--text-muted);">
                                @foreach($order->products as $product)
                                    <span>{{ $product->name }} × {{ $product->pivot->quantity }}</span>@if(!$loop->last)، @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5" style="color: var(--text-muted);">
                        <i class="fas fa-receipt fa-2x mb-3 d-block"></i>
                        {{ __('profile.no_orders_yet') }}
                    </div>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-color); border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" id="editProfileModalLabel" style="color: var(--primary-color);"><i class="fas fa-user-pen me-2"></i> {{ __('profile.update_account_security') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('buttons.cancel') }}"></button>
            </div>

            <form action="{{ route('account.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    {{-- القسم الأول: البيانات الشخصية --}}
                    <h6 class="text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase;"><i class="fas fa-id-card me-1"></i> {{ __('profile.basic_personal_data') }}</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.name') }}</label>
                            <input type="text" name="name" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.email') }}</label>
                            <input type="email" name="email" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.phone') }}</label>
                            <input type="text" name="phone_number" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.birthday') }}</label>
                            <input type="date" name="birth_date" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" value="{{ old('birth_date', $user->birth_date) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.gender') }}</label>
                            <select name="gender" class="form-select" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);">
                                <option value="">{{ __('profile.choose') }}</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('profile.male') }}</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('profile.female') }}</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" style="color: var(--primary-color);">{{ __('profile.address') }}</label>
                            <input type="text" name="address" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" value="{{ old('address', $user->address) }}" placeholder="{{ __('profile.address_placeholder') }}">
                        </div>
                    </div>

                    <hr style="border-color: var(--border-color); margin: 25px 0;">

                    {{-- القسم الثاني: حقول تغيير كلمة المرور --}}
                    <h6 class="text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase;"><i class="fas fa-lock me-1"></i> {{ __('profile.security_password_section') }}</h6>
                   <div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" style="color: var(--primary-color);">{{ __('profile.current_password') }}</label>
        <div class="input-group">
            <input type="password" name="current_password" id="current_password" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" placeholder="{{ __('profile.current_password_placeholder') }}" autocomplete="current-password">
            <button type="button" class="btn toggle-password" data-target="current_password" style="border-color: var(--input-border); color: var(--text-color); background: var(--section-bg);">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label" style="color: var(--primary-color);">{{ __('profile.new_password') }}</label>
        <div class="input-group">
            <input type="password" name="new_password" id="new_password" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" placeholder="{{ __('profile.new_password_placeholder') }}" autocomplete="new-password">
            <button type="button" class="btn toggle-password" data-target="new_password" style="border-color: var(--input-border); color: var(--text-color); background: var(--section-bg);">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label" style="color: var(--primary-color);">{{ __('profile.confirm_password') }}</label>
        <div class="input-group">
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" style="background: var(--input-bg); color: var(--input-text); border-color: var(--input-border);" placeholder="{{ __('profile.confirm_password_placeholder') }}" autocomplete="new-password">
            <button type="button" class="btn toggle-password" data-target="new_password_confirmation" style="border-color: var(--input-border); color: var(--text-color); background: var(--section-bg);">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
</div>

                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: var(--section-bg); border: none;">{{ __('buttons.cancel') }}</button>
                    <button type="submit" class="btn" style="background: var(--primary-color); color: var(--btn-text-color, #000); font-weight: bold; padding: 6px 25px;">{{ __('profile.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.footer')


<script>
console.log('Bootstrap loaded?', typeof bootstrap !== 'undefined');
</script>

{{--
    مهم جداً: تأكد إنه ملف admin.footer فيه السكربت التالي قبل إغلاق body،
    وإلا المودال (نافذة التعديل) وكل أزرار Bootstrap التانية ما رح تشتغل أبداً:

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
--}}

<script>
document.getElementById('profileImageInput')?.addEventListener('change', function () {
    const file = this.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const previewImg = document.getElementById('avatarPreview');
        const initialsSpan = document.getElementById('avatarInitials');

        if (previewImg) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            if (initialsSpan) initialsSpan.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
});
</script>


<script>
document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
@include('components.theme-toggle')
</body>
</html>
