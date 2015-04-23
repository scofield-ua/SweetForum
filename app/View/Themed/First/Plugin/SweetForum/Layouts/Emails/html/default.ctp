<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">	
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; max-width:700px; box-shadow:0 2px 4px rgba(0,0,0,0.075)" width="95%">
					<tr>
						<td align="center" bgcolor="#FFF7F1" style="font-size: 24px; font-weight: bold; font-family: Arial, sans-serif;">
							<a href='http://<?= $_SERVER['SERVER_NAME'].SWEET_FORUM_BASE_URL; ?>' style='color:#000; text-decoration:none; display:block; padding:20px 0'>SweetForum</a>
						</td>
					</tr>
					<tr>
						<?php echo $this->fetch('content'); ?>						
					</tr>
					<tr>
						<td bgcolor="#f7f7f7" style="padding: 30px 30px 30px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="color: #000; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
										&reg; <?= date("Y"); ?> <a href="http://sf.saydima.com">SweetForum</a><br/>
									</td>									
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>