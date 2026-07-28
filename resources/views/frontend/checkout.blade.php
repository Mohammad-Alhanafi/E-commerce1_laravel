<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('checkout.title') ?? 'إتمام الشراء' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body>
@include('admin.header')
<div class="checkout-container">
    <h1 class="header-gold"><i class="fas fa-shield-alt"></i> إتمام الطلب</h1>

    <div class="checkout-grid">
        <div class="card">
            <h3 style="color:#d4af37; border-bottom: 1px solid rgba(212, 175, 55, 0.2); padding-bottom: 10px;">
                <i class="fas fa-user-edit"></i> معلومات الزبون
            </h3>
            
            <form id="checkout-form"  action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <label>طريقة الحصول على الطلب</label>
                <div class="shipping-options">
                    <label class="ship-opt active" id="opt-delivery">
                        <input type="radio" name="shipping_method" value="delivery" checked>
                        <i class="fas fa-truck"></i>
                        خدمة التوصيل
                    </label>
                    <label class="ship-opt" id="opt-pickup">
                        <input type="radio" name="shipping_method" value="pickup">
                        <i class="fas fa-store"></i>
                        استلام من المحل
                    </label>
                </div>

                <div class="form-group">
                    <label>الاسم الكامل</label>
                    <input type="text" name="customer_name" placeholder="أدخل اسمك الثلاثي" required>
                </div>
                
                <div class="form-group">
                    <label>رقم الهاتف (واتساب)</label>
                    <input type="tel" name="customer_phone" placeholder="70 000 000" required>
                </div>

                           <div id="address-fields">
    <div class="form-group">
        <label>المنطقة / المدينة</label>

        @if($shipping_type === 'region')
            <select name="city" id="city-field" required>
                <option value="">-- اختر منطقتك --</option>
                @foreach($regions as $region)
                    <option value="{{ $region['name'] }}" data-fee="{{ $region['fee'] }}">
                        {{ $region['name'] }} ({{ number_format($region['fee'], 2) }} $)
                    </option>
                @endforeach
            </select>
        @else
            <input type="text" name="city" id="city-field" required>
        @endif
    </div>
    <div class="form-group">
        <label>العنوان الكامل</label>
        <textarea name="address" id="address-field" placeholder="اسم الشارع، البناية، الطابق..." required></textarea>
    </div>
</div>

                <div class="form-group">
                    <label>ملاحظات إضافية (اختياري)</label>
                    <input type="text" name="notes" placeholder="أي تفاصيل أخرى...">
                </div>
            </form>
        </div>

        <div class="card order-summary">
            <h3 style="margin-top:0;"> الفاتورة</h3>
            <div style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                @foreach($cart as $id => $item)
                <div class="item-row">
                    <span>{{ $item['name'] }} <small>(x{{ $item['quantity'] }})</small></span>
                    <span>{{ number_format($item['price'] * $item['quantity'], 2) }} $</span>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px; font-size: 14px; color: #b8a07c;">
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($subtotal, 2) }} $</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>رسوم التوصيل:</span>
                    <span id="shipping-cost-display">{{ $ship_fee == 0 ? 'مجاني' : number_format($ship_fee, 2) . ' $' }}</span>
                </div>
            </div>

            <div class="total-row">
                <span>الإجمالي:</span>
                <span id="final-total-display">{{ number_format($subtotal + $ship_fee, 2) }} $</span>
            </div>

            <button type="submit" form="checkout-form" class="confirm-btn">
                تأكيد الطلب الآن <i class="fas fa-paper-plane"></i>
            </button>

            <div class="payment-methods">
                <img src="https://wishmoney.com/wp-content/uploads/2023/02/wish-logo-1.png" alt="Wish">
                <img src="https://www.omt.com.lb/images/logo.png" alt="OMT">
                <i class="fas fa-money-bill-wave" title="الدفع عند الاستلام" style="font-size: 20px; color: #d4af37;"></i>
            </div>
        </div>
    </div>
    <br>
    


</div>

            @include("admin.footer")







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  const shippingType = "{{ $shipping_type }}";
const fixedShipFee = parseFloat("{{ $ship_fee }}"); // بتنفع لحالة fixed فقط
const subtotal = parseFloat("{{ $subtotal }}");

function currentShippingMethod() {
    return $('input[name="shipping_method"]:checked').val();
}

// السعر الحالي المفروض يظهر بالفاتورة بناءً على كل الشروط
function getActiveShippingFee() {
    if (currentShippingMethod() === 'pickup') {
        return 0;
    }

    if (shippingType === 'region') {
        const selected = $('#city-field option:selected');
        const fee = selected.length ? parseFloat(selected.data('fee')) : NaN;
        return isNaN(fee) ? 0 : fee;
    }

    // free أو fixed
    return fixedShipFee;
}

function updateInvoiceDisplay() {
    const fee = getActiveShippingFee();
    $('#shipping-cost-display').text(fee === 0 ? 'مجاني' : fee.toFixed(2) + ' $');
    $('#final-total-display').text((subtotal + fee).toFixed(2) + ' $');
}

// تبديل خيارات الشحن (توصيل / استلام)
$('input[name="shipping_method"]').on('change', function() {
    $('.ship-opt').removeClass('active');
    $(this).closest('.ship-opt').addClass('active');

    const isPickup = $(this).val() === 'pickup';
    if (isPickup) {
        $('#address-fields').slideUp();
        $('#city-field, #address-field').prop('required', false);
    } else {
        $('#address-fields').slideDown();
        $('#city-field, #address-field').prop('required', true);
    }

    updateInvoiceDisplay();
});

// لما يختار العميل منطقته (فقط بحالة shipping_type = region)
$('#city-field').on('change', function() {
    updateInvoiceDisplay();
});

// تهيئة أولية عند تحميل الصفحة
updateInvoiceDisplay();

$('#checkout-form').on('submit', function(e) {
    e.preventDefault(); 

    let formData = $(this).serialize();

    Swal.fire({
        title: 'جاري تسجيل طلبك...',
        background: '#121212',
        color: '#fff',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    $.ajax({
        url: "{{ route('checkout.store') }}", 
        type: "POST",
        data: formData,
        dataType: 'json', 
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم تسجيل طلبك بنجاح!',
                    text: 'رقم طلبك هو #' + response.order_id + '. كيف تحب أن تتابع الطلب؟',
                    showCancelButton: true, 
                    confirmButtonColor: '#25D366', // أخضر واتساب
                    cancelButtonColor: '#d4af37',  // ذهبي للمتجر
                    confirmButtonText: '<i class="fab fa-whatsapp"></i> متابعة عبر واتساب',
                    cancelButtonText: 'كلا',
                    background: '#121212',
                    color: '#fff',
                }).then((result) => { 
                    if (result.isConfirmed) {
                        window.location.href = response.whatsapp_url;
                    } 
                    else {
                        Swal.fire({
                            title: 'شكراً لثقتك!',
                            text: 'تم حفظ طلبك، سيقوم فريقنا بالتواصل معك قريباً.',
                            icon: 'info',
                            background: '#121212',
                            color: '#fff',
                            confirmButtonColor: '#d4af37'
                        }).then(() => {
                            window.location.href = '/'; // العودة للرئيسية
                        });
                    }
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'عذراً...',
                text: 'حدث خطأ أثناء معالجة الطلب، حاول مرة أخرى.',
                background: '#121212',
                color: '#fff'
            });
        }
    });
});



document.querySelectorAll('input[name="shipping_method"]').forEach((elem) => {
    elem.addEventListener("change", function(event) {
        var item = event.target.value;
        const addressFields = document.getElementById('address-fields');
        if (item === 'pickup') {
            addressFields.style.display = 'none';
            document.getElementById('city-field').required = false;
            document.getElementById('address-field').required = false;
        } else {
            addressFields.style.display = 'block';
            document.getElementById('city-field').required = true;
            document.getElementById('address-field').required = true;
        }
    });
});
</script>
@include('components.theme-toggle')
</body>
</html>