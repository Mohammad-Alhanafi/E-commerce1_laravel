<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
   
   public function index()
{
    $sliders = Slider::orderBy('order')->paginate(10);
    return view('admin.sliders.index', compact('sliders'));
}

 




public function store(Request $request)
{
    $request->validate([
        'image' => 'required|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,webm|max:20000',
    ]);

    if ($request->hasFile('image')) {

        $file = $request->file('image');
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = time() . '.' . $extension;

        $isImage = in_array($extension, [
            'jpeg',
            'png',
            'jpg',
            'gif'
        ]);

        $isVideo = in_array($extension, [
            'mp4',
            'mov',
            'webm',
            'ogg'
        ]);


        if ($isImage) {

            $manager = new ImageManager(new Driver());

            $image = $manager->read($file);

            $image->cover(1600, 700);


            // حفظ داخل storage/app/public/sliders
            $path = storage_path('app/public/sliders/' . $filename);

            // إنشاء المجلد إذا لم يكن موجوداً
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            $image->save($path);

        } 
        
        elseif ($isVideo) {

            $file->storeAs(
                'sliders',
                $filename,
                'public'
            );
        }


        $slider = new Slider();

        $slider->image = 'sliders/' . $filename;

        $slider->title = $request->title;

        $slider->status = $request->status ?? 'active';

        $slider->order = $request->order ?? 0;

        $slider->save();


        return response()->json([
            'success' => true
        ]);
    }
}


    /**
    
     */
    public function update(Request $request, $id)
{
    $slider = Slider::findOrFail($id);

    if ($request->hasFile('image')) {
    }

    $slider->update([
        'title' => $request->title,
        'order' => $request->order,
        'status' => $request->status,
    ]);

    return response()->json(['success' => true]);
}
   
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        if ($slider->image && file_exists(public_path($slider->image))) {
            @unlink(public_path($slider->image));
        }

        $slider->delete();
        return back()->with('success', 'تم الحذف  .');
    }


    public function updateLogo(Request $request)
{
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $directory = public_path('uploads/settings');

        // نأكد وجود المجلد
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // حفظ الصورة باسم ثابت لاستبدال القديمة فوراً
        $file->move($directory, 'logo.png');

        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 400);
}
}