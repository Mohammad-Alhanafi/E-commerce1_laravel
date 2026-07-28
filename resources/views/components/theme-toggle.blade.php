{{--
    ============================================================
    components/theme-toggle.blade.php
    ────────────────────────────────────────────────────────────
    PURPOSE:
      Loads theme.js which creates the floating toggle button.
      Place this @include just before the closing </body> tag
      (or at the bottom of your footer component).

    USAGE:
      @include('components.theme-toggle')

    The actual button is injected by theme.js into the DOM.
    The button's CSS is already in theme.css (Section 25).
    ============================================================
--}}

{{-- Load the theme toggle script ─────────────────────────────
     defer: runs after HTML is parsed, non-blocking
--}}
<script src="{{ asset('js/theme.js') }}" defer></script>
