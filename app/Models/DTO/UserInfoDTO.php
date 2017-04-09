<?php
namespace App\Models\DTO;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class UserInfoDTO
{


    public $id;

    public $name;

    public $email;

    public $phone;

    public $imageUrl;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param mixed $appInstalled
     */
    public function setAppInstalled($appInstalled)
    {
        $this->appInstalled = $appInstalled;
    }

    public function convertToDto($userOrm){
        $userMinimalDto = new UserInfoDTO();
        $userMinimalDto->setId($userOrm->id);
        $userMinimalDto->setName(ucfirst($userOrm->name));
        $userMinimalDto->setImageUrl(url("/user/profile/".$userOrm->id));

        $userMinimalDto->setEmail($userOrm->email);
        $userMinimalDto->setPhone($userOrm->phone);
        return $userMinimalDto;
    }

    public function convertToDtoWithApp($userOrm){
        $userMinimalDto = new UserInfoDTO();
        $userMinimalDto->setId($userOrm->id);
        $userMinimalDto->setName(ucfirst($userOrm->name));
        $userMinimalDto->setImageUrl(url("/user/profile/".$userOrm->id));

        $userMinimalDto->setEmail($userOrm->email);
        $userMinimalDto->setAppInstalled($userOrm->appInstalled);
        return $userMinimalDto;
    }



}