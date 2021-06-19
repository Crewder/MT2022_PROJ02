<?php


namespace App\Controller;

use App\Tools\Commons;
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
                echo 'Echec de l\'upload !';
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
        $picture = Commons::GetImageByPath($path);


        $size = getimagesize($this->folder . $picture['name']);
        $pictureName = explode(".", $picture['name']);
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

        $image = $image_create_func($this->folder . $picture['name']);
        $img_resized = imagecreatetruecolor($newWidth, $newHeigth);
        imagecopyresampled($img_resized, $image, 0, 0, 0, 0, $newWidth, $newHeigth, $size[0], $size[1]);

        $newPictureFilename = $this->folder . $pictureName[0] . '_resized.' . $type;
        if (!$image_save_func($img_resized, $newPictureFilename)) {
            throw new Exception('L\'image n\'as pas pue être redimensioné');
        }
        unlink($this->folder . $picture['name']);
    }


}