<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">

        
</head>
<body>

@include('admin.header')

<div class="container">

    <div class="product-container">
        <div class="row g-0">
            <div class="col-lg-7 p-4 p-lg-5">
                @php
                    // 1. جلب الستايل المختار من صفحة الـ Settings بالآدمن
                    $settingKey = 'category_style_' . $product->category_id;
                    $adminStyle = $settings[$settingKey] ?? null;

                    // 2. إذا الآدمن ما اختار شي، بنرجع للنظام القديم (فحص الكلمات الدالة)
                    if (!$adminStyle) {
                        $clothingKeywords = ['عبايات', 'ملابس', 'ثياب', 'أزياء', 'لباس','عباءات', 'ثوب'];
                        $isClothing = false;
                        foreach ($clothingKeywords as $keyword) {
                            if (str_contains($product->category->name, $keyword)) {
                                $isClothing = true;
                                break;
                            }
                        }
                        $finalStyle = $isClothing ? 'abaya-frame' : 'general-frame';
                    } else {
                        // إذا الآدمن اختار ستايل يدوي، بنعتمده فوراً
                        $finalStyle = $adminStyle;
                    }
                @endphp

                {{-- الكانتينر الأصلي هلق صار "جوكر" بياخد الستايل من الآدمن أو التلقائي --}}
                <div class="main-image-container {{ $finalStyle }}">
                    <img src="{{ $product->image_url }}" class="main-img" id="mainProductImage">
                </div>
                
                <div class="thumbnail-container mt-3"></div>
            </div>




<div class="col-lg-5 p-4 p-lg-5">

    <h1 class="product-title text-gold">{{ $product->name }}</h1>

    <div id="stock-{{ $product->id }}" style="margin:10px 0; color: var(--primary-color); font-weight:bold;">
        {{ __('products.qty_available', ['qty' => $product->stock]) }}
    </div>

    <div class="price-card-box mt-3 mb-4 d-flex align-items-center gap-3">
        <div style="background: color-mix(in srgb, var(--primary-color) 10%, transparent); border: 1px solid var(--primary-color); padding: 10px 20px; border-radius: 8px; display: inline-block;">
            <span style="color: var(--primary-color); font-size: 18px; margin-inline-end: 5px;">$</span>
            <span id="current_price" style="font-size: 28px; font-weight: bold; color: var(--text-color);">
                {{ number_format($product->price, 2) }}
            </span>
        </div>
        <div id="variant-notes-top" class="badge-sale" style="display:none; background-color: var(--danger-color); color: #fff; padding: 5px 12px; border-radius: 20px;"></div>
    </div>

    <p class="text-light-50">{{ $product->description }}</p>

    {{-- الحقل المخفي المفقود سابقاً - يخزن دائماً الـ variant المختار --}}
    <input type="hidden" id="selected_variant_id" value="">

    <div class="variants-section mb-4">

        {{-- 1. منتجات الألوان --}}
        @if($product->variants->whereNotNull('color')->where('color', '!=', '')->where('color', '!=', '#000000')->where('color', '!=', 'بدون لون')->count() > 0)
            <div class="d-flex flex-wrap gap-3 mb-4">
                @foreach($product->variants->unique('color') as $variant)
                    <div class="text-center">
                        <button type="button" class="color-btn"
                                data-variant-id="{{ $variant->id }}"
                                data-stock="{{ $variant->stock }}"
                                data-price="{{ number_format($variant->variant_price, 2) }}"
                                data-notes="{{ $variant->notes }}"
                                onclick="filterModelsByColor('{{ trim($variant->color) }}', '{{ $variant->image_url ?? $product->image_url }}', this)"
                                style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--border-color); background-color: {{ $variant->color }}; cursor: pointer; transition: 0.3s;">
                        </button>
                        <div class="stock-label" style="font-size: 10px; color: var(--text-muted); margin-top: 4px;">
                            @if($variant->stock <= 0)
                                <span class="text-danger">{{ __('products.out_of_stock') }}</span>
                            @else
                                {{ __('products.qty_available', ['qty' => $variant->stock]) }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        {{-- 2. منتجات الأنواع العامة (عسل، زيوت..) --}}
        @elseif(isset($groupedAttributes) && $groupedAttributes->isNotEmpty())

            @foreach($groupedAttributes as $attributeName => $values)
                <h3 class="text-gold small mb-3">{{ $attributeName }}:</h3>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach($values as $value)
                        @php
                            // نجيب الـ variant المطابق فعلياً لهاي القيمة، مش أول واحد بس (كان هاد الخطأ الأساسي)
                            $matchedVariant = $product->variants->first(function($v) use ($value) {
                                return $v->attributeValues->contains('value', $value);
                            }) ?? $product->variants->first();

                            $stock   = $matchedVariant ? $matchedVariant->stock : 0;
                            $v_id    = $matchedVariant ? $matchedVariant->id : '';
                            $v_price = $matchedVariant ? number_format($matchedVariant->variant_price, 2) : number_format($product->price, 2);
                            $v_notes = $matchedVariant ? $matchedVariant->notes : '';
                        @endphp

                        <button type="button" class="btn btn-outline-gold px-3 py-2 attribute-btn"
                                data-variant-id="{{ $v_id }}"
                                data-stock="{{ $stock }}"
                                data-price="{{ $v_price }}"
                                data-notes="{{ $v_notes }}"
                                onclick="selectGeneralAttribute('{{ $attributeName }}', '{{ (string)$value }}', this)"
                                style="border: 1px solid var(--primary-color); color: var(--primary-color); background: transparent; min-width: 60px;">
                            {{ $value }}
                        </button>
                    @endforeach
                </div>
            @endforeach

            <div id="stock-wrapper" class="mt-3" style="display: none; transition: 0.3s;">
                <div id="stock-display" style="font-size: 0.95rem; font-weight: bold; display: flex; align-items: center; gap: 8px;"></div>
            </div>
        @endif
    </div>

    <div id="size-stock-wrapper" class="mt-2 mb-3" style="display: none;">
    <div id="stock-status-container" style="font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 6px;"></div>
</div>

    <div id="sizes-container" class="mb-4">
        @foreach($product->variants as $variant)
            @foreach($variant->attributeValues as $attributeValue)
                <button type="button" class="btn btn-outline-light size-item"
                       data-variant-id="{{ $variant->id }}"
                        data-color="{{ trim($variant->color) }}"
                        data-stock="{{ $variant->stock }}"
                        data-price="{{ number_format($variant->variant_price, 2) }}"
                        data-notes="{{ $variant->notes }}"
                        style="display: none; padding: 8px 15px; font-size: 14px; margin: 2px; border: 1px solid var(--border-color); border-radius: 6px;"
                        onclick="activateSize(this)">
                    {{ $attributeValue->value }}
                </button>
            @endforeach
        @endforeach
    </div>

    <div class="cart-section mt-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div id="addToCart" class="cart-box disabled" style="flex-grow: 2; opacity: 0.5; cursor: not-allowed; transition: 0.3s;">
                <div class="cart-left"><i class="fas fa-shopping-bag cart-main-icon"></i></div>
                <div class="cart-center"><div class="cart-title">{{ __('products.add_to_cart') }}</div></div>
            </div>

            <div class="quantity-control d-flex align-items-center rounded p-1" style="background: var(--section-bg); border: 1px solid var(--border-color); height: 50px;">
                <button class="btn px-3" id="decreaseQuantity" style="font-weight: bold; color: var(--text-color);">-</button>
                <input type="text" class="form-control bg-transparent border-0 text-center p-0"
                       value="1" id="productQuantity" readonly style="width: 40px; font-weight: bold;">
                <button class="btn px-3" id="increaseQuantity" style="font-weight: bold; color: var(--text-color);">+</button>
            </div>
        </div>

        <div id="notes-near-cart" class="mb-4" style="font-size: 14px; color: var(--primary-color); font-weight: bold; min-height: 20px;"></div>

        <div class="accordion accordion-flush" id="productExtra">
            <div class="accordion-item bg-transparent" style="border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
                <h2 class="accordion-header">
                    <button id="careBtn" class="accordion-button bg-transparent text-gold collapsed px-0 shadow-none"
                            type="button" onclick="toggleCareAccordion(event)"
                            style="font-size: 14px; color: var(--primary-color); cursor: pointer; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <span><i class="fas fa-info-circle me-2"></i> {{ __('products.care_details') }}</span>
                        <i id="careArrow" class="fas fa-chevron-down" style="transition: transform 0.3s ease; font-size: 12px; margin-inline-start: 10px;"></i>
                    </button>
                </h2>
                <div id="careTab" class="care-tab-content" style="max-height: 0; overflow: hidden; opacity: 0; transition: max-height 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), opacity 0.3s ease; color: var(--text-muted);">
                    <div class="px-0 py-3" style="font-size: 13px; line-height: 1.6; white-space: pre-line;">
                        {{ $product->care_instructions ?: __('admin.no_care_instructions') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="variants-store" style="display:none;">
    @foreach($product->variants as $variant)
        <div class="v-data" 
             data-id="{{ $variant->id }}"
             data-price="{{ number_format($variant->variant_price, 2) }}"
             data-stock="{{ $variant->stock }}"
             data-notes="{{ $variant->notes }}"
             data-values="{{ $variant->attributeValues->pluck('value')->join('|') }}">
        </div>
    @endforeach
</div>


</div>













<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">











@php 
    $layout     = $settings['related_products_layout'] ?? 'curved-slider';
    $animClass  = $settings['title_animation_style'] ?? 'blur-effect';
    $titleColor = !empty($settings['title_anim_color']) ? $settings['title_anim_color'] : null;
    $titleStyle = $titleColor ? "color: {$titleColor} !important; --title-glow-color: {$titleColor}; text-shadow: 0 0 14px {$titleColor}66;" : "";
@endphp

<section class="related-products mt-5 mb-5">
    <div class="container">
        <h3 class="section-title-gold {{ $animClass }}" style="{{ $titleStyle }}">{{ __('products.related_may_like') }}</h3>

        @if($layout === 'curved-slider')
            <div class="swiper relatedSwiper">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $related)
                        <div class="swiper-slide">
                            <div class="related-card-curved">
                                <a href="{{ url('/item/' . $related->id) }}" class="text-decoration-none h-100 d-flex flex-column">
                                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                                    <div class="related-info">
                                        <h6>{{ $related->name }}</h6>
                                        <p>{{ number_format($related->price, 2) }} $</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        @elseif($layout === 'horizontal-scroll')
            <div class="related-horizontal-scroll mt-4">
                @foreach($relatedProducts as $related)
                    <div class="related-card-item">
                        <div class="related-card-curved">
                            <a href="{{ url('/item/' . $related->id) }}" class="text-decoration-none h-100 d-flex flex-column">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                                <div class="related-info">
                                    <h6>{{ $related->name }}</h6>
                                    <p>{{ number_format($related->price, 2) }} $</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Grid View --}}
            <div class="row mt-4">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="related-card-curved" style="height: 380px;">
                            <a href="{{ url('/item/' . $related->id) }}" class="text-decoration-none h-100 d-flex flex-column">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                                <div class="related-info">
                                    <h6>{{ $related->name }}</h6>
                                    <p>{{ number_format($related->price, 2) }} $</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<div>
  @include("admin.footer")  
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@php
    $productTrans = [
        'add_to_cart' => __('products.add_to_cart'),
        'out_of_stock' => __('products.out_of_stock'),
        'adding_to_cart' => __('products.adding_to_cart'),
        'choose_option_first_title' => __('products.choose_option_first_title'),
        'choose_option_first_text' => __('products.choose_option_first_text'),
        'ok' => __('products.ok'),
        'added_to_cart' => __('products.added_to_cart'),
        'add_to_cart_error' => __('products.add_to_cart_error'),
        'select_size' => __('products.choose_size'),
        'available_qty' => __('products.available_qty'),
        'remaining_qty' => __('products.remaining_qty'),
        'remaining_qty_hurry' => __('products.remaining_qty_hurry'),
        'stock_out' => __('products.out_of_stock'),
        'remove_confirm_title' => __('cart.remove_confirm_title'),
        'remove_confirm_text' => __('cart.remove_confirm'),
        'remove_confirm_button' => __('cart.remove_confirm_button'),
        'cancel' => __('buttons.cancel'),
        'remove_product_title' => __('cart.remove_product_title'),
        'remove_product_text' => __('cart.remove_product_text'),
        'removed' => __('cart.removed'),
        'choose_size_cta' => __('products.choose_size_cta'),
    ];
@endphp

<script>
window.ProductDetailTrans = @json($productTrans);
let selectedVariantId = null;
let currentColor = '';

// ============ نقطة مركزية واحدة لتحديث كل شي مرتبط بالـ variant المختار ============
function selectVariant(variantId, price, stock, notes) {
    selectedVariantId = variantId || null;
    $('#selected_variant_id').val(variantId || '');

    if (price) {
        document.getElementById('current_price').innerText = price;
    }

    updateNotesDisplay(notes);
    syncAddToCartButton(stock);
}

function updateNotesDisplay(notes) {
    const topSale = document.getElementById('variant-notes-top');
    const bottomFire = document.getElementById('notes-near-cart');

    if (topSale) topSale.style.display = 'none';
    if (bottomFire) bottomFire.innerHTML = '';

    if (notes && notes.trim() !== "") {
        const isSale = notes.toLowerCase().includes('sale') || notes.includes('خصم') || notes.includes('عرض');
        if (isSale && topSale) {
            topSale.innerText = notes;
            topSale.style.display = 'block';
        } else if (bottomFire) {
            bottomFire.innerHTML = `<span>🔥</span> ${notes}`;
        }
    }
}

// المصدر الوحيد لتفعيل/تعطيل زر السلة - يعتمد على المخزون الحقيقي
function syncAddToCartButton(stock) {
    const cartBox = document.getElementById('addToCart');
    if (!cartBox) return;

    const stockInt = parseInt(stock);
    const hasVariant = !!selectedVariantId;

    if (hasVariant && stockInt > 0) {
        cartBox.classList.remove('disabled');
        cartBox.style.opacity = "1";
        cartBox.style.cursor = "pointer";
        cartBox.querySelector('.cart-title').innerText = window.ProductDetailTrans.add_to_cart;
    } else {
        cartBox.classList.add('disabled');
        cartBox.style.opacity = "0.5";
        cartBox.style.cursor = "not-allowed";
        cartBox.querySelector('.cart-title').innerText = (hasVariant && stockInt <= 0) ? window.ProductDetailTrans.out_of_stock : window.ProductDetailTrans.add_to_cart;
        if (stockInt <= 0) selectedVariantId = null;
    }
}

// ============ منتجات الألوان ============
function filterModelsByColor(color, imageUrl, btn) {
    currentColor = color.trim();

    const mainImg = document.getElementById('mainProductImage');
    if (mainImg && imageUrl) mainImg.src = imageUrl;

    document.querySelectorAll('.color-btn').forEach(b => b.style.boxShadow = 'none');
    btn.style.boxShadow = '0 0 0 3px var(--primary-color)';

    const sizes = document.querySelectorAll('.size-item');
    let matches = [];

    sizes.forEach(s => {
        if (s.getAttribute('data-color') === currentColor) {
            s.style.display = 'inline-block';
            matches.push(s);
        } else {
            s.style.display = 'none';
        }
    });

    const sectionSize = document.querySelector('.section-size');

    if (matches.length > 0) {
        if (sectionSize) sectionSize.style.display = 'block';
        document.querySelectorAll('.size-item').forEach(s => {
            s.style.background = 'transparent';
            s.style.color = 'var(--text-color)';
            s.style.borderColor = 'var(--border-color)';
        });
        selectVariant(null, null, 0, ''); // نصفر لحد ما يختار مقاس
    } else {
        if (sectionSize) sectionSize.style.display = 'none';
        selectVariant(
            btn.getAttribute('data-variant-id'),
            btn.getAttribute('data-price'),
            btn.getAttribute('data-stock'),
            btn.getAttribute('data-notes')
        );
    }
}

// ============ اختيار المقاس بعد اللون ============
function activateSize(btn) {
    document.querySelectorAll('.size-item').forEach(s => {
        s.style.background = 'transparent';
        s.style.color = 'var(--text-color)';
        s.style.borderColor = 'var(--border-color)';
    });

    btn.style.background = 'color-mix(in srgb, var(--primary-color) 18%, transparent)';
    btn.style.color = 'var(--primary-color)';
    btn.style.borderColor = 'var(--primary-color)';

    const stock = btn.getAttribute('data-stock');
    const stockContainer = document.getElementById('stock-status-container');
    const stockWrapper = document.getElementById('size-stock-wrapper');

    if (stockContainer) {
        const stockInt = parseInt(stock) || 0;
        let icon, color, text;
        const trans = window.ProductDetailTrans || {};

        if (stockInt > 5) { 
            icon = '<i class="fas fa-boxes"></i>'; 
            color = 'var(--primary-color, #d4af37)'; 
            const template = trans.available_qty || 'متوفر: :qty';
            text = template.replace(':qty', stockInt); 
        } else if (stockInt > 0) { 
            icon = '<i class="fas fa-exclamation-triangle"></i>'; 
            color = '#ffc107'; 
            const template = trans.remaining_qty || '⚠️ الكمية المتبقية: :qty';
            text = template.replace(':qty', stockInt); 
        } else { 
            icon = '<i class="fas fa-times-circle"></i>'; 
            color = 'var(--danger-color, #ff4d4d)'; 
            text = trans.stock_out || 'نفدت الكمية'; 
        }

        stockContainer.innerHTML = `${icon} <span style="color:${color}">${text}</span>`;
        if (stockWrapper) stockWrapper.style.display = 'block'; 
    }

    selectVariant(
        btn.getAttribute('data-variant-id'),
        btn.getAttribute('data-price'),
        stock,
        btn.getAttribute('data-notes')
    );
}

// ============ منتجات الأنواع العامة (عسل، زيوت..) ============
function selectGeneralAttribute(name, value, element) {
    $(element).parent().find('button').css({ 'background-color': 'transparent', 'color': 'var(--primary-color)', 'border-color': 'var(--primary-color)' });
    $(element).css({ 'background-color': 'var(--primary-color)', 'color': 'var(--btn-text-color, #000)' });

    const stock = element.getAttribute('data-stock');
    const display = document.getElementById('stock-display');
    const wrapper = document.getElementById('stock-wrapper');
    const trans = window.ProductDetailTrans || {};

    if (display && wrapper) {
        wrapper.style.display = 'block';
        const stockNum = parseInt(stock) || 0;
        let icon, color, text;

        if (stockNum > 5) { 
            icon = '<i class="fas fa-boxes"></i>'; 
            color = 'var(--text-muted)'; 
            const template = trans.available_qty || 'متوفر: :qty';
            text = template.replace(':qty', stockNum); 
        } else if (stockNum > 0) { 
            icon = '<i class="fas fa-exclamation-triangle"></i>'; 
            color = 'var(--warning-color)'; 
            const template = trans.remaining_qty_hurry || trans.remaining_qty || 'سارع للشراء، متبقي :qty قطعة';
            text = template.replace(':qty', stockNum); 
        } else { 
            icon = '<i class="fas fa-times-circle"></i>'; 
            color = 'var(--danger-color)'; 
            text = trans.stock_out || 'نفدت الكمية'; 
        }

        display.style.color = color;
        display.innerHTML = `${icon} <span class="ms-1">${text}</span>`;
        $(wrapper).hide().fadeIn(300);
    }

    selectVariant(
        element.getAttribute('data-variant-id'),
        element.getAttribute('data-price'),
        stock,
        element.getAttribute('data-notes')
    );
}

function filterSizesByModel(modelName, btn) {
    document.querySelectorAll('.model-btn').forEach(m => {
        m.style.background = 'transparent';
        m.style.color = 'var(--primary-color)';
    });
    btn.style.background = 'var(--primary-color)';
    btn.style.color = 'var(--btn-text-color, #000)';

    let hasSizes = false;
    document.querySelectorAll('.size-item').forEach(s => {
        const isMatch = s.getAttribute('data-color') === currentColor && 
                        s.getAttribute('data-model') === modelName;
        if (isMatch) {
            s.style.display = 'inline-block';
            hasSizes = true;
        } else {
            s.style.display = 'none';
        }
        s.style.background = 'transparent';
        s.style.color = 'var(--text-color)';
    });

    const secSize = document.querySelector('.section-size');
    if (secSize) secSize.style.display = hasSizes ? 'block' : 'none';
    
    const cartBtn = document.getElementById('addToCart');
    if (cartBtn) {
        const cartTitle = cartBtn.querySelector('.cart-title');
        if (cartTitle) {
            cartTitle.innerText = window.ProductDetailTrans.choose_size_cta;
        } else {
            cartBtn.innerText = window.ProductDetailTrans.choose_size_cta;
        }
    }
}

// ============ الضغط على "أضف للسلة" ============
$(document).on('click', '#addToCart', function(e) {
    e.preventDefault();

    if ($(this).hasClass('disabled') || !selectedVariantId) {
        Swal.fire({
            icon: 'error',
            iconColor: 'var(--danger-color)',
            title: window.ProductDetailTrans.choose_option_first_title,
            text: window.ProductDetailTrans.choose_option_first_text,
            confirmButtonText: window.ProductDetailTrans.ok,
            customClass: {
                popup: 'swal-theme-popup',
                title: 'swal-theme-title',
                htmlContainer: 'swal-theme-text',
                confirmButton: 'swal-theme-confirm',
                cancelButton: 'swal-theme-cancel'
            }
        });
        return;
    }

    let quantity = $('#productQuantity').val() || 1;

    $.ajax({
        url: "{{ route('cart.add') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            variant_id: selectedVariantId,
            quantity: quantity
        },
        beforeSend: function() {
            $('.cart-title').text(window.ProductDetailTrans.adding_to_cart);
        },
        success: function(response) {
            $('.cart-title').text(window.ProductDetailTrans.add_to_cart);
            if (response.success) {
                $('#cart-count, #side-cart-count, .cart-count').text(response.cart_count);
                $('#cart-items-content').html(response.cart_html);
                $('#side-cart-total, #side-cart-subtotal, #cart-total-amount').text(response.total_price);

                if (typeof window.updateSideCartCheckoutButton === 'function') {
                    window.updateSideCartCheckoutButton(response.cart_count);
                }

                if (typeof window.openGlobalCart === 'function') {
                    window.openGlobalCart();
                } else {
                    var sideCart = document.getElementById('side-cart');
                    var overlay  = document.getElementById('cart-overlay');
                    if (sideCart) sideCart.style.right = '0';
                    if (overlay) {
                        overlay.style.display = 'block';
                        setTimeout(function() { overlay.style.opacity = '1'; }, 10);
                    }
                }

                Swal.fire({ 
                    icon: 'success', 
                    title: window.ProductDetailTrans.added_to_cart, 
                    timer: 1500, 
                    showConfirmButton: false, 
                    toast: true, 
                    position: 'top-end' 
                });
            }
        },
        error: function(xhr) {
            $('.cart-title').text(window.ProductDetailTrans.add_to_cart);
            Swal.fire({ 
                icon: 'error', 
                title: window.ProductDetailTrans.choose_option_first_title, 
                text: window.ProductDetailTrans.add_to_cart_error 
            });
        }
    });
});

// ============ التحكم بالكمية والأحداث ============
document.addEventListener('DOMContentLoaded', function() {

    // أزرار زيادة ونقصان الكمية (مربوطة مرة واحدة فقط)
    const incBtn = document.getElementById('increaseQuantity');
    const decBtn = document.getElementById('decreaseQuantity');

    if (incBtn) {
        incBtn.addEventListener('click', function() {
            let qtyInput = document.getElementById('productQuantity');
            if (qtyInput) qtyInput.value = parseInt(qtyInput.value || 1) + 1;
        });
    }

    if (decBtn) {
        decBtn.addEventListener('click', function() {
            let qtyInput = document.getElementById('productQuantity');
            if (qtyInput) {
                let currentQty = parseInt(qtyInput.value || 1);
                if (currentQty > 1) qtyInput.value = currentQty - 1;
            }
        });
    }

    // فتح وإغلاق السلة الجانبية
    const closeBtn = document.getElementById('close-cart');
    const overlay = document.getElementById('cart-overlay');
    const sideCart = document.getElementById('side-cart');

    function openCart() {
        if (sideCart) sideCart.style.right = '0';
        if (overlay) {
            overlay.style.display = 'block';
            setTimeout(() => { overlay.style.opacity = '1'; }, 10);
        }
    }

    function closeCart() {
        if (sideCart) sideCart.style.right = '-450px';
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => { overlay.style.display = 'none'; }, 400);
        }
    }

    document.querySelectorAll('#open-cart, .cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this.closest('#addToCart')) {
                e.preventDefault();
                openCart();
            }
        });
    });

    if (closeBtn) closeBtn.onclick = closeCart;
    if (overlay) overlay.onclick = closeCart;

    // Swiper - Related Products
    if (typeof Swiper !== 'undefined' && document.querySelector('.relatedSwiper')) {
        new Swiper(".relatedSwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            speed: 600,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            coverflowEffect: {
                rotate: 25,
                stretch: -10,
                depth: 120,
                modifier: 1,
                slideShadows: true,
            },
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    }
});

// ============ الحالة الابتدائية عند تحميل الصفحة ============
$(document).ready(function() {
    const hasColorVariants = $('.color-btn').length > 0;
    const hasAttributeButtons = $('.attribute-btn').length > 0;

    if (!hasColorVariants && !hasAttributeButtons) {
        const firstVariant = @json($product->variants->first());
        if (firstVariant) {
            selectVariant(
                firstVariant.id,
                Number(firstVariant.variant_price).toFixed(2),
                firstVariant.stock,
                firstVariant.notes
            );
        } else {
            selectVariant('', "{{ number_format($product->price, 2) }}", {{ $product->stock }}, '');
        }
    }

    // تحديث وحذف السلة عبر Ajax
    $(document).off('click', '.update-cart').on('click', '.update-cart', function (e) {
        e.preventDefault();
        e.stopPropagation(); 
        
        let id = $(this).data('id');
        let quantity = parseInt($(this).data('quantity'));

        if (quantity <= 0) {
            Swal.fire({
                title: window.ProductDetailTrans.remove_confirm_title,
                text: window.ProductDetailTrans.remove_confirm_text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: window.ProductDetailTrans.remove_confirm_button,
                cancelButtonText: window.ProductDetailTrans.cancel,
                customClass: {
                    popup: 'swal-theme-popup',
                    title: 'swal-theme-title',
                    htmlContainer: 'swal-theme-text',
                    confirmButton: 'swal-theme-confirm',
                    cancelButton: 'swal-theme-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) removeProduct(id);
            });
        } else {
            updateQuantity(id, quantity);
        }
    });

    $(document).off('click', '.remove-from-cart').on('click', '.remove-from-cart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).data('id');

        Swal.fire({
            title: window.ProductDetailTrans.remove_product_title,
            text: window.ProductDetailTrans.remove_product_text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: window.ProductDetailTrans.remove_confirm_button,
            cancelButtonText: window.ProductDetailTrans.cancel,
            customClass: {
                popup: 'swal-theme-popup',
                title: 'swal-theme-title',
                htmlContainer: 'swal-theme-text',
                confirmButton: 'swal-theme-confirm',
                cancelButton: 'swal-theme-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) removeProduct(id);
        });
    });

    function updateQuantity(id, qty) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: "patch",
            data: { _token: '{{ csrf_token() }}', id: id, quantity: qty },
            success: function (response) {
                if(response.success) {
                    $('#cart-items-content').html(response.cart_html);
                    $('#cart-total-amount, #side-cart-total, #side-cart-subtotal').text(response.total);
                    $('#cart-count, #side-cart-count').text(response.cart_count);
                }
            }
        });
    }

    function removeProduct(id) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: "DELETE",
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function (response) {
                if(response.success) {
                    $('#cart-items-content').html(response.cart_html);
                    $('#cart-total-amount, #side-cart-total, #side-cart-subtotal').text(response.total);
                    $('#cart-count, #side-cart-count').text(response.cart_count);
                    Swal.fire({
                        title: window.ProductDetailTrans.removed,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'swal-theme-popup',
                            title: 'swal-theme-title',
                            htmlContainer: 'swal-theme-text',
                            confirmButton: 'swal-theme-confirm',
                            cancelButton: 'swal-theme-cancel'
                        }
                    });
                }
            }
        });
    }
});

function toggleCareAccordion(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const tab = document.getElementById('careTab');
    const arrow = document.getElementById('careArrow');
    const btn = document.getElementById('careBtn');
    if (!tab) return;

    const isOpen = tab.classList.contains('active-care-tab');
    if (isOpen) {
        tab.classList.remove('active-care-tab');
        tab.style.maxHeight = '0px';
        tab.style.opacity = '0';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
        if (btn) btn.classList.add('collapsed');
    } else {
        tab.classList.add('active-care-tab');
        tab.style.maxHeight = (tab.scrollHeight + 40) + 'px';
        tab.style.opacity = '1';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
        if (btn) btn.classList.remove('collapsed');
    }
}
</script>

@include('components.theme-toggle')
</body>
</html>
