<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('checkout.title') }}</title>
    <meta name="description" content="{{ __('checkout.meta_description') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body>
@include('admin.header')
<div class="checkout-container">
    <h1 class="header-gold"><i class="fas fa-shield-alt"></i> {{ __('checkout.title') }}</h1>

    <div class="checkout-grid">
        <div class="card">
            <h3 style="color: var(--primary-color); border-bottom: 1px solid color-mix(in srgb, var(--primary-color) 22%, transparent); padding-bottom: 10px;">
                <i class="fas fa-user-edit"></i> {{ __('checkout.customer_info') }}
            </h3>
            
            <form id="checkout-form"  action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <label>{{ __('checkout.delivery_method') }}</label>
                <div class="shipping-options">
                    <label class="ship-opt active" id="opt-delivery">
                        <input type="radio" name="shipping_method" value="delivery" checked>
                        <i class="fas fa-truck"></i>
                        {{ __('checkout.delivery_service') }}
                    </label>
                    <label class="ship-opt" id="opt-pickup">
                        <input type="radio" name="shipping_method" value="pickup">
                        <i class="fas fa-store"></i>
                        {{ __('checkout.pickup_store') }}
                    </label>
                </div>

                <div class="form-group">
                    <label>{{ __('checkout.name') }}</label>
                    <input type="text" name="customer_name" placeholder="{{ __('checkout.name_placeholder') }}" required>
                </div>
                
                <div class="form-group">
                    <label>{{ __('checkout.phone_whatsapp') }}</label>
                    <input type="tel" name="customer_phone" placeholder="{{ __('checkout.phone_placeholder') }}" required>
                </div>

                           <div id="address-fields">
    <div class="form-group">
        <label>{{ __('checkout.region_city') }}</label>

        @if($shipping_type === 'region')
            <select name="city" id="city-field" required>
                <option value="">{{ __('checkout.choose_region') }}</option>
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
        <label>{{ __('checkout.full_address') }}</label>
        <textarea name="address" id="address-field" placeholder="{{ __('checkout.address_placeholder') }}" required></textarea>
    </div>
</div>

                <div class="form-group">
                    <label>{{ __('checkout.additional_notes') }}</label>
                    <input type="text" name="notes" placeholder="{{ __('checkout.notes_placeholder_extended') }}">
                </div>
            </form>
        </div>

        <div class="card order-summary">
            <h3 style="margin-top:0; color: var(--heading-color, var(--text-color));">{{ __('checkout.invoice') }}</h3>
            <div style="max-height: 250px; overflow-y: auto; padding-inline-end: 5px;">
                @foreach($cart as $id => $item)
                <div class="item-row">
                    <span>{{ $item['name'] }} <small>(x{{ $item['quantity'] }})</small></span>
                    <span>{{ number_format($item['price'] * $item['quantity'], 2) }} $</span>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px; font-size: 14px; color: var(--text-muted);">
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span>{{ __('checkout.subtotal') }}:</span>
                    <span>{{ number_format($subtotal, 2) }} $</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>{{ __('checkout.shipping_fee') }}:</span>
                    <span id="shipping-cost-display">{{ $ship_fee == 0 ? __('cart.free') : number_format($ship_fee, 2) . ' $' }}</span>
                </div>
            </div>

            <div class="total-row">
                <span>{{ __('checkout.total') }}:</span>
                <span id="final-total-display">{{ number_format($subtotal + $ship_fee, 2) }} $</span>
            </div>

            <button type="submit" form="checkout-form" class="confirm-btn">
                {{ __('checkout.place_order_now') }} <i class="fas fa-paper-plane"></i>
            </button>

            <div class="payment-methods">
                <img src="https://wishmoney.com/wp-content/uploads/2023/02/wish-logo-1.png" alt="Wish">
                <img src="https://www.omt.com.lb/images/logo.png" alt="OMT">
                <i class="fas fa-money-bill-wave" title="{{ __('checkout.cash_on_delivery') }}" style="font-size: 20px; color: var(--primary-color);"></i>
            </div>
        </div>
    </div>
    <br>
    


</div>

        @include("admin.footer")

@include('components.theme-toggle')

@php
    $translations = [
        'free'                    => __('cart.free'),
        'saving_order'            => __('checkout.saving_order'),
        'order_saved'             => __('checkout.order_saved'),
        'order_saved_with_number' => __('checkout.order_saved_with_number'),
        'whatsapp_followup'       => __('checkout.whatsapp_followup'),
        'skip'                    => __('checkout.skip_followup'),
        'thanks_title'            => __('checkout.thanks_title'),
        'thanks_text'             => __('checkout.thanks_text'),
        'error_title'             => __('checkout.error_title'),
        'error_text'              => __('checkout.error_text'),
    ];
@endphp

<script>
const checkoutTrans = {!! json_encode($translations, JSON_UNESCAPED_UNICODE) !!};

const shippingType   = "{{ $shipping_type }}";
const fixedShipFee   = parseFloat("{{ $ship_fee }}");
const subtotalAmount = parseFloat("{{ $subtotal }}");

function currentShippingMethod() {
    return $('input[name="shipping_method"]:checked').val();
}

function getActiveShippingFee() {
    if (currentShippingMethod() === 'pickup') {
        return 0;
    }

    if (shippingType === 'region') {
        const selected = $('#city-field option:selected');
        const fee = selected.length ? parseFloat(selected.data('fee')) : NaN;
        return isNaN(fee) ? 0 : fee;
    }

    return fixedShipFee;
}

function updateInvoiceDisplay() {
    const fee = getActiveShippingFee();
    $('#shipping-cost-display').text(fee === 0 ? checkoutTrans.free : fee.toFixed(2) + ' $');
    $('#final-total-display').text((subtotalAmount + fee).toFixed(2) + ' $');
}

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

$('#city-field').on('change', function() {
    updateInvoiceDisplay();
});

updateInvoiceDisplay();

$('#checkout-form').on('submit', function(e) {
    e.preventDefault();

    let formData = $(this).serialize();

    Swal.fire({
        title: checkoutTrans.saving_order,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
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
                    title: checkoutTrans.order_saved,
                    // إصلاح: ملفات الترجمة تستخدم :number وليس :id
                    text: checkoutTrans.order_saved_with_number.replace(':number', response.order_id),
                    showCancelButton: true,
                    confirmButtonText: '<i class="fab fa-whatsapp"></i> ' + checkoutTrans.whatsapp_followup,
                    cancelButtonText: checkoutTrans.skip,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = response.whatsapp_url;
                    } else {
                        Swal.fire({
                            title: checkoutTrans.thanks_title,
                            text: checkoutTrans.thanks_text,
                            icon: 'info',
                        }).then(() => {
                            window.location.href = '/';
                        });
                    }
                });
            }
        },
        error: function(xhr) {
            let msg = checkoutTrans.error_text;
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errs = Object.values(xhr.responseJSON.errors).flat();
                if (errs.length) msg = errs.join('\n');
            }
            Swal.fire({
                icon: 'error',
                title: checkoutTrans.error_title,
                text: msg
            });
        }
    });
});
</script>
</body>
</html>
