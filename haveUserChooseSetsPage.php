<?php
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/listOfSetsPageStyle.css">
<title>QuizletToMP3</title>
<link rel="stylesheet" type="text/css" href="css/indexStyle.css">
<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

</head>
<body>

<?php include 'quizletApi.php';?>

<div id="header">
<img id="headerLogo" src="images/quizletIsolatedLogoWhite.png"></img>
</div>
<div id="spinner">
<style>



</style>

<div class="wrapper">

<div id="listPageContentContainer">

<div id="listContainer">
	<div id="listHeader"><p id="listHeaderText">Choose a set to study</p></div>
	<ul id="listSetsUl">
	

	</ul>
</div>

</div>
	<div class="push"></div>

</div>
<div class="footer">
	<div class="break" id="breakBottom"></div>
	Copyright 2018 Cameron Smith. All rights reserved.
</div>

<form method="get" action="playerPage.php" id="passDataOnToPlayerForm">
<input type="hidden" id="indexInput" name="indexClickedSmuggler" value="ye"/>
<input type="hidden"/>
</form>


</body>


	<script>
var arrayOfUserData = <?php echo json_encode($_SESSION['data']) ?>;
//prepateSetForSpeech.js will depend on this variable

</script>
<script src="jquery.js"></script>
<script src="spin.js"></script>
<script src="prepareSetForSpeech.js"></script>
</html>
