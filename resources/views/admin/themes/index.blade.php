{{-- Admin Themes Index --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات المظهر | لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <style>
        :root{
            --bg:            var(--bg-color, #0b0b0d);
            --surface:       var(--card-bg, #17171a);
            --border:        var(--border-color, rgba(201,162,39,.16));
            --gold:          var(--primary-color, #c9a227);
            --ink:           var(--text-color, #f3efe4);
            --ink-muted:     var(--text-muted, #a8a297);
            --radius-lg: 18px;
        }
        body { font-family: 'Cairo', sans-serif; background: var(--bg); color: var(--ink); }
        .main-content-wrapper{ padding: 34px 28px 56px; margin-right: 260px; }
        @media (max-width: 992px){ .main-content-wrapper{ margin-right: 0; } }

        .page-head {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--border); padding-bottom: 20px; margin-bottom: 30px;
        }
        .page-head h1 { margin: 0; font-size: 24px; font-weight: 700; }
        
        .btn-gold {
            background: var(--gold); color: #000; border: none; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer;
        }
        .btn-gold:hover { color: #000; opacity: 0.9; }

        .theme-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;
        }
        .theme-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column;
        }
        .theme-card.active { border-color: var(--gold); box-shadow: 0 0 15px rgba(201,162,39,0.3); }
        .theme-preview {
            height: 150px; background: #222; display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .theme-swatches { display: flex; gap: 5px; }
        .swatch { width: 30px; height: 30px; border-radius: 50%; border: 2px solid #fff; }
        
        .theme-info { padding: 20px; flex: 1; }
        .theme-info h3 { margin: 0 0 10px; font-size: 18px; }
        .theme-info p { color: var(--ink-muted); font-size: 14px; margin-bottom: 15px; }
        
        .theme-actions {
            display: flex; gap: 10px; padding: 15px 20px; border-top: 1px solid var(--border);
            background: rgba(0,0,0,0.2);
        }
        .btn-action {
            flex: 1; text-align: center; padding: 8px; border-radius: 6px;
            text-decoration: none; font-size: 13px; font-weight: 600; cursor: pointer; border: none;
        }
        
.btn-activate { background: #28A745; color: #fff; }
.btn-edit { background: var(--gold); color: #000; }

.btn-action.no-hover:hover {
    background: var(--gold) !important;
    color: #000 !important;
    opacity: 1 !important;
    filter: none !important;
    box-shadow: none !important;
    transform: none !important;
}

.btn-outline { background: transparent; border: 1px solid var(--border); color: var(--ink); }



        .btn-outline:hover { background: rgba(255,255,255,0.05); color: var(--ink); }

        .badge-active {
            position: absolute; top: 10px; right: 10px; background: var(--gold); color: #000;
            padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;
        }

        .import-section {
            background: var(--surface); padding: 20px; border-radius: var(--radius-lg);
            border: 1px dashed var(--border); margin-bottom: 30px; display: none;
        }
    </style>
</head>
<body>
@include('admin.header')
@include('admin.sidebar')

<div class="main-content-wrapper">
    <div class="page-head">
        <div>
            <h1>إدارة القوالب (Theme Management)</h1>
            <p style="color: var(--ink-muted); margin-top:5px;">تحكم كامل بألوان ومظهر المتجر</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="document.getElementById('import-section').style.display='block'" class="btn-gold" style="background: transparent; border: 1px solid var(--gold); color: var(--gold);">
                <i class="fas fa-file-import"></i> استيراد قالب
            </button>
            <a href="{{ route('admin.themes.create') }}" class="btn-gold"><i class="fas fa-plus"></i> إنشاء قالب جديد</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(40, 167, 69, 0.2); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #28a745;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(220, 53, 69, 0.2); color: #dc3545; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dc3545;">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: rgba(220, 53, 69, 0.2); color: #dc3545; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dc3545;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="import-section" class="import-section">
        <form action="{{ route('admin.themes.import') }}" method="POST" enctype="multipart/form-data" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 5px;">اختر ملف القالب <span style="color: var(--ink-muted); font-size: 12px;">(JSON أو ZIP)</span></label>
                <input type="file" name="theme_file" accept=".json,.zip" required style="background: #000; color: #fff; padding: 10px; border-radius: 5px; border: 1px solid var(--border);">
                <small style="display: block; margin-top: 4px; color: var(--ink-muted);">يمكنك رفع ملف <code>.json</code> مباشرة أو ملف <code>.zip</code> يحتوي على ملف JSON</small>
            </div>
            <button type="submit" class="btn-gold" style="margin-top: 25px;">استيراد</button>
            <button type="button" onclick="document.getElementById('import-section').style.display='none'" class="btn-action btn-outline" style="margin-top: 25px; flex: none; padding: 10px 20px;">إلغاء</button>
        </form>
    </div>

    <div class="theme-grid">
        @foreach($themes as $t)
            @php 
                $colors = is_array($t->colors) ? $t->colors : json_decode($t->colors, true) ?? [];
                $bg = $colors['background'] ?? '#1A1A1A';
            @endphp
            <div class="theme-card {{ $t->is_active ? 'active' : '' }}">
                <div class="theme-preview" style="background-color: {{ $bg }}">
                    @if($t->is_active)
                        <div class="badge-active">مفعل حالياً</div>
                    @endif
                    <div class="theme-swatches">
                        <div class="swatch" style="background-color: {{ $colors['primary'] ?? '#ccc' }}" title="Primary"></div>
                        <div class="swatch" style="background-color: {{ $colors['secondary'] ?? '#ccc' }}" title="Secondary"></div>
                        <div class="swatch" style="background-color: {{ $colors['accent'] ?? '#ccc' }}" title="Accent"></div>
                    </div>
                </div>
                <div class="theme-info">
                    <h3>{{ $t->name }}</h3>
                    <p>{{ $t->description ?? 'لا يوجد وصف.' }}</p>
                    <small style="color: var(--ink-faint)">تاريخ التحديث: {{ $t->updated_at->format('Y-m-d') }}</small>
                </div>
                <div class="theme-actions">
                    @if(!$t->is_active)
                        <form action="{{ route('admin.themes.activate', $t) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn-action btn-activate" style="width: 100%;"><i class="fas fa-check"></i> تفعيل</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.themes.show', $t) }}" class="btn-action btn-edit no-hover"><i class="fas fa-edit"></i> تعديل</a>
                    
                    <div style="position: relative; flex: 1;">
                        <button onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block'" class="btn-action btn-outline" style="width: 100%;"><i class="fas fa-ellipsis-h"></i></button>
                        <div style="display: none; position: absolute; bottom: 100%; left: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 5px; width: 120px; z-index: 10;">
                            <form action="{{ route('admin.themes.duplicate', $t) }}" method="POST" style="margin: 5px 0;">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:var(--ink); width:100%; text-align:right; padding:5px; cursor:pointer;"><i class="fas fa-copy"></i> تكرار</button>
                            </form>
                            <a href="{{ route('admin.themes.export', $t) }}" style="display:block; color:var(--ink); text-decoration:none; padding:5px; margin: 5px 0;"><i class="fas fa-download"></i> تصدير JSON</a>
                            <a href="{{ route('admin.themes.export.zip', $t) }}" style="display:block; color:var(--ink); text-decoration:none; padding:5px; margin: 5px 0;"><i class="fas fa-file-archive"></i> تصدير ZIP</a>
                            @if(!$t->is_active)
                                <form action="{{ route('admin.themes.destroy', $t) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')" style="margin: 5px 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#dc3545; width:100%; text-align:right; padding:5px; cursor:pointer;"><i class="fas fa-trash"></i> حذف</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('admin.footer')
</body>
</html>
