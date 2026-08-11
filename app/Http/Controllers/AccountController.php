<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * تحديث البيانات الشخصية والباسوورد (بدون الصورة).
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. التحقق من صحة جميع المدخلات المدعومة
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ], [
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتتمكن من تغييرها.',
            'new_password.confirmed' => 'كلمة المرور الجديدة غير متطابقة مع حقل التأكيد.',
            'new_password.min' => 'يجب ألا تقل كلمة المرور الجديدة عن 8 أحرف.',
        ]);

        // 2. التحقق من كلمة المرور الحالية إذا أراد تغييرها
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية التي أدخلتها غير صحيحة.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // 3. حفظ باقي البيانات المطلوبة في قاعدة البيانات
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;

        // الـ status يبقى كما هو للمستخدم الحالي لحمايته من العبث بصلحياته
        // $user->status = $request->status;
        $user->save();

        return redirect()->back()->with('success', 'تم تحديث كافة بيانات حسابك الشخصية بنجاح!');
    }

    public function updateAvatar(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = auth()->user();
    $disk = config('filesystems.default', 'public');

    // حذف الصورة القديمة من السيرفر إذا موجودة
    if ($user->profile_image) {
        \Illuminate\Support\Facades\Storage::disk($disk)->delete($user->profile_image);
    }

    $file = $request->file('profile_image');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('avatars', $filename, $disk);

    $user->profile_image = $path;
    $user->save();

    return redirect()->back()->with('success', 'تم تحديث صورة البروفايل بنجاح!');
}
}