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
    $sliders = Slider::orderBy('order')->paginate(10)->withQueryString();
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

    // حفظ مؤقت محليًا ثم رفعه لـ S3
    $tempPath = sys_get_temp_dir() . '/' . $filename;
    $image->save($tempPath);

    \Illuminate\Support\Facades\Storage::disk('s3')->put(
        'sliders/' . $filename,
        file_get_contents($tempPath)
    );

    @unlink($tempPath);

} 

elseif ($isVideo) {

    $file->storeAs(
        'sliders',
        $filename,
        's3'
    );
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
        
       if ($slider->image) {
    \Illuminate\Support\Facades\Storage::disk('s3')->delete($slider->image);
}

        $slider->delete();
        return back()->with('success', 'تم الحذف  .');
    }


   public function updateLogo(Request $request)
{
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $path = $file->storeAs('settings', 'logo.png', 's3');

        return response()->json(['success' => true, 'path' => $path]);
    }
    return response()->json(['success' => false], 400);
}
}