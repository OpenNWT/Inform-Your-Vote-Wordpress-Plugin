/**
*	Javascript for setting the Answers menu item into something more descriptive
* @since Election_Data 1.1
*/
window.onload = function() {
	// Change the Answer item to something more descriptive via javascript
		var answers = document.querySelector("#menu-posts-ed_answers > a > div.wp-menu-name");
		if (answers != null) {
			answers.innerHTML = "Questionnaire";
	 }
}
