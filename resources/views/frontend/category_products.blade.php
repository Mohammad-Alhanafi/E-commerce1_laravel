<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - {{ $settings['store_name'] ?? 'الوقار' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category_product.css') }}">
</head>
<body>

@include('admin.header')

<section class="py-4">
    <div class="container">

        <div class="category-heading">
            <div class="category-heading-line">
                <span></span>
                <i class="fas fa-gem"></i>
                <span></span>
            </div>
            <h1 class="category-heading-title">{{ $category->name }}</h1>
        </div>

        <div class="products-masonry">
            @forelse($products as $product)
                @php
                    $fallbackImage = asset('assets/images/default-product.svg');
                    $productImage = $product->image ? asset('storage/' . $product->image) : $fallbackImage;
                @endphp

                <div class="product-card">
                    <a href="{{ route('products.show', $product->id) }}">
                        <div class="img-box">
                            <img
                                src="{{ $productImage }}"
                                onerror="this.onerror=null; this.src='{{ $fallbackImage }}'"
                                alt="{{ $product->name }}"
                                loading="lazy"
                                decoding="async"
                            />
                            <div class="product-overlay">
                                <div class="product-info">
                                    <h5>{{ $product->name }}</h5>
                                    <div class="product-price">
                                        <span class="price-ornament"></span>
                                        {{ number_format($product->price, 2) }} $
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5 empty-state">
                    <i class="fas fa-box-open fa-4x mb-3" style="color: var(--primary-color); opacity:0.3;"></i>
                    <h3 class="text-muted">هذه المجموعة لا تحتوي على منتجات حالياً.</h3>
                </div>
            @endforelse
        </div>

        @if(method_exists($products, 'links'))
        <div class="d-flex justify-content-center pagination-container">
            {{ $products->links() }}
        </div>
        @endif

    </div>
</section>

@include('admin.footer')

</body>
</html>