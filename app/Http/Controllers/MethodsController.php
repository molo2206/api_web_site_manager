<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class MethodsController extends Controller
{
    public static function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 9;

        $code = 'WA_';

        while (strlen($code) < 9) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (User::where('verify_code', $code)->exists()) {
            self::generateUniqueCode();
        }

        return $code;
    }

    public static function uploadImageUrl($field, $destination)
    {
        if ($field) {
            $image = Image::make($field);
            $png_url = md5(rand(1000, 10000)) . ".png";
            $width = $image->width();
            $height = $image->height();
            $image->resize($width / 2, $height / 2); // Redimensionnement de l'image à 120 x 80 px
            $image->save(public_path() . $destination . $png_url);
            return env('APP_URL') . $destination . $png_url;
        }
    }

    public static function uploadDoc($request, $name, $destination, $file)
    {
        if($request->hasFile($file)){
            $profileImage = $request->file($file);
            $profileImageSaveAsName = Str::slug($name).'.'.$profileImage->getClientOriginalExtension();
            $profileImage->move(public_path().$destination, $profileImageSaveAsName);
            return env('APP_URL') . $destination . $profileImageSaveAsName;
        }
    }

    public static function removeImage($field, $destination)
    {
        if (File::exists(public_path($destination . $field))) {
            File::delete(public_path($destination . $field));
        }
    }

    public static function removeImageUrl($url)
    {
        $path = str_replace(env('APP_URL'), "", $url);
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
