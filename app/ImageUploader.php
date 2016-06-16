<?php
/**
 * Class used for image uploads
 * Refer to http://image.intervention.io/
 */
namespace App;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ImageUploader
{
    private $file;
    private $image;

    public function __construct($image_file)
    {
        $this->file = $image_file;
        $this->image = Image::make($this->file);
    }

    /**
     * Uploads an image file
     * @param  string  $fileName    filename to save the image as (e.g: Picture.png)
     * @param  string  $destination  file directory to save the image to
     * @param  integer $width       width to resize the image to. If 0, use original width
     * @param  integer $height      height to resize the image to If 0, use original height
     * @param  boolean $crop_image  crop image to specified width and height instead of resizing it
     * @param  boolean $force_png   force image to be saved as png. If not, image extension will be based on MIME type
     * @return string/boolean       if image is valid, return filename of saved image
     *                              if image is not valid, return false;
     */
    public function upload($fileName, $destination, $width = 0, $height = 0, $crop_image = false, $force_png = false)
    {
        if ($this->image) {
            // encrypt file name
            if ($force_png) {
                $fileName = self::encryptFilename($fileName) . '.png';
            } else {
                if ($this->file instanceof \Illuminate\Http\UploadedFile) {
                    $fileName = self::encryptFilename($fileName) . '.' . $this->file->guessExtension();
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

    /**
     * Static helper function to format image upload ajax responses for jQuery file upload plugin
     * @param  string $responseUrl  fully qualified url of the uploaded file
     * @return array
     */
    public static function formatResponse($responseUrl)
    {
        return ['files' => [['url' => $responseUrl]]];
    }

    /**
     * Static helper function to format image upload ajax error responses for jQuery file upload plugin
     * @return array
     */
    public static function getErrorResponse()
    {
        return self::formatResponse(url('/uploads/error.png'));
    }

    public static function encryptFilename($filename)
    {
        return hash('ripemd256', $filename);
    }

    private function createDirectoryIfAbsent($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            return true;
        }

        return false;
    }
}
