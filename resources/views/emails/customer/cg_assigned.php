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
                Dear Sir/Madam,<br /><br />
                <div>
                    <p>Dear Customer, the service provider has been assigned for your service. Please check the App for more details.</p>
                </div>
                <br />
                <p>Your service details are as followed:</p>
            </td>
        </tr>
        <tr>
            <td width="30%">Service Required</td>
            <td><?php echo $lead->service->name;  ?></td>
        </tr>

        <tr>
            <td width="30%">Start Date</td>
            <td><?php echo $lead->start_date;  ?></td>
        </tr>

        <?php
        if($lead->primaryVendorsAssigned->count()>0) {
            ?>
            <tr>
                <td width="30%">Care Giver Assigned</td>
                <td><?php echo $lead->primaryVendorsAssigned->last()->name;  ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td colspan="2">
                <p>Thank you for choosing Pramati Care</p>
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
