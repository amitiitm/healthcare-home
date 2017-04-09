<?php

namespace App\Contracts\Rest;

interface IVendorRestContract
{

    public function getVendorGridList($pageNumber,$pageSize);
    public function getVendorGridListByLead($leadId);
    public function getVendorDeployedStatusList();

    public function getVendorCount();

    public function submitVendor($inputAll);

    public function getVendorDetail($vendorId);

    public  function  updateVendorDetail($inputAll, $vendorId);

    public  function  deleteVendorDetail($vendorId);
    public  function  getVendorAvailabilityOptions();
    public  function  getVendorDocumentTypes();
    public  function  getVendorTrainingReasons();

    public function updateVendorAvailability($vendorId,$inputAll);
    public function deleteVendorDocument($vendorDocumentId);
    public function uploadVendorDocument($file,$inputAll);
    public function getVendorTaskDetailGrouped($vendorId);
    public function getAvailableVendors();


}