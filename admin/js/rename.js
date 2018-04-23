/**
*	Javascript for setting an explanation of what MetaData is when in the admin panel.
*
*/
window.onload = function() {
	alert ('this is a test!');
	// Change the Answer item to something more descriptive via javascript
		var answers = document.querySelector("#menu-posts-ed_answers > a > div.wp-menu-name");
		if (answers != null) {
			answers.innerHTML = "Questionnaire";
	 }
}
