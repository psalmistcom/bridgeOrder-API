<?php

namespace App\Services\Media;

use JD\Cloudder\Facades\Cloudder;

class Cloudinary
{
    public function uploadFile($imageName, $model = null): array
    {
        //Also note you could set a default height for all the images
        // and Cloudinary does a good job of handling and rendering the image.
        Cloudder::upload(
            $imageName,
            null,
            array(
                "folder" => "bridge_order",
                "overwrite" => false,
                "resource_type" => "image",
                "responsive" => true,
                "transformation" => array(
                    "quality" => "70",
                    "width" => "250",
                    "height" => "250",
                    "crop" => "scale"
                )
            )
        );

        //Cloudinary returns the publicId of the media uploaded which we'll store in our
        // database for ease of access when displaying it.
        $publicId = Cloudder::getPublicId();

        if ($publicId !== null && optional($model)['image_public_id'] !== null) {
            Cloudder::delete($model['image_public_id']);
        }
        //The show method returns the URL of the media file on Cloudinary
        $imageUrl = Cloudder::show(
            Cloudder::getPublicId(),
            [
                "width" => 250,
                "height" => 250,
                "crop" => "scale",
                "quality" => 70,
                "secure" => "true"
            ]
        );

        return [$imageUrl, $publicId];
    }
}
