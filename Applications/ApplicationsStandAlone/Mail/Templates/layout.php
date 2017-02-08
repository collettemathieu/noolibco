<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Layout d'un email issu et adapté depuis CampaignMonitor.co			  |
// +----------------------------------------------------------------------+
// | Auteur : Steve Despres <despressteve@noolib.com	    			  |
// +----------------------------------------------------------------------+

 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=320, target-densitydpi=device-dpi">
<style type="text/css">
/* Mobile-specific Styles */
@media only screen and (max-width: 660px) { 
table[class=w0], td[class=w0] { width: 0 !important; }
table[class=w10], td[class=w10], img[class=w10] { width:10px !important; }
table[class=w15], td[class=w15], img[class=w15] { width:5px !important; }
table[class=w30], td[class=w30], img[class=w30] { width:10px !important; }
table[class=w60], td[class=w60], img[class=w60] { width:10px !important; }
table[class=w125], td[class=w125], img[class=w125] { width:80px !important; }
table[class=w130], td[class=w130], img[class=w130] { width:55px !important; }
table[class=w140], td[class=w140], img[class=w140] { width:90px !important; }
table[class=w160], td[class=w160], img[class=w160] { width:180px !important; }
table[class=w170], td[class=w170], img[class=w170] { width:100px !important; }
table[class=w180], td[class=w180], img[class=w180] { width:80px !important; }
table[class=w195], td[class=w195], img[class=w195] { width:80px !important; }
table[class=w220], td[class=w220], img[class=w220] { width:80px !important; }
table[class=w240], td[class=w240], img[class=w240] { width:180px !important; }
table[class=w255], td[class=w255], img[class=w255] { width:185px !important; }
table[class=w275], td[class=w275], img[class=w275] { width:135px !important; }
table[class=w280], td[class=w280], img[class=w280] { width:135px !important; }
table[class=w300], td[class=w300], img[class=w300] { width:140px !important; }
table[class=w325], td[class=w325], img[class=w325] { width:95px !important; }
table[class=w360], td[class=w360], img[class=w360] { width:140px !important; }
table[class=w410], td[class=w410], img[class=w410] { width:180px !important; }
table[class=w470], td[class=w470], img[class=w470] { width:200px !important; }
table[class=w580], td[class=w580], img[class=w580] { width:280px !important; }
table[class=w640], td[class=w640], img[class=w640] { width:300px !important; }
table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] { display:none !important; }
table[class=h0], td[class=h0] { height: 0 !important; }
p[class=footer-content-left] { text-align: center !important; }
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 } 
 
/* Client-specific Styles */

/* Force Outlook to provide a "view in browser" button. */
#outlook a { padding: 0; }	

body {
	width: 100% !important; 
	min-width: 100%;
}

.ReadMsgBody { 
	width: 100%; 
}

/* Force Hotmail to display emails at full width */
.ExternalClass { 
	width: 100%; 
	display:block !important;
} 

/* Force min width on Gmail web*/
.gmail {
	margin: 0 auto;
	width: 670px; 
	min-width: 670px; 
	border-spacing: 0;
}
 /* Reset Styles */
.gmail td {
	font-size: 0; 
	line-height: 0; 
	padding: 0;

}

/* Add 100px so mobile switch bar doesn't cover street address. */
body { 
	background-color: #363540; 
	margin: 0; 
	padding: 0; 
}

img { 	
	outline: none; 
	text-decoration: none; 
	display: block;
}

br, strong br, b br, em br, i br { 
	line-height:100%;
}

h1, h2, h3, h4, h5, h6 {
	line-height: 100% !important;
	-webkit-font-smoothing: antialiased;
}

h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
	color: blue !important; 
}

h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
	color: red !important; 
}

/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { 
	color: purple !important; 
}

/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
table td, table tr { 
	border-collapse: collapse; 
}
/* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
	color: black; 
	text-decoration: none !important; 
	border-bottom: none !important; 
	background: none !important;
}	

/* This most probably won't work in all email clients. Don't include code blocks in email. */
code {
  white-space: normal;
  word-break: break-all;
}

#background-table { 
	background-color: #363540; 
}

/* Webkit Elements */
#top-bar {
	border-radius:6px 6px 0px 0px; 
	-moz-border-radius: 6px 6px 0px 0px; 
	-webkit-border-radius:6px 6px 0px 0px;
	-webkit-font-smoothing: antialiased;
	background-color: #363540;
	color: #7f8c4f; 
}

#top-bar a { 
	font-weight: bold; 
	color: #7f8c4f; 
	text-decoration: none;
}

#footer { 
	border-radius:0px 0px 6px 6px;
	-moz-border-radius: 0px 0px 6px 6px;
	-webkit-border-radius:0px 0px 6px 6px; 
	-webkit-font-smoothing: antialiased; 
}

/* Fonts and Content */
body, td { 
	font-family: HelveticaNeue, sans-serif; 
}

.header-content, .footer-content-left, .footer-content-right {
	-webkit-text-size-adjust: none; -ms-text-size-adjust: none; 
}

/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
.header-content { 
	font-size: 12px; 
	color: #ffffff; 
}

.header-content a {
	font-weight: bold;
	color: #7f8c4f;
	text-decoration: none; 
}

#headline p {
	color: #7f8c4f; 
	font-family: HelveticaNeue, sans-serif; 
	font-size: 36px; 
	text-align: center;
	margin-top:0px;
	margin-bottom:30px; 
}

#headline p a {
	color: #7f8c4f; 
	text-decoration: none; 
}

.article-title {
	font-size: 18px; 
	line-height:24px; 
	color: #526f92; 
	font-weight:bold; 
	margin-top:0px; 
	margin-bottom:18px; 
	font-family: HelveticaNeue, sans-serif; 
}

.article-title a { 
	color: #526f92; 
	text-decoration: none; 
}

.article-title.with-meta {
	margin-bottom: 0;
}

.article-meta { 
font-size: 13px;
 line-height: 20px; 
 color: #ccc; 
 font-weight: bold;
 margin-top: 0;
 }
 
.article-content { 
	font-size: 13px; 
	line-height: 18px; 
	color: #444444; 
	margin-top: 0px; 
	margin-bottom: 18px; 
	font-family: HelveticaNeue, sans-serif; 
}

.article-content a { 
	color: #ffffff; 
	font-weight:bold; 
	text-decoration:none; 
}

.article-content a:link { 
	text-decoration:none; 
}

.article-content img {
	max-width: 100% 
}

.article-content ol, .article-content ul {
	margin-top:0px; 
	margin-bottom:18px; 
	margin-left:19px; 
	padding:0; 
}

.article-content li {
	font-size: 13px;
	line-height: 18px;
	color: #444444;
}

.article-content li a { 
	color: #7f8c4f; 
	text-decoration:none; 
}

.article-content p {
	margin-bottom: 15px;
}

.footer-content-left { 
	font-size: 12px; 
	line-height: 15px; 
	color: #f2dfa7; 
	margin-top: 0px; 
	margin-bottom: 15px; 
}

.footer-content-left a {
	color: #f2e9a8; 
	font-weight: bold; 
	text-decoration: none; 
}

.footer-content-right {
	 font-size: 11px; 
	 line-height: 16px; 
	 color: #f2dfa7; 
	 margin-top: 0px;
	 margin-bottom: 15px;
}

.footer-content-right a {
	color: #f2e9a8; 
	font-weight: bold; 
	text-decoration: none; 
}

#footer { 
	background-color: #4e4c59;
	color: #f2dfa7; 
}

#footer a {
	color: #f2e9a8;
	text-decoration: none; 
	font-weight: bold; 
}

#permission-reminder {
	white-space: normal; 
}

#street-address {
	color: #f2e9a8;
	white-space: normal; 
}

.myButton {
	  background: #3498db;
	  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
	  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
	  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
	  background-image: -o-linear-gradient(top, #3498db, #2980b9);
	  background-image: linear-gradient(to bottom, #3498db, #2980b9);
	  -webkit-border-radius: 28;
	  -moz-border-radius: 28;
	  border-radius: 28px;
	  text-shadow: 3px 3px 4px #666666;
	  -webkit-box-shadow: 3px 4px 5px #666666;
	  -moz-box-shadow: 3px 4px 5px #666666;
	  box-shadow: 3px 4px 5px #666666;
	  font-family: Arial;
	  text: #ffffff;
	  font-size: 14px;
	  padding: 10px 20px 10px 20px;
	  text-decoration: none;
}
.myButton:hover {
	  background: #3cb0fd;
	  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
	  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
	  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
	  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
	  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
	  text-decoration: none;
}
.myButton:active {
	position:relative;
	top:1px;
}
</style>
</head><body><table align="center" class="gmail hide"><tbody><tr><td>&nbsp;</td></tr></tbody></table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table" style="table-layout:fixed" align="center">
	<tbody>
		<tr>
			<td align="center" bgcolor="#363540">
				<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
					<tbody><tr><td class="w640" width="640" height="20"></td></tr>
						<tr>
							<td class="w640" width="640">
								<table id="top-bar" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#b4bf8a">
									<tbody><tr>
												<td class="w15" width="15"></td>
												<td class="w325" width="350" valign="middle" align="left">
													<table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
														<tbody><tr><td class="w325" width="350" height="8"></td></tr>
														</tbody>
													</table>
													<table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
														<tbody><tr><td class="w325" width="350" height="8"></td></tr>
														</tbody>
													</table>
												</td>
												<td class="w30" width="30"></td>
												<td class="w255" width="255" valign="middle" align="right">
													<table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
														<tbody><tr><td class="w255" width="255" height="8"></td></tr>
														</tbody>
													</table>
													<table cellpadding="0" cellspacing="0" border="0">
														<tbody>
															<tr>
																<td valign="middle"><fblike><img src="https://img.createsend1.com/img/templatebuilder/like-glyph.png" border="0" width="8" height="14" alt="Facebook icon"=""></fblike></td>
																<td width="3"></td>
																<td valign="middle"><div class="header-content"><fblike>J'aime</fblike></div></td>
																<td class="w10" width="10"></td>
																<td valign="middle"><tweet><img src="https://img.createsend1.com/img/templatebuilder/tweet-glyph.png" border="0" width="17" height="13" alt="Twitter icon"=""></tweet></td>
																<td width="3"></td>
																<td valign="middle"><div class="header-content"><tweet>Tweet</tweet></div></td>
															</tr>
														</tbody>
													</table>
													<table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
														<tbody><tr><td class="w255" width="255" height="8"></td></tr>
														</tbody>
													</table>
												</td>
												<td class="w15" width="15"></td>
											</tr>
									</tbody>
								</table>
								
							</td>
						</tr>
					<tr>
						<td id="header" class="w640" width="640" align="center" bgcolor="#ffffff">
							<div align="center" style="text-align: center">
								<a href="https://www.noolib.com">
								<img id="customHeaderImage" label="Header Image" editable="true" width="160" src="https://noolib.com/Images/Logos/logoNooLib.png" class="w160" border="0" align="left" style="display: inline" >
								</a>
							</div>
						</td>
					</tr>
					<tr><td class="w640" width="640" height="30" bgcolor="#ffffff"></td></tr>
					<tr id="simple-content-row">
						<td class="w640" width="640" bgcolor="#ffffff">
							<table align="left" class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
								<tbody>
									<tr>
										<td class="w30" width="30"></td>
										<td class="w580" width="580">
											<repeater> 
												<?php echo $content; ?>
											</repeater>
										</td>
										<td class="w30" width="30"></td>
									</tr>
								</tbody>
							</table>	
						</td>
					</tr>
					<tr>
						<td class="w640" width="640" height="15" bgcolor="#ffffff"></td>
					</tr>
					<tr>
						<td class="w640" width="640">
							<table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#4e4c59">
								<tbody>
									<tr>
										<td class="w30" width="30"></td>
										<td class="w580 h0" width="360" height="30">
										</td><td class="w0" width="60">
										</td><td class="w0" width="160">
										</td><td class="w30" width="30">
										</td>
									</tr>
									<tr>
										<td class="w30" width="30"></td>
										<td class="w580" width="360" valign="top">
										<span class="hide">
											<p id="permission-reminder" align="left" class="footer-content-left">
												<span></span>
											</p>
										</span>
										<p align="left" class="footer-content-left"><preferences lang="fr-FR"><a href="https://www.noolib.com/LogIn/">Connect you to NooLib</a></preferences></p>
										</td>
										<td class="hide w0" width="60"></td>
										<td class="hide w0" width="160" valign="top">
											<p id="street-address" align="right" class="footer-content-right"></p>
										</td>
										<td class="w30" width="30"></td>
									</tr>
									<tr>
										<td class="w30" width="30"></td>
										<td class="w580 h0" width="360" height="15"></td>
										<td class="w0" width="60"></td>
										<td class="w0" width="160"></td>
										<td class="w30" width="30"></td>
									</tr>
								</tbody>
							</table>
						</td>
						</tr>
						<tr>
							<td class="w640" width="640" height="60"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table></body></html>
