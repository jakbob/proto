<?php
	header('Content-Type: application/json; charset=utf-8');

	require('simpleCache.php'); 
	$cache = new SimpleCache();
	$cache->cache_time = 30;

	if ($_GET ['api'] == "platsuppslag") {
		$url = "http://api.sl.se/api2/typeahead.json?key=701a00aed8e3482dab74149d93d7788a&stationsonly=true&maxresults=1&searchstring=".urlencode($_GET['q']);

		echo  $cache->get_data($_GET['api'].'_'.$_GET['q'], $url);
	} else if ($_GET ['api'] == "realtidsinformation") {
		$url = "api.sl.se/api2/realtimedepartures.json?key=f2269025452a4fdebe1d0a119e006c8a&timewindow=15&siteid=".urlencode($_GET['q']);
		
		echo  $cache->get_data($_GET['api'].'_'.$_GET['q'], $url);
	}

?>