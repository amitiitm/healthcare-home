<?php
namespace App\Models\DTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

class VendorIncentiveDTO
{


    public $id;

    public $vendorId;

    public $leadId;

    public $incentiveDate;

    public $dateTime;

    public function convertToDto($incentive){
        $vendorIncentiveDTO = new VendorIncentiveDTO();

        if(!$incentive){
            return $vendorIncentiveDTO;
        }
        $vendorIncentiveDTO->setId($incentive->id);
        $vendorIncentiveDTO->setVendorId($incentive->user_id);
        $vendorIncentiveDTO->setLeadId($incentive->lead_id);
        $vendorIncentiveDTO->setIncentiveDate($incentive->date);
        $vendorIncentiveDTO->setDateTime($incentive->created_at);

        return $vendorIncentiveDTO;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setVendorId($vendorId){
        $this->vendorId = $vendorId;
    }

    public function setLeadId($leadId){
        $this->leadId = $leadId;
    }

    public function setIncentiveDate($incentiveDate){
        $this->incentiveDate = $incentiveDate;
    }

    public function setDateTime($dateTime){
        $this->dateTime = $dateTime;
    }



}