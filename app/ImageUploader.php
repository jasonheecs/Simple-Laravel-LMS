<?php
namespace App;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ImageUploader {
    private $request;
    private $file;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->file = $request->file('files')[0];
        $this->fq_filename = $this->file->getRealPath();
    }

    public function upload($fileName, $destination, $width = 0, $height = 0, $crop_image = false)
    {
        if ($this->file->isValid()) {
            if ($width > 0 && $height > 0) { //crop or resize uploaded image
                if ($crop_image) {
                    $img = $this->crop($width, $height);
                } else {
                    $img = $this->resize($width, $height);
                }
                
                $img->save($destination . $fileName);
            } else {
                $this->file->move($destination, $fileName);
            }

            return true;
        }

        return false;
    }

    public function resize($width, $height)
    {
        if ($this->file->isValid()) {
            $img = Image::make($this->file)->resize($width, $height);
            return $img;
        }

        return null;
    }

    public function crop($width, $height)
    {
        if ($this->file->isValid()) {
            $img = Image::make($this->file)->crop($width, $height);
            return $img;
        }

        return null;
    }

    public function getFile()
    {
        return $this->file;
    }
}