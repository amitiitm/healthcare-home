<?php

namespace App\Contracts\Helper;

interface ISlackHelperContract
{
    public function generateSlackForUser($userOrm);

    public function newLeadNotification($leadOrm);
    public function employeeAssignedNotification($leadOrm,$assignedUser);
    public function qcAssignedNotification($leadOrm,$assignedUser);
    public function fieldAssignedNotification($leadOrm,$assignedUser);
    public function cgAssignedNotification($leadOrm,$assignedUser,$isPrimary);
    public function projectStartNotification($leadOrm);
    public function projectClosureRequestNotification($leadOrm,$requestObject);
    public function cgNotReachedNotification($leadOrm,$requestObject);
    public function projectRestartRequestNotification($leadOrm,$requestObject);
    public function customerSubmittedCaregiverAttendanceNotification($leadOrm,$requestObject);
}
