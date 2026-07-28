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

    <style>
        :root {
            --primary-gold: #D4AF37;
            --dark-gold: #B8860B;
            --light-gold: #FFD700;
            --pale-gold: #F5E8B0;
            --primary-black: #0A0A0A;
            --secondary-black: #1A1A1A;
            --light-black: #2A2A2A;
            --charcoal: #333333;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #B8860B 100%);
            --gradient-black: linear-gradient(135deg, #0A0A0A 0%, #1A1A1A 50%, #2A2A2A 100%);
        }
        
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--primary-black);
            color: var(--pale-gold);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .site-header {
            background: var(--gradient-black);
            border-bottom: 2px solid var(--primary-gold);
            padding: 20px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: 2.5rem;
            color: var(--light-gold);
            margin-left: 15px;
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Product Container */
        .product-container {
            background: var(--gradient-black);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            margin: 50px auto;
            border: 2px solid rgba(212, 175, 55, 0.3);
            max-width: 1400px;
        }
        
        /* Image Gallery Styles */
      .main-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    background: var(--secondary-black);

    /* هاد الجزء المهم: بدل الـ height الثابت */
    display: inline-block;   /* يخلي الكونتينر ياخد بالضبط عرض/طول محتواه */
    width: auto;
    height: auto;
    max-width: 100%;         /* يمنعه يكبر أكتر من مساحة العمود المتاحة */
    max-height: 600px;       /* سقف أقصى اختياري، حتى لو الصورة طويلة كتير ما تطلع عن الشاشة */
}

.main-img {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 600px;       /* نفس القيمة فوق، عشان الكونتينر والصورة يتطابقو بالحجم */
    object-fit: contain;     /* هلق ما إلها تأثير عملياً لأنه ما في فرق بين حجم الصورة والكونتينر */
    transition: transform 0.5s ease;
}

.main-img:hover {
    transform: scale(1.05);
}
        
        .thumbnail-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .thumbnail-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .thumbnail-img:hover, .thumbnail-img.active {
            border-color: var(--primary-gold);
            transform: translateY(-5px);
        }
        
        /* Product Info Styles */
        .product-info {
            padding: 40px;
        }
        
        .breadcrumb {
            background: rgba(212, 175, 55, 0.1);
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 30px;
        }
        
        .breadcrumb-item a {
            color: var(--pale-gold);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--light-gold);
        }
        
        .breadcrumb-item.active {
            color: var(--light-gold);
        }
        
        .product-title {
            color: var(--light-gold);
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .rating-stars {
            color: var(--light-gold);
        }
        
        .rating-text {
            color: var(--pale-gold);
            font-size: 0.9rem;
        }
        
        /* Price Styles */
        .price-section {
            background: rgba(212, 175, 55, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        
        .price-display {
            font-size: 3rem;
            font-weight: 800;
            color: var(--light-gold);
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .price-currency {
            font-size: 1.5rem;
            color: var(--pale-gold);
        }
        
        .old-price {
            text-decoration: line-through;
            color: rgba(245, 232, 176, 0.5);
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .discount-badge {
            background: var(--gradient-gold);
            color: var(--primary-black);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        /* Product Description */
        .product-description {
            color: var(--pale-gold);
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 30px;
            padding-right: 10px;
        }
        
        /* Product Details */
        .product-details {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: var(--pale-gold);
            font-weight: 600;
        }
        
        .detail-value {
            color: var(--light-gold);
            font-weight: 500;
        }
        
        /* Variants Styles */
        .variants-section {
            margin-bottom: 30px;
        }
        
        .variant-title {
            color: var(--light-gold);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .variant-title i {
            color: var(--primary-gold);
        }
        
        .variants-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .variant-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: var(--pale-gold);
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 600;
            min-width: 100px;
            text-align: center;
        }
        
        .variant-btn:hover {
            border-color: var(--primary-gold);
            color: var(--light-gold);
            transform: translateY(-3px);
        }
        
        .variant-btn.active {
            background: rgba(212, 175, 55, 0.2);
            border-color: var(--primary-gold);
            color: var(--light-gold);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }
        
        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .quantity-label {
            color: var(--light-gold);
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            overflow: hidden;
        }
        
        .quantity-btn {
            background: transparent;
            border: none;
            color: var(--light-gold);
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .quantity-btn:hover {
            background: rgba(212, 175, 55, 0.1);
        }
        
        .quantity-input {
            width: 70px;
            height: 50px;
            border: none;
            background: transparent;
            color: var(--light-gold);
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .btn-primary {
            background: var(--gradient-gold);
            color: var(--primary-black);
            border: none;
            padding: 18px 30px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
            flex: 2;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(212, 175, 55, 0.3);
            color: var(--light-gold);
            padding: 18px 30px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            flex: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-secondary:hover {
            background: rgba(212, 175, 55, 0.1);
            border-color: var(--primary-gold);
            transform: translateY(-3px);
        }
        
        /* Features Section */
        .features-section {
            background: rgba(212, 175, 55, 0.05);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .feature-item:last-child {
            margin-bottom: 0;
        }
        
        .feature-icon {
            color: var(--light-gold);
            font-size: 1.5rem;
            width: 40px;
        }
        
        .feature-text {
            color: var(--pale-gold);
            font-size: 0.95rem;
        }
        
        /* Related Products */
        .related-products {
            margin-top: 80px;
        }
        
        .section-title {
            color: var(--light-gold);
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            right: 50%;
            transform: translateX(50%);
            width: 150px;
            height: 3px;
            background: var(--gradient-gold);
        }
        
        .product-card {
            background: var(--gradient-black);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-gold);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15);
        }
        
        .product-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-card-body {
            padding: 20px;
        }
        
        .product-card-title {
            color: var(--light-gold);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .product-card-price {
            color: var(--primary-gold);
            font-weight: 800;
            font-size: 1.3rem;
        }
        
        /* Footer */
        .site-footer {
            background: var(--gradient-black);
            border-top: 2px solid var(--primary-gold);
            padding: 50px 0 20px;
            margin-top: 80px;
        }
        
        .footer-title {
            color: var(--light-gold);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 2px;
            background: var(--gradient-gold);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: var(--pale-gold);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--light-gold);
            padding-right: 5px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .product-title {
                font-size: 2rem;
            }
            
            .price-display {
                font-size: 2.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
        
        @media (max-width: 768px) {
            .product-container {
                margin: 20px auto;
                border-radius: 15px;
            }
            
            .product-info {
                padding: 25px;
            }
            
            .product-title {
                font-size: 1.8rem;
            }
            
            .main-image-container {
                height: 400px;
            }
        }
        
        @media (max-width: 576px) {
            .product-title {
                font-size: 1.5rem;
            }
            
            .price-display {
                font-size: 2rem;
            }
            
            .variants-container {
                justify-content: center;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--secondary-black);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gradient-gold);
            border-radius: 4px;
        }




        /* تنسيق حاوية المنتج */
.product-container {
    background-color: #1a1a1a; /* خلفية داكنة فخمة */
    border-radius: 15px;
    overflow: hidden;
    color: #ffffff;
}

/* العنوان والسعر */
.product-title {
    font-family: 'Cairo', sans-serif;
    font-weight: 700;
    letter-spacing: 1px;
}

.text-gold {
    color: #d4af37 !important; /* لون ذهبي ملكي */
}

/* تنسيق أزرار المقاسات (التحويل لمربعات احترافية) */
.variant-btn {
    border: 1px solid #d4af37;
    background: transparent;
    color: #ffffff;
    padding: 10px 20px;
    min-width: 60px;
    border-radius: 5px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.variant-btn:hover {
    background: rgba(212, 175, 55, 0.1);
    color: #d4af37;
}

.variant-btn.active {
    background: #d4af37 !important;
    color: #000 !important;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
}

/* تنسيق أداة التحكم بالكمية */
.quantity-control {
    border: 1px solid #444;
    padding: 5px;
}

.quantity-btn {
    background: none;
    border: none;
    color: white;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}

.quantity-btn:hover {
    color: #d4af37;
}

/* زر أضف للسلة */
.btn-gold {
    background: #d4af37;
    color: #000;
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.btn-gold:hover {
    background: #b8952e;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

.btn-gold:disabled {
    background: #555;
    color: #888;
}

/* التنبيهات (المتبقي) */
#stock-display {
    font-weight: 500;
    padding: 5px 0;
}

/* الأكورديون (التفاصيل) */
.accordion-item {
    border-color: #333 !important;
}

.accordion-button::after {
    filter: invert(1); /* تحويل سهم الأكورديون للون الأبيض */
}

.accordion-button:not(.collapsed) {
    background-color: rgba(212, 175, 55, 0.1);
    color: #d4af37;
    box-shadow: none;
}

/* تصميم الدائرة الخارجية */
.color-circle-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #444; /* لون الحدود العادي */
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3px;
    transition: all 0.3s ease;
    cursor: pointer;
}


.color-circle-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid #444;
    background: transparent;
    padding: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-dot {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: block;
    /* لا نضع لوناً هنا، اللون يأتي من الـ HTML */
}

.color-circle-btn.active-color {
    border-color: #d4af37; /* التحديد الذهبي عند اختيار اللون */
    transform: scale(1.1);
}



/* تصميم أزرار الألوان */
.color-circle-btn {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    border: 2px solid #444;
    background: transparent;
    padding: 3px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex; /* يضمن بقاء الزر مرئياً */
    align-items: center;
    justify-content: center;
    outline: none;
}

.color-dot {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: block;
    pointer-events: none; /* لمنع تداخل الضغط */
}

.color-circle-btn.active-color {
    border-color: #d4af37 !important;
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
}



.model-btn {
    border-color: #d4af37 !important; /* لون الإطار ذهبي */
    color: #d4af37 !important;        /* لون النص ذهبي */
    background-color: transparent !important; /* خلفية شفافة */
    transition: all 0.3s ease;
}

.model-btn:hover {
    background-color: #d4af37 !important;
    color: #000 !important; /* النص يصير أسود عند التمرير */
}

.model-btn.active-gold {
    background-color: #d4af37 !important;
    color: #000 !important;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
}


@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}




.cart-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 15px;
    border-radius: 12px;
    background: linear-gradient(45deg, #b8962e, #d4af37);
    color: #000;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

.cart-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(212, 175, 55, 0.5);
}

.cart-left {
    font-size: 30px;
}

.cart-main-icon {
    transition: transform 0.3s ease;
}

.cart-box:hover .cart-main-icon {
    transform: rotate(-10deg) scale(1.1);
}

.cart-title {
    font-weight: bold;
    font-size: 16px;
}

.cart-subtitle {
    font-size: 13px;
    opacity: 0.8;
}

.cart-right {
    font-size: 18px;
}

.cart-box.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #444;
    color: #aaa;
    box-shadow: none;
}
.accordion-button::after {
    filter: invert(1); 
}
.accordion-button:not(.collapsed) {
    color: #d4af37 !important;
    background-color: transparent !important;
}


  @keyframes slideIn {
            from { right: -450px; opacity: 0; }
            to { right: 0; opacity: 1; }
        }
        
        @keyframes slideOut {
            from { right: 0; opacity: 1; }
            to { right: -450px; opacity: 0; }
        }
        
        @keyframes goldPulse {
            0% { box-shadow: 0 0 5px rgba(212, 175, 55, 0.3); }
            50% { box-shadow: 0 0 20px rgba(212, 175, 55, 0.8); }
            100% { box-shadow: 0 0 5px rgba(212, 175, 55, 0.3); }
        }
        
        @keyframes shimmer {
            0% { background-position: -100% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes rotateGlow {
            0% { filter: drop-shadow(0 0 2px #d4af37); }
            50% { filter: drop-shadow(0 0 10px #d4af37); }
            100% { filter: drop-shadow(0 0 2px #d4af37); }
        }
        
        @keyframes borderGlow {
            0% { border-color: rgba(212, 175, 55, 0.3); }
            50% { border-color: rgba(212, 175, 55, 1); }
            100% { border-color: rgba(212, 175, 55, 0.3); }
        }
        
        .cart-open #side-cart {
            animation: slideIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        
        .cart-closing #side-cart {
            animation: slideOut 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        
        .cart-item {
            animation: fadeInScale 0.5s ease-out;
            animation-fill-mode: both;
        }
        
        .cart-item:nth-child(1) { animation-delay: 0.1s; }
        .cart-item:nth-child(2) { animation-delay: 0.2s; }
        .cart-item:nth-child(3) { animation-delay: 0.3s; }
        .cart-item:nth-child(4) { animation-delay: 0.4s; }
        .cart-item:nth-child(5) { animation-delay: 0.5s; }






        /* تنسيق خاص لأزرار السلة الذهبية لضمان ظهورها */
.quantity-wrapper {
    display: flex !important;
    align-items: center !important;
    gap: 0 !important;
    background: #000 !important;
    border: 1px solid #d4af37 !important;
    border-radius: 5px !important;
    width: 100px !important; /* تحديد عرض ثابت */
    height: 32px !important;
    overflow: hidden !important;
    margin-top: 10px !important;
}

.quantity-wrapper button {
    width: 33% !important;
    height: 100% !important;
    background: #d4af37 !important;
    color: #000 !important;
    border: none !important;
    cursor: pointer !important;
    font-weight: bold !important;
    font-size: 18px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
}

.quantity-wrapper .qty-num {
    width: 34% !important;
    color: #fff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 13px !important;
    border-left: 1px solid #d4af37 !important;
    border-right: 1px solid #d4af37 !important;
}

.delete-icon {
    color: #ff4d4d !important;
    margin-right: 15px !important;
    cursor: pointer !important;
    font-size: 16px !important;
    transition: 0.3s !important;
}

.delete-icon:hover {
    transform: scale(1.2);
    color: #ff0000 !important;
}



/* تنسيق أزرار التحكم داخل السلة الجانبية */
.side-cart-controls {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    margin-top: 10px !important;
}

.btn-update {
    width: 30px !important;
    height: 30px !important;
    background: #d4af37 !important;
    color: #000 !important;
    border: none !important;
    border-radius: 4px !important;
    font-weight: bold !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.cart-qty-display {
    color: #fff !important;
    font-weight: bold !important;
    min-width: 20px;
    text-align: center;
}

.btn-remove {
    color: #ff4d4d !important;
    margin-right: 10px !important;
    cursor: pointer !important;
    background: none !important;
    border: none !important;
}


.product-card-hover {
        transition: transform 0.3s ease, border 0.3s ease;
        border: 1px solid transparent !important;
    }
    .product-card-hover:hover {
        transform: translateY(-10px);
        border: 1px solid #d4af37 !important;
    }
    .text-gold {
        color: #d4af37;
    }






/* 1. تعريف حركات الظهور (Animations) */
@keyframes fadeInUpScale {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes goldGlow {
    0% { box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 5px rgba(212, 175, 55, 0.2); }
    50% { box-shadow: 0 10px 35px rgba(0,0,0,0.6), 0 0 20px rgba(212, 175, 55, 0.5); }
    100% { box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 5px rgba(212, 175, 55, 0.2); }
}

/* 2. التنسيق المشترك لكل الحاويات */
.main-image-container {
    position: relative;
    animation: fadeInUpScale 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    transition: all 0.4s ease;
    overflow: hidden; /* عشان اللمعة ما تطلع برا الإطار */
}

/* 3. تنسيق الدائرة (إكسسوارات) مع أنميشن توهج */
.circle-frame {
    width: 100%;
    max-width: 450px;
    aspect-ratio: 1 / 1;
    margin: 0 auto;
    border-radius: 50% !important;
    border: 3px solid #d4af37;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeInUpScale 0.8s forwards, goldGlow 3s infinite ease-in-out;
}

.circle-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.circle-frame:hover img {
    transform: scale(1.1);
}

/* 4. تنسيق الطولي (عبايات) */
.abaya-frame {
    width: 100%;
    aspect-ratio: 3 / 4.5;
    border-radius: 15px;
    background-color: #f9f9f9;
}

.abaya-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.abaya-frame:hover img {
    transform: scale(1.05);
}

/* 5. التنسيق الافتراضي (عسل وزيوت) */
.general-frame {
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 15px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.general-frame img {
    max-width: 85%;
    max-height: 85%;
    object-fit: contain;
    transition: transform 0.4s ease;
}

.general-frame:hover img {
    transform: scale(1.1) rotate(2deg); /* حركة خفيفة للمرطبان */
}

/* 6. تأثير اللمعة السحرية (بمرّ مرة واحدة عند التحميل أو عند الـ Hover) */
.main-image-container::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -100%;
    width: 50%;
    height: 200%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(30deg);
    transition: 0.8s;
}

.main-image-container:hover::after {
    left: 150%;
}



/* 1. التنسيق الأساسي للعنوان (ثابت) */
/* النص الذهبي الصافي مع أنيميشن الضباب فقط */
.section-title-gold.blur-effect {
    color: #d4af37 !important; /* لون ذهبي ثابت */
    text-align: right;
    direction: rtl;
    border-right: 5px solid #d4af37;
    padding-right: 15px;
    font-weight: bold;
    font-size: 1.6rem;
    position: relative;
    display: inline-block;
    background: none !important; /* التأكد من عدم وجود خلفية */
    animation: blurToClear 8s infinite ease-in-out;
}

/* حذف أي تأثير خلفية أو لمعة سابقة */
.section-title-gold.blur-effect::after {
    display: none !important;
    content: none !important;
}

/* أنيميشن الضباب (بدون تغيير الألوان) */
@keyframes blurToClear {
    0%, 5% { 
        opacity: 0; 
        filter: blur(12px); 
        transform: scale(0.9); 
    }
    15%, 85% { 
        opacity: 1; 
        filter: blur(0); 
        transform: scale(1); 
    }
    95%, 100% { 
        opacity: 0; 
        filter: blur(10px); 
        transform: scale(1.05); 
    }
}

/* 4. تنسيق السلايدر المطعوج (3D) */
.relatedSwiper {
    overflow: visible !important; /* ضروري جداً لظهور الطعجة */
    padding: 40px 0;
}

.swiper-slide {
    width: 240px; 
    height: 380px; 
    transition: 0.3s;
}
/* الكرت المطعوج - الخلفية والتنسيق العام */
/* الكرت كقطعة واحدة - توحيد اللون */
.related-card-curved {
    height: 100%;
    background: #0a0a0a !important; /* نفس لون خلفية بودي الموقع عندك */
    border-radius: 20px;
    border: 1px solid rgba(212, 175, 55, 0.3); /* حدود ذهبية نحيفة */
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    transition: 0.3s ease-in-out;
}

/* صورة المنتج */
.related-card-curved img {
    width: 100%;
    height: 72%; /* توازن بين الصورة والنص */
    object-fit: cover;
    /* مسحنا الـ border-bottom عشان ما يفصل الكرت */
}

/* منطقة المعلومات - بدون خلفية مختلفة */
/* منطقة المعلومات - التوسيط الكامل */
.related-info {
    padding: 20px 10px; /* مساحة داخلية مريحة */
    text-align: center; /* توسيط النص أفقياً */
    background: transparent !important;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;    /* توسيط العناصر أفقياً داخل الـ Flex */
    justify-content: center; /* توسيط العناصر عمودياً */
}

/* اسم المنتج - ذهبي ومرتب في النص */
.related-info h6 { 
    color: #d4af37 !important; 
    font-weight: bold;
    margin: 0 0 12px 0; /* مسافة تحت الاسم */
    font-size: 16px;
    width: 100%; /* لضمان التوسيط الكامل */
    display: block;
}

/* السعر - أبيض في منتصف الصندوق */
.related-info p { 
    color: #ffffff !important; 
    font-size: 14px;
    margin: 0;
    padding: 6px 15px;
    background: rgba(212, 175, 55, 0.12); /* خلفية خفيفة جداً للسعر */
    border-radius: 30px; /* شكل بيضاوي للسعر أجمل في النص */
    display: inline-block;
    min-width: 80px; /* عرض أدنى لتوحيد الشكل */
}

/* عند تمرير الماوس - حركة فخمة للكرت كامل */
.related-card-curved:hover {
    border-color: #d4af37;
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.15);
}

/* نقاط التنقل (Pagination) - ذهبية بدل الزرقاء */
.swiper-pagination-bullet-active {
    background: #d4af37 !important;
}

/* التجاوب مع الجوال */
@media (max-width: 768px) {
    .section-title-gold {
        font-size: 1.3rem;
        padding-right: 12px;
    }
    .swiper-slide {
        width: 190px; /* حجم مثالي لشاشة الجوال لترى التداخل */
        height: 320px;
    }
    .related-info h6 { font-size: 13px; }
}




.btn-checkout{
    width: 100%;
    background: linear-gradient(135deg, #d4af37 0%, #b4941c 50%, #d4af37 100%);
    color: #000;
    border: none;
    padding: 18px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 2px;
    cursor: pointer;
    font-weight: bold;
    border-radius: 30px;
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-family: inherit;
}
.btn-checkout:hover{
    box-shadow: 0 0 28px rgba(212, 175, 55, 0.6);
    transform: translateY(-1px);
}
.btn-checkout:focus-visible{
    outline: 2px solid #e8cc6b;
    outline-offset: 3px;
}







        </style>
        
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
                    $adminStyle = \Illuminate\Support\Facades\DB::table('settings')
                        ->where('key', $settingKey)
                        ->value('value');

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
                    <img src="{{ asset('storage/' . $product->image) }}" class="main-img" id="mainProductImage">
                </div>
                
                <div class="thumbnail-container mt-3"></div>
            </div>




<div class="col-lg-5 p-4 p-lg-5">

    <h1 class="product-title text-gold">{{ $product->name }}</h1>

    <div id="stock-{{ $product->id }}" style="margin:10px 0; color:#d4af37; font-weight:bold;">
        المتوفر: {{ $product->stock }}
    </div>

    <div class="price-card-box mt-3 mb-4 d-flex align-items-center gap-3">
        <div style="background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; padding: 10px 20px; border-radius: 8px; display: inline-block;">
            <span style="color: #d4af37; font-size: 18px; margin-left: 5px;">$</span>
            <span id="current_price" style="font-size: 28px; font-weight: bold; color: #fff;">
                {{ number_format($product->price, 2) }}
            </span>
        </div>
        <div id="variant-notes-top" class="badge-sale" style="display:none; background-color: #ff4747; color: #fff; padding: 5px 12px; border-radius: 20px;"></div>
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
                                onclick="filterModelsByColor('{{ trim($variant->color) }}', '{{ $variant->variant_image ? asset('storage/' . $variant->variant_image) : asset('storage/' . $product->image) }}', this)"
                                style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #444; background-color: {{ $variant->color }}; cursor: pointer; transition: 0.3s;">
                        </button>
                        <div class="stock-label" style="font-size: 10px; color: #888; margin-top: 4px;">
                            @if($variant->stock <= 0) <span class="text-danger">نفذ</span> @else متوفر: {{ $variant->stock }} @endif
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
                                style="border: 1px solid #d4af37; color: #d4af37; background: transparent; min-width: 60px;">
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

    <h3 class="text-gold small mb-3 section-size" style="display: none; align-items: center; gap: 8px;">
        <span id="stock-status-container" style="display: flex; align-items: center; gap: 5px; font-size: 12px; transition: 0.3s;"></span>
    </h3>

    <div id="sizes-container" class="mb-4">
        @foreach($product->variants as $variant)
            @foreach($variant->attributeValues as $attributeValue)
                <button type="button" class="btn btn-outline-light size-item"
                       data-variant-id="{{ $variant->id }}"
                        data-color="{{ trim($variant->color) }}"
                        data-stock="{{ $variant->stock }}"
                        data-price="{{ number_format($variant->variant_price, 2) }}"
                        data-notes="{{ $variant->notes }}"
                        style="display: none; padding: 8px 15px; font-size: 14px; margin: 2px; border: 1px solid #555; border-radius: 6px;"
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
                <div class="cart-center"><div class="cart-title">أضف إلى السلة</div></div>
            </div>

            <div class="quantity-control d-flex align-items-center bg-dark rounded p-1" style="border: 1px solid #444; height: 50px;">
                <button class="btn text-white px-3" id="decreaseQuantity" style="font-weight: bold;">-</button>
                <input type="text" class="form-control bg-transparent border-0 text-white text-center p-0"
                       value="1" id="productQuantity" readonly style="width: 40px; font-weight: bold;">
                <button class="btn text-white px-3" id="increaseQuantity" style="font-weight: bold;">+</button>
            </div>
        </div>

        <div id="notes-near-cart" class="mb-4" style="font-size: 14px; color: #d4af37; font-weight: bold; min-height: 20px;"></div>

        @if($product->care_instructions)
            <div class="accordion accordion-flush" id="productExtra">
                <div class="accordion-item bg-transparent" style="border-top: 1px solid #333; border-bottom: 1px solid #333;">
                    <h2 class="accordion-header">
                        <button class="accordion-button bg-transparent text-gold collapsed px-0 shadow-none"
                                type="button" data-bs-toggle="collapse" data-bs-target="#careTab"
                                style="font-size: 14px; color: #d4af37;">
                            <i class="fas fa-info-circle me-2"></i> بعض التعليمات والتفاصيل
                        </button>
                    </h2>
                    <div id="careTab" class="accordion-collapse collapse text-white-50">
                        <div class="accordion-body px-0 py-3" style="font-size: 13px; line-height: 1.6; white-space: pre-line;">
                            {{ $product->care_instructions }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
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










<div id="cart-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(33,33,33,0.9) 100%); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index:9998; opacity:0; transition: opacity 0.4s ease;"></div>

<div id="side-cart" style="position:fixed; top:0; right:-450px; width:400px; height:100%; background:rgba(18, 18, 18, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); color:#fff; z-index:9999; box-shadow: -10px 0 40px rgba(212, 175, 55, 0.25); font-family: 'Arial', sans-serif; border-left: 2px solid rgba(212, 175, 55, 0.3); display: flex; flex-direction: column;">
    
    <div style="padding: 25px 20px; border-bottom: 2px solid #d4af37; background: linear-gradient(90deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.2) 50%, rgba(212, 175, 55, 0.1) 100%); display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
        <h4 style="margin:0; font-weight: 300; letter-spacing: 3px; color: #d4af37; text-transform: uppercase; font-size: 20px; text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);">
            <i class="fas fa-shopping-bag" style="margin-right: 10px; font-size: 18px;"></i>
            السلة الذهبية
            <span id="side-cart-count" style="background:#d4af37; color:#000; border-radius:50%; padding:2px 10px; font-size:12px; margin-left:8px; font-weight:bold;">{{ session('cart') ? count(session('cart')) : 0 }}</span>
        </h4>
        <span id="close-cart" style="cursor:pointer; font-size:32px; color:#d4af37; transition:0.3s;">&times;</span>
    </div>

    <div id="cart-items-content" style="padding: 20px; flex-grow: 1; overflow-y: auto;">
    @if(session('cart') && count(session('cart')) > 0)
        @foreach(session('cart') as $id => $details)
            <div class="cart-item" style="display: flex; gap: 15px; margin-bottom: 20px; padding: 15px; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 8px; background: rgba(255,255,255,0.05); position: relative;">
                
                <div style="width: 80px; height: 100px; background: #222; border-radius: 5px; overflow: hidden; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <img src="{{ $details['image'] }}" style="width:100%; height:100%; object-fit: cover;">
                </div>
                
                <div style="flex: 1;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <h5 style="margin:0; font-size:14px; color:#fff;">{{ $details['name'] }}</h5>
                        <span style="font-size:16px; color:#d4af37; font-weight:bold;">{{ number_format($details['price'], 2) }} $</span>
                    </div>
                    <p style="color:#b8a07c; font-size:12px; margin:5px 0;">المقاس: {{ $details['size'] }}</p>
                    
  <div style="display: flex !important; flex-direction: row !important; align-items: center !important; margin-top: 20px !important; position: relative !important; z-index: 10000 !important; visibility: visible !important; opacity: 1 !important;">
    
   <div style="display: flex; align-items: center; margin-top: 10px;">
    <div class="quantity-wrapper">
        <button class="update-cart" data-id="{{ $id }}" data-quantity="{{ $details['quantity'] - 1 }}">-</button>
        <div class="qty-num">{{ $details['quantity'] }}</div>
        <button class="update-cart" data-id="{{ $id }}" data-quantity="{{ $details['quantity'] + 1 }}">+</button>
    </div>

    <i class="fas fa-trash-alt remove-from-cart delete-icon" data-id="{{ $id }}"></i>
</div>

    <i class="fas fa-trash-alt remove-from-cart" data-id="{{ $id }}" 
       style="color:#ff4d4d; cursor:pointer; font-size:16px; margin-right:10px; padding:5px;"></i>
</div>




                </div>
            </div>
        @endforeach
        @else
            <div style="text-align:center; margin-top:100px; color:#888;">
                <i class="fas fa-shopping-basket" style="font-size:40px; margin-bottom:15px; opacity:0.2;"></i>
                <p>السلة فارغة حالياً</p>
            </div>
        @endif
    </div>



@php 
    $site_settings = DB::table('settings')->pluck('value', 'key');
    $ship_type = $site_settings['shipping_type'] ?? 'free';
    $ship_fee = $site_settings['shipping_fee'] ?? 0;

    $current_shipping_cost = ($ship_type == 'free') ? 0 : (float)$ship_fee;

    $cart_total = 0; 
    if(session('cart')) {
        foreach(session('cart') as $item) {
            $cart_total += $item['price'] * $item['quantity'];
        }
    }
@endphp
    <div style="padding: 20px; border-top: 2px solid #d4af37; background: rgba(0,0,0,0.9); flex-shrink: 0;">
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#b8a07c; font-size:14px;">
            <span>المجموع الفرعي</span>
            <span style="color:#fff;">{{ number_format($cart_total, 2) }} $</span>
        </div>
   <div style="display:flex; justify-content:space-between; margin-bottom:12px; color:#b8a07c; font-size:14px;">
   
</div>
        

    
        <div style="display:flex; justify-content:space-between; margin-bottom:20px; padding-top:10px; border-top: 1px dashed rgba(212, 175, 55, 0.5);">
    <span style="font-weight:bold; color:#fff;">الإجمالي النهائي</span>
    <span style="font-size:22px; font-weight:bold; color:#d4af37; text-shadow: 0 0 15px rgba(212, 175, 55, 0.4);">
        <span id="cart-total-amount">{{ number_format($cart_total + $current_shipping_cost, 2) }}</span> 
    </span>
</div>
    @auth
    <button onclick="window.location.href='{{ route('checkout') }}'" class="btn-checkout">
        <span>إتمام العملية الشرائية</span>
        <i class="fas fa-lock"></i>
    </button>
@else
    <div class="login-notice">
        <i class="fas fa-info-circle"></i>
        <span>يجب تسجيل الدخول لإتمام عملية الشراء</span>
    </div>
    <button onclick="window.location.href='{{ route('login.page') }}'" class="btn-checkout">
        <span>تسجيل الدخول</span>
        <i class="fas fa-user-lock"></i>
    </button>
@endauth

       <div style="display:flex; justify-content:center; align-items:center; gap:20px; margin-top:20px; opacity:0.8;">
    <div style="text-align: center;">
        <img src="https://wishmoney.com/wp-content/uploads/2023/02/wish-logo-1.png" alt="Wish Money" style="height: 22px; filter: grayscale(1) brightness(1.5) sepia(1) hue-rotate(-10deg) saturate(5); opacity: 0.9;">
    </div>

    <div style="text-align: center;">
        <img src="https://www.omt.com.lb/images/logo.png" alt="OMT" style="height: 22px; filter: grayscale(1) brightness(1.2) sepia(1) hue-rotate(-10deg) saturate(4); opacity: 0.9;">
    </div>
</div>

<div style="text-align: center; margin-top: 8px;">
    <span style="color: #d4af37; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; opacity: 0.6;">دفع عند الاستلام أو عبر التحويل</span>
</div>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">











@php 
    $layout = $settings['related_products_layout'] ?? 'curved-slider';
    $animClass = $settings['title_animation_style'] ?? 'blur-effect';
@endphp

<section class="related-products mt-5 mb-5">
    <div class="container">
        <h3 class="section-title-gold {{ $animClass }}">منتجات قد تعجبك:</h3>

        @if($layout == 'curved-slider')
            <div class="swiper relatedSwiper">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $related)
                        <div class="swiper-slide">
                            <div class="related-card-curved">
                                <a href="{{ url('/item/' . $related->id) }}" class="text-decoration-none">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
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
        @else
            <div class="row mt-4">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3 mb-4">
                         <div class="related-card-curved">
                            <img src="{{ asset('storage/' . $related->image) }}">
                            <div class="related-info"><h6>{{ $related->name }}</h6></div>
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
<script>
    

let currentColor = "";
let selectedVariantId = null;

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

    topSale.style.display = 'none';
    bottomFire.innerHTML = '';

    if (notes && notes.trim() !== "") {
        const isSale = notes.toLowerCase().includes('sale') || notes.includes('خصم') || notes.includes('عرض');
        if (isSale) {
            topSale.innerText = notes;
            topSale.style.display = 'block';
        } else {
            bottomFire.innerHTML = `<span>🔥</span> ${notes}`;
        }
    }
}

// المصدر الوحيد الآن لتفعيل/تعطيل زر السلة - يعتمد على المخزون الحقيقي دائماً
function syncAddToCartButton(stock) {
    const cartBox = document.getElementById('addToCart');
    if (!cartBox) return;

    const stockInt = parseInt(stock);
    const hasVariant = !!selectedVariantId;

    if (hasVariant && stockInt > 0) {
        cartBox.classList.remove('disabled');
        cartBox.style.opacity = "1";
        cartBox.style.cursor = "pointer";
        cartBox.querySelector('.cart-title').innerText = "أضف إلى السلة";
    } else {
        cartBox.classList.add('disabled');
        cartBox.style.opacity = "0.5";
        cartBox.style.cursor = "not-allowed";
        cartBox.querySelector('.cart-title').innerText = (hasVariant && stockInt <= 0) ? "نفذت الكمية" : "أضف إلى السلة";
        if (stockInt <= 0) selectedVariantId = null; // منتج خلص، ما بنسمح نبيعه
    }
}

// ============ منتجات الألوان ============
function filterModelsByColor(color, imageUrl, btn) {
    currentColor = color.trim();

    const mainImg = document.getElementById('mainProductImage');
    if (mainImg && imageUrl) mainImg.src = imageUrl;

    document.querySelectorAll('.color-btn').forEach(b => b.style.boxShadow = 'none');
    btn.style.boxShadow = '0 0 0 3px #d4af37';

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
        // هالمنتج فعلاً عنده مقاسات مرتبطة باللون - لازم يختار مقاس كمان
        sectionSize.style.display = 'block';
        document.querySelectorAll('.size-item').forEach(s => {
            s.style.background = 'transparent';
            s.style.color = '#fff';
            s.style.borderColor = '#555';
        });
        selectVariant(null, null, 0, ''); // نصفر لحد ما يختار مقاس
    } else {
        // ما في مقاسات لهالون -> اللون نفسه هو الـ variant الكامل
        sectionSize.style.display = 'none';
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
        s.style.color = '#fff';
        s.style.borderColor = '#555';
    });
    btn.style.background = 'rgba(212, 175, 55, 0.2)';
    btn.style.color = '#d4af37';
    btn.style.borderColor = '#d4af37';

    const stock = btn.getAttribute('data-stock');

    const stockContainer = document.getElementById('stock-status-container');
    if (stockContainer) {
        const stockInt = parseInt(stock);
        let icon, color, text;
        if (stockInt > 5) { icon = '<i class="fas fa-boxes"></i>'; color = '#888'; text = `متوفر عدد: ${stockInt}`; }
        else if (stockInt > 0) { icon = '<i class="fas fa-exclamation-triangle"></i>'; color = '#ffa500'; text = `باقي عدد: ${stockInt}`; }
        else { icon = '<i class="fas fa-times-circle"></i>'; color = '#ff4747'; text = `نفذت الكمية`; }
        stockContainer.innerHTML = `${icon} <span style="color:${color}">${text}</span>`;
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
    $(element).parent().find('button').css({ 'background-color': 'transparent', 'color': '#d4af37', 'border-color': '#d4af37' });
    $(element).css({ 'background-color': '#d4af37', 'color': '#000' });

    const stock = element.getAttribute('data-stock');
    const display = document.getElementById('stock-display');
    const wrapper = document.getElementById('stock-wrapper');

    if (display && wrapper) {
        wrapper.style.display = 'block';
        const stockNum = parseInt(stock);
        let icon, color, text;
        if (stockNum > 5) { icon = '<i class="fas fa-boxes"></i>'; color = '#888'; text = `متوفر عدد: ${stockNum}`; }
        else if (stockNum > 0) { icon = '<i class="fas fa-exclamation-triangle"></i>'; color = '#ffa500'; text = `باقي عدد: ${stockNum} (اطلب قبل النفاذ!)`; }
        else { icon = '<i class="fas fa-times-circle"></i>'; color = '#ff4747'; text = `للأسف، نفذت الكمية`; }
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

// ============ الضغط على "أضف للسلة" ============
$(document).on('click', '#addToCart', function(e) {
    e.preventDefault();

    if ($(this).hasClass('disabled') || !selectedVariantId) {
        Swal.fire({
            icon: 'error',
            iconColor: '#ff0000',
            title: 'تنبيه',
            text: 'يرجى اختيار الخيار المناسب أولاً ✨',
            confirmButtonColor: '#ff0000',
            confirmButtonText: 'حسناً'
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
            $('.cart-title').text('جاري الإضافة...');
        },
        success: function(response) {
            $('.cart-title').text('أضف إلى السلة');
            if (response.success) {
                Swal.fire({ icon: 'success', title: 'تمت الإضافة!', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                $('.cart-count').text(response.cart_count);
                $('#side-cart').css('right', '0');
                $('#cart-overlay').fadeIn();
                $('#cart-total-amount').text(response.total_price);
                $('#cart-items-content').html(response.cart_html);
            }
        },
        error: function(xhr) {
            $('.cart-title').text('أضف إلى السلة');
            alert('حدث خطأ، يرجى المحاولة مرة أخرى');
        }
    });
});

// ============ الحالة الابتدائية عند فتح الصفحة ============
$(document).ready(function() {
    const hasColorVariants = $('.color-btn').length > 0;
    const hasAttributeButtons = $('.attribute-btn').length > 0;

    if (!hasColorVariants && !hasAttributeButtons) {
        // منتج بلا أي متغيرات - variant واحد بس
        const firstVariant = @json($product->variants->first());
        if (firstVariant) {
            selectVariant(
                firstVariant.id,
                Number(firstVariant.variant_price).toFixed(2),
                firstVariant.stock,
                firstVariant.notes
            );
        } else {
            // ولا حتى variant، منعتمد على ستوك المنتج نفسه
            selectVariant('', "{{ number_format($product->price, 2) }}", {{ $product->stock }}, '');
        }
    }
    // إذا في ألوان أو أنواع عامة، الزر بيضل معطل لحد ما المستخدم يختار فعلياً
});

// ============ الكمية ============
document.getElementById('increaseQuantity').addEventListener('click', function() {
    let qtyInput = document.getElementById('productQuantity');
    qtyInput.value = parseInt(qtyInput.value) + 1;
});

document.getElementById('decreaseQuantity').addEventListener('click', function() {
    let qtyInput = document.getElementById('productQuantity');
    let currentQty = parseInt(qtyInput.value);
    if (currentQty > 1) qtyInput.value = currentQty - 1;
});




















function filterSizesByModel(modelName, btn) {
    // ستايل الموديل المختار
    document.querySelectorAll('.model-btn').forEach(m => {
        m.style.background = 'transparent';
        m.style.color = '#d4af37';
    });
    btn.style.background = '#d4af37';
    btn.style.color = '#000';

    // إظهار المقاسات المرتبطة باللون والموديل
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
        s.style.color = '#fff';
    });

    document.querySelector('.section-size').style.display = hasSizes ? 'block' : 'none';
    
    const cartBtn = document.getElementById('addToCart');
    if(cartBtn) cartBtn.innerText = "يرجى اختيار المقاس";
}

















document.getElementById('increaseQuantity').addEventListener('click', function() {
    let qtyInput = document.getElementById('productQuantity');
    let currentQty = parseInt(qtyInput.value);
    qtyInput.value = currentQty + 1;
});

document.getElementById('decreaseQuantity').addEventListener('click', function() {
    let qtyInput = document.getElementById('productQuantity');
    let currentQty = parseInt(qtyInput.value);
    if (currentQty > 1) {
        qtyInput.value = currentQty - 1;
    }
});








// close the cart 

    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.getElementById('close-cart');
        const overlay = document.getElementById('cart-overlay');
        const sideCart = document.getElementById('side-cart');

        // وظيفة الإغلاق
        function closeCart() {
            sideCart.style.right = '-450px'; // دفع السلة خارج الشاشة
            overlay.style.opacity = '0';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 400); // انتظر انتهاء الأنميشن
        }

        // عند الضغط على X
        if(closeBtn) {
            closeBtn.onclick = closeCart;
        }

        // عند الضغط على الخلفية المظلمة
        if(overlay) {
            overlay.onclick = closeCart;
        }
    });







$(document).ready(function() {
    
    $(document).off('click', '.update-cart').on('click', '.update-cart', function (e) {
        e.preventDefault();
        e.stopPropagation(); 
        
        let id = $(this).data('id');
        let quantity = parseInt($(this).data('quantity'));

        if(quantity <= 0) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم إزالة هذا المنتج من السلة!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d4af37', // لون ذهبي
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء',
                background: '#121212', // خلفية داكنة تناسب تصميمك
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeProduct(id);
                }
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
            title: 'حذف المنتج',
            text: "هل تريد حقاً إزالة هذا المنتج؟",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d4af37',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، احذفه',
            cancelButtonText: 'تراجع',
            background: '#121212',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                removeProduct(id);
            }
        });
    });

    // دالة التحديث (تأكد من مطابقة الـ ID: cart-items-content)
    function updateQuantity(id, qty) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: "patch",
            data: { _token: '{{ csrf_token() }}', id: id, quantity: qty },
            success: function (response) {
                if(response.success) {
                    $('#cart-items-content').html(response.cart_html);
                    $('#cart-total-amount').text(response.total);
                    $('#side-cart-count').text(response.cart_count);
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
                    $('#cart-total-amount').text(response.total);
                    $('#side-cart-count').text(response.cart_count);
                    
                    // تنبيه نجاح الحذف بشكل أنيق
                    Swal.fire({
                        title: 'تم الحذف!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#121212',
                        color: '#fff'
                    });
                }
            }
        });
    }




});

  













function selectGeneralAttribute(name, value, element, stock, variantId) {
    // 1. تنسيق الأزرار عند الضغط
    $(element).parent().find('button').css({'background-color': 'transparent', 'color': '#d4af37', 'border-color': '#d4af37'});
    $(element).css({'background-color': '#d4af37', 'color': '#000'});

    const display = document.getElementById('stock-display');
    const wrapper = document.getElementById('stock-wrapper');
    const addToCartBtn = document.querySelector('.add-to-cart-btn');

    if (display && wrapper) {
        wrapper.style.display = 'block';
        let stockNum = parseInt(stock);
        let icon, color, text;

        if (stockNum > 5) {
            icon = '<i class="fas fa-boxes"></i>'; 
            color = '#888'; // رمادي للمتوفر بكثرة
            text = `متوفر عدد: ${stockNum}`;
        } else if (stockNum > 0) {
            icon = '<i class="fas fa-exclamation-triangle"></i>'; 
            color = '#ffa500'; // برتقالي للتحذير (كمية قليلة)
            text = `باقي عدد: ${stockNum} (اطلب قبل النفاذ!)`;
        } else {
            icon = '<i class="fas fa-times-circle"></i>'; 
            color = '#ff4747'; // أحمر للنفاذ
            text = `للأسف، نفذت الكمية`;
        }

        display.style.color = color;
        display.innerHTML = `${icon} <span class="ms-1">${text}</span>`;
        
        $(wrapper).hide().fadeIn(300);

        if(addToCartBtn) {
            if (stockNum > 0) {
                $(addToCartBtn).prop('disabled', false).css('opacity', '1').text('إضافة للسلة');
            } else {
                $(addToCartBtn).prop('disabled', true).css('opacity', '0.5').text('غير متوفر');
            }
        }
    }

    if (document.getElementById('selected_variant_id')) {
        document.getElementById('selected_variant_id').value = variantId;
    }
}






















document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper(".relatedSwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto", 
        loop: true,
        // أضفنا سرعة الانتقال لتكون أنعم
        speed: 800,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        coverflowEffect: {
            rotate: 35,      /* ميلان الكروت الجانبية */
            stretch: -20,    /* القيمة السالبة تجعل الكروت تتداخل فوق بعضها (سر الطعجة) */
            depth: 200,      /* إبعاد الكروت الجانبية للخلف ليعطي عمق 3D */
            modifier: 1,     /* قوة التأثير الإجمالية */
            slideShadows: true, 
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        // كود إضافي عشان الماوس (اختياري)
        mousewheel: {
            invert: false,
        },
    });
});




























</script>
@include('components.theme-toggle')
</body>
</html>