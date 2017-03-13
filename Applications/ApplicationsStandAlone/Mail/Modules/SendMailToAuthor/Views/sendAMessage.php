<style type="text/css">
.btn-class{
 display: inline-block;
 border:1px solid #bfbfbf;
 color: #8c8c8c;
 border-radius: 5px 5px 5px 5px;
 -webkit-border-radius: 5px 5px 5px 5px;
 -moz-border-radius: 5px 5px 5px 5px;
 font-family: Verdana;
 width: auto;
 height: auto;
 font-size: 16px;
 padding: 10px 40px;
 box-shadow: inset 0 1px 0 0 #fff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2,0 2px 4px 0 #f2f2f2;
 -moz-box-shadow: inset 0 1px 0 0 #fff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2,0 2px 4px 0 #f2f2f2;
 -webkit-box-shadow: inset 0 1px 0 0 #fff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2,0 2px 4px 0 #f2f2f2;
 text-shadow: 0 1px 0 #fff;
 background-image: linear-gradient(to top, #f2f2f2, #f2f2f2);
 background-color: #f2f2f2;
 text-decoration: none;
}
.btn-class:hover, .btn-class:active{
 border:1px solid #8c8c8c;
 color: #8c8c8c;
 box-shadow: inset 0 1px 0 0 #ffffff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2;
 -moz-box-shadow: inset 0 1px 0 0 #ffffff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2;
 -webkit-box-shadow: inset 0 1px 0 0 #ffffff,inset 0 -1px 0 0 #d9d9d9,inset 0 0 0 1px #f2f2f2;
 background-color: #f2f2f2;
}

</style>

<tr>
	<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
					<h3><?php echo $titreMail;?></h3>
				</td>
			</tr>
			<tr>
				<td style="text-align: justify; padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
					<p><?php echo $message; ?></p>
				</td>
			</tr>
			<tr>
				<td style="text-align: justify;">
					<ul>
						<li>You can response directly to the author of this email. If you do not want to response, your email will remain confidential for the sender.</li><br>
						<li>If you have received this email in error, please notify the system manager <a href="mailto:contact@noolib.com">contact@noolib.com</a>. This message contains confidential information and is intended only for the individual named. If you are not the named addressee you should not disseminate, distribute or copy this e-mail.</li><br>
						<li>For further informations, please contact : <a href="mailto:contactteam@noolib.com">contact@noolib.com</a></li><br>
					</ul>
				</td>
			</tr>
		</table>
	</td>
</tr>