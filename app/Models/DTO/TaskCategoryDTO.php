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

class TaskCategoryDTO{


    public $id;

    public $label;

    public $tasks;

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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
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



    public function convertToDTO($task){
        $taskDto = new TaskCategoryDTO();
        $taskDto->setId($task->id);
        $taskDto->setLabel($task->label);
        if($task->tasks && $task->tasks->count()){
            $taskItemDto = new TaskDTO();
            $taskListArr = array();
            foreach($task->tasks as $tempTask){
                array_push($taskListArr,$taskItemDto->convertToDTO($tempTask));
            }
            $taskDto->setTasks($taskListArr);
        }else{
            $taskDto->setTasks(array());
        }

        return $taskDto;
    }




}