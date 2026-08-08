<?php

/**
 * Theme Builder — variable registry.
 * Add new keys here to extend the system without schema changes.
 */
return [

    'statuses' => ['draft', 'published'],

    'modes' => ['light', 'dark', 'both'],

    /*
    |--------------------------------------------------------------------------
    | Default theme palette (Luxury Gold)
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'primary'              => '#D4AF37',
        'secondary'            => '#B8941E',
        'accent'               => '#E8C547',
        'background'           => '#1A1A1A',
        'surface'              => '#111111',
        'text_primary'         => '#FFFFFF',
        'text_secondary'       => '#AAAAAA',
        'heading'              => '#FFFFFF',
        'border'               => '#3A3A3A',
        'success'              => '#28A745',
        'warning'              => '#FFC107',
        'danger'               => '#DC3545',
        'info'                 => '#17A2B8',

        /* ─── Order Status Colors ─── */
        'order_pending'        => '#FFC107',  // قيد الانتظار
        'order_processing'     => '#17A2B8',  // جاري المعالجة
        'order_completed'      => '#28A745',  // مكتمل
        'order_shipped'        => '#D4AF37',  // تم الشحن
        'order_canceled'       => '#DC3545',  // ملغي
        'btn_primary'          => '#D4AF37',
        'btn_secondary'        => '#B8941E',
        'btn_outline'          => '#D4AF37',
        'btn_hover'            => '#C89B2C',
        'btn_active'           => '#A8841A',
        'btn_disabled'         => '#555555',
        'btn_text'             => '#000000',
        'link_normal'          => '#D4AF37',
        'link_hover'           => '#E8C547',
        'link_active'          => '#B8941E',
        'input_bg'             => '#222222',
        'input_border'         => '#3A3A3A',
        'input_focus_border'   => '#D4AF37',
        'input_placeholder'    => '#666666',
        'input_label'          => '#CCCCCC',
        'navbar_bg'            => '#111111',
        'navbar_text'          => '#FFFFFF',
        'sidebar_bg'           => '#111111',
        'sidebar_text'         => '#AAAAAA',
        'sidebar_active'       => '#D4AF37',
        'sidebar_hover'        => '#2C2C2C',
        'footer_bg'            => '#111111',
        'footer_text'          => '#AAAAAA',
        'footer_links'         => '#D4AF37',
    ],

    /*
    |--------------------------------------------------------------------------
    | Light-mode overrides (merged when mode is light or both + light preview)
    |--------------------------------------------------------------------------
    */
    'light_overrides' => [
        'background'           => '#F8F9FA',
        'surface'              => '#FFFFFF',
        'text_primary'         => '#212529',
        'text_secondary'       => '#6C757D',
        'heading'              => '#212529',
        'border'               => '#DEE2E6',
        'input_bg'             => '#FFFFFF',
        'input_border'         => '#DEE2E6',
        'input_placeholder'    => '#ADB5BD',
        'input_label'          => '#495057',
        'navbar_bg'            => '#FFFFFF',
        'navbar_text'          => '#212529',
        'sidebar_bg'           => '#FFFFFF',
        'sidebar_text'         => '#6C757D',
        'sidebar_hover'        => '#F1F3F5',
        'footer_bg'            => '#F1F3F5',
        'footer_text'          => '#6C757D',
        'btn_text'             => '#000000',
    ],

    /*
    |--------------------------------------------------------------------------
    | CSS variable mapping: internal key => CSS custom property name
    |--------------------------------------------------------------------------
    */
    'css_map' => [
        'primary'              => '--primary',
        'secondary'            => '--secondary',
        'accent'               => '--accent',
        'background'           => '--background',
        'surface'              => '--surface',
        'text_primary'         => '--text-primary',
        'text_secondary'       => '--text-secondary',
        'heading'              => '--heading',
        'border'               => '--border',
        'success'              => '--success',
        'warning'              => '--warning',
        'danger'               => '--danger',
        'info'                 => '--info',
        'order_pending'        => '--order-pending',
        'order_processing'     => '--order-processing',
        'order_completed'      => '--order-completed',
        'order_shipped'        => '--order-shipped',
        'order_canceled'       => '--order-canceled',
        'btn_primary'          => '--btn-primary',
        'btn_secondary'        => '--btn-secondary',
        'btn_outline'          => '--btn-outline',
        'btn_hover'            => '--btn-hover',
        'btn_active'           => '--btn-active',
        'btn_disabled'         => '--btn-disabled',
        'btn_text'             => '--btn-text',
        'link_normal'          => '--link-normal',
        'link_hover'           => '--link-hover',
        'link_active'          => '--link-active',
        'input_bg'             => '--input-bg',
        'input_border'         => '--input-border',
        'input_focus_border'   => '--input-focus-border',
        'input_placeholder'    => '--input-placeholder',
        'input_label'          => '--input-label',
        'navbar_bg'            => '--navbar-bg',
        'navbar_text'          => '--navbar-text',
        'sidebar_bg'           => '--sidebar-bg',
        'sidebar_text'         => '--sidebar-text',
        'sidebar_active'       => '--sidebar-active',
        'sidebar_hover'        => '--sidebar-hover',
        'footer_bg'            => '--footer-bg',
        'footer_text'          => '--footer-text',
        'footer_links'         => '--footer-links',
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy aliases for existing theme.css (--primary-color, --bg-color, etc.)
    |--------------------------------------------------------------------------
    */
    'legacy_css_map' => [
        'primary'              => '--primary-color',
        'secondary'            => '--secondary-color',
        'btn_hover'            => '--hover-color',
        'background'           => '--bg-color',
        'surface'              => '--card-bg',
        'text_primary'         => '--text-color',
        'text_secondary'       => '--text-muted',
        'border'               => '--border-color',
        'success'              => '--success-color',
        'warning'              => '--warning-color',
        'danger'               => '--danger-color',
        'info'                 => '--info-color',
        'order_pending'        => '--order-pending-color',
        'order_processing'     => '--order-processing-color',
        'order_completed'      => '--order-completed-color',
        'order_shipped'        => '--order-shipped-color',
        'order_canceled'       => '--order-canceled-color',
        'input_bg'             => '--input-bg',
        'navbar_bg'            => '--navbar-bg',
        'footer_bg'            => '--footer-bg',
        'sidebar_bg'           => '--sidebar-bg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin UI groups (order + translation keys)
    |--------------------------------------------------------------------------
    */
    'groups' => [
        'core' => [
            'label' => 'theme.groups.core',
            'icon'  => 'bi-palette',
            'keys'  => ['primary', 'secondary', 'accent', 'background', 'surface', 'text_primary', 'text_secondary', 'heading', 'border'],
        ],
        'status' => [
            'label' => 'theme.groups.status',
            'icon'  => 'bi-check-circle',
            'keys'  => ['success', 'warning', 'danger', 'info'],
        ],
        'order_status' => [
            'label' => 'theme.groups.order_status',
            'icon'  => 'bi-bag-check',
            'keys'  => ['order_pending', 'order_processing', 'order_completed', 'order_shipped', 'order_canceled'],
        ],
        'buttons' => [
            'label' => 'theme.groups.buttons',
            'icon'  => 'bi-hand-index',
            'keys'  => ['btn_primary', 'btn_secondary', 'btn_outline', 'btn_hover', 'btn_active', 'btn_disabled', 'btn_text'],
        ],
        'links' => [
            'label' => 'theme.groups.links',
            'icon'  => 'bi-link-45deg',
            'keys'  => ['link_normal', 'link_hover', 'link_active'],
        ],
        'forms' => [
            'label' => 'theme.groups.forms',
            'icon'  => 'bi-input-cursor-text',
            'keys'  => ['input_bg', 'input_border', 'input_focus_border', 'input_placeholder', 'input_label'],
        ],
        'navigation' => [
            'label' => 'theme.groups.navigation',
            'icon'  => 'bi-layout-sidebar',
            'keys'  => ['navbar_bg', 'navbar_text', 'sidebar_bg', 'sidebar_text', 'sidebar_active', 'sidebar_hover'],
        ],
        'footer' => [
            'label' => 'theme.groups.footer',
            'icon'  => 'bi-layout-text-window-reverse',
            'keys'  => ['footer_bg', 'footer_text', 'footer_links'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Keys auto-generated from primary (unless manually overridden)
    |--------------------------------------------------------------------------
    */
    'generated_from_primary' => [
        'secondary', 'accent', 'btn_primary', 'btn_secondary', 'btn_outline',
        'btn_hover', 'btn_active', 'btn_text', 'link_normal', 'link_hover', 'link_active',
        'input_focus_border', 'sidebar_active', 'footer_links', 'border',
    ],

];
