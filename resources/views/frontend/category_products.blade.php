<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - الوقار</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <!-- #endregion --> <link rel="stylesheet" href="{{ asset('css/category_product.css') }}">


  
</head>
<body>

@include('admin.header')

<section class="py-3">
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
            @forelse($products as $index => $product)
                @php
                    $sizes = ['large', 'medium', 'wide', 'medium', 'tall', 'medium', 'large', 'medium', 'wide'];
                    $currentClass = $sizes[$index % count($sizes)];
                    $fallbackImage = asset('assets/images/default-product.svg');
                    $productImage = $product->image ? asset('storage/' . $product->image) : $fallbackImage;
                @endphp

                <div class="product-card {{ $currentClass }}">
                    <a href="{{ url('/item/' . $product->id) }}">
                        <div class="img-box">
                            <img
                                src="{{ $productImage }}"
                                onerror="this.onerror=null; this.src='{{ $fallbackImage }}'"
                                alt="{{ $product->name }}"
                                loading="lazy"
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
                    <i class="fas fa-box-open fa-4x mb-3" style="color: #d4af37; opacity:0.3;"></i>
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