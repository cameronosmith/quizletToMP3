<?php

// Your credentials
$myClientId = 'your client id';
$mySecret = 'your secret id';
$myUrl = 'your redirect url';

// URLs
$authorizeUrl = "https://quizlet.com/authorize?client_id={$myClientId}&response_type=code&scope=read%20write_set";
$tokenUrl = 'https://api.quizlet.com/oauth/token';

session_start();

// Helper function for errors
function displayError($error, $step) {
	echo '<h2>An error occurred in step '.$step.'</h2><p>Error: '.htmlspecialchars($error['error']).
		'<br />Description: '.(isset($error['error_description']) ? htmlspecialchars($error['error_description']) : 'n/a').'</p>';
}

// Step 1: Display dialog box on quizlet to ask the user to authorize my application
// =================================================================================
if (empty($_GET['code']) && empty($_GET['error'])) {
	$_SESSION['state'] = md5(mt_rand().microtime(true)); // CSRF protection
	$urlToAuthenticate = $authorizeUrl.'&state='.urlencode($_SESSION['state']).'&redirect_uri='.urlencode($myUrl);
	header("Location: $urlToAuthenticate");
//	echo '<a href="'.$authorizeUrl.'&state='.urlencode($_SESSION['state']).'&redirect_uri='.urlencode($myUrl).'">Step 1: Start Authorization</a>';
	exit();
}

// Check for issues from step 1:
if (!empty($_GET['error'])) { // An error occurred authorizing
	displayError($_GET, 1);
	exit();
}

if ($_GET['state'] !== $_SESSION['state']) {
	exit("We did not receive the expected state. Possible CSRF attack.");
}

// Step 2: Get the authorization token (via POST)
// ==============================================
if (!isset($_SESSION['access_token'])) {
	$payload = [
		'code' => $_GET['code'],
		'redirect_uri' => $myUrl,
		'grant_type' => 'authorization_code',
	];
	$curl = curl_init($tokenUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERPWD, "{$myClientId}:{$mySecret}");
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
	$token = json_decode(curl_exec($curl), true);
	$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($responseCode !== 200) { // An error occurred getting the token
		displayError($token, 2);
		exit();
	}

	$accessToken = $token['access_token'];
	$username = $token['user_id']; // the API sends back the username of the user in the access token

	// Store the token for later use (outside of this example, you might use a real database)
	// You must treat the "access token" like a password and store it securely
	$_SESSION['access_token'] = $accessToken;
	$_SESSION['username'] = $username;

}

// Step 3: Use the Quizlet API with the received token
// ===================================================
$curl = curl_init("https://api.quizlet.com/2.0/users/{$_SESSION['username']}/sets");
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$_SESSION['access_token']]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$_SESSION['data'] = json_decode(curl_exec($curl));
$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if (intval(floor($responseCode / 100)) !== 2) { // A non 200-level code is an error (our API typically responds with 200 and 204 on success)
	displayError((array) $data, 3);
	exit();
}

/*/ Display the user's sets
echo "<ol>";
foreach ($data as $set) {
	echo "<li>".htmlspecialchars($set->title)."</li>"; // Notice that we ensure HTML is displayed safely
}
echo "</ol>";*/
