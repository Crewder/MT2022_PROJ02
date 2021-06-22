<?php

namespace App\Controller;

use App\Model\Avatar\AvatarModel;
use CURLFile;
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

                $setavatar = new AvatarModel();
                $setavatar->insert($path);
            } else {
                throw new Exception('Echec de l\'upload !');
            }
        }
    }
}