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

class CustomerStatusRequestDTO
{


    public $id;

    public $status;

    public $reason;

    public $issue;

    public $remark;

    public $dateTime;

    public function convertToDto($requestOrm){


        $employeeDetailDto = new CustomerStatusRequestDTO();

        $employeeDetailDto->setId($requestOrm->id);

        $operationalStatusDto = new OperationalStatusMinimalDTO();
        $operationalStatusReasonDto = new OperationalStatusReasonDTO();
        $operationalStatusIssueDto = new CommonDTO();
        //dd($requestOrm);
        $employeeDetailDto->setStatus($operationalStatusDto->convertToDTO($requestOrm->status));
        $employeeDetailDto->setReason($operationalStatusReasonDto->convertToDTO($requestOrm->reason));
        $employeeDetailDto->setIssue($operationalStatusIssueDto->convertToDTO($requestOrm->issue));

        $employeeDetailDto->setRemark($requestOrm->other_info);

        $employeeDetailDto->setDateTime($requestOrm->created_at);

        return $employeeDetailDto;

    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }



    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
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
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param mixed $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }



}