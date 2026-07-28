{{--
    ============================================================
    resources/views/admin/footer.blade.php — Admin Footer Partial
    Included via @include('admin.footer') — no HTML page wrapper.
    ============================================================
--}}
    <style>
    /* =========================================================
       FOOTER — scoped tokens (safe to reuse site-wide, not just
       inside the admin panel). Same boutique language as the
       settings page: charcoal ground, restrained gold, one
       signature motif — the clasp — echoed here as a top seam.
    ========================================================= */
    .footer-full{
        --f-bg:        #0b0b0d;
        --f-surface:   #141416;
        --f-border:    rgba(201,162,39,.16);
        --f-border-s:  rgba(201,162,39,.34);
        --f-gold:      #c9a227;
        --f-gold-light:#e8cc6b;
        --f-gold-dim:  #8a7328;
        --f-ink:       #f3efe4;
        --f-ink-muted: #a8a297;
        --f-ink-faint: #6f6a60;
        --f-whatsapp:  #2fbf60;
        --f-ease: cubic-bezier(.4,0,.2,1);
 
        position: relative;
        background:
            radial-gradient(900px 400px at 85% 0%, rgba(201,162,39,.05), transparent 60%),
            var(--f-bg);
        font-family: 'Cairo', sans-serif;
        color: var(--f-ink);
        margin-top: 0 !important;
    }
 
    /* the seam — a thin gold hairline with a centered clasp,
       tying the footer back to the card motif above it */
    .footer-full::before{
        content: "";
        position: absolute;
        top: 0; right: 0; left: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--f-border-s) 50%, transparent);
    }
    .footer-full::after{
        content: "";
        position: absolute;
        top: -6px; right: 50%;
        transform: translateX(50%) rotate(45deg);
        width: 11px; height: 11px;
        background: var(--f-gold);
        border-radius: 3px;
        box-shadow: 0 2px 10px rgba(201,162,39,.45);
    }
 
    .footer-full .container{ max-width: 1120px; }
 
    .footer-panel{
        display: grid;
        grid-template-columns: 1.3fr .9fr;
        gap: 48px;
        align-items: start;
    }
 
    /* ---------- brand column ---------- */
    .footer-brand{ max-width: 420px; }
 
    .footer-kicker{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .4px;
        color: var(--f-gold);
    }
    .footer-kicker::before{
        content: "";
        width: 14px; height: 1px;
        background: var(--f-gold-dim);
        display: inline-block;
    }
 
    .footer-full .footer-title{
        margin: 2px 0 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--f-ink);
        letter-spacing: .2px;
    }
 
    .footer-full .footer-text{
        font-size: 13.5px;
        line-height: 1.9;
        color: var(--f-ink-muted);
        margin: 0;
    }
 
    .footer-full .footer-subtitle{
        font-size: 12.5px;
        color: var(--f-ink-faint);
        margin: 0;
    }
 
    /* QR block */
    .qr-code-zone{
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 6px;
        padding: 12px 16px;
        background: var(--f-surface);
        border: 1px solid var(--f-border);
        border-radius: 14px;
        transition: border-color .2s var(--f-ease), background .2s var(--f-ease);
    }
    .qr-code-zone:hover{
        border-color: var(--f-border-s);
        background: #17171a;
    }
    .qr-code-wrapper{
        background: #0f0f10;
        padding: 6px;
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,.06);
        line-height: 0;
        flex-shrink: 0;
    }
    .qr-code-wrapper #qrcode img{
        display: block;
        border-radius: 3px;
    }
    .qr-meta{ display: flex; flex-direction: column; gap: 2px; }
    .qr-meta .qr-label{
        font-size: 12.5px;
        color: var(--f-gold-light);
        font-weight: 700;
    }
    .qr-meta .qr-desc{
        font-size: 11px;
        color: var(--f-ink-faint);
        line-height: 1.5;
    }
 
    /* ---------- contact column ---------- */
    .footer-contact-card{
        background: var(--f-surface);
        border: 1px solid var(--f-border);
        border-radius: 16px;
        padding: 24px 26px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .footer-contact-card .footer-title{ font-size: 15px; margin-bottom: 2px; }
 
    .social-icons{
        display: flex;
        gap: 10px;
    }
    .social-icons a{
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--f-border);
        color: var(--f-gold-light);
        font-size: 15px;
        text-decoration: none;
        transition: background .2s var(--f-ease), color .2s var(--f-ease), transform .15s var(--f-ease);
    }
    .social-icons a:hover{
        background: var(--f-gold);
        color: #0b0b0d;
        transform: translateY(-2px);
    }
    .social-icons a:focus-visible{
        outline: 2px solid var(--f-gold-light);
        outline-offset: 2px;
    }
 
    .developer-credit{
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: var(--f-ink-faint);
        padding-top: 12px;
        border-top: 1px solid var(--f-border);
    }
    .developer-credit .developer-name{
        color: var(--f-ink);
        font-weight: 700;
    }
 
    .developer-phone{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        padding: 8px 16px;
        border-radius: 999px;
        border: 1px solid rgba(47,191,96,.4);
        color: var(--f-whatsapp);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s var(--f-ease), color .2s var(--f-ease);
    }
    .developer-phone:hover{
        background: var(--f-whatsapp);
        color: #0b0b0d;
    }
    .developer-phone:focus-visible{
        outline: 2px solid var(--f-gold-light);
        outline-offset: 2px;
    }
 
    /* ---------- bottom bar ---------- */
    .footer-separator{
        border: 0;
        border-top: 1px solid var(--f-border);
        margin: 40px 0 20px;
    }
    .footer-bottom{
        text-align: center;
        font-size: 12px;
        color: var(--f-ink-faint);
        letter-spacing: .2px;
    }
 
    /* ---------- responsive ---------- */
    @media (max-width: 900px){
        .footer-panel{ grid-template-columns: 1fr; gap: 32px; }
        .footer-brand{ max-width: none; }
    }
    @media (max-width: 480px){
        .qr-code-zone{ flex-direction: column; align-items: flex-start; }
    }
 
    @media (prefers-reduced-motion: reduce){
        .footer-full *{ transition: none !important; }
    }
</style>
 
<footer class="footer-full mt-5">
    <div class="container py-5">
        <div class="footer-panel">

            {{-- البراند + النص + QR --}}
            <div class="footer-brand">

                <span class="footer-kicker">
                    {{ __('footer.tagline') }}
                </span>

                <h5 class="footer-title" id="footerStoreName">
                    {{ $settings['store_name'] ?? __('footer.store_name') }}
                </h5>

                <p class="footer-text">
                    {{ __('footer.description') }}
                </p>

                <p class="footer-subtitle">
                    {{ __('footer.follow_us_text') }}
                </p>


                <div class="qr-code-zone">
                    <div class="qr-code-wrapper">
                        <div id="qrcode"></div>
                    </div>

                    <div class="qr-meta">
                        <span class="qr-label">
                            {{ __('footer.qr_label') }}
                        </span>

                        <span class="qr-desc">
                            {{ __('footer.qr_description') }}
                        </span>
                    </div>
                </div>

            </div>


            {{-- التواصل والمطور --}}
            <div class="footer-contact-card">

                <h5 class="footer-title">
                    {{ __('footer.follow_us') }}
                </h5>


                <div class="social-icons" aria-label="social links">

                    <a href="" aria-label="tiktok">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <a href="https://www.instagram.com/waqaar.leb?igsh=MW5oYWM2dHU1MnBjMA==" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="https://wa.me/96181025201" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                </div>


                <div class="developer-credit">
                    <span>{{ __('footer.developed_by') }}</span>

                    <strong class="developer-name">
                        Mohammad Alhanafi
                    </strong>
                </div>


                <a class="developer-phone" href="https://wa.me/96170842075" dir="ltr">
                    <i class="fab fa-whatsapp"></i>
                    +961 70 842 075
                </a>

            </div>

        </div>


        <hr class="footer-separator">


        <div class="footer-bottom">
            © 2026 — {{ __('footer.rights_reserved') }}
        </div>

    </div>
</footer>
 
{{-- مكتبة توليد الـ QR Code --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById("qrcode"), {
        text: window.location.origin,
        width: 58,
        height: 58,
        colorDark: "#c9a227",
        colorLight: "#0f0f10",
        correctLevel: QRCode.CorrectLevel.H
    });
});
</script>