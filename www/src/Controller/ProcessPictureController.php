<?php


namespace App\Controller;


class ProcessPictureController
{
    public function __construct()
    {
    }

    public static function processPicture($picture)
    {
        $FOLDER = 'upload/';
        $size = getimagesize($FOLDER . $picture['name']);
        $pictureName = explode(".",$picture['name']);
        $width = $size[0];
        $heigth = $size[1];
        $newWidth = 128;
        $newHeigth = 128;

        switch ($picture['type']) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $type = "jpg";
                break;
            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $type = "png";
                break;
            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $type = "gif";
                break;
            default:
                throw new Exception('Unknown image type.');
        }

        $image = $image_create_func($FOLDER . $picture['name']);
        $img_resized = imagecreatetruecolor ($newWidth, $newHeigth);
        imagecopyresampled ($img_resized,$image,0,0,0,0,$newWidth,$newHeigth,$size[0],$size[1]);

        $newPictureFilename = $FOLDER.$pictureName[0].'_resized.'.$type;
        if(!$image_save_func($img_resized,$newPictureFilename)){
            throw new Exception('L\'image n\'as pas pue être redimensioné');
        }
        unlink($FOLDER . $picture['name']);
    }
}