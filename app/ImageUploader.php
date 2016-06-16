<?php
namespace App;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ImageUploader {
    private $file;
    private $image;

    public function __construct($image_file)
    {
        $this->file = $image_file;
        $this->image = Image::make($this->file);
    }

    public function upload($fileName, $destination, $width = 0, $height = 0, $crop_image = false, $force_png = false)
    {
        if ($this->image) {

            // encrypt file name
            if ($force_png) {
                $fileName = hash('ripemd256', $fileName) . '.png';
            } else {
                if ($this->file instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = hash('ripemd256', $fileName) . '.' . $this->file->guessExtension();
                }
            }

            $this->createDirectoryIfAbsent($destination);

            if ($width > 0 && $height > 0) { //crop or resize uploaded image
                if ($crop_image) {
                    $this->crop($width, $height);
                } else {
                    $this->resize($width, $height);
                }
            }

            $this->image->save($destination . $fileName);

            return $fileName;
        }

        return false;
    }

    public function resize($width, $height)
    {
        if ($this->image) {
            $this->image->resize($width, $height);
        }
    }

    public function crop($width, $height)
    {
        if ($this->image) {
            $this->image->crop($width, $height);
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public static function formatResponse($responseUrl)
    {
        return ['files' => [['url' => $responseUrl]]];
    }

    public static function getErrorResponse() {
        return self::formatResponse(url('/uploads/error.png'));
    }

    private function createDirectoryIfAbsent($dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            return true;
        }

        return false;
    }
}