<?php
session_start();
if(isset($_GET["code"])){
	$data = http_build_query(array(
	  "grant_type" => "authorization_code",
	  "code" => $_GET["code"],
	  "redirect_uri" => "https://melocure.fugamaru.com/php/callbackspotify.php"
	));

	$json = file_get_contents("./secrets.json");
	$secrets = json_decode($json, true);
	$header_ary = array($secrets["SpotifyAPIHeader"]);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_HTTPHEADER, $header_ary);
	curl_setopt($curl, CURLOPT_URL, "https://accounts.spotify.com/api/token");
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$res = json_decode(curl_exec($curl), true);
	curl_close($curl);

	$header_ary = array("Authorization: Bearer ".$res["access_token"]);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_HTTPHEADER, $header_ary);
	curl_setopt($curl, CURLOPT_URL, "https://api.spotify.com/v1/me");
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$res2 = json_decode(curl_exec($curl), true);
	curl_close($curl);

	echo $res2;

	if($res2["product"] == "premium"){
		$_SESSION["refresh_token"] = $res["refresh_token"];
	}
	
	http_response_code(301);
	header( "Location: https://melocure.fugamaru.com/");
}else{
	http_response_code(301);
	header( "Location: https://melocure.fugamaru.com/");
	//パスは適宜変更
}