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

class UserMinimalDTO
{


    public $id;

    public $name;

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
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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





    public function convertToDto($userOrm){
        $userMinimalDto = new UserMinimalDTO();
        //d($userOrm);
        if(!$userOrm){
            return null;
        }
        $userMinimalDto->setId($userOrm->id);
        $userMinimalDto->setName($userOrm->name);
        $userMinimalDto->setEmail($userOrm->email);
        $userMinimalDto->setPhone($userOrm->phone);
        $userMinimalDto->setImageUrl(url("/user/profile/".$userOrm->id));
        return $userMinimalDto;
    }



}