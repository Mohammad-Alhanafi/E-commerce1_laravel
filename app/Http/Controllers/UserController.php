<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\RateLimiter;
use App\Rules\ValidPhoneNumber;
use App\Models\User;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.users.partials.users_table', compact('users'))->render();
        }

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email,' . $request->id,
            'phone_number' => 'nullable|string|max:20',
            'password' => $request->id 
    ? 'nullable|min:8|regex:/[a-z]/|regex:/[0-9]/' 
    : 'required|min:8|regex:/[a-z]/|regex:/[0-9]/',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } elseif(!$request->id) {
            return response()->json(['errors'=>['password'=>['كلمة المرور مطلوبة']]],422);
        } else {
            unset($data['password']);
        }

        $user = User::updateOrCreate(
            ['id' => $request->id],
            $data
        );
        
        $user->created_at = $user->created_at->format('Y-m-d');
        $user->updated_at = $user->updated_at->format('Y-m-d H:i:s');

        return response()->json(['success'=>true,'user'=>$user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function filter(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10); 

        return response()->json([
            'table' => view('admin.users.users_table', compact('users'))->render(),
            'pagination' => (string) $users->appends($request->query())->links('pagination::bootstrap-5')
        ]); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);
        $data = $request->only(['name', 'email', 'phone_number', 'role', 'status', 'address', 'birth_date', 'gender']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * 📝 إنشاء حساب زبون جديد
     */
public function registerClient(Request $request)
{

    $request->validate([
        'name' => 'required|string|max:255',

        'email' => 'required|email|max:255|unique:users,email',

        'phone_number' => [
    'required',
    new ValidPhoneNumber($request->country_code)
],

        'password' => 'required|min:8|confirmed|regex:/[a-z]/|regex:/[0-9]/',

    ], [
        'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
        'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف ورقم واحد على الأقل.',
        'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
    ]);



    // 2. فحص البريد الإلكتروني عبر Abstract API
    $apiKey = '3a07a07af1e649b5b06a4b7b29c1457e';
    $userEmail = $request->email;

    try {

        $response = Http::withoutVerifying()
            ->timeout(10)
            ->get("https://emailreputation.abstractapi.com/v1/", [
                'api_key' => $apiKey,
                'email'   => $userEmail
            ]);


        if (!$response->successful()) {

            return response()->json([
                'success' => false,
                'message' => 'تعذر الاتصال بخدمة فحص البريد الإلكتروني.'
            ], 422);

        }

$apiData = $response->json();



// ===============================
// فحص صلاحية البريد
// ===============================

$deliverabilityStatus = strtolower(
    $apiData['email_deliverability']['status'] ?? ''
);

$isSmtpValid =
    $apiData['email_deliverability']['is_smtp_valid'] ?? false;


// 1- فحص صيغة البريد
$isFormatValid =
    $apiData['email_deliverability']['is_format_valid'] ?? false;


if (!$isFormatValid) {

    return response()->json([
        'success' => false,
        'message' => 'صيغة البريد الإلكتروني غير صحيحة.'
    ], 422);

}


// 2- فحص وجود البريد فعلياً
if ($deliverabilityStatus === 'undeliverable' || !$isSmtpValid) {

    return response()->json([
        'success' => false,
        'message' => 'هذا البريد الإلكتروني غير موجود فعلياً، يرجى استخدام بريد حقيقي.'
    ], 422);

}


// 3- منع الإيميلات المؤقتة
$isDisposable =
    $apiData['email_quality']['is_disposable'] ?? false;


if ($isDisposable) {

    return response()->json([
        'success'=>false,
        'message'=>'لا يسمح باستخدام بريد إلكتروني مؤقت.'
    ],422);

}



  } catch (\Exception $e) {

    return response()->json([
        'success' => false,
        'message' => 'حدث خطأ أثناء التحقق من البريد الإلكتروني، حاول لاحقًا.'
    ], 500);

}



    // 3. إنشاء المستخدم بعد نجاح الفحص

    // إذا كان هذا أول مستخدم يسجل بالموقع، اجعله أدمن تلقائياً
$isFirstUser = \App\Models\User::count() === 0;

$user = User::create([

    'name'         => $request->name,
    'email'        => $request->email,
    'phone_number' => $request->phone_number,
    'password'     => bcrypt($request->password),
    'role'         => $isFirstUser ? 'admin' : 'user',
    'status'       => 'active',

]);


    // تسجيل الدخول مباشرة

   return response()->json([
        'success'  => true,
        'message'  => 'تم إنشاء حسابك بنجاح!       .',
        'redirect' => '/login' // قم بتغيير المسار إذا كان رابط صفحة تسجيل الدخول لديك مختلفاً

    ]);

}

    /**
     * 🔑 تسجيل الدخول بـ (البريد أو الهاتف)
     */
 public function loginClient(Request $request)
{
    $request->validate([
        'login_field' => 'required|string',
        'password'    => 'required|string',
    ]);

    $fieldType = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

    $credentials = [
        $fieldType => $request->login_field,
        'password' => $request->password 
    ];

    // محاولة تسجيل الدخول
    if (Auth::attempt($credentials, $request->has('remember'))) {
        
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            $redirectUrl = '/admin';
        } else {
            $safeRedirect = $request->input('redirect');
            $redirectUrl = ($safeRedirect && str_starts_with($safeRedirect, url('/')))
                ? $safeRedirect
                : '/';
        }

        return response()->json([
            'success'  => true,
            'redirect' => $redirectUrl
        ]);
    }

    // في حال فشل تسجيل الدخول، نرجع رسالة خطأ مباشرة دون قيود محاولات
    return response()->json([
        'success' => false,
        'message' => 'البريد الالكتروني أو كلمة المرور غير صحيحة.'
    ], 401);
}







    /**
     * 📩 إرسال كود استعادة كلمة المرور
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'هذا البريد الالكتروني غير مسجل لدينا'], 404);
        }

        $code = rand(100000, 999999);

        \DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $request->email],
            ['code' => $code, 'created_at' => now()]
        );

        \Mail::to($request->email)->send(new \App\Mail\PasswordResetCodeMail($code));

        return response()->json(['success' => true]);
    }

    /**
     * 🔁 تأكيد الكود وتغيير كلمة المرور
     */
    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'code'     => 'required',
               'password' => 'required|min:8|confirmed|regex:/[a-z]/|regex:/[0-9]/',
], [
    'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
    'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف وحرف صغير ورقم على الأقل',
    'password.confirmed' => 'كلمتا المرور غير متطابقتين',
]);

        $record = \DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'الكود غير صحيح'], 422);
        }

        if (now()->diffInMinutes($record->created_at) > 10) {
            return response()->json(['message' => 'الكود منتهي الصلاحية، اطلب كود جديد'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        \DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return response()->json(['success' => true]);
    }
}