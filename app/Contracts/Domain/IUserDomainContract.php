<?php

namespace App\Contracts\Domain;

interface IUserDomainContract
{

    public function getUserList();
    public function addUser($userType,$name,$email,$password,$phone);
    public function getUserByEmail($email);




    // Below are waste for Pramaticare
    public function getUser($id);

    public function getUserWithLevels($id);

    public function changeLevel($userId, $newLevelId);

    public function getIdsFromEmails($emailList);

    public function getAdministrators();

    public function getModerators();

    public function getEditors();

    public function getAuthors();

    public function getUserContributions($id);
    
    public function getUserArticles($id);

    public function sendResetLink($user,$token);



    public function getUserByPhone($phone);

    public function getCustomerByPhone($phone);
    public function createCustomerByPhone($leadOrm,$phone);
    public function createCustomerByData($data);
    public function updateCustomerPhoneOnLead($userId,$phone);
    public function sendOtpForLogin($user);
    public function registerDeviceToken($userId,$deviceToken);


    public function updateSlackUserInfo($slackInfo);

    public function getUserIdByEmailList($userEmailList);
    public function getEmployeeByIdList($userIdList);

}
