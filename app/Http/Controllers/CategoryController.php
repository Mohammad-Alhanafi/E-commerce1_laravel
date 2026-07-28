<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // عرض كل الفئات
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        $totalProducts = \App\Models\Product::count();

        return view('admin.categories.index', compact('categories', 'totalProducts'));
    }

    // حفظ أو تعديل صورة
    private function handleImage(Request $request, $currentImage = null)
    {
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . Str::random(5) . '.' . $request->image->extension();
            $request->image->move(public_path('categories'), $imageName);
            return $imageName;
        }
        return $currentImage; // لو ما فيه صورة جديدة، نحتفظ بالصورة القديمة
    }

    // حفظ فئة جديدة
   
public function store(Request $request)
{
    $data = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sort_order'  => 'nullable|integer|min:0',
    ]);

    // Checkbox values
    $data['is_active']   = $request->has('is_active') ? 1 : 0;
    $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
    $data['sort_order']  = $request->sort_order ?? 0;

if ($request->hasFile('image')) {
    $imageName = time() . '.' . $request->image->extension();
    $request->image->move(public_path('categories'), $imageName);
    $data['image'] = 'categories/' . $imageName;   // ✅ ضفنا المجلد
}

    Category::create($data);

    return redirect()
        ->route('category.index')
        ->with('success', 'تمت إضافة الفئة بنجاح');
}

// تحديث الفئة
public function update(Request $request, Category $category)
{
    $data = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sort_order'  => 'nullable|integer|min:0',
    ]);

    // checkbox values
    $data['is_active']   = $request->has('is_active') ? 1 : 0;
    $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
    $data['sort_order']  = $request->sort_order ?? 0;

    // image upload
if ($request->hasFile('image')) {
    $imageName = time() . '.' . $request->image->extension();
    $request->image->move(public_path('categories'), $imageName);
    $data['image'] = 'categories/' . $imageName;   // ✅ ضفنا المجلد
}

    $category->update($data);

    // ✅ AJAX request
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الفئة بنجاح',
            'category' => $category
        ]);
    }

    // ✅ Normal request
    return redirect()
        ->route('category.index')
        ->with('success', 'تم تعديل الفئة بنجاح');
}



  public function destroy($id)
{
    $category = \App\Models\Category::findOrFail($id);

    // حذف الصورة من السيرفر إذا وجدت
    if ($category->image) {
        // نستخدم المسار اللي تعودنا عليه في مشروعك
        $imagePath = str_replace('storage/', 'public/', $category->image);
        if (\Storage::exists($imagePath)) {
            \Storage::delete($imagePath);
        }
    }

    $category->delete();

    // إذا كان الطلب من الجافا سكريبت (Ajax) نرجع نجاح فقط
    if (request()->ajax()) {
        return response()->json(['success' => 'تم الحذف بنجاح']);
    }

    // للمتصفح العادي
    return back()->with('success', 'تم حذف القسم');
}


    public function getDetails($id) {
    $category = Category::with('products')->findOrFail($id);
    $view = view('admin.categories.partials.details_table', compact('category'))->render();
    return response()->json(['html' => $view]);
}






public function updateImage(Request $request, $id) {
    $category = Category::findOrFail($id);
    if ($request->hasFile('image')) {
        // رفع الصورة وحفظ المسار
        $path = $request->file('image')->move(public_path('uploads/categories'), time().'.'.$request->image->extension());
        $category->image = 'uploads/categories/'.basename($path);
        $category->save();
    }
    return back()->with('success', 'تم تحديث الصورة بنجاح');
}




public function show($id)
{
    $category = \App\Models\Category::where('is_active', 1)->findOrFail($id);

    $products = \App\Models\Product::where('category_id', $id)
                                   ->where('status', 'active')
                                   ->paginate(12); // عرض 12 منتج في الصفحة

    return view('frontend.category_products', compact('category', 'products'));
}





public function storeFast() {
        Category::create([
            'name' => 'قسم جديد (اضغط للتعديل)',
            'description' => 'وصف القسم هنا بقليل من التفاصيل',
            'image' => 'assets/images/default-cat.jpg', // تأكد أن هذه الصورة موجودة أو ضع مسار صورة افتراضية
            'is_active' => 1
        ]);

        return back()->with('success', 'تم إضافة قسم جديد بنجاح!');
    }
}