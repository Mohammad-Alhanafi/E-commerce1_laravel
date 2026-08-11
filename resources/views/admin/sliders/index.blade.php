<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.sliders') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">

    
    <style>
        /* الخلفية الفخمة */
        body { 
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 30px;
        }

        /* الكارد الرئيسي */
        .main-card { 
            background-color: var(--card-bg);
            border: 1px solid var(--primary-color);
            border-radius: 15px; 
            box-shadow: 0 10px 30px var(--shadow-color);
            overflow: hidden;
        }

        .card-header { 
            background-color: var(--card-bg) !important; 
            border-bottom: 1px solid var(--primary-color) !important;
            padding: 20px;
        }

        .gold-title { color: var(--primary-color); font-weight: bold; }

  /* 1. تجبير الخلفية على الكارد والجدول */
.main-card {
    background-color: var(--bg-color) !important;
    border: 1px solid var(--primary-color);
}

/* 2. جعل الجدول شفاف ليظهر لون الكارد خلفه */
.table {
    background-color: transparent !important;
    color: var(--text-color) !important;
    margin-bottom: 0;
}

.table thead {
    background-color: var(--card-bg) !important;
}

.table thead th {
    color: var(--primary-color) !important; 
    border-bottom: 1px solid var(--primary-color) !important;
    background-color: var(--card-bg) !important;
}

.table td {
    background-color: var(--bg-color) !important;
    border-bottom: 1px solid var(--border-color) !important; 
    color: var(--text-color) !important;
}



.table-responsive {
    background-color: var(--bg-color) !important;
    border: none !important;
}
     

        /* أزرار ذهبية */
        .btn-gold {
            background-color: var(--primary-color);
            color: var(--bg-color);
            font-weight: bold;
            border-radius: 50px;
            padding: 8px 25px;
            border: none;
            transition: 0.3s;
        }
        .btn-gold:hover { background-color: var(--hover-color); color: var(--bg-color); transform: translateY(-2px); }

        /* المودال */
        .modal-content { background-color: var(--card-bg); color: var(--text-color); border: 1px solid var(--primary-color); border-radius: 15px; }
        .modal-header { border-bottom: 1px solid var(--border-color); }
        .form-control { background-color: var(--input-bg); border: 1px solid var(--border-color); color: var(--input-text); border-radius: 8px; }
        .form-control:focus { background-color: var(--input-bg); border-color: var(--primary-color); color: var(--input-text); box-shadow: none; }




        .btn-outline-warning {
    border-color: var(--primary-color);
    color: var(--primary-color);
}
.btn-outline-warning:hover {
    background-color: var(--primary-color);
    color: var(--bg-color);
    border-color: var(--primary-color);
}

.btn-outline-info {
    border-color: var(--info-color, #0dcaf0);
    color: var(--info-color, #0dcaf0);
}

.btn-sm.rounded-circle {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.btn-sm.rounded-circle:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px var(--shadow-color);
}

.gold-btn {
    background-color: var(--primary-color);
    color: var(--bg-color) !important;
    border: 1px solid var(--secondary-color);
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px color-mix(in srgb, var(--primary-color) 20%, transparent);
}

.gold-btn:hover {
    background-color: var(--hover-color);
    color: var(--bg-color) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px color-mix(in srgb, var(--primary-color) 40%, transparent);
}

.gold-btn:active {
    transform: translateY(0);
}

#editSliderModal .modal-content {
    border: 2px solid var(--primary-color);
    box-shadow: 0 0 25px color-mix(in srgb, var(--primary-color) 10%, transparent);
}



.media-preview { 
    width: 85px; 
    height: 55px; 
    object-fit: contain !important; 
    background-color: var(--bg-color) !important; 
    border: 1px solid var(--primary-color);
    border-radius: 8px;
}


/* تنسيق إزاحة المحتوى ليظهر بجانب السايدبار تماماً وبشكل متناسق */
.main-content-wrapper {
    padding-right: 260px; /* قم بتعديل الـ 260px لتطابق العرض الفعلي للسايدبار في مشروعك */
    transition: all 0.3s ease;
    width: 100%;
}

/* إلغاء الإزاحة في الشاشات الصغيرة للموبايل والتابلت لحماية التصميم */
@media (max-width: 992px) {
    .main-content-wrapper {
        padding-right: 0 !important;
    }
}
    </style>
</head>
<body>
@include('admin.sidebar')
@include('admin.header')


<!-- الغلاف لحماية المحتوى من السايدبار -->
<div class="main-content-wrapper">
    <div class="container py-4">
        <div class="card main-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 gold-title"><i class="fas fa-crown me-2"></i> {{ __('admin.sliders') }}</h5>
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fas fa-plus-circle"></i> {{ __('admin.add_slider') }}
                </button>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle text-center">
                        <thead>
                            <tr>
                                <th>{{ __('admin.order') }}</th>
                                <th>{{ __('admin.image') }}/{{ __('admin.video') }}</th>
                                <th class="text-start">{{ __('admin.title') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sliders->sortBy('order') as $slider)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $slider->order }}</td>
                                    @php
                                        $isVideo = $slider->isVideo();
                                    @endphp

                                    @if($isVideo)
                                        <div class="position-relative d-inline-block">
                                            <video class="media-preview" muted onmouseover="this.play()" onmouseout="this.pause(); this.currentTime=0;">
                                                <source src="{{ $slider->image_url }}">
                                            </video>
                                        <small class="position-absolute bottom-0 end-0 text-warning px-1" style="font-size: 8px; border-radius: 4px; background-color: var(--input-bg);">{{ __('admin.video') }}</small>
                                        </div>
                                    @else
                                        <img src="{{ $slider->image_url }}" class="media-preview">
                                    @endif
                                </td>
                                <td class="text-start fw-semibold">{{ $slider->title ?? '---' }}</td>
                                <td>
                                    @if($slider->status == 'active')
                                        <span class="badge border border-success text-success bg-transparent" style="font-size: 0.7rem;">
                                             {{ __('admin.active') }}
                                        </span>
                                    @else
                                        <span class="badge border border-danger text-danger bg-transparent" style="font-size: 0.7rem;">
                                           {{ __('admin.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-info btn-sm rounded-circle" 
                                                onclick="previewMedia('{{ $slider->image_url }}', '{{ $isVideo ? 'video' : 'image' }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="btn btn-outline-warning btn-sm rounded-circle" 
                                                onclick='openEditModal({!! json_encode($slider) !!})'>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST" id="delete-form-{{ $slider->id }}" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="confirmDelete({{ $slider->id }})" title="{{ __('admin.delete') }}">
                                                <i class="fas fa-trash-alt fa-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-5 text-muted">{{ __('admin.no_sliders') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($sliders->hasPages())
            <div class="d-flex justify-content-center py-3">
                <nav class="admin-pagination">
                    {{ $sliders->links('pagination::bootstrap-5') }}
                </nav>
            </div>
            @endif

        </div>
    </div>
</div> {{-- إغلاق الغلاف --}}

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('sliders.store') }}" id="addSliderForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title gold-title">{{ __('admin.add_slider') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">{{ __('admin.title') }}</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">{{ __('admin.image') }} / {{ __('admin.video') }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*,video/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small gold-text">{{ __('admin.status') }}</label>
                        <select name="status" class="form-control">
                            <option value="active" selected>{{ __('admin.active') }}</option>
                            <option value="inactive">{{ __('admin.inactive') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">{{ __('admin.order') }}</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-gold w-100">{{ __('admin.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSliderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--card-bg); border: 1px solid var(--primary-color);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title gold-title">{{ __('admin.edit') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSliderForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="gold-text small">{{ __('admin.title') }}</label>
                        <input type="text" id="edit_title" name="title" class="form-control">
                    </div>
                     <div class="mb-3">
                        <label class="gold-text small">{{ __('admin.status') }}</label>
                        <select id="edit_status" name="status" class="form-control text-white">
                            <option value="active">{{ __('admin.active') }}</option>
                            <option value="inactive">{{ __('admin.inactive') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="gold-text small">{{ __('admin.order') }}</label>
                        <input type="number" id="edit_order" name="order" class="form-control">
                    </div>
                </div>
                <div class="p-3">
                    <button type="submit" class="btn gold-btn w-100 py-2 fw-bold">
                        <i class="fas fa-save me-1"></i> {{ __('admin.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    @include('admin.footer')

      




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
// ── Translations injected from PHP → JS ──────────────────────────────────────
window.AppTrans = {!! json_encode([
    'update_success'   => __('admin.update_success'),
    'add_success'      => __('admin.add_success'),
    'error_title'      => __('admin.error_title'),
    'delete_confirm'   => __('admin.delete_confirm'),
    'delete_success'   => __('admin.delete_success'),
    'yes_delete'       => __('admin.yes_delete'),
    'cancel'           => __('admin.cancel'),
    'unexpected_error' => __('admin.unexpected_error'),
    'alert_data_error' => __('admin.alert_data_error'),
], JSON_UNESCAPED_UNICODE) !!};
window.AppLocale = '{{ app()->getLocale() }}';
// ─────────────────────────────────────────────────────────────────────────────
        // تشغيل السلايدر
        const swiper = new Swiper('#previewSwiper', {
            loop: true,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        // فتح المودل وتعبئة البيانات
      function openEditModal(slider) {
    $('#edit_id').val(slider.id);
    $('#edit_title').val(slider.title);
    $('#edit_order').val(slider.order);
    $('#edit_status').val(slider.status);
    $('#editSliderModal').modal('show');
}

$('#editSliderForm').on('submit', function(e) {
    e.preventDefault();
    
    let id = $('#edit_id').val();
    let formData = new FormData(this); 
    formData.append('_method', 'PUT'); 

    $.ajax({
        url: "/sliders/" + id, 
        type: 'POST', 
        data: formData,
        processData: false, 
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      success: function(response) {
    $('#editSliderModal').modal('hide');
    
    Swal.fire({
        icon: 'success',
        title: window.AppTrans.update_success,
        background: '#161616',
        color: '#d4af37',
        iconColor: '#d4af37',
        showConfirmButton: false,
        timer: 1500, 
        position: 'top-end', 
        toast: true 
    }).then(() => {
        location.reload(); 
    });
},
        error: function(xhr) {
            console.error("الخطأ:", xhr.responseText);
            
            let errorMsg = window.AppTrans.unexpected_error;
            if(xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: window.AppTrans.error_title,
                text: errorMsg,
                background: '#000',
                color: '#ff4444'
            });
        }
    });
});

function previewMedia(url, type) {
    if (type === 'video') {
        Swal.fire({
            html: `<video width="100%" controls autoplay><source src="${url}"></video>`,
            background: '#000',
            showConfirmButton: false,
        });
    } else {
        Swal.fire({
            imageUrl: url,
            imageWidth: 600,
            background: '#000',
            showConfirmButton: false,
        });
    }
}



function confirmDelete(id) {
    Swal.fire({
        title: window.AppTrans.delete_confirm,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: window.AppTrans.yes_delete,
        cancelButtonText: window.AppTrans.cancel,
        background: '#161616',
        color: '#d4af37',
        iconColor: 'red'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}


$('#addSliderForm').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('sliders.store') }}", 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                $('#addSliderForm')[0].reset();
                $('#addModal').modal('hide'); 
                
                Swal.fire({
                    icon: 'success',
                    title: window.AppTrans.add_success,
                    background: '#161616',
                    color: '#d4af37',
                    iconColor: '#d4af37',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                }).then(() => {
                    location.reload(); 
                });
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: window.AppTrans.error_title,
                text: window.AppTrans.alert_data_error,
                background: '#000',
                color: '#ff4444'
            });
        }
    });
});


    </script>
</body>
</html>