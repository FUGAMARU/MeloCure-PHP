<?php
header('Access-Control-Allow-Origin: https://melocure.fugamaru.com');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');

session_start();

function getNewToken(){
	$data = http_build_query(array(
	  "grant_type" => "refresh_token",
	  "refresh_token" => $_SESSION["refresh_token"]
	));

	$json = file_get_contents("./secrets.json");
	$secrets = json_decode($json, true);
	$header_ary = array($secrets["SpotifyAPIHeader"]);

	$curl = curl_init();

	curl_setopt($curl , CURLOPT_HTTPHEADER , $header_ary);
	curl_setopt($curl, CURLOPT_URL, "https://accounts.spotify.com/api/token");
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$res = json_decode(curl_exec($curl), true);
	return $res["access_token"];
}

if(isset($_SESSION["refresh_token"])){
	$out = array("state"=>true, "token"=>getNewToken());
}else{
	$out = array("state"=>false, "token"=>NULL);
}
echo json_encode($out);