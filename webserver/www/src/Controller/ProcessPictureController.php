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
     * Upload picture in the folder define in constructor
     *
     * @return void
     * @throws Exception
     */
    public function uploadPicture(): void
    {
        if (isset($_FILES['picture'])) {
            if (!file_exists($this->folder)) {
                mkdir($this->folder);
            }
            $fileName = basename($_FILES['picture']['name']);
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $this->folder . $fileName)) {
                echo 'Upload effectué avec succès !';

                $path = $this->folder . $fileName;
                $this->rabbitMQHandler->SendMessage($path);

            } else {
                throw new Exception('Echec de l\'upload !');
            }
        }
    }

    /**
     * Resize a picture
     *
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function processPicture(string $path): void
    {
        if (file_exists($path)) {
            $pictureNameTmp = explode("/", $path);
            $pictureName = explode(".", $pictureNameTmp[1]);
            $pictureinfo = getimagesize($path);
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

            $image = $image_create_func($path);
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
            unlink($path);
        } else {
            throw new Exception('L\'image n\'existe pas sur le serveur');
        }
    }
}