<?php
header('Access-Control-Allow-Origin: https://melocure.fugamaru.com');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');

$json = file_get_contents("./secrets.json");
$secrets = json_decode($json, true);
$API_KEY = $secrets["YouTubeAPIKEY"];

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?id=".$_GET["id"]."&part=snippet,contentDetails,statistics,status&key=".$API_KEY);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$res = json_decode(curl_exec($curl), true);
curl_close($curl);
$send = array("title" => $res["items"][0]["snippet"]["title"], "duration" => $res["items"][0]["contentDetails"]["duration"]);
echo json_encode($send);