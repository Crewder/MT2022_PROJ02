<?php

namespace App\Model\Avatar;

class AvatarSQL
{
    public static function findById()
    {
        return "
            SELECT id, picture FROM avatar WHERE id = :id
            ;
        ";
    }

    public static function updateById()
    {
        return "
            UPDATE avatar SET picture = :value WHERE id = :id
        ";
    }


    public static function insert()
    {
        return "
            INSERT INTO avatar (picture)
             VALUES
             (:picture)
             ";
    }
}