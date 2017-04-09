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
				<div class="zw-contentpane selectableSection" style=" margin-top: 0px; position: relative;">
                        <table>
                            <tbody>
                                <tr>
                                    <td><b>Complaint ID:</b> </td>
                                    <td><?php echo $complaint->id;?></td>
                                </tr>
                                <tr>
                                    <td><b>User Name:</b> </td>
                                    <td><?php echo $userInfo->name.' ('.$user_type_label.')';?></td>
                                </tr>
                                <tr>
                                    <td><b>User Phone:</b> </td>
                                    <td><?php echo $userInfo->phone;?></td>
                                </tr>
                                <tr>
                                    <td><b>Complaint Category:</b> </td>
                                    <td><?php echo $complaint_category_label;?></td>
                                </tr>
                                <tr>
                                    <td><b>Details:</b> </td>
                                    <td><?php echo $complaint->details;?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
			</td>
		</tr>
	</table>

</div>
</body>
</html>
