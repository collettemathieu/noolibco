<tr>
	<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
					<b>Vous avez reçu un message d'un utilisateur !</b>
				</td>
			</tr>
			<tr>
				<td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
					<strong><?php echo $titreMail;?></strong>
				</td>
			</tr>
			<tr>
				<td style="text-align: justify;">
					<?php echo nl2br($message);?>
				</td>
			</tr>
		</table>
	</td>
</tr>