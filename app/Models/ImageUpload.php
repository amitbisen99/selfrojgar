<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class ImageUpload extends Model
{
    use HasFactory;
    
    public static function upload($path, $image)
    {
        $folderPath = public_path($path);
        if(!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0755, true, true);
        }
        $imageName = time().'_'.$image->getClientOriginalName();
       
        $image->move(public_path($path), $imageName);

        $image = $path.$imageName;
        return $image;
    }
}
