<?php


namespace App\Tools;


use Exception;

class Commons
{

    /**
     * Fetch Image by his relative path
     *
     * @param string $path
     * @return array
     * @throws Exception
     */
    public static function GetImageByPath(string $path): array
    {

        $picture = opendir('.');
        var_dump($picture);


        file_exists("");

        try {



            $picture = [];
        } catch (Exception $e) {
            throw $e;
        }

        return $picture;
    }
}