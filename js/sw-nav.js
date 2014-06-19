$(document).ready(function() {
	var activePage = 0;
	var activeIndicator = 0;
	var lastEvent = 0;

	function cycleActive(selector, activeElement) {
		var elements = document.querySelectorAll(selector);
		elements[activeElement].className = elements[activeElement].className.replace(/active/g, '');
		activeElement = (activeElement + 1) % elements.length;
		elements[activeElement].className += " active";

		return activeElement;
	}

	function switchPage(e) {
		e.preventDefault();

		if (e.timeStamp - lastEvent < 100) {
			//dubbeltryck
			return false;
		}
		lastEvent = e.timeStamp;

		activePage = cycleActive(".transportMode", activePage);
		activeIndicator = cycleActive(".page-indicator li", activeIndicator);

		return false;
	}

	var handleMediaChange = function (mediaQueryList) {
	    if (mediaQueryList.matches) {
	        $("html").on('click', switchPage);
	    }
	    else {
	        $("html").off('click', switchPage);

	    }
	}

	var mql = window.matchMedia("(max-width: 220px)");
	mql.addListener(handleMediaChange);
	handleMediaChange(mql);

});