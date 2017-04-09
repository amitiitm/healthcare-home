<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Collection Notification</title>
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
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td ><img src="https://gallery.mailchimp.com/0912bc03d4dfb83b06a77b2b9/images/a11f9c34-c4bb-43b6-83d7-c29ae744eea7.png" width="200" alt="Pramati Care" /></td>
			<td align="right">
				<div style="line-height:50px;font-size:24px">
					<strong style="float:right"><a href="tel:%2B91-8010667766" value="+918010667766" target="_blank">+91-8010667766</a></strong>
					<img src="https://gallery.mailchimp.com/0912bc03d4dfb83b06a77b2b9/images/1dacf680-607b-4f23-9f60-da9eb7a7bbdc.png" style="float:right;display:inline-block" class="CToWUd">
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<img src="https://gallery.mailchimp.com/0912bc03d4dfb83b06a77b2b9/images/1925aaeb-6161-4047-b654-28f9c81115c2.jpg" width="100%" />
				<div class="zw-contentpane selectableSection" style=" margin-top: 0px; position: relative;">
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">Dear <?php echo $user->name ?>  </p>
                        
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">Please collect payment of Rs. <?php echo $invoice->total_amount ?> against Invoice# <?php echo $invoice->invoice_number ?> on Date : <?php echo $date_of_collection ?></p>
                        
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">Customer Name : <?php echo $lead->customer_name ?> </p>
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">Phone : <?php echo $lead->phone ?> </p>
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">Landmark : <?php echo $lead->landmark ?> </p>
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">For any query or feedback call us at 8010667766 </p>
                        <p class="zw-portion" data-text-len="15" style="color: rgb(111, 111, 111);">We are happy to serve you!!</p>
                    </div>
			</td>
		</tr>
	</table>

</div>
</body>
</html>
