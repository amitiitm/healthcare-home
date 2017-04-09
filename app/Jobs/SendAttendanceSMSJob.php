<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Contracts\Domain\IOperationDomainContract;

class SendAttendanceSMSJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $phone;

    protected $userType;

    public function __construct($phone, $userType)
    {
        $this->phone = $phone;
        $this->userType = $userType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("SMS Sending in job to ".$this->phone." for attendance.");

        if($this->userType == 2){
            $message = urlencode('प्रिये Pramaticare साथी - यदि आप आज ड्यूटी पे जा रहे हैं तो जवाब दे "P" इस no 9230002323 पे. नहीं तो जवाब दे "AB". यदि आप SMS नहीं करते तो आपको Rs 50 incentive नहीं मिल पायेगा. धन्यवाद.');
        } else if($this->userType == 3){
            $message = urlencode('Dear Customer, to mark attendance of your Pramaticare Nursing staff, Pls send "P" to 923002323. For absent, pls send "AB"');
        }

        $smsUrl = env('SMS_URL');
        $url = 'user='.env('SMS_USER');
        $url.= '&pwd='.env('SMS_PASSWORD');
        $url.= '&senderid='.env('SMS_SENDER_ID');
        $url.= '&mobileno='.$this->phone;
        $url.= '&msgtext='.$message;
        $url.= '&smstype=13';
        $url.= '&dnd=1';
        $url.= '&unicode=0';

        $urlToUse =  $smsUrl.$url;

        // TEMP: Send msgs to only CG
        //if($this->userType == 2){
            $ch = curl_init($urlToUse);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            echo $curl_scraped_page;
            curl_close($ch);
        //}

    }
}
