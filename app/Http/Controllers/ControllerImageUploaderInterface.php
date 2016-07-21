<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface ControllerImageUploaderInterface
{
    /**
     * Handles uploading of image file.
     * If $model_id is 0, means that the model is a temporary one
     * (most likely one made during the create() view before saving the model)
     * If model is temporary, upload the image file to a temporary directory first.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $model_id
     * @return JSON   JSON response
     */
    public function upload(Request $request, $model_id);

    /**
     * Upload image file to the tmp directory
     * Used as an ajax endpoint for the jQuery file upload plugin
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\Uploaders\ImageUploader $imageUploader
     * @return array          Response Array containing the directory path of the uploaded file
     */
    public function uploadToTmp($imageUploader);
}