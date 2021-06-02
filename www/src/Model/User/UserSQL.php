<?php

namespace App\Model\User;

class UserSQL
{
    public static function findById()
    {
        return "
            SELECT id, name, email, avatar_id, a.picture FROM user u
            LEFT JOIN (
                SELECT
                    id subQuery_avatar_id,
                    picture
                FROM avatar
            ) a  ON a.subQuery_avatar_id = u.avatar_id
            WHERE id = :id
            ;
        ";
    }

    public static function updateById(string $type)
    {
        return "
            UPDATE user SET $type = :value WHERE id = :id
        ";
    }
}