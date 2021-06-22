<?php

namespace App\Controller;

use Exception;

class ProcessPictureController
{
    private string $folder;
    private RabbitMQHandler $rabbitMQHandler;

    public function __construct(RabbitMqConfig $config)
    {
        $this->rabbitMQHandler = new RabbitMQHandler($config);
        $this->folder = 'upload/';
    }

    /**
     * Resize a picture
     *
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function processPicture(string $path, string $webserverhost): void
    {
        $path = $webserverhost . "/" . $path;

        $pictureNameTmp = explode("/", $path);
        $pictureName = explode(".", $pictureNameTmp[2]);

        $ch = curl_init($path);
        $fp = fopen('upload/'. $pictureNameTmp[2], 'a+');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $localPath = "upload/" . $pictureNameTmp[2];

        $pictureinfo = getimagesize($localPath);

        $newWidth = 128;
        $newHeigth = 128;

        switch ($pictureinfo['mime']) {
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

        $image = $image_create_func($localPath);
        $img_resized = imagecreatetruecolor($newWidth, $newHeigth);

        imagecopyresampled(
            $img_resized,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeigth,
            $pictureinfo[0],
            $pictureinfo[1]
        );

        $newPictureFilename = $this->folder . $pictureName[0] . '_resized.' . $type;

        if (!$image_save_func($img_resized, $newPictureFilename)) {
            throw new Exception('L\'image n\'as pas pue être redimensioné');
        } else {
            echo "l'image a été redimensionné";
        }
        unlink($localPath);
    }
}