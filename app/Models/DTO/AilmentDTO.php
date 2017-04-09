<?php
namespace App\Models\DTO;
use App\Models\ORM\Ailment;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class AilmentDTO{


    public $id;

    public $name;

    public $description;

    public $validationRequired;

    public  $tasks;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getValidationRequired()
    {
        return $this->validationRequired;
    }

    /**
     * @param mixed $validationRequired
     */
    public function setValidationRequired($validationRequired)
    {
        $this->validationRequired = $validationRequired;
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }






    public function convertToDTO($ailment){
        //d($ailment);
        $ailmentDto = new AilmentDTO();
        $ailmentDto->setId($ailment->id);
        $ailmentDto->setName($ailment->name);
        $ailmentDto->setDescription($ailment->description);
        $ailmentDto->setValidationRequired($ailment->validation_required);
        if($ailment->tasks->count()>0){
            $taskList = array();
            $taskDto = new TaskDTO();

            foreach ($ailment->tasks as $tempTask) {

                array_push($taskList,$taskDto->convertToDTO($tempTask));
            }
            $ailmentDto->setTasks($taskList);

        }else{
            $ailmentDto->setTasks(array());
        }
        return $ailmentDto;
    }




}