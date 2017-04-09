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

class VendorDetailedDTO{

    public $id;

    public $userId;

    public $category;

    public $source;

    public $name;

    public $email;

    public $phone;

    public $alternateNo;

    public $address;

    public $locality;

    public $localityFormattedAddress;

    public $zone;

    public $age;

    public $gender;

    public $weight;

    public $height;

    public $religion;

    public $food;

    public $qualification;

    public $experience;

    public $shift;

    public $task;

    public $hasSmartPhone;

    public $hasBankAccount;

    public $trainingAttended;

    public $trainingNotAttendedReason;

    public $trainingNotAttendedOtherReason;

    public $trainingDate;

    public $bankDetail;

    public $voter;

    public $aadhar;

    public $worksForMale;

    public $agency;

    public $documents;

    public $availability;

    /**
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param mixed $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }



    /**
     * @return mixed
     */
    public function getTrainingDate()
    {
        return $this->trainingDate;
    }

    /**
     * @param mixed $trainingDate
     */
    public function setTrainingDate($trainingDate)
    {
        $this->trainingDate = $trainingDate;
    }

    /**
     * @return mixed
     */
    public function getTrainingNotAttendedOtherReason()
    {
        return $this->trainingNotAttendedOtherReason;
    }

    /**
     * @param mixed $trainingNotAttendedOtherReason
     */
    public function setTrainingNotAttendedOtherReason($trainingNotAttendedOtherReason)
    {
        $this->trainingNotAttendedOtherReason = $trainingNotAttendedOtherReason;
    }

    /**
     * @return mixed
     */
    public function getTrainingNotAttendedReason()
    {
        return $this->trainingNotAttendedReason;
    }

    /**
     * @param mixed $trainingNotAttendedReason
     */
    public function setTrainingNotAttendedReason($trainingNotAttendedReason)
    {
        $this->trainingNotAttendedReason = $trainingNotAttendedReason;
    }




    /**
     * @return mixed
     */
    public function setAadhar($aadhar)
    {
        $this->aadhar = $aadhar;
    }

    /**
     * @param mixed $aadhar
     */
    public function getVoter()
    {
        return $this->voter;
    }
    /**
     * @param mixed $voter
     */
    public function setVoter($voter)
    {
        $this->voter = $voter;
    }
    /**
     * @param mixed $voter
     */
    public function getAadhar()
    {
        return $this->aadhar;
    }
    /**
     * @param mixed $aadhar
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getAlternateNo()
    {
        return $this->alternateNo;
    }

    /**
     * @param mixed $alternateNo
     */
    public function setAlternateNo($alternateNo)
    {
        $this->alternateNo = $alternateNo;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * @param mixed $food
     */
    public function setFood($food)
    {
        $this->food = $food;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getHasBankAccount()
    {
        return $this->hasBankAccount;
    }

    /**
     * @param mixed $hasBankAccount
     */
    public function setHasBankAccount($hasBankAccount)
    {
        $this->hasBankAccount = $hasBankAccount;
    }

    /**
     * @return mixed
     */
    public function getHasSmartPhone()
    {
        return $this->hasSmartPhone;
    }

    /**
     * @param mixed $hasSmartPhone
     */
    public function setHasSmartPhone($hasSmartPhone)
    {
        $this->hasSmartPhone = $hasSmartPhone;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
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
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * @param mixed $qualification
     */
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;
    }

    /**
     * @return mixed
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param mixed $religion
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    }

    /**
     * @return mixed
     */
    public function getShift()
    {
        return $this->shift;
    }

    /**
     * @param mixed $shift
     */
    public function setShift($shift)
    {
        $this->shift = $shift;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param mixed $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return mixed
     */
    public function getTrainingAttended()
    {
        return $this->trainingAttended;
    }

    /**
     * @param mixed $trainingAttended
     */
    public function setTrainingAttended($trainingAttended)
    {
        $this->trainingAttended = $trainingAttended;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getBankDetail()
    {
        return $this->bankDetail;
    }

    /**
     * @param mixed $bankDetail
     */
    public function setBankDetail($bankDetail)
    {
        $this->bankDetail = $bankDetail;
    }

    public function getWorksForMale()
    {
        return $this->worksForMale;
    }

    public function setWorksForMale($worksForMale)
    {
        $this->worksForMale = $worksForMale;
    }

    public function getAgency(){
        return $this->agency;
    }

    public function setAgency($agency){
        $this->agency = $agency;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param mixed $locality
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    /**
     * @return mixed
     */
    public function getLocalityFormattedAddress()
    {
        return $this->localityFormattedAddress;
    }

    /**
     * @param mixed $localityFormattedAddress
     */
    public function setLocalityFormattedAddress($localityFormattedAddress)
    {
        $this->localityFormattedAddress = $localityFormattedAddress;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param mixed $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }





    public function convertToDTO($vendor){
        $vendorDto = new VendorDetailedDTO();
       //dd($vendor);
        $vendorDto->setId($vendor->id);
        $vendorDto->setUserId($vendor->user_id);
        $vendorDto->setAadhar($vendor->aadhar);
        $vendorDto->setVoter($vendor->voter);
        if($vendor->user){
            $vendorDto->setEmail($vendor->user->email);
            $vendorDto->setName($vendor->user->name);
            $vendorDto->setPhone($vendor->user->phone);
        }
        $vendorDto->setCategory($vendor->category);
        $vendorDto->setAlternateNo($vendor->alternate_no);
        $vendorDto->setAddress($vendor->address);
        $vendorDto->setZone($vendor->locationOfWork);
        $vendorDto->setAge($vendor->age);
        $vendorDto->setWeight($vendor->weight);
        $vendorDto->setHeight($vendor->height);
        $vendorDto->setSource($vendor->source);
        $vendorDto->setAgency($vendor->agency);
        $vendorDto->setReligion($vendor->religion);
        $vendorDto->setFood($vendor->foodType);
        //d($vendor->localityObject);
        if($vendor->localityObject){
            $vendorDto->setLocality(json_decode($vendor->localityObject->json));
            $vendorDto->setLocalityFormattedAddress($vendor->localityObject->formatted_address);
        }

        $taskDto = new TaskDTO();
        // dd($vendor->task);
        $tasks = [];
        foreach ($vendor->task as $task) {
            array_push($tasks, $taskDto->convertToDTO($task));
        }
        $vendorDto->setTask($tasks);
        $vendorDto->setQualification($vendor->qualification);
        $vendorDto->setTrainingAttended($vendor->training_attended);
        if($vendor->training_attended == 1){
            $vendorDto->trainingAttended = true;
            if($vendor->training_attended_date){
                $vendorDto->setTrainingDate($vendor->training_attended_date);
            }else{
                $vendorDto->setTrainingDate('');
            }
        }
        else{
            $vendorDto->trainingAttended = false;
            $vendorDto->setTrainingNotAttendedReason($vendor->trainingNotAttendedReason);
            $vendorDto->setTrainingNotAttendedOtherReason($vendor->training_not_attended_other_reason);
            if($vendor->training_attended_date){
                $vendorDto->setTrainingDate($vendor->training_attended_date);
            }else{
                $vendorDto->setTrainingDate('');
            }
        }
        $vendorDto->hasSmartPhone = $vendor->has_smart_phone;
        if( $vendor->has_smart_phone == 1){
            $vendorDto->hasSmartPhone = true;
        }
        else{
            $vendorDto->hasSmartPhone = false;
        }
        $vendorDto->setGender($vendor->genderObject);
        $vendorDto->setExperience($vendor->experience);
        $vendorDto->setShift($vendor->shift);
        $vendorDto->setHasBankAccount($vendor->has_bank_account);
        if($vendor->has_bank_account == 1){
            $vendorDto->hasBankAccount = true;
        }
        else{
            $vendorDto->hasBankAccount = false;
        }
        if($vendor->has_bank_account){
            $vendorDto->setBankDetail($vendor->bankDetail);
        }
        if($vendor->works_for_male == 1){
            $vendorDto->worksForMale = true;
        }
        else{
            $vendorDto->worksForMale = false;
        }


        $vendorDto->setDocuments(array());
        if(count($vendor->uploadedDocuments)){
            $vendorDocumentDto = new VendorDocumentDTO();
            $docs = array();
            foreach($vendor->uploadedDocuments as $tempDoc){
                array_push($vendorDto->documents,$vendorDocumentDto->convertToDTO($tempDoc));
            }
        }

        //d($vendor->vendorAvailabilities);

        if(count($vendor->vendorAvailabilities)>0){
            $vendorAvailabilityDto =  new VendorAvailabilityDTO();
            $vendorDto->setAvailability($vendorAvailabilityDto->convertToDTO($vendor->vendorAvailabilities->last()));
        }

        return $vendorDto;
    }




}