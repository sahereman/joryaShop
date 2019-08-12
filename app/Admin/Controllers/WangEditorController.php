<?php

namespace App\Admin\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WangEditorController extends Controller
{

    public function images(Request $request, ImageUploadHandler $uploader)
    {
        $data = $this->validate($request, [
            'images.*' => 'required|image|mimes:jpeg,png,gif',
        ], [], [
            'images.*' => '图片',
        ]);

        $paths = array();
        foreach ($data['images'] as $item) {
            // $paths[] = \Storage::disk('public')->url($uploader->uploadOriginal($item));
            $date = Carbon::now();
            $path = 'original/' . date('Ym', $date->timestamp);/*存储文件格式为 201706 文件夹内*/
            $i = 0;
            $file_name = $item->getClientOriginalName();
            $name = pathinfo($file_name, PATHINFO_FILENAME);
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            while (\Storage::disk('public')->exists("{$path}/{$file_name}")) {
                $file_name = $name . '-' . $i . '.' . $extension;
                $i++;
            }
            $name = pathinfo($file_name, PATHINFO_FILENAME);
            $paths[] = \Storage::disk('public')->url($uploader->uploadOriginal($item, $path, $name));
        }

        return response()->json([
            'errno' => 0,
            'data' => $paths
        ]);
    }
}
