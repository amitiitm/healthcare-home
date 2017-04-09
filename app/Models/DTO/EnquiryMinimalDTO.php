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

class EnquiryMinimalDTO
{

    public $id;

    public $name;

    public $email;

    public $phone;

    public $enquiryDate;

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
     * @return mixed
     */
    public function getEnquiryDate()
    {
        return $this->enquiryDate;
    }

    /**
     * @param mixed $enquiryDate
     */
    public function setEnquiryDate($enquiryDate)
    {
        $this->enquiryDate = $enquiryDate;
    }




    public function convertToDto($enquiryOrm){
        $enquiryMinimalDto = new EnquiryMinimalDTO();
        $enquiryMinimalDto->setId($enquiryOrm->id);
        $enquiryMinimalDto->setName($enquiryOrm->name);
        $enquiryMinimalDto->setEmail($enquiryOrm->email);
        $enquiryMinimalDto->setEnquiryDate($enquiryOrm->created_at);
        return $enquiryMinimalDto;
    }



}