<?php

namespace App\Model\Avatar;

use App\Model\AbstractModel;

class AvatarModel extends AbstractModel
{
    public function findById(int $id)
    {
        $sql = AvatarSQL::findById();
        $variables = [
            'id' => $id
        ];

        return $this->execute($sql, $variables);
    }

    public function updateById(int $id, string $value)
    {
        $sql = AvatarSQL::updateById();
        $variables = [
            'id' => $id,
            'value' => $value
        ];

        return $this->execute($sql, $variables);
    }

    public function getPicture($id)
    {
        $avatar = $this->findById($id);

        return $avatar['picture'];
    }

    public function setPicture($id, $value)
    {
        $this->updateById($id, $value);
    }
}