{{-- 
    ============================================================
    components/side-cart.blade.php — Global Side Cart Component
    ============================================================
--}}

<div id="cart-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9998; background: rgba(0,0,0,0.3); backdrop-filter: none; opacity:0; transition: opacity 0.3s ease;"></div>

<div id="side-cart" style="position:fixed; top:0; right:-450px; width:100%; max-width:400px; height:100%; z-index:9999; background: var(--bg-card, #121212); border-left: 1px solid var(--border-color, #2a2a2a); box-shadow: -10px 0 30px rgba(0,0,0,0.5); display: flex; flex-direction: column; transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
    
    {{-- Header --}}
    <div style="padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color, #2a2a2a); flex-shrink: 0;">
        <h4 style="margin:0; font-weight: 600; color: var(--primary-color, #D4AF37); text-transform: uppercase; font-size: 18px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-shopping-bag" style="font-size: 20px;"></i>
            <span>{{ __('cart.title') }}</span>
            <span id="side-cart-count" style="background: var(--primary-color, #D4AF37); color: #000; border-radius: 20px; padding: 2px 10px; font-size: 12px; font-weight: bold;">
                {{ session('cart') ? count(session('cart')) : 0 }}
            </span>
        </h4>
        <span id="close-cart" style="cursor:pointer; font-size:28px; color: var(--text-color, #fff); transition:0.2s; line-height: 1;" title="{{ __('navbar.close') }}">&times;</span>
    </div>

    {{-- Items Content Container --}}
    <div id="cart-items-content" style="padding: 20px; flex-grow: 1; overflow-y: auto;">
        @if(session('cart') && count(session('cart')) > 0)
            @foreach(session('cart') as $id => $details)
                <div class="cart-item" style="display: flex; gap: 15px; margin-bottom: 16px; padding: 12px; background: var(--section-bg, #1a1a1a); border: 1px solid var(--border-color, #2a2a2a); border-radius: 10px; position: relative;">
                    
                    <div style="width: 70px; height: 85px; background: var(--input-bg, #000); border-radius: 6px; overflow: hidden; flex-shrink: 0;">
                        <img src="{{ get_image_url($details['image'] ?? null, 'assets/images/default.jpg') }}" alt="{{ $details['name'] ?? '' }}" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap: 8px;">
                            <h5 style="margin:0; font-size:14px; font-weight:600; color:var(--text-color, #fff);">{{ $details['name'] ?? '' }}</h5>
                            <span style="font-size:15px; color:var(--primary-color, #D4AF37); font-weight:bold; white-space: nowrap;">
                                ${{ number_format($details['price'] ?? 0, 2) }}
                            </span>
                        </div>

                        @if(!empty($details['options']))
    <p style="color:var(--text-muted, #888); font-size:12px; margin: 4px 0 0 0; display:flex; align-items:center; flex-wrap:wrap; gap:6px;">
        @foreach($details['options'] as $optName => $optValue)
            <span style="display:inline-flex; align-items:center; gap:4px;">
                <strong>{{ $optName }}:</strong>

                @if($optName === __('admin.color') && preg_match('/^#[0-9A-Fa-f]{3,6}$/', $optValue))
                    <span style="display:inline-block; width:12px; height:12px; border-radius:50%; background-color: {{ $optValue }}; border:1px solid var(--border-color, #444);"></span>
                @else
                    {{ $optValue }}
                @endif
            </span>
            @if(!$loop->last)
                <span style="color: var(--border-color, #555);">—</span>
            @endif
        @endforeach
    </p>
@endif

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <div class="quantity-wrapper" style="display: flex; align-items: center; border: 1px solid var(--border-color, #333); border-radius: 6px; overflow: hidden; background: var(--bg-card, #121212);">
                                <button type="button" class="update-cart" data-id="{{ $id }}" data-quantity="{{ ($details['quantity'] ?? 1) - 1 }}" style="background: none; border: none; color: var(--text-color, #fff); padding: 4px 10px; cursor: pointer; font-weight: bold;">-</button>
                                <span class="qty-num" style="padding: 4px 8px; font-size: 13px; font-weight: bold; color: var(--text-color, #fff);">{{ $details['quantity'] ?? 1 }}</span>
                                <button type="button" class="update-cart" data-id="{{ $id }}" data-quantity="{{ ($details['quantity'] ?? 1) + 1 }}" style="background: none; border: none; color: var(--text-color, #fff); padding: 4px 10px; cursor: pointer; font-weight: bold;">+</button>
                            </div>

                            <i class="fas fa-trash-alt remove-from-cart" data-id="{{ $id }}" style="color: var(--danger-color, #e74c3c); cursor: pointer; font-size: 15px; padding: 6px; transition: 0.2s;" title="{{ __('cart.remove') }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div style="text-align:center; padding: 60px 20px; color:var(--text-muted, #888);">
                <i class="fas fa-shopping-basket" style="font-size:48px; margin-bottom:15px; opacity:0.3; color: var(--primary-color, #D4AF37);"></i>
                <p style="font-size: 15px; margin: 0;">{{ __('cart.empty') }}</p>
            </div>
        @endif
    </div>

    {{-- Footer with Totals and Checkout Button --}}
    @php 
        $cart_total = 0; 
        if(session('cart')) {
            foreach(session('cart') as $item) {
                $cart_total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }
        }
    @endphp

    <div style="padding: 20px 24px; border-top: 1px solid var(--border-color, #2a2a2a); flex-shrink: 0; background: var(--section-bg, #161616);">
        <div style="display:flex; justify-content:space-between; margin-bottom:12px; color:var(--text-muted, #aaa); font-size:14px;">
            <span>{{ __('cart.subtotal') }}</span>
            <span id="side-cart-subtotal" style="color:var(--text-color, #fff); font-weight: bold; font-size: 16px;">
                ${{ number_format($cart_total, 2) }}
            </span>
        </div>

        <div style="display:flex; justify-content:space-between; margin-bottom:20px; padding-top:10px; border-top: 1px dashed var(--border-color, #333); color:var(--text-color, #fff); font-weight:bold; font-size:16px;">
            <span>{{ __('cart.grand_total') }}</span>
            <span id="side-cart-total" style="color:var(--primary-color, #D4AF37); font-size: 18px;">
                ${{ number_format($cart_total, 2) }}
            </span>
        </div>

            @if(session('cart') && count(session('cart')) > 0)

    @auth
        {{-- المستخدم مسجل دخول --}}
        <a href="{{ route('checkout') }}"
           id="side-cart-checkout-btn"
           class="btn-main"
           style="display: block; text-align: center; width: 100%; padding: 14px; background: var(--primary-color, #D4AF37); color: #000; font-weight: bold; border-radius: 8px; text-decoration: none; transition: 0.3s;">
            <i class="fas fa-check-circle me-2"></i>
            {{ __('cart.checkout') }}
        </a>
    @else
        {{-- المستخدم غير مسجل دخول --}}
        <a href="{{ route('login.page') }}"
           id="side-cart-checkout-btn"
           class="btn-main"
           style="display: block; text-align: center; width: 100%; padding: 14px; background: var(--primary-color, #D4AF37); color: #000; font-weight: bold; border-radius: 8px; text-decoration: none; transition: 0.3s;">
            <i class="fas fa-sign-in-alt me-2"></i>
            {{ __('cart.login_to_checkout') }}
        </a>
    @endauth

@else

    <a href="{{ route('home') }}"
       id="side-cart-checkout-btn"
       class="btn-main"
       style="display: block; text-align: center; width: 100%; padding: 14px; background: var(--border-color, #333); color: #fff; font-weight: bold; border-radius: 8px; text-decoration: none; transition: 0.3s;">
        {{ __('cart.continue_shopping') }}
    </a>

@endif
    </div>

</div>

{{-- Global Side Cart Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sideCart = document.getElementById('side-cart');
    const overlay  = document.getElementById('cart-overlay');
    const closeBtn = document.getElementById('close-cart');
    const itemsContent = document.getElementById('cart-items-content');

   window.updateSideCartCheckoutButton = function(cartCount) {
    var btn = document.getElementById('side-cart-checkout-btn');
    if (!btn) return;

    var count = parseInt(cartCount, 10);

    if (isNaN(count)) {
        var items = itemsContent ? itemsContent.querySelectorAll('.cart-item') : [];
        count = items.length;
    }

    if (count > 0) {

        @auth
            btn.setAttribute('href', '{{ route("checkout") }}');
            btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> {{ __("cart.checkout") }}';
        @else
            btn.setAttribute('href', '{{ route("login.page") }}');
            btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i> {{ __("cart.login_to_checkout") }}';
        @endauth

        btn.style.background = 'var(--primary-color, #D4AF37)';
        btn.style.color = '#000';

    } else {

        btn.setAttribute('href', '{{ route("home") }}');
        btn.innerHTML = '<i class="fas fa-shopping-bag me-2"></i> {{ __("cart.continue_shopping") }}';
        btn.style.background = 'var(--border-color, #333)';
        btn.style.color = '#fff';
    }
};
    // Auto update checkout button state
    window.updateSideCartCheckoutButton();

    // Observe changes inside cart items container to keep button updated
    if (itemsContent) {
        const observer = new MutationObserver(function() {
            window.updateSideCartCheckoutButton();
        });
        observer.observe(itemsContent, { childList: true, subtree: true });
    }

    function openGlobalCart() {
        window.updateSideCartCheckoutButton();
        if (sideCart) sideCart.style.right = '0';
        if (overlay) {
            overlay.style.display = 'block';
            setTimeout(() => { overlay.style.opacity = '1'; }, 10);
        }
    }

    function closeGlobalCart() {
        if (sideCart) sideCart.style.right = '-450px';
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => { overlay.style.display = 'none'; }, 300);
        }
    }

    window.openGlobalCart  = openGlobalCart;
    window.closeGlobalCart = closeGlobalCart;

    // Attach click listeners to all open-cart triggers across the page
    document.body.addEventListener('click', function(e) {
        const trigger = e.target.closest('#open-cart, .cart-btn, [data-open-cart]');
        if (trigger && !trigger.closest('#addToCart')) {
            e.preventDefault();
            openGlobalCart();
        }
    });

    if (closeBtn) closeBtn.onclick = closeGlobalCart;
    if (overlay)  overlay.onclick  = closeGlobalCart;

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGlobalCart();
    });
});
</script>
