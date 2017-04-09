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

class ServiceMinimalDTO
{

    public $id;

    public $name;

    public $slug;

    public $iconClass;

    /**
     * @return mixed
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * @param mixed $iconClass
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }





    public function convertToDto($serviceOrm){
        $serviceMinimalDto = new ServiceMinimalDTO();
        if(!$serviceOrm){
            return $serviceMinimalDto;
        }
        $serviceMinimalDto->setId($serviceOrm->id);
        $serviceMinimalDto->setName($serviceOrm->name);
        $serviceMinimalDto->setSlug($serviceOrm->slug);
        $serviceMinimalDto->setIconClass($serviceOrm->icon_class);
        return $serviceMinimalDto;
    }



}