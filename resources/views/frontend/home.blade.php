<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('components.theme-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('home.title') ?? 'الصفحة الرئيسية' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        video.media-preview {
            width: auto;
            max-width: 85px;
            height: 55px;
            background-color: #000;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/adminpanel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    @include('admin.header')
   
    <div class="swiper mySwiper home-slider">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
                <div class="swiper-slide home-slide">
                    
                    @php
                        $extension = pathinfo($slider->image, PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'ogg', 'webm']);
                    @endphp

                    @if($isVideo)
                        <div class="video-slider-wrapper">     
                            <video muted loop playsinline class="video-background">
                                <source src="{{ asset('storage/'.$slider->image) }}" type="video/{{ $extension }}">
                            </video>

                            <!-- إصلاح: إغلاق وسم الفيديو المفتوح هنا -->
                            <video autoplay muted loop playsinline class="video-main">
                                <source src="{{ asset('storage/'.$slider->image) }}" type="video/{{ $extension }}">
                                متصفحك لا يدعم تشغيل الفيديو.
                            </video>
                        </div>
                    @else
                        <!-- إصلاح: إغلاق وسم الـ img المفتوح -->
                        <img src="{{ asset('storage/'.$slider->image) }}" alt="{{ $slider->title }}" class="slider-image">
                    @endif

                    @if($slider->title || $slider->link)
                        <div class="slide-content">
                            @if($slider->title)
                                <h1 class="slider-title">{{ $slider->title }}</h1>
                            @endif

                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="btn-main">اكتشف المزيد</a>
                            @endif
                        </div>
                        
                        <!-- إصلاح: إغلاق وسم طبقة التعتيم بشكل صحيح -->
                        <div class="slider-overlay"></div>
                    @endif

                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Categories Section -->
    <section class="waqar-categories py-5">
        <div class="container">
            <div class="waqar-cat-header">
                <div>
                    <div class="waqar-cat-eyebrow"></div>
                    <h2 class="waqar-cat-title" contenteditable="{{ (Auth::check() && Auth::user()->role == 'admin') ? 'true' : 'false' }}"></h2>
                </div>
                @if(Auth::check() && Auth::user()->role == 'admin')
                    <a href="{{ route('category.fast_store') }}" class="waqar-add-btn">
    <i class="fas fa-plus"></i> {{ __('categories.add_new') }}
</a>
                @endif
            </div>

            <div class="waqar-cat-grid">
                @foreach($categories as $category)
                    @php
                        $shape = $settings['category_style_' . $category->id] ?? 'square';
                        $coverProduct = $category->products->first();
                        if ($coverProduct && $coverProduct->image) {
                            $catImageUrl = asset('storage/' . $coverProduct->image);
                        } elseif ($category->image) {
                            $catImageUrl = asset($category->image);
                        } else {
                            $catImageUrl = asset('assets/images/default-cat.jpg');
                        }
                    @endphp

                    <a href="{{ url('/category/' . $category->id) }}" class="waqar-cat-card shape-{{ $shape }}" id="category-item-{{ $category->id }}">

                        @if(Auth::check() && Auth::user()->role == 'admin')
                            <div class="waqar-admin-controls" onclick="event.preventDefault(); event.stopPropagation();">
                                <button onclick="triggerUpload({{ $category->id }})" class="waqar-admin-btn cam">
                                    <i class="fas fa-camera"></i>
                                </button>

                                <div class="waqar-shape-picker">
                                    <button onclick="toggleShapePicker({{ $category->id }})" class="waqar-admin-btn shape">
                                        <i class="fas fa-shapes"></i>
                                    </button>
                                    <div class="waqar-shape-options d-none" id="shape-options-{{ $category->id }}">
                                        <button data-shape="square" onclick="setCategoryShape({{ $category->id }}, 'square')" title="{{ __('categories.square') }}"></button>
                                        <button data-shape="soft" onclick="setCategoryShape({{ $category->id }}, 'soft')" title="{{ __('categories.soft') }}"></button>
                                        <button data-shape="round" onclick="setCategoryShape({{ $category->id }}, 'round')" title="{{ __('categories.round') }}"></button>
                                    </div>
                                </div>

                                <button onclick="deleteCategory({{ $category->id }})" class="waqar-admin-btn del">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endif

                        <div class="waqar-cat-img-wrap">
                            <img src="{{ $catImageUrl }}" alt="{{ $category->name }}" id="img-{{ $category->id }}">
                        </div>

                        <div class="waqar-cat-overlay"></div>

                        <div class="waqar-cat-content">
                            <h3 onclick="event.preventDefault(); event.stopPropagation();"
                                contenteditable="{{ (Auth::check() && Auth::user()->role == 'admin') ? 'true' : 'false' }}"
                                onblur="updateCategoryText({{ $category->id }}, 'name', this.innerText)">
                                {{ $category->name }}
                            </h3>
                            <div class="waqar-cat-divider"></div>
                            <p onclick="event.preventDefault(); event.stopPropagation();"
                               contenteditable="{{ (Auth::check() && Auth::user()->role == 'admin') ? 'true' : 'false' }}"
                               onblur="updateCategoryText({{ $category->id }}, 'description', this.innerText)">
                                {{ $category->description }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <input type="file" id="admin-image-uploader" style="display: none;" onchange="uploadImage(this)">

    <!-- Comments Section -->
    <section class="waqar-comments py-5">
        <div class="container">
            <div class="waqar-com-header">
                <div class="waqar-com-eyebrow"></div>
            </div>
<br> <br>
            @auth
            <button type="button" onclick="toggleCommentModal()" class="comment-add-btn border-0 shadow-sm" title="إضافة تعليق">
    <i class="fas fa-comment-dots"></i>
</button>
       @endauth

            <div class="swiper comments-swiper">
                <div class="swiper-wrapper">
                    @foreach($comments as $comment)
                        <div class="swiper-slide comment-slide">
                            <div class="waqar-comment-card chat-bubble" id="comment-{{ $comment->id }}">
                                
                                <div class="waqar-com-top">
                                    <div class="waqar-com-user">
                                        <div class="waqar-avatar">
                                            {{ mb_substr($comment->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="waqar-com-name">{{ $comment->name }}</span>
                                            <span class="waqar-com-time">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="waqar-com-text">{{ $comment->comment }}</p>

                                <div class="waqar-com-actions">
                                    @if(Auth::check() && Auth::user()->role != 'admin')
                                    <button class="waqar-action-btn" onclick="likeComment({{ $comment->id }})">
                                        <i class="fas fa-heart"></i>
                                        <span id="like-{{ $comment->id }}">{{ $comment->likes->count() }}</span>
                                    </button>
                                    @endif

                                    @if(Auth::check() && Auth::user()->role == 'admin')
                                    <button class="waqar-action-btn" onclick="openEdit({{ $comment->id }}, `{{ $comment->comment }}`)">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="waqar-action-btn" onclick="toggleReplyBox({{ $comment->id }})">
                                        <i class="fas fa-reply"></i> {{ __('home.reply') }}
                                    </button>
                                    <button class="waqar-action-btn danger" onclick="deleteComment({{ $comment->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>

                                <div class="waqar-com-divider"></div>

                                <!-- إصلاح: تم حذف التكرار المزدوج لبلوك الـ waqar-replies هنا -->
                                <div class="waqar-replies" id="replies-{{ $comment->id }}">
    @if($comment->replies && $comment->replies->count())
        @foreach($comment->replies as $reply)
            <div class="waqar-reply-item">
                <span class="waqar-reply-name">{{ $reply->name ?? 'الإدارة' }}</span>
                <span>{{ $reply->comment }}</span>
            </div>
        @endforeach
    @endif
</div>

                                @if(Auth::check() && Auth::user()->role == 'admin')
                                    <div class="waqar-reply-box d-none" id="reply-box-{{ $comment->id }}">
                                        <textarea id="reply-{{ $comment->id }}" placeholder="{{ __('home.write_reply') }}" rows="1" oninput="autoResizeTextarea(this)"></textarea>
                                        <button class="waqar-reply-send" onclick="sendReply({{ $comment->id }})"> {{ __('home.send_reply') }}</button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Modal التعليقات النظيف والآمن -->
    <div id="customCommentModal" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; background: rgba(0, 0, 0, 0.8) !important; z-index: 999999 !important; display: none; align-items: center !important; justify-content: center !important;">
        <div style="background: #ffffff !important; padding: 30px !important; box-shadow: 0px 10px 40px rgba(0,0,0,0.6) !important; border-radius: 15px !important; width: 90% !important; max-width: 450px !important; direction: rtl !important; position: relative !important; z-index: 1000000 !important; border: 1px solid #ffffff !important;">
            <div style="display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 20px !important;">
                <h5 style="color: #000000 !important; font-weight: bold !important; margin: 0 !important; font-size: 1.3rem !important; font-family: sans-serif !important;">{{ __('home.share_opinion') }}</h5>
            </div>
            <div style="margin-bottom: 20px !important;">
                <textarea id="newComment" placeholder="{{ __('home.write_comment') }}" style="resize: none !important; border-radius: 10px !important; font-size: 1rem !important; color: #000000 !important; background: #ffffff !important; display: block !important; width: 100% !important; border: 1px solid #cccccc !important; padding: 12px !important; font-family: sans-serif !important; min-height: 100px !important;"></textarea>
            </div>
            <div style="display: flex !important; justify-content: flex-end !important; gap: 15px !important;">
                <button type="button" onclick="toggleCommentModal()" style="width: 45px !important; height: 45px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 50% !important; border: 1px solid #f8d7da !important; background-color: #fff5f5 !important; cursor: pointer !important;" title="إلغاء">
                    <i class="bi bi-x-lg" style="color: #dc3545 !important; font-size: 1.2rem !important; font-weight: bold !important;"></i>
                </button>
                <button type="button" onclick="sendComment()" style="width: 45px !important; height: 45px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 50% !important; border: 1px solid #d1e7dd !important; background-color: #f3fcf7 !important; cursor: pointer !important;" title="إرسال">
                    <i class="bi bi-check-lg" style="color: #198754 !important; font-size: 1.2rem !important; font-weight: bold !important;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal التعديل -->
    <div id="editModal" class="edit-modal" style="display: none;">
        <div class="edit-modal-content">
            <textarea id="editText" class="edit-textarea"></textarea>
            <input type="hidden" id="editId">
            <div class="edit-modal-actions">
                <button onclick="updateComment()" class="btn btn-light-success p-2 rounded-circle border-0" title="حفظ">
                    <i class="bi bi-check-lg text-success fs-5"></i>
                </button>
                <button onclick="closeEdit()" class="btn btn-light-danger p-2 rounded-circle border-0" title="إغلاق">
                    <i class="bi bi-x-lg text-danger fs-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Features Info Section -->
    <section class="waqar-info-section">
        <div class="container">
            <div class="waqar-info-grid">
                <div class="waqar-info-card">
                    <div class="waqar-info-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h3>{{ __('home.fast_shipping') }}</h3>
                    <p>{{ __('home.fast_shipping_desc') }}</p>
                </div>
                <div class="waqar-info-card">
                    <div class="waqar-info-icon"><i class="fas fa-award"></i></div>
                      <h3>{{ __('home.high_quality') }}</h3>
                          <p>{{ __('home.high_quality_desc') }}</p>

                </div>
                <div class="waqar-info-card">
                    <div class="waqar-info-icon"><i class="fas fa-headset"></i></div>
                                    <h3>{{ __('home.support') }}</h3>
                                <p>{{ __('home.support_desc') }}</p>

                </div>
            </div>
        </div>
    </section>

    @include('admin.footer')
     
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ==========================================================
   1) تأثير الهيدر عند التمرير (Scroll)
========================================================== */
window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (!header) return;
    if (window.scrollY > 50) {
        header.style.backgroundColor = 'rgba(26, 26, 26, 0.95)';
    } else {
        header.style.backgroundColor = 'var(--primary-black)';
    }
});

/* ==========================================================
   2) السلة الجانبية (Side Cart)
========================================================== */
document.getElementById('open-cart')?.addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('side-cart').style.right = '0';
    document.getElementById('cart-overlay').style.display = 'block';
    document.getElementById('cart-overlay').style.opacity = '1';
});

document.getElementById('close-cart')?.addEventListener('click', function () {
    document.getElementById('side-cart').style.right = '-450px';
    document.getElementById('cart-overlay').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('cart-overlay').style.display = 'none';
    }, 300);
});

document.getElementById('cart-overlay')?.addEventListener('click', function () {
    document.getElementById('side-cart').style.right = '-450px';
    this.style.opacity = '0';
    setTimeout(() => {
        this.style.display = 'none';
    }, 300);
});

/* ==========================================================
   3) الأقسام (Categories)
========================================================== */
let currentCategoryIdForImage = null;

function triggerUpload(categoryId) {
    currentCategoryIdForImage = categoryId;
    document.getElementById('admin-image-uploader').click();
}

function uploadImage(input) {
    if (!input.files || !input.files[0] || !currentCategoryIdForImage) return;

    const formData = new FormData();
    formData.append('image', input.files[0]);
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    Swal.fire({
        title: 'جاري رفع الصورة...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    axios.post(`/admin/category/update-image/${currentCategoryIdForImage}`, formData, {
        headers: { 'X-CSRF-TOKEN': token }
    })
    .then(response => {
        Swal.fire('تم التحديث!', 'تم تغيير صورة القسم بنجاح.', 'success');
        const imgElement = document.getElementById(`img-${currentCategoryIdForImage}`);
        if (imgElement) imgElement.src = response.data.image_url;
    })
    .catch(() => {
        Swal.fire('فشل Tعديل', 'تأكد من صلاحيات الآدمن وحجم الملف.', 'error');
    });
}

function updateCategoryText(categoryId, field, updatedText) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    axios.post(`/admin/category/update-text/${categoryId}`, {
        field: field,
        value: updatedText
    }, {
        headers: { 'X-CSRF-TOKEN': token }
    })
    .then(() => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({ icon: 'success', title: 'تم حفظ التعديل تلقائياً' });
    })
    .catch(error => console.error('حدث خطأ أثناء تحديث البيانات:', error));
}

function deleteCategory(categoryId) {
    Swal.fire({
        title: 'هل أنت متأكد من الحذف؟',
        text: 'لن تتمكن من استعادة هذا القسم بعد الحذف!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذفه!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (!result.isConfirmed) return;

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        axios.delete(`/admin/category/delete/${categoryId}`, {
            headers: { 'X-CSRF-TOKEN': token }
        })
        .then(() => {
            Swal.fire('تم الحذف!', 'تم إزالة القسم بنجاح.', 'success');
            const element = document.getElementById(`category-item-${categoryId}`);
            if (element) {
                element.style.transition = 'all 0.5s ease';
                element.style.opacity = '0';
                element.style.transform = 'scale(0.7)';
                setTimeout(() => element.remove(), 500);
            }
        })
        .catch(() => Swal.fire('خطأ!', 'حدثت مشكلة أثناء الحذف.', 'error'));
    });
}

function toggleShapePicker(id) {
    document.querySelectorAll('.waqar-shape-options').forEach(el => {
        if (el.id !== 'shape-options-' + id) el.classList.add('d-none');
    });
    document.getElementById('shape-options-' + id).classList.toggle('d-none');
}

function setCategoryShape(id, shape) {
    const card = document.getElementById('category-item-' + id);
    card.classList.remove('shape-square', 'shape-soft', 'shape-round');
    card.classList.add('shape-' + shape);
    document.getElementById('shape-options-' + id).classList.add('d-none');

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const formData = new FormData();
    formData.append('category_style[' + id + ']', shape);

    fetch("{{ route('admin.settings.update') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        body: formData
    }).catch(() => alert('حدث خطأ أثناء حفظ شكل القسم'));
}

/* ==========================================================
   4) التعليقات (Comments)
========================================================== */
function toggleCommentModal() {
    const modal = document.getElementById('customCommentModal');
    if (!modal) return;
    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.setProperty('display', 'flex', 'important');
    } else {
        modal.style.setProperty('display', 'none', 'important');
    }
}

function sendComment() {
    const comment = document.getElementById('newComment');
    if (!comment) return;

    const value = comment.value.trim();
    if (!value) {
        alert('اكتب تعليق أولاً');
        return; 
    }

    fetch("{{ route('comments.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ comment: value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            comment.value = '';
            toggleCommentModal();
            location.reload();
        } else {
            alert(data.message || 'فشل الإرسال');
        }
    })
    .catch(err => {
        console.log('ERROR:', err);
        alert('مشكلة بالسيرفر');
    });
}

function likeComment(id) {
    fetch('/comments/' + id + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('like-' + id).innerText = data.likes;
        const btn = document.getElementById('like-' + id).closest('.waqar-action-btn');
        btn.classList.toggle('like-active', data.liked);
    });
}

function toggleReplyBox(id) {
    const box = document.getElementById('reply-box-' + id);
    if(box) box.classList.toggle('d-none');
    const textarea = document.getElementById('reply-' + id);
    if (textarea) autoResizeTextarea(textarea);
}

function sendReply(id) {
    const reply = document.getElementById('reply-' + id).value;
    fetch('/comments/' + id + '/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ comment: reply })
    })
    .then(res => res.json())
    .then(() => location.reload());
}

function deleteComment(id) {
    fetch('/comments/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) document.getElementById('comment-' + id).remove();
    });
}

function openEdit(id, text) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editText').value = text;
    document.getElementById('editId').value = id;
}

function closeEdit() {
    document.getElementById('editModal').style.display = 'none';
}

function updateComment() {
    const id = document.getElementById('editId').value;
    const comment = document.getElementById('editText').value;

    fetch('/comments/' + id, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ comment })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelector('#comment-' + id + ' p').innerText = comment;
            closeEdit();
        } else {
            alert('فشل التعديل');
        }
    })
    .catch(err => {
        console.log(err);
        alert('خطأ بالسيرفر');
    });
}

function autoResizeTextarea(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

/* ==========================================================
   5) تشغيل عند تحميل الصفحة (DOMContentLoaded)
========================================================== */
document.addEventListener('DOMContentLoaded', function () {
    // تشغيل السلايدر الرئيسي للهوم (إضافة جديدة لإصلاح عدم عمل السلايدر الرئيسي)
    const homeSliderEl = document.querySelector('.home-slider');
    if (homeSliderEl) {
        new Swiper(homeSliderEl, {
            loop: true,
            speed: 800,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
        });
    }

    const cards = document.querySelectorAll('.waqar-cat-card');
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('is-visible'), i * 90);
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    cards.forEach(card => cardObserver.observe(card));

    const commentsSwiperEl = document.querySelector('.comments-swiper');
    if (commentsSwiperEl) {
        new Swiper(commentsSwiperEl, {
            slidesPerView: 'auto',
            spaceBetween: 24,
            centeredSlides: true,
            grabCursor: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
            pagination: { el: commentsSwiperEl.querySelector('.swiper-pagination'), clickable: true },
        });
    }

    
});
</script>
@include('components.theme-toggle')
</body>
</html>