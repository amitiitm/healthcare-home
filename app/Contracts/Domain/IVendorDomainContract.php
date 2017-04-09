<?php

namespace App\Contracts\Domain;

interface IVendorDomainContract
{

    public function getVendorList($pageNumber = 0,$pageSize = 0);   
    public function getVendorListByLead($leadId);   
    public function getDeployedVendor();

    public function getVendorCount();
    public function createVendorUser($name,$email,$phone,$password);
    public function updateVendorUser($userId,$name,$email,$phone,$password);
    public function createVendorDetailByORM($vendorOrm);
    public function updateVendorDetailByORM($vendorOrm);
    public function updateVendorTask($vendorId,$taskList);
    public function createLocalityByOrm($localityOrm);
    public function updateBankAccountDetail($vendorId,$bankDetail);
    public function getVendorDetailedOrm($vendorId);
    public function getVendorLocality($vendorId);
    public function getVendorBankDetailedOrmByVendorId($vendorId);
    public function voterIdExist($voter);
    public function voterIdExistForUpdate($voter,$vendorId);
    public function aaddharIdExistForUpdate($aadhar,$vendorId);
    public function aadharIdExist($aadhar);
    public function phoneExist($phone);
    public function phoneExistForUpdate($phone,$userId);
    public function isEmailExistForAdd($email);

    public function updateVendorTrackingLocation($vendorUserId,$latitude,$longitude,$locationTime);

    public function getVendorAvailabilityOptionsWithReason();
    public function getVendorDocumentTypes();
    public function getVendorTrainingReasons();
    public function uploadVendorDocument($file,$documentTypeId,$documentCaption,$vendorId);
    public function addVendorDocumentToVendor($vendorId,$documentList);
    public function updateVendorAvailabilityByORM($vendorId, $vendorAvailabilityOrm);
    public function deleteVendorDocument($vendorDocumentId);
    public function getVendorDocumentById($documentId);
    public function getAvailableVendors();



}
