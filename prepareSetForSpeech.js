
//handle document.ready functions first
$(document).ready(function(){
	//get which list index user clicks
	$("li").click(function(){
		var setIndexClicked = $(this).index();
		var dataset = arrayOfUserData[setIndexClicked];
		//send this set's data to php
		sendDatasetStringToPhp( dataset );
	});
});
//display the sets for the user to choose
for(var i=0; i < arrayOfUserData.length; i++){
	var setName = arrayOfUserData[i].title;
	var setHtml = "<li id='setItem'><p id='setItemText'>"+setName+"</p></li>";
	$("#listSetsUl").append(setHtml);
}


//just for testing purposes we will say it is the first data set

//function to convert a data set's flashcards to a string. we will then take this string and 
//convert that string to speech
function convertDatasetToString( dataset ){
	var datasetString = "this a reading of your set " + dataset.title + ". ";
	//iterate through termms and add each term and definition to file
	for(var i=0; i < dataset.terms.length; i++){
		var term = dataset.terms[i].term;
		var definition = dataset.terms[i].definition;
		datasetString+= "term. " + term + ". definition. " + definition + ". ";
	}

	return datasetString;
}

//function to send this dataset string to php to convert to audio
function sendDatasetStringToPhp(dataset){
	var datasetStringToSend = convertDatasetToString(dataset);
    //load spinner while ajax request processing
    loadingSpinner();
	$.ajax({
		type: 'POST',
		url: 'convertSetToAudio.php',
		data: { datasetString : datasetStringToSend,
                username : dataset.created_by
		},
		success: function(response){
			//go to player page
			window.location = "http://acsweb.ucsd.edu/~cos008/qmp3/playerPage.php?unique="+response;
		}
	});
}

//function to add loading spinner to page
function loadingSpinner(){
    //spinner options
    var opts = {
          lines: 13 // The number of lines to draw
        , length: 28 // The length of each line
        , width: 14 // The line thickness
        , radius: 42 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#000' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '43%' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'absolute' // Element positioning
    }
    //load spinner
    var htmlSpinner = document.getElementById("spinner");
    var spinner = new Spinner(opts).spin(htmlSpinner);
}
