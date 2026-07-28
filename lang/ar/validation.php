<?php
/*
 * ============================================================
 *  lang/ar/validation.php — Arabic Validation Messages
 * ============================================================
 */
return [
    'required'         => ':attribute مطلوب.',
    'email'            => ':attribute يجب أن يكون بريداً إلكترونياً صحيحاً.',
    'min'              => [
        'string'  => ':attribute يجب ألا يقل عن :min حروف.',
        'numeric' => ':attribute يجب ألا يقل عن :min.',
        'file'    => ':attribute يجب ألا يقل عن :min كيلوبايت.',
    ],
    'max'              => [
        'string'  => ':attribute يجب ألا يزيد عن :max حروف.',
        'numeric' => ':attribute يجب ألا يزيد عن :max.',
        'file'    => ':attribute يجب ألا يزيد عن :max كيلوبايت.',
    ],
    'unique'           => ':attribute مستخدم مسبقاً.',
    'confirmed'        => ':attribute غير متطابق مع حقل التأكيد.',
    'numeric'          => ':attribute يجب أن يكون رقماً.',
    'integer'          => ':attribute يجب أن يكون رقماً صحيحاً.',
    'string'           => ':attribute يجب أن يكون نصاً.',
    'boolean'          => ':attribute يجب أن يكون صح أو خطأ.',
    'array'            => ':attribute يجب أن يكون مصفوفة.',
    'in'               => ':attribute يحتوي على قيمة غير مقبولة.',
    'not_in'           => ':attribute يحتوي على قيمة غير مقبولة.',
    'exists'           => ':attribute غير موجود في قاعدة البيانات.',
    'mimes'            => ':attribute يجب أن يكون ملفاً من نوع: :values.',
    'mimetypes'        => ':attribute يجب أن يكون ملفاً من نوع: :values.',
    'image'            => ':attribute يجب أن يكون صورة.',
    'date'             => ':attribute يجب أن يكون تاريخاً صحيحاً.',
    'after'            => ':attribute يجب أن يكون تاريخاً بعد :date.',
    'before'           => ':attribute يجب أن يكون تاريخاً قبل :date.',
    'regex'            => ':attribute يحتوي على تنسيق غير صحيح.',
    'url'              => ':attribute يجب أن يكون رابطاً صحيحاً.',
    'phone'            => ':attribute يجب أن يكون رقم هاتف صحيحاً.',
    'password_strength'=> 'كلمة المرور يجب أن تحتوي على أحرف وأرقام.',

    /* ── Custom field names ── */
    'attributes'       => [
        'name'         => 'الاسم',
        'email'        => 'البريد الإلكتروني',
        'password'     => 'كلمة المرور',
        'phone'        => 'رقم الهاتف',
        'address'      => 'العنوان',
        'city'         => 'المدينة',
        'country'      => 'الدولة',
        'image'        => 'الصورة',
        'price'        => 'السعر',
        'quantity'     => 'الكمية',
        'description'  => 'الوصف',
        'title'        => 'العنوان',
        'category_id'  => 'القسم',
        'size'         => 'المقاس',
        'color'        => 'اللون',
    ],
];
