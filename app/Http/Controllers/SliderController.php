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


        $disk = config('filesystems.default', 'public');

        if ($isImage) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->cover(1600, 700);

            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            \Illuminate\Support\Facades\Storage::disk($disk)->put(
                'sliders/' . $filename,
                file_get_contents($tempPath)
            );

            @unlink($tempPath);
        } elseif ($isVideo) {
            $file->storeAs('sliders', $filename, $disk);
        }

        $slider = Slider::create([
            'title'  => $request->title,
            'image'  => 'sliders/' . $filename,
            'link'   => $request->link,
            'status' => $request->status ?? 'active',
            'order'  => $request->order ?? 0,
        ]);

        return back()->with('success', 'تم إضافة السلايدر بنجاح');
    }

    return back()->withErrors(['image' => 'لم يتم إرفاق ملف']);
}

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $disk = config('filesystems.default', 'public');

        if ($request->hasFile('image')) {
            if ($slider->image) {
                \Illuminate\Support\Facades\Storage::disk($disk)->delete($slider->image);
            }

            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = time() . '.' . $extension;

            if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif'])) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->cover(1600, 700);
                $tempPath = sys_get_temp_dir() . '/' . $filename;
                $image->save($tempPath);

                \Illuminate\Support\Facades\Storage::disk($disk)->put(
                    'sliders/' . $filename,
                    file_get_contents($tempPath)
                );
                @unlink($tempPath);
            } else {
                $file->storeAs('sliders', $filename, $disk);
            }

            $slider->image = 'sliders/' . $filename;
        }

        $slider->update([
            'title'  => $request->title ?? $slider->title,
            'order'  => $request->order ?? $slider->order,
            'status' => $request->status ?? $slider->status,
            'link'   => $request->link ?? $slider->link,
        ]);

        return response()->json(['success' => true]);
    }
   
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $disk = config('filesystems.default', 'public');
        
        if ($slider->image) {
            \Illuminate\Support\Facades\Storage::disk($disk)->delete($slider->image);
        }

        $slider->delete();
        return back()->with('success', 'تم الحذف.');
    }

    public function updateLogo(Request $request)
    {
        if ($request->hasFile('logo')) {
            $disk = config('filesystems.default', 'public');
            $file = $request->file('logo');
            $path = $file->storeAs('settings', 'logo.png', $disk);

            return response()->json(['success' => true, 'path' => $path]);
        }
        return response()->json(['success' => false], 400);
    }
}