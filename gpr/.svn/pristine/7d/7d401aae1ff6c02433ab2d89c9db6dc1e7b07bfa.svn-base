/**
 * ProgressBar for jQuery
 * @param  {Number} max, actuel
 * @param  {Number} $element progressBar DOM element
 */
function progressBar(actuel,max, $element) {
	var progressBarWidth = (actuel/max) * $element.width();
	$element.find('div').animate({ width: progressBarWidth }, 500).html( actuel + "Go/" + max +'Go' ); //+ "%&nbsp;");
}