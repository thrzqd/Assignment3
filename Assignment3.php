<?php
function curl_beforeAuth($headr, $rest_url){
$result = array();
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $rest_url);
	curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_POST,true);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
	curl_setopt($curl, CURLOPT_POSTFIELDS,'grant_type=client_credentials');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($curl);
	curl_close($curl);
	$res = json_decode($result);
return $res;

}



function curl_afterAuth($header, $url){
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
curl_setopt($curl, CURLOPT_FAILONERROR, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

$result=curl_exec($curl);
$res = json_decode($result);
curl_close($curl);
return $result;

}


$rest_url = 'https://ops.epo.org/3.1/auth/accesstoken';
$headr = array();
//From EPO documentation
$headr[] = 'Content-type: application/x-www-form-urlencoded';
$headr[] = 'Authorization: Basic R216Um1IdnVUSjdyWm9CM3I1YXREWUtSV3A3UWN3Y2Q6UWdYY3psU3pBUER6ZFc0cQ==';

$test = array();
$test = curl_beforeAuth($headr, $rest_url);
//Grab the access token and then get the search data
$token = $test->access_token;
	if(!$titleSearch){
		if($fname && $lname){
		$url = "http://ops.epo.org/3.1/rest-services/published-data/search/biblio/?q=ia%20all%20%22$fname%20$lname,%22&Range=$rangeStart-$rangeEnd";
		}
		else if($lname){
		$url = "http://ops.epo.org/3.1/rest-services/published-data/search/biblio/?q=ia%20all%20%22$lname%22&Range=$rangeStart-$rangeEnd";
		}
		else if($fname){
			$url="http://ops.epo.org/3.1/rest-services/published-data/search/biblio/?q=ia%20all%20%22$fname%22&Range=$rangeStart-$rangeEnd";
		}
	}
	else{
		$url = "http://ops.epo.org/3.1/rest-services/published-data/search/biblio/?q=ti=%22".urlencode($titleSearch)."%22&Range=$rangeStart-$rangeEnd";
	}
	$header = array();
	$header[] = "Authorization: Bearer $token";
	$xmldata = curl_afterAuth($header, $url);
 
?>