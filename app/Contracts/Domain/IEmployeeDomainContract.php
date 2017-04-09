<?php

namespace App\Contracts\Domain;

interface IEmployeeDomainContract
{

    public function getAllEmployee();
    public function getAllEmployeeTrackingData();
    public function getFilteredEmployeeTrackingData($userName,$limit);
    public function getEmployeeDetailedOrm($employeeId);
    public function generateSlackUsernameForEmployee($employeeId);

}
