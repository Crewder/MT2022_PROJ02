<?php

namespace App\Model\User;

use App\Model\AbstractModel;

class UserModel extends AbstractModel
{
    public function findById(int $id)
    {
        $sql = UserSQL::findById();
        $variables = [
            'id' => $id
        ];

        return $this->execute($sql, $variables);
    }

    public function updateById(int $id, string $type, string $value)
    {
        $sql = UserSQL::updateById($type);
        $variables = [
            'id' => $id,
            'value' => $value
        ];

        return $this->execute($sql, $variables);
    }

    public function getName($id)
    {
        $user = $this->findById($id);

        return $user['name'];
    }

    public function setName($id, $value)
    {
        $this->updateById($id, 'name', $value);
    }

    public function getEmail($id)
    {
        $user = $this->findById($id);

        return $user['email'];
    }

    public function setEmail($id, $value)
    {
        $this->updateById($id, 'email', $value);
    }

    public function getPassword($id)
    {
        $user = $this->findById($id);

        return $user['password'];
    }

    public function setPassword($id, $value)
    {
        $this->updateById($id, 'password', password_hash($value, PASSWORD_BCRYPT));
    }

    public function getAvatar($id)
    {
        $user = $this->findById($id);

        return $user['picture'];
    }

    public function setAvatar($id, $value)
    {
        $this->updateById($id, 'avatar_id', $value);
    }
}