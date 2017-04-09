<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        body {
            font-family: Calibri;
            font-size: 14.66px;
            text-align: justify;
        }
    </style>
</head>
<body>
<div class="zw-paginated zw-page" style="width: 600px; height: auto; position: relative; margin: auto; background: none rgb(255, 255, 255);">
    <table cellspacing="0" cellpadding="5"  width="600" style="background: #FFFFFF">
        <tr>
            <td colspan="2" style="text-align: center" ><img src="https://gallery.mailchimp.com/0912bc03d4dfb83b06a77b2b9/images/a11f9c34-c4bb-43b6-83d7-c29ae744eea7.png" width="200" alt="Pramati Care" /></td>

        </tr>
        <?php
        ?>
        <tr>
            <td colspan="2">
                Dear Sir/ Madam<br /><br />
                <div>
                    <?php
                    if(isset($lead->userCreated)){
                        echo "New lead is created by ".$lead->userCreated->name." with following details:";
                    }else{
                        echo "New lead is created with following details:";
                    }
                    ?>
                </div>
                <br />
            </td>
        </tr>
        <tr>
            <td width="30%">Customer Name</td>
            <td><?php echo $lead->customer_name;  ?> <?php echo $lead->customer_last_name;  ?></td>
        </tr>
        <tr>
            <td width="30%">Service Required</td>
            <td><?php echo $lead->service->name;  ?></td>
        </tr>
        <tr>
            <td width="30%">Phone No.</td>
            <td><a href="<?php echo $leadUrl; ?>">xxxxxxxxxx</a></td>
        </tr>
        <tr>
            <td width="30%">Email</td>
            <td><?php echo $lead->email;  ?></td>
        </tr>
        <tr>
            <td width="30%">Location</td>
            <td><?php echo $lead->address; ?> <?php  echo (isset($lead->locality)?$lead->locality->formatted_address:'NA');  ?></td>
        </tr>
        <tr>
            <td width="30%">Reference</td>
            <td><?php  echo (isset($lead->leadReference)?$lead->leadReference->label:'NA');  ?></td>
        </tr>
        <tr>
            <td colspan="2">

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Patient Information</strong>
            </td>
        </tr>

        <tr>
            <td width="30%">Name</td>
            <td><?php  echo ((isset($lead->patient) && isset($lead->patient->name))?$lead->patient->name:'NA');  ?></td>
        </tr>
        <tr>
            <td width="30%">Age of Patient</td>
            <td><?php  echo ((isset($lead->patient) && isset($lead->patient->age))?$lead->patient->age:'NA');  ?></td>
        </tr>
        <tr>
            <td width="30%">Weight of Patient</td>
            <td><?php  echo ((isset($lead->patient) && isset($lead->patient->weight))?$lead->patient->weight:'NA');  ?></td>
        </tr>
        <tr>
            <td width="30%">Gender</td>
            <td><?php  echo ((isset($lead->patient) && isset($lead->patient->genderItem)&& isset($lead->patient->genderItem->label))?$lead->patient->genderItem->label:'NA');  ?></td>
        </tr>
        <tr>
            <td width="30%">Ailments</td>
            <td>
                <?php
                    if(isset($lead->patient) && isset($lead->patient->ailments) && $lead->patient->ailments->count()){
                        $ailmentCount = 0;
                        foreach($lead->patient->ailments as $tempAilment){
                            if($ailmentCount==0){
                                echo  $tempAilment->name;
                            }else{
                                echo  ", ".$tempAilment->name;
                            }
                            $ailmentCount++;
                        }
                    }else{
                        echo "NA";
                    }
                ?>
            </td>

        </tr>
        <tr>
            <td width="30%">Task Required</td>
            <td>
                <?php
                if(isset($lead->patient) && isset($lead->patient->tasks) && $lead->patient->tasks->count()>0){
                    $ailmentCount = 0;
                    foreach($lead->patient->tasks as $tempAilment){
                        if($ailmentCount==0){
                            echo  $tempAilment->label;
                        }else{
                            echo  ", ".$tempAilment->label;
                        }
                        $ailmentCount++;
                    }
                }else{
                    echo "NA";
                }
                ?>
            </td>

        </tr>


        <tr>
            <td colspan="2">

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Service Detail</strong>
            </td>
        </tr>
        <tr>
            <td width="30%">Start Date</td>
            <td><?php echo $lead->start_date;  ?></td>
        </tr>
        <tr style="">
            <td colspan="2">
                <div style="text-align: center">
                    <a href="<?php echo $leadUrl; ?>">Click to View Lead Detail</a>
                </div>
            </td>
        </tr>
        <tr style="background: #1eb290; color: #FFFFFF ">
            <td colspan="2">
                <div style="text-align: center">
                    To know more about us visit us at <a href="http://www.pramaticare.com">www.pramaticare.com</a> <br />Like us on <a href="https://www.facebook.com/Pramaticare">https://www.facebook.com/Pramaticare</a>
                </div>
            </td>
        </tr>
    </table>


</div>
</body>
</html>
