<?php
header('content-type: application/json');
//Sharrre by Julien Hany
$json = array('url'=>'','count'=>0);
$json['url'] = $_GET['url'];
$url = urlencode($_GET['url']);

if(filter_var($_GET['url'], FILTER_VALIDATE_URL)){
		$contents = parse('https://plusone.google.com/u/0/_/+1/fastbutton?url=' . $url . '&amp;count=true');

		preg_match( '/window\.__SSR = {c: ([\d]+)/', $contents, $matches );

		if(isset($matches[0])){
			$json['count'] = (int)str_replace('window.__SSR = {c: ', '', $matches[0]);
		}
}
echo str_replace('\\/','/',json_encode($json));

function parse($encUrl){
	$options = array(
		CURLOPT_RETURNTRANSFER => true, // return web page
		CURLOPT_HEADER => false, // don't return headers
		CURLOPT_FOLLOWLOCATION => true, // follow redirects
		CURLOPT_ENCODING => "", // handle all encodings
		CURLOPT_USERAGENT => 'extra', // who am i
		CURLOPT_AUTOREFERER => true, // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
		CURLOPT_TIMEOUT => 10, // timeout on response
		CURLOPT_MAXREDIRS => 3, // stop after 10 redirects
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => false,
	);
	$ch = curl_init();

	$options[CURLOPT_URL] = $encUrl;
	curl_setopt_array($ch, $options);

	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);

	curl_close($ch);

	if ($errmsg != '' || $err != '') {
		/*print_r($errmsg);
		  print_r($errmsg);*/
	}
	return $content;
}