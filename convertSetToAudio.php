<?php
//we have to break the imported dataset string into several pieces since the speech synthesis website does not support very long texts
$arrayOfShortenedDatasetStrings = str_split( $_POST['datasetString'], 600) ;

//save the username as a session variable
$_SESSION['username'] = $_POST['username'];

//function to set up the url for a string to convert to audio
function getUrlToDownload( $str ){
	//encode string for url
	$encodedStr = rawurlencode( $str );
	$urlPreset="https://code.responsivevoice.org/getvoice.php?t=%27+" . $encodedStr . "+%27&tl=en-US&rate=.4";

	return $urlPreset;
}

//create a random string for the file name

function randStrGen($len){
	    $result = "";
	        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	        $charArray = str_split($chars);
		    for($i = 0; $i < $len; $i++){
			    $randItem = array_rand($charArray);
			    $result .= "".$charArray[$randItem];
				        }
	    return $result;
}
$randomString = randStrGen(10);

echo $randomString;

//function to download the audio fromurl
//@param  str : the string to convert to audio
function downloadAudioFromUrl( $url ){
	global $randomString;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	$output = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($status == 200) {
		file_put_contents('audio/'.$randomString.'.mp3', $output, FILE_APPEND);
	}
}
	//$url = getUrlToDownload( $arrayOfShortenedDatasetStrings[0] );

	//downloadAudioFromUrl( $url );

//download the audio
foreach( $arrayOfShortenedDatasetStrings as $str ){	
	$url = getUrlToDownload( $str );
	downloadAudioFromUrl( $url );	
}

//add their username to the username folder list
file_put_contents('usernames/'.$_POST['username'].'.mp3', $_POST['username'], FILE_APPEND);

