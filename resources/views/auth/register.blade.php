<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.title') ?? 'تسجيل الدخول / إنشاء حساب' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.5.3/css/intlTelInput.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background: var(--bg-color); color: var(--text-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        
        .auth-container { width: 100%; max-width: 450px; padding: 20px; box-sizing: border-box; }
        .brand-logo { text-align: center; color: var(--primary-color); font-size: 28px; font-weight: bold; margin-bottom: 30px; text-shadow: 0 0 15px color-mix(in srgb, var(--primary-color) 30%, transparent); }
        .brand-logo i { margin-left: 8px; }

        .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; padding: 35px; backdrop-filter: blur(10px); box-shadow: 0 10px 30px var(--shadow-color, rgba(0,0,0,0.5)); position: relative; overflow: hidden; }
        
        /* ألسنة التبديل العلوي */
        .auth-tabs { display: flex; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); }
        .tab-btn { flex: 1; padding: 12px; text-align: center; color: var(--text-muted); cursor: pointer; transition: 0.3s; font-size: 16px; font-weight: 500; }
        .tab-btn.active { color: var(--primary-color); border-bottom: 3px solid var(--primary-color); font-weight: bold; }
        
        /* حقول الإدخال */
        .form-group { margin-bottom: 20px; position: relative; }
label { display: block; margin-bottom: 8px; color: var(--text-muted, #b8a07c); font-size: 14px; }
.input-wrapper { position: relative; }
.input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: color-mix(in srgb, var(--primary-color) 50%, transparent); }
input { width: 100%; padding: 12px 40px 12px 12px; background: var(--input-bg, rgba(0, 0, 0, 0.5)); border: 1px solid color-mix(in srgb, var(--primary-color) 30%, transparent); color: var(--input-text, #fff); border-radius: 10px; outline: none; box-sizing: border-box; font-size: 15px; }
input:focus { border-color: var(--primary-color, #d4af37); box-shadow: 0 0 10px color-mix(in srgb, var(--primary-color) 20%, transparent); }

        /* خيارات إضافية */
        .form-options { display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin-bottom: 25px; color: var(--text-muted, #b8a07c); }
.form-options a { color: var(--primary-color, #d4af37); text-decoration: none; transition: 0.2s; }
.form-options a:hover { text-decoration: underline; }
.checkbox-label { display: flex; align-items: center; gap: 6px; cursor: pointer; margin-bottom: 0; }
.checkbox-label input { width: auto; margin: 0; cursor: pointer; }
        /* الأزرار */
        .auth-btn { width: 100%; background: linear-gradient(135deg, var(--primary-color, #d4af37) 0%, var(--secondary-color, #b4941c) 100%); color: var(--btn-text-color, #000); border: none; padding: 14px; font-size: 16px; font-weight: bold; border-radius: 40px; cursor: pointer; transition: 0.3s; }

        .auth-btn:hover { opacity: 0.9; box-shadow: 0 0 15px color-mix(in srgb, var(--primary-color) 40%, transparent); }        
        /* إخفاء النماذج تلقائياً */
        .auth-form { display: none; }
        .auth-form.active { display: block; animation: fadeIn 0.4s ease-in-out; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }









        .swal-neon-popup {
    background: #0a0a0f !important;
    border: 1px solid rgba(0, 255, 200, 0.35) !important;
    border-radius: 18px !important;
    box-shadow:
        0 0 20px rgba(0, 255, 200, 0.25),
        0 0 45px rgba(155, 0, 255, 0.15) !important;
}

.swal-neon-title {
    color: #00ffc8 !important;
    text-shadow: 0 0 10px rgba(0, 255, 200, 0.6);
    font-weight: bold;
}

.swal-neon-text {
    color: #d8d8ff !important;
}

.swal2-icon.swal2-success {
    border-color: #00ffc8 !important;
}
.swal2-icon.swal2-success [class^='swal2-success-line'] {
    background-color: #00ffc8 !important;
}
.swal2-icon.swal2-success .swal2-success-ring {
    border-color: rgba(0, 255, 200, 0.4) !important;
}

.swal2-icon.swal2-error {
    border-color: #ff2b6d !important;
}
.swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
    background-color: #ff2b6d !important;
}

.swal2-icon.swal2-warning {
    border-color: #ffb800 !important;
    color: #ffb800 !important;
}

.swal-neon-confirm {
    background: linear-gradient(135deg, #00ffc8 0%, #9b00ff 100%) !important;
    color: #000 !important;
    font-weight: bold !important;
    border-radius: 30px !important;
    box-shadow: 0 0 15px rgba(0, 255, 200, 0.4) !important;
}

.swal2-timer-progress-bar {
    background: linear-gradient(90deg, #00ffc8, #9b00ff) !important;
}





/* تنسيق أيقونة العين لتظهر في أقصى اليسار بشكل متناسق */
/* الحاوية الأساسية للحقل */
.input-wrapper-custom {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    margin-bottom: 15px;
}

/* صندوق الإدخال الفعلي */
.neon-input {
    width: 100%;
    padding: 12px 40px 12px 45px !important; /* مساحة لليمين (أيقونة القفل) واليسار (أيقونة العين) */
    box-sizing: border-box;
    direction: rtl; /* اتجاه الكتابة من اليمين لليومين */
    background: #12121a;
    border: 1px solid #222;
    color: #fff;
    border-radius: 8px;
    outline: none;
}

/* أيقونة القفل/الحماية (على اليمين) */
.input-icon-right {
    position: absolute;
    right: 15px;
    color: #888;
    pointer-events: none; /* تمنع الأيقونة من اعتراض نقرات الماوس */
    z-index: 2;
}

/* أيقونة العين (على اليسار) */
.toggle-password {
    position: absolute;
    left: 15px;
    color: #888;
    cursor: pointer;
    z-index: 5; /* للتأكد من أنها فوق الحقل وقابلة للضغط */
    padding: 5px;
    transition: color 0.2s ease;
}

.toggle-password:hover {
    color: #00f3ff; /* تأثير النيون عند التمرير */
}


















/* 🌟 تخصيص نافذة التنبيهات بالثيم الذهبي الفخم */

/* 1. الحاوية الرئيسية للتنبيه (الخلفية والإطار والتوهج الذهبي) */
.swal-neon-popup {
    background-color: #0a0a0f !important; /* خلفية داكنة جداً ليبرز اللون الذهبي */
    border: 2px solid #d4af37 !important; /* إطار ذهبي معدني */
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.35) !important; /* توهج نيون ذهبي أنيق */
    border-radius: 16px !important;
}

/* 2. عنوان التنبيه الرئيسي */
.swal-neon-title {
    color: #ffd700 !important; /* لون ذهبي ناصع */
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.5) !important; /* تأثير توهج خفيف للخط */
    font-weight: bold !important;
}

/* 3. نص التنبيه الداخلي والرسائل */
.swal-neon-text {
    color: #f3e9dc !important; /* أبيض مائل للذهبي الفاتح ليكون مريحاً للعين وقابل للقراءة */
    font-size: 15px !important;
    line-height: 1.6 !important;
}

/* 4. زر التأكيد الذهبي */
.swal-neon-confirm {
    background: linear-gradient(45deg, #b8860b, #ffd700) !important; /* تدرج ذهبي فخم */
    color: #0a0a0f !important; /* كتابة بالأسود الداكن ليكون التباين ممتازاً ومقروءاً */
    font-weight: bold !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 24px !important;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3) !important;
    transition: all 0.3s ease-in-out !important;
}

/* تأثير زر التأكيد عند تمرير الماوس (Hover) */
.swal-neon-confirm:hover {
    background: linear-gradient(45deg, #ffd700, #fff7d6) !important; /* تدرج ذهبي ساطع */
    box-shadow: 0 0 25px rgba(255, 215, 0, 0.7) !important; /* زيادة التوهج */
    transform: scale(1.03) !important; /* تكبير خفيف وحيوي للزر */
}

/* 5. تعديل شريط التحميل (Progress Bar) ليتماشى مع اللون الذهبي */
.swal2-progress-bar {
    background: #ffd700 !important;
    box-shadow: 0 0 10px #ffd700 !important;
}

/* 6. تخصيص أيقونات SweetAlert (مثل الصح والخطأ) لتتناسب مع التصميم الداكن */
.swal2-icon.swal2-success {
    border-color: #ffd700 !important;
    color: #ffd700 !important;
}
.swal2-icon.swal2-success [class^='swal2-success-line'] {
    background-color: #ffd700 !important;
}
.swal2-icon.swal2-success .swal2-success-ring {
    border: 4px solid rgba(255, 215, 0, 0.3) !important;
}



/* تنسيق الحقول الإدخال الافتراضية لـ SweetAlert2 لتناسب الثيم الداكن والذهبي */
.swal2-input {
    background-color: #12121a !important;
    border: 1px solid #d4af37 !important; /* إطار ذهبي */
    color: #fff !important;
    border-radius: 8px !important;
}

.swal2-input:focus {
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5) !important; /* توهج ذهبي عند التركيز */
    border-color: #ffd700 !important;
}

/* تخصيص زر التنبيهات الفرعي (مثل الإلغاء) باللون الرمادي الداكن */
.swal-neon-cancel {
    background-color: #2c2c35 !important;
    color: #fff !important;
    border-radius: 8px !important;
}






/* حاوية الهاتف */
.phone-wrapper {
    position: relative;
    width: 100%;
}


/* أيقونة الهاتف */
.phone-wrapper > i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #d4af37;
    font-size: 18px;
    z-index: 2;
}


/* الحقل */
#phone {
    width: 100%;
    height: 50px;
    background: #111;
    border: 1px solid rgba(212,175,55,.4);
    border-radius: 12px;

    color: white;
    font-size: 15px;

    padding-right: 50px;
    padding-left: 90px;

    outline: none;
    transition: .3s;
}


/* عند التركيز */
#phone:focus {
    border-color: #d4af37;
    box-shadow: 0 0 15px rgba(212,175,55,.35);
}


/* صندوق اختيار الدولة */
.iti {
    width: 100%;
}


/* مكان علم الدولة والمفتاح */
.iti__flag-container {
    left: auto;
    right: 0;
}


/* القائمة المنسدلة */
.iti__country-list {
    background: goldenrod;
    color: white;
    border: 1px solid #d4af37;
    border-radius: 10px;
}


/* الدول عند المرور */
.iti__country:hover {
    background: rgba(212,175,55,.2);
}


/* النص داخل القائمة */
.iti__country-name {
    color: white;
}


/* مفتاح الدولة */
.iti__selected-dial-code {
    color: #d4af37;
    font-weight: bold;
}




.iti--allow-dropdown .iti__flag-container {
    right: 0;
    left: auto;
}
    </style>
</head>
<body>



    <div class="card">
        <!-- ألسنة التحكم بالتبديل -->
        <div class="auth-tabs">
            <div class="tab-btn active" onclick="switchForm('login')">{{ __('auth.login') }}</div>
            <div class="tab-btn" onclick="switchForm('register')">{{ __('auth.register') }}</div>
        </div>
<div id="register-error-msg" style="display: none; color: #ff4a5a; background: rgba(255, 74, 90, 0.1); border: 1px solid #ff4a5a; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; text-align: right;">
    </div>
        <!-- أولاً: نموذج تسجيل الدخول -->
        <form id="login-form" class="auth-form active" action="{{ route('client.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>{{ __('auth.login_identifier') }}</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="text" name="login_field" placeholder="example@mail.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('auth.password') }}</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> {{ __('remember me') }}
                </label>
                <a href="#" class="forgot-password-link">{{ __('forgot password') }}</a>
            </div>

            <button type="submit" class="auth-btn">{{ __('Login') }} <i class="fas fa-sign-in-alt"></i></button>
        </form>

        <!-- ثانياً: نموذج إنشاء حساب جديد -->
        
        <form id="register-form" class="auth-form" action="{{ route('client.register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>{{ __('auth.name') }}</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="{{ __('Enter Your Full Name') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('auth.email') }}</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="example@mail.com" required>
                </div>
            </div>

        <div class="form-group phone-group">
    <label>{{ __('auth.phone') }}</label>

    <div class="phone-wrapper">

        <i class="fas fa-phone"></i>

        <input 
            type="tel" 
            id="phone"
            name="phone_number"
            placeholder="70 000 000"
            required
        >

    </div>
</div>

      <div class="form-group">
    <label>{{ __('auth.password') }}</label>
    <div class="input-wrapper-custom">
        <i class="fas fa-lock input-icon-right" style="color:#b4941c"></i>
        <input type="password" name="password" id="reg-password" class="neon-input" placeholder="••••••••" required>
        <i class="fas fa-eye toggle-password" data-target="#reg-password"></i>
    </div>
</div>

<div class="form-group">
    <label>{{ __('auth.confirm_password') }}</label>
    <div class="input-wrapper-custom">
        <i class="fas fa-shield-alt input-icon-right" style="color:#b4941c"></i>
        <input type="password" name="password_confirmation" id="reg-password-confirm" class="neon-input" placeholder="••••••••" required>
        <i class="fas fa-eye toggle-password" data-target="#reg-password-confirm"></i>
    </div>
</div>

            <button type="submit" class="auth-btn">{{ __('register') }} <i class="fas fa-user-plus"></i></button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.5.3/js/intlTelInput.min.js"></script>


<script>

const input = document.querySelector("#phone");


const iti = window.intlTelInput(input, {

    initialCountry: "lb", // لبنان افتراضياً

   preferredCountries: [
    "lb", // لبنان
    "sa", // السعودية
    "ae", // الإمارات
    "kw", // الكويت
    "qa", // قطر
    "bh", // البحرين
    "om", // عمان
    "jo", // الأردن
    "ps", // فلسطين
    "sy", // سوريا
    "iq", // العراق
    "eg", // مصر
    "ye", // اليمن
    "ma", // المغرب
    "dz", // الجزائر
    "tn", // تونس
    "ly", // ليبيا
    "sd", // السودان
    "mr", // موريتانيا
    "so", // الصومال
    "dj", // جيبوتي
    "km"  // جزر القمر
],
    separateDialCode: true,

    utilsScript:
    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.5.3/js/utils.js"

});


</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@php
    $authTrans = [
        'checking_account' => __('auth.checking_account'),
        'welcome_back' => __('auth.welcome_back'),
        'account_verified' => __('auth.account_verified'),
        'login_failed' => __('auth.login_failed'),
        'retry' => __('auth.retry'),
        'invalid_phone_title' => __('auth.invalid_phone_title'),
        'invalid_phone_text' => __('auth.invalid_phone_text'),
        'ok' => __('buttons.ok'),
        'creating_account' => __('auth.creating_account'),
        'account_created' => __('auth.account_created'),
        'reset_password' => __('auth.reset_password'),
        'reset_password_label' => __('auth.reset_password_label'),
        'send_code' => __('auth.send_code'),
        'cancel' => __('buttons.cancel'),
        'enter_email' => __('auth.enter_email'),
        'send_code_failed' => __('auth.send_code_failed'),
        'code_sent_title' => __('auth.code_sent_title'),
        'code_sent_text' => __('auth.code_sent_text'),
        'continue' => __('auth.continue'),
        'reset_password_title' => __('auth.reset_password_title'),
        'verification_code' => __('auth.verification_code'),
        'new_password' => __('auth.new_password'),
        'new_password_confirm' => __('auth.new_password_confirm'),
        'change_password' => __('auth.change_password'),
        'fill_required_fields' => __('auth.fill_required_fields'),
        'password_min_length' => __('auth.password_min_length'),
        'password_require_small_number' => __('auth.password_require_small_number'),
        'password_mismatch' => __('auth.password_mismatch'),
        'invalid_or_expired_code' => __('auth.invalid_or_expired_code'),
        'password_changed_title' => __('auth.password_changed_title'),
        'password_changed_text' => __('auth.password_changed_text'),
        'login_now' => __('auth.login_now'),
    ];
@endphp
<script>
$(document).ready(function() {
    const authTrans = @json($authTrans);

    // دالة التبديل السلس بين اللوحات
    window.switchForm = function(type) {
        $('.tab-btn').removeClass('active');
        $('.auth-form').removeClass('active');

        if (type === 'login') {
            $('.tab-btn').first().addClass('active');
            $('#login-form').addClass('active');
        } else {
            $('.tab-btn').last().addClass('active');
            $('#register-form').addClass('active');
        }
    };

    // 🔑 تسجيل الدخول مع فحص الأخطاء
    // 🔑 تسجيل الدخول مع فحص الأخطاء ونظام الحظر (3 محاولات خاطئة = حظر ساعة)
   // 🔑 تسجيل الدخول الذهبي المتكامل مع السيرفر
   $('#login-form').on('submit', function(e) {
    e.preventDefault();
    let form = this;
    let formData = $(form).serialize();

    // إظهار نافذة التحميل بالثيم الذهبي
    Swal.fire({
        title: authTrans.checking_account,
        allowOutsideClick: false,
        customClass: { 
            popup: 'swal-neon-popup', 
            title: 'swal-neon-title' 
        },
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: $(form).attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            // تصفير أي محاولات محلية إذا كانت موجودة سابقاً
            localStorage.removeItem('login_block_until');
            localStorage.removeItem('login_attempts');

            Swal.fire({
                icon: 'success',
                title: authTrans.welcome_back,
                text: authTrans.account_verified,
                showConfirmButton: false,
                timer: 1300,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-neon-popup',
                    title: 'swal-neon-title',
                    htmlContainer: 'swal-neon-text'
                }
            });
            
            setTimeout(() => {
                window.location.href = response.redirect || '/';
            }, 1300);
        },
        error: function(xhr) {
            let message = 'البريد الإلكتروني أو كلمة المرور غير صحيحة، حاول مجدداً';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 500) {
                message = 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.';
            }

            // عرض رسالة الخطأ بالثيم الذهبي الفخم
            Swal.fire({
                icon: 'error',
                title: authTrans.login_failed,
                html: `<span style="color: #f3e9dc;">${message}</span>`,
                confirmButtonText: authTrans.retry,
                customClass: {
                    popup: 'swal-neon-popup',
                    title: 'swal-neon-title',
                    htmlContainer: 'swal-neon-text',
                    confirmButton: 'swal-neon-confirm'
                }
            });
        }
    });
});

    // 🚀 إنشاء الحساب الحقيقي مع فحص كلمة المرور
  // 🚀 إنشاء الحساب الحقيقي مع فحص فوري للأخطاء فوق الفورم
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        let form = this;
        let password = $('#reg-password').val();
        let passwordConfirm = $('#reg-password-confirm').val();
        let errorDiv = $('#register-error-msg');
if (!iti.isValidNumber()) {

    Swal.fire({
    icon: 'error',
    title: authTrans.invalid_phone_title,
    text: authTrans.invalid_phone_text,
    confirmButtonText: authTrans.ok,

    customClass: {
        popup: 'swal-gold-popup',
        title: 'swal-gold-title',
        confirmButton: 'swal-gold-button'
    }
});

    return false;
}


// تحويل الرقم للصيغة الدولية
let fullNumber = iti.getNumber();
console.log(fullNumber);
    $('#phone').val(fullNumber);

        // إخفاء صندوق الأخطاء وتفريغه عند كل محاولة جديدة
        errorDiv.hide().html('');

        let errors = [];

        // 1. فحص طول كلمة المرور
        if (password.length < 8) {
            errors.push('• يجب أن تكون كلمة المرور 8 خانات على الأقل.');
        }
        // 2. فحص وجود حرف كبير
        if (!/[A-Z]/.test(password)) {
            errors.push('• يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل (A-Z).');
        }
        // 3. فحص وجود حرف صغير
        if (!/[a-z]/.test(password)) {
            errors.push('• يجب أن تحتوي كلمة المرور على حرف صغير واحد على الأقل (a-z).');
        }
        // 4. فحص وجود رقم
        if (!/\d/.test(password)) {
            errors.push('• يجب أن تحتوي كلمة المرور على رقم واحد على الأقل (0-9).');
        }
        // 5. فحص تطابق كلمتي المرور
        if (password !== passwordConfirm) {
            errors.push('• كلمتا المرور غير متطابقتين!');
        }

        // إذا كان هناك أي خطأ، اعرضه فوق الفورم وتوقف فوراً
        if (errors.length > 0) {
            errorDiv.html(errors.join('<br>')).fadeIn();
            
            // عمل Scroll خفيف ليرى المستخدم الخطأ بوضوح
            $('html, body').animate({
                scrollTop: errorDiv.offset().top - 100
            }, 300);
            return; 
        }

        // إذا كانت كل الفحوصات سليمة، نبدأ عملية الإرسال عبر Ajax
        Swal.fire({
            title: authTrans.creating_account,
            allowOutsideClick: false,
            customClass: { popup: 'swal-neon-popup', title: 'swal-neon-title' },
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: $(form).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: authTrans.account_created,
                    showConfirmButton: false,
                    timer: 1200,
                    timerProgressBar: true,
                    customClass: { popup: 'swal-neon-popup', title: 'swal-neon-title' }
                });
                setTimeout(() => {
                    window.location.href = response.redirect || '/';
                }, 1200);
            },
            error: function(xhr) {

    console.log(xhr.responseText);

    let message = 'حدث خطأ أثناء إنشاء الحساب، حاول مجدداً';

    if (xhr.responseJSON?.message) {
        message = xhr.responseJSON.message;
    }

    if (xhr.responseJSON?.errors) {
        message = Object.values(xhr.responseJSON.errors)[0][0];
    }

    Swal.close();

    errorDiv.html('• ' + message).fadeIn();
}
        });
    });
       });  






// 👁️ ميزة إظهار وإخفاء كلمة المرور عند النقر على العين
    $(document).on('click', '.toggle-password', function() {
        // جلب عنصر الإدخال المستهدف بناءً على الـ data-target
        let targetInput = $($(this).data('target'));
        
        if (targetInput.attr('type') === 'password') {
            // تحويل الحقل إلى نص عادي لرؤية الكلمة
            targetInput.attr('type', 'text');
            // تغيير شكل الأيقونة إلى العين المشطوبة
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            // إعادة الحقل إلى وضع كلمة المرور المشفرة
            targetInput.attr('type', 'password');
            // إعادة شكل الأيقونة للعين العادية
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });









    // 🔐 نسيت كلمة المرور - الخطوة 1: إرسال الكود
   // 🔐 نسيت كلمة المرور - الخطوة 1: إرسال الكود (ذهبي فخم)
    $(document).on('click', '.forgot-password-link', function(e) {
        e.preventDefault();

        Swal.fire({
            title: authTrans.reset_password,
            input: 'email',
            inputLabel: authTrans.reset_password_label,
            inputPlaceholder: 'example@mail.com',
            confirmButtonText: authTrans.send_code,
            showCancelButton: true,
            cancelButtonText: authTrans.cancel,
            showLoaderOnConfirm: true,
            customClass: {
                popup: 'swal-neon-popup',
                title: 'swal-neon-title',
                htmlContainer: 'swal-neon-text',
                confirmButton: 'swal-neon-confirm',
                cancelButton: 'swal-neon-cancel' // لتخصيص زر الإلغاء إذا رغبت
            },
            preConfirm: (email) => {
                if (!email) {
                    Swal.showValidationMessage(authTrans.enter_email);
                    return false;
                }
                return $.ajax({
                    url: '/forgot-password/send-code',
                    method: 'POST',
                    data: {
                        email: email,
                        _token: $('input[name="_token"]').val()
                    }
                }).then(function() {
                    return email; 
                }).catch(function(xhr) {
                    Swal.showValidationMessage(xhr.responseJSON.message || authTrans.send_code_failed);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showResetCodeStep(result.value);
            }
        });
    });

    // 🔑 الخطوة 2: إدخال الكود وتحديث كلمة المرور مع الفحص الذكي وكاشف الباسوورد
    function showResetCodeStep(email) {
        Swal.fire({
            icon: 'success',
            title: authTrans.code_sent_title,
            text: authTrans.code_sent_text,
            confirmButtonText: authTrans.continue,
            customClass: {
                popup: 'swal-neon-popup',
                title: 'swal-neon-title',
                htmlContainer: 'swal-neon-text',
                confirmButton: 'swal-neon-confirm'
            }
        }).then(() => {
            Swal.fire({
                title: authTrans.reset_password_title,
                // تم تعديل الـ HTML لإضافة أيقونات العين المخصصة لكشف الباسوورد
                html: `
                    <div style="margin-bottom: 15px;">
                        <input id="swal-code" class="swal2-input" placeholder="${authTrans.verification_code}" style="text-align: center;">
                    </div>
                    <div class="input-wrapper-custom" style="position: relative; margin-bottom: 15px;">
                        <input id="swal-password" type="password" class="swal2-input neon-input" placeholder="${authTrans.new_password}" style="width: 100%; padding-left: 45px; box-sizing: border-box;">
                        <i class="fas fa-eye swal-toggle-password" data-target="#swal-password" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; z-index: 10;"></i>
                    </div>
                    <div class="input-wrapper-custom" style="position: relative; margin-bottom: 15px;">
                        <input id="swal-password-confirm" type="password" class="swal2-input neon-input" placeholder="${authTrans.new_password_confirm}" style="width: 100%; padding-left: 45px; box-sizing: border-box;">
                        <i class="fas fa-eye swal-toggle-password" data-target="#swal-password-confirm" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; z-index: 10;"></i>
                    </div>
                `,
                confirmButtonText: authTrans.change_password,
                focusConfirm: false,
                showLoaderOnConfirm: true,
                customClass: {
                    popup: 'swal-neon-popup',
                    title: 'swal-neon-title',
                    confirmButton: 'swal-neon-confirm'
                },
                didOpen: () => {
                    // تفعيل كاشف كلمات المرور للـ inputs داخل SweetAlert
                    $('.swal-toggle-password').on('click', function() {
                        let target = $($(this).data('target'));
                        if (target.attr('type') === 'password') {
                            target.attr('type', 'text');
                            $(this).removeClass('fa-eye').addClass('fa-eye-slash').css('color', '#ffd700');
                        } else {
                            target.attr('type', 'password');
                            $(this).removeClass('fa-eye-slash').addClass('fa-eye').css('color', '#888');
                        }
                    });
                },
                preConfirm: () => {
                    const code = $('#swal-code').val();
                    const password = $('#swal-password').val();
                    const passwordConfirm = $('#swal-password-confirm').val();

                    if (!code || !password || !passwordConfirm) {
                        Swal.showValidationMessage(authTrans.fill_required_fields);
                        return false;
                    }

                    // 1. فحص الطول (يجب ألا تقل عن 8 خانات)
                    if (password.length < 8) {
                        Swal.showValidationMessage(authTrans.password_min_length);
                        return false;
                    }

                    // 2. فحص وجود حروف وأرقام معاً بناءً على قواعد الباك-إند
                    if (!/[a-z]/.test(password) || !/[0-9]/.test(password)) {
                        Swal.showValidationMessage(authTrans.password_require_small_number);
                        return false;
                    }

                    // 3. فحص تطابق كلمتي المرور
                    if (password !== passwordConfirm) {
                        Swal.showValidationMessage(authTrans.password_mismatch);
                        return false;
                    }

                    // إرسال البيانات بعد نجاح الفحص الداخلي
                    return $.ajax({
                        url: '/forgot-password/reset',
                        method: 'POST',
                        data: {
                            email: email,
                            code: code,
                            password: password,
                            password_confirmation: passwordConfirm,
                            _token: $('input[name="_token"]').val()
                        }
                    }).fail(function(xhr) {
                        Swal.showValidationMessage(xhr.responseJSON.message || authTrans.invalid_or_expired_code);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: authTrans.password_changed_title,
                        text: authTrans.password_changed_text,
                        confirmButtonText: authTrans.login_now,
                        customClass: {
                            popup: 'swal-neon-popup',
                            title: 'swal-neon-title',
                            htmlContainer: 'swal-neon-text',
                            confirmButton: 'swal-neon-confirm'
                        }
                    }).then(() => {
                        switchForm('login');
                    });
                }
            });
        });
    }

 // 👈 تم إغلاق $(document).ready هنا بشكل صحيح

// حدث منفصل لتهيئة الـ Redirect
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');
    if (redirect) {
        const redirectField = document.getElementById('login-redirect-field');
        if (redirectField) {
            redirectField.value = redirect;
        }
    }
});
</script>
@include('components.theme-toggle')
</body>
</html>
