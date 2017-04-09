<?php

namespace App\Contracts\Rest;

interface ICommonRestContract
{
    public function getLeadReferences();
    public  function getShifts();
    public  function getAilments();
    public function getEquipments();
    public function getLanguages();
    public function getReligions();
    public function getAgeRangesForFront();
    public function getFoodTypes();
    public function getServicesList($sorted);
    public function getConditions();
    public function getComplaints();
    public function getPtconditions();
    public function getModalities();
    public function getAilmentList($serviceId);
    public function getTaskList($ailmentId);
    public function getExportData();
    public function getVendorData($id);
}