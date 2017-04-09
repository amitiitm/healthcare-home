<?php

namespace App\Models\Enums;

class PramatiConstants
{
    /*
     * Map of ids of User Levels
     */
    const REGULAR = 1;
    const AUTHOR = 2;
    const MODERATOR = 3;
    const EDITOR = 4;
    const ADMIN = 5;
    /*
     * Error Messages
     */


    const EMPLOYEE_USER_TYPE=1;
    const CAREGIVER_USER_TYPE=2;
    const CUSTOMER_USER_TYPE=3;

    const LEAD_SUBMISSION_MODE_ONLINE_CALL='online-call-me-now';
    const LEAD_SUBMISSION_MODE_ONLINE_FORM='online-submission';
    const LEAD_SUBMISSION_MODE_CRM='crm';
    const LEAD_SUBMISSION_MODE_CAMPAIGN='campaign';


    const CARE_PLAN_ACTION_PRIMARY_EVALUATION = "primary-cg-evaluation";
    const CARE_PLAN_ACTION_BACKUP_EVALUATION = "backup-cg-evaluation";
    const CARE_PLAN_ACTION_TRAINING_EVALUATION = "cg-training-evaluation";
    const CARE_PLAN_ACTION_SIGN_OFF_EVALUATION = "cg-sign-off-evaluation";

    const PENDING_INVOICE_STATUS = 'pending';
    const APPROVED_INVOICE_STATUS = 'approved';
    const PAID_INVOICE_STATUS = 'payment received';
    
    //Invoice Payment Status
    const PAID_PAYMENT_STATUS = 'paid';
    const UNPAID_PAYMENT_STATUS = 'unpaid';
    const RECOVERABLE_OS_AMT_TYPE = 'recoverable';
    const NORECOVERABLE_OS_AMT_TYPE = 'norecoverable';
    const PRICE_ISSUE = 'price_issue';
    const PART_CLOSE = 'part_close';
    const LEAVES_ISSUE = 'leaves_issue';
    const UNHAPPY = 'unhappy_with_service';
    const BAD_DEBT = 'bad_debt';
    const OTHERS = 'others';
    
    // Unpaid Payment Statuses
    const SERVICE_CLOSE = 'service_close';
    const SERVICE_ISSUE = 'service_issue';
    const CLIENT_NOT_AVAILABLE = 'client_not_available';
    
    //Enquiry Status Constants
    const INFO_GATHERING = 'info_gathering';
    const PRICE_CHECKING = 'price_checking';
    const NOT_INTERESTED = 'not_interested';
    const FOLLOW_UP = 'followup_needed';
    const WILL_GET_BACK = 'will_get_back';
    const FOLLOWUP_CONVERTED = 'followup_converted';
    const EXISTING_USER_TYPE = 'existing';
    const NONEXISTING_USER_TYPE = 'non_existing';

}