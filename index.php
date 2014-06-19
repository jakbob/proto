<?php
	$experiment = 1;
	if (isset($_GET['e']))
		$experiment = $_GET['e'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width">
	<title></title>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.min.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="bower_components/pure/pure-min.css" />	
	<link rel="stylesheet" type="text/css" href="bower_components/pure/grids-responsive-min.css" />
	<link rel="stylesheet" href="css/main.css">
	<?php
		switch($experiment):
			case 1: ?> 
				<link rel="stylesheet" href="css/responsive-smartphone.css"> 
			<?php break;
			case 2: ?>
				<link rel="stylesheet" href="css/responsive-smartphone.css" media="(min-width: 15em)"> 
				<link rel="stylesheet" href="css/responsive-sw.css"> 
			<?php break;
			case 3:?>
				<link rel="stylesheet" href="css/responsive-smartphone.css" media="(min-width: 15em)"> 
				<link rel="stylesheet" href="css/responsive-sw.css"> 
				<link rel="stylesheet" href="css/responsive-sw-2.css"> 
			<?php break;
			case 4: ?>
				<link rel="stylesheet" href="css/responsive-smartphone.css" media="(min-width: 15em)"> 
				<link rel="stylesheet" href="css/responsive-sw.css"> 
				<link rel="stylesheet" href="css/responsive-sw-2.css"> 
				<link rel="stylesheet" href="css/sw-nav.css"> 
				<script type="text/javascript" src="js/sw-nav.js"></script>
			<?php break;
		endswitch;
	?>
</head>
<body>
	<header>
		<div class="container">
			<form id="searchSelect">
				<div>
					<input type="radio" name="searchMethod" value="input" id="searchInput" />
					<label for="searchInput">Sök hållplats</label> 
					<input type="text" id="searchStation" />
					<button type="submit" id="searchButton" style="display: none;">Sök</button>
				</div>
				<div><input type="radio" name="searchMethod" value="closest" id="searchClosest" checked /> <label for="searchClosest">Närmaste hållplats:</label> <span id="closest" /></div>
			</form>
		</div>

	</header>
	<div class="container">
		<p>Här kan du se närmaste avgång för SLs bussar, tunnelbana, pendeltåg och spårvagn. <span class="search-possible">Du kan söka efter en station eller använda den närmast dig.</span> Just nu visas avgångstider ifrån:</p>
		<h1></h1>
		<ul class="page-indicator"></ul>
		<div id="results" class="pure-g">
			<i class="fa fa-spinner fa-spin fa-4x"></i>
		</div>
	</div>

	<script id="main-template" type="x-tmpl-mustache">
		{{#ResponseData}}
			{{! Bussar }}

			{{# Buses.length}}
				<div id="buses" class="transportMode pure-u-1 pure-u-md-1-2 pure-u-lg-1-3 pure-u-xl-1-4">
					<div class="transport-header">
						<img src="img/buss.jpg" />
						<h2>Buss:</h2>
					</div>
					<div>
			{{/ Buses.length}}

			{{#Buses}}
				{{> departure}}
			{{/Buses}}

			{{# Buses.length}}
					</div>
				</div>
			{{/ Buses.length}}


			{{! Tunnelbana }}

			{{# Metros.length}}
				<div id="metros" class="transportMode pure-u-1 pure-u-md-1-2 pure-u-lg-1-3 pure-u-xl-1-4">
					<div class="transport-header">
						<img src="img/tunnelbana.jpg" />
						<h2>Tunnelbana:</h2>
					</div>
					<div>
			{{/ Metros.length}}

			{{#Metros}}
				{{> departure}}
			{{/Metros}}

			{{# Metros.length}}
					</div>
				</div>
			{{/ Metros.length}}


			{{! Spårvagnar }}

			{{# Trams.length}}
				<div id="trams" class="transportMode pure-u-1 pure-u-md-1-2 pure-u-lg-1-3 pure-u-xl-1-4">
					<div class="transport-header">
						<img src="img/sparvagn.jpg" />
						<h2>Spårvagn:</h2>
					</div>
					<div>
			{{/ Trams.length}}

			{{#Trams}}
				{{> departure}}
			{{/Trams}}

			{{# Trams.length}}
					</div>
				</div>
			{{/ Trams.length}}


			{{! Pendel }}

			{{# Trains.length}}
				<div id="trains" class="transportMode pure-u-1 pure-u-md-1-2 pure-u-lg-1-3 pure-u-xl-1-4">
					<div class="transport-header">
						<img src="img/pendel.jpg" />
						<h2>Pendeltåg:</h2>
					</div>
					<div>
			{{/ Trains.length}}

			{{#Trains}}
				{{> departure}}
			{{/Trains}}

			{{# Trains.length}}
					</div>
				</div>
			{{/ Trains.length}}
		{{/ResponseData}}
	</script>

	<script id="departure-template" type="x-tmpl-mustache">
		<div class="departure pure-g">
			<div class="pure-u-3-4">Linje <span class="transportNumber">{{LineNumber}}</span> mot {{Destination}}</div>
			<div class="pure-u-1-4 align-right"><span class="transportTime">{{DisplayTime}}</span></div>
		</div>
	</script>
</body>
</html>