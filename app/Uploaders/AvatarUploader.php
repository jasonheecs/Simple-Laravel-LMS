<?php
/**
 * Class used for user avatar image uploads
 */
namespace App\Uploaders;

use Intervention\Image\ImageManagerStatic as Image;

class AvatarUploader extends ImageUploader
{
    const THUMBNAIL_SIZE = 28;
    const IMAGE_SIZE = 150;

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
    public function upload($fileName, $destination, $width = self::IMAGE_SIZE, $height = self::IMAGE_SIZE, $crop_image = false, $force_png = false)
    {
        $uploadedFile = parent::upload($fileName, $destination, $width, $height, $crop_image, $force_png);

        if ($uploadedFile) {
            $this->generateThumbnail($fileName, $destination, $force_png);

            return $uploadedFile;
        }

        return false;
    }

    private function generateThumbnail($filename, $destination, $force_png = false)
    {
        $new_filename = parent::getNewFilename($filename, $force_png, config('constants.thumbnail_suffix'));
        $thumbnail = parent::resize(self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE);
        $thumbnail->save($destination . $new_filename);
    }
}
