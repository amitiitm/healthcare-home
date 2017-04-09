<?php

namespace App\Contracts\Rest;

interface IEmployeeRestContract
{

    public function getEmployeeGridList();
    public function getEmployeeTrackingList();
    public function getFilteredEmployeeTrackingList($userName,$limit);
    public function getEmployeeDetail($employeeId);
    public function getEmployeeDetailedInfo($employeeId);
    public function generateSlackUser($employeeId);
}