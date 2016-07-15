<?php
/**
 * Class used for image uploads
 * Refer to http://image.intervention.io/
 */
namespace App\Uploaders;

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
     * @return string | boolean       if image is valid, return filename of saved image
     *                              if image is not valid, return false;
     */
    public function upload($fileName, $destination, $width = 0, $height = 0, $crop_image = false, $force_png = false)
    {
        if ($this->image) {
            $fileName = $this->getNewFilename($fileName, $force_png);
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
            return $this->image;
        }
    }

    public function crop($width, $height)
    {
        if ($this->image) {
            $this->image->crop($width, $height);
            return $this->image;
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getImage()
    {
        return $this->image;
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

    /**
     * Generates encrypted new filename with file extension
     * @param  string  $old_filename old image file name
     * @param  boolean $force_png    force new file extension to be .png. Default value is false
     * @param  string  $suffix       optional suffix to the filename (eg: _thumb for thumbnails)
     * @return string  new image filename with file extension
     */
    protected function getNewFilename($old_filename, $force_png = false, $suffix = '')
    {
        $filename = self::encryptFilename($old_filename);

        if (strlen($suffix) > 0) {
            $filename .= $suffix;
        }

        if ($force_png) {
            return $filename . '.png';
        } else {
            if ($this->file) {
                if ($this->file instanceof \Illuminate\Http\UploadedFile) {
                    return $filename . '.' . $this->file->guessExtension();
                } else {
                    return $filename . '.jpg';
                }
            }
        }

        return $filename;
    }

    public static function encryptFilename($filename)
    {
        return hash('ripemd256', $filename);
    }

    protected function createDirectoryIfAbsent($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            return true;
        }

        return false;
    }
}
