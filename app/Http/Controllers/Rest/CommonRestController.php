<?php

namespace App\Http\Controllers\Rest;

use App\Contracts\Rest\ICommonRestContract;
use App\Contracts\Rest\IOperationRestContract;
use App\Contracts\Rest\IUserRestContract;
use App\Contracts\Rest\IVendorRestContract;
use App\Http\Controllers\Controller;
use App\Models\ORM\Customer;
use App\Models\ORM\Export;
use App\Models\ORM\Feedback;
use App\Models\ORM\Lead;
use App\Models\ORM\LeadEmployee;
use App\Models\ORM\OfficeEmp;
use App\Models\ORM\UserCustomer;
use App\Models\ORM\UserVendor;
use App\Models\User;
use App\Models\ORM\UserEmployee;
use App\Templates\PRResponse;
use App\Templates\SCResponse;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Illuminate\Support\Facades\Response;

class CommonRestController extends Controller
{

    protected $commonRestService;

    protected $operationRestService;

    protected $vendorRestService;

    public function __construct(ICommonRestContract $commonRestContract, IOperationRestContract $operationRestContract, IVendorRestContract $vendorRestService)
    {
        $this->commonRestService = $commonRestContract;
        $this->operationRestService= $operationRestContract;
        $this->vendorRestService = $vendorRestService;
    }

    public function getLeadReferences()
    {

        $data = $this->commonRestService->getLeadReferences();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getShifts()
    {
        $data = $this->commonRestService->getShifts();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getAilments()
    {
        $data = $this->commonRestService->getAilments();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getEquipments()
    {
        $data = $this->commonRestService->getEquipments();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getLanguages()
    {
        $data = $this->commonRestService->getLanguages();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getReligions()
    {
        $data = $this->commonRestService->getReligions();
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getServicesList()
    {
        $data = $this->commonRestService->getServicesList(true);
        return response()->json(PRResponse::getSuccessResponse("List fetched successfully", $data));
    }

    public function getExportData()
    {
        $allRows = Export::get();
        $mapper = [];
        $counter = 0;

        //echo "<table border='1'><thead><tr><td>Employee_ID</td><td>Employee_Name</td><td>Gender</td><td>Employee_Category_ID</td><td>Weight</td><td>Age</td><td>Height</td><td>Religion</td><td>Is Vegetarian</td><td>Location_ID</td><td>Address</td><td>Alternate_No</td><td>Has_Smart_Phone</td><td>Has_Bank_Account</td><td>Qualification_ID</td><td>Training_Attended</td><td>Training_ID</td><td>Experience</td><td>Preferred_Shift</td><td>Location_Of_Work</td><td>Agency_ID</td><td>Security_Cheque</td><td>Is_Active</td><td>Feedback</td></tr></thead>";
        foreach ($allRows as $temp) {
            if ($counter == 0) {
                $counter++;
                continue;
            }
            //echo "<tbody><tr>"."<td width='100px'>".$temp->EmployeeID."</td>"."<td width='100px'>".$temp->EmpName."</td>"."<td width='100px'>".$temp->Gender."</td>"."<td width='100px'>".$temp->EmpCategoryID."</td>"."<td width='100px'>".$temp->Weight."</td>"."<td width='100px'>".$temp->Age."</td>"."<td width='100px'>".$temp->Height."</td>"."<td width='100px'>".$temp->Religion."</td>"."<td width='100px'>".$temp->IsVegitarian."</td>"."<td width='100px'>".$temp->LocationID."</td>"."<td width='100px'>".$temp->Address."</td>"."<td width='100px'>".$temp->AlternateNo."</td>"."<td width='100px'>".$temp->HasSmartPhone."</td>"."<td width='100px'>".$temp->IsBankAccount."</td>"."<td width='100px'>".$temp->QualificationID."</td>"."<td width='100px'>".$temp->TrainingAttende."</td>"."<td width='100px'>".$temp->TrainingID."</td>"."<td width='100px'>".$temp->Expirience."</td>"."<td width='100px'>".$temp->PrefDutyShift."</td>"."<td width='100px'>".$temp->LocationofWork."</td>"."<td width='100px'>".$temp->AgencyID."</td>"."<td width='100px'>".$temp->SecurityCheque."</td>"."<td width='100px'>".$temp->isActive."</td>";

            /*echo "<td width='100px'>";
            foreach($temp->feedback as $feeds){
                $action= $feeds->any_action_taken;
                $feeddate= $feeds->feedback_date;
                $reason= $feeds->other_reason;
                echo "<b>Action: </b>".$action."<br><b>Other reason: </b>".$reason."<br><b>On: </b>".$feeddate."<hr />";
            }
            echo "</td>";

            echo "</tr></tbody>";

            }echo "</table>";  */
            $user = new User();
            $user->name = $temp->EmpName;
            $counter++;

            $userAlready = User::where('email','=',$temp->EmailID)->count();
            if($userAlready>0){
             //   continue;
            }

            if ($temp->EmailID != "" && strpos('@', $temp->EmailID) >= 0 && $userAlready==0) {
                $user->email = $temp->EmailID;
            } else {
                $user->email = "info" . $counter . "@pramaticare.com";
            }

            $user->phone = $temp->PhoneNo;
            $user->user_type_id = '2';

            $user->save();

            if ($temp->EmailID !="" && !strpos($temp->EmailID, '@')) {
                $user->email = "vendor" . $user->id . "@pramaticare.com";
            }else {
             //   $user->email = "info" . $counter . "@pramaticare.com";
            }
            $user->password = "pramaticare";
            $user->save();

            echo $user->name . "<hr />";

            $employee = new UserVendor();
            $employee->user_id = $user->id;
            $employee->gender = $temp->Gender;
            $employee->employee_category_id = $temp->EmpCategoryID;
            $employee->weight = $temp->Weight;
            $employee->age = $temp->Age;
            $employee->height = $temp->Height;
            $employee->religion_id = $temp->Religion;
            $employee->food_type_id = $temp->IsVegitarian;
            $employee->locality_id = $temp->LocationID;
            $employee->address = $temp->Address;
            $employee->alternate_no = $temp->AlternateNo;
            $employee->has_smart_phone = $temp->HasSmartPhone;
            $employee->has_bank_account = $temp->IsBankAccount;
            $employee->qualification_id = $temp->QualificationID;
            $employee->training_attended = $temp->TrainingAttende;
            $employee->training_id = $temp->TrainingID;
            $employee->experience = $temp->Expirience;
            $employee->preferred_shift_id = $temp->PrefDutyShift;
            $employee->location_of_work = $temp->LocationofWork;
            $employee->agency_id = $temp->AgencyID;
            $employee->security_check = $temp->SecurityCheque;
            $employee->is_active = $temp->isActive;

            $employee->save();

        }
    }
    public function getOfficeEmp()
    {
        return redirect('/');
        die();
        $officers = OfficeEmp::get();
        $counter = 0;

            echo "<table>";
            echo "<tr>";
            echo "<td>Name</td>";
            echo "<td>Email</td>";
            echo "<td>Password</td>";
            echo "<td>Phone</td>";
            echo "</tr>";
            foreach ($officers as $temp) {
                if ($counter == 0) {
                    $counter++;
                    continue;
                }
                $counter++;
                $user= new User();
                $user->name = $temp->name;
                $valid=true;
                if(!strpos($temp->user_id,'@')){
                    $user->email = $temp->email_id;
                }else{
                        $user->email = $temp->user_id;
                        $valid=false;
                }

                $userAlready = User::where('email','=',$user->email)->count();
                if($userAlready>0){
                    continue;
                }
                $user->user_type_id=1;

                $user->phone = $temp->phone_no;
                $user->password = bcrypt($temp['password']);



            $user->save();




                $employee = new UserEmployee();
                $employee->address = $temp->address;
                $employee->user_id = $user->id;
                $employee->username = $temp->user_id;
                $employee->employee_category_id = $temp->EmpCategoryID;
                $employee->save();
                echo "<tr>";
                echo "<td>".$user->name."</td>";
                echo "<td>".$user->email."</td>";
                echo "<td>".$temp->password."</td>";
                echo "<td>".$user->phone."</td>";
                echo "</tr>";
            }
            echo "</table>";
        
    }

    private function httpRequest($url){
        $pattern = "/http...([0-9a-zA-Z-.]*).([0-9]*).(.*)/";

        //echo $pattern;
        $args = array();
        preg_match($pattern,$url,$args);

        $in = "";
        $fp = fsockopen($args[1],80, $errno, $errstr, 30);
        if (!$fp) {
            return("$errstr ($errno)");
        } else {
            $args[3] = "C".$args[3];
            $out = "GET /$args[3] HTTP/1.1\r\n";
            $out .= "Host: $args[1]:$args[2]\r\n";
            $out .= "User-agent: PARSHWA WEB SOLUTIONS\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            while (!feof($fp)) {
                $in.=fgets($fp, 128);
            }
        }
        fclose($fp);
        return($in);
    }

    public function sendSMS(){
        $debug=true;
        $smsUrl = env('SMS_URL');

        $url = 'user=20078639';
        $url.= '&password=rpuuib';
        $url.= '&sender=PRAMTI';
        $url.= '&mobileno='.urlencode("9971119874");
        $url.= '&msgtext='.urlencode("Tesing");
        $url.= '&smstype=0';
        $url.= '&dnd=1';
        $url.= '&unicode=0';

        echo $urlToUse =  $smsUrl.$url;

error_reporting(1);
        $ch = curl_init($urlToUse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        echo $curl_scraped_page;



        die();


        //Open the URL to send the message

        //http://bulksmsindia.mobi/sendurlcomma.aspx?user=profileid&pwd=password&senderid=ABC&mobileno=9999999989,9999999990,9999999991&msgtext=Hello&smstype=0/4/3
        $response = $this->httpRequest($urlToUse);
        if ($debug) {
            echo "Response: <br><pre>".
                str_replace(array("<",">"),array("&lt;","&gt;"),$response).
                "</pre><br>"; }

        return($response);
    }

    public function getCustomer() {
        $customer = Customer::get();
        $counter = 0;

        foreach ($customer as $temp) {
            if ($counter==0){
                $counter++;
                continue;
            }
            $counter++;
            $user = new User();
            //$user->name = $temp->CustomerName;
            if($temp->Email == ""){
                $user->email = "info".$counter."@paramaticare.com";
            }else {
                $user->email = $temp->Email;
            }
            $user->phone = $temp->PhoneNo;
            $user->password = "pramaticare";
            $user->user_type_id = '3';
            $lead = new Lead();
            $user_cx = new UserCustomer();
            $isAlreadyExist = User::where('email','=',$user->email)->first();
            if(!$isAlreadyExist){
                $user->save();
                $lead->user_id = $user->id;
                $user_cx->user_id = $user->id;
            }else{
                $lead->user_id = $isAlreadyExist->id;
            }



            $lead->customer_name = $temp->CustomerName;
            $lead->email = $temp->Email;
            $lead->phone = $temp->PhoneNo;
            $lead->address = $temp->CustAddress;
            $lead->locality_id = $temp->LocationID;
            $lead->start_date = $temp->StartDate;
            $lead->number_of_days = $temp->NoofDays;
            $lead->payment_type_id = $temp->CollectionType;
            $lead->payment_period_id = $temp->CollectionPeriod;
            $lead->payment_mode_id = $temp->ModeOfPayment;
            $lead->remark = $temp->Remarks;
            $lead->save();

            $user_cx->address = $lead->address;
            $user_cx->save();

        }
    }

    public function getAdminDashboardData(){
        $dataObject = array(
            'leadCount'=>$this->operationRestService->getLeadCount(),
            'unassignedLeadCount'=>$this->operationRestService->getUnassignedLeadCount(),
            'availableEmployees'=>$this->vendorRestService->getVendorCount(),
            'ongoingSessions'=>$this->operationRestService->getActiveLeadCount()
        );
        return Response::json(PRResponse::getSuccessResponse('',$dataObject));
    }




}