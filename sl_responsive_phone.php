<!DOCTYPE html>
<html>
<head>
	<title>SL koll</title>
	<meta name="viewport" content="width=device-width">
	<meta charset="utf-8" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.min.js"></script>

	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script>

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

	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			var closestStation;

			function searchDepartures(stationName) {
				$("#results").html('<i class="fa fa-spinner fa-spin fa-4x"></i>')
				$.getJSON("trafiklab.php?api=platsuppslag&q="+stationName, function(data) {
					var siteId = data["ResponseData"][0]["SiteId"];

					$("h1").html("Avgångar från " + data["ResponseData"][0]["Name"]);

					$.getJSON("trafiklab.php?api=realtidsinformation&q="+siteId, function(data) {
						console.log(data);
						$("#results").html(Mustache.render($("#main-template").html(), data, {
								departure: $("#departure-template").html()
						}));
					});
				});
			}

			function getPosition(pos) {
				service = new google.maps.places.PlacesService(document.createElement('div'));
				service.nearbySearch({
					location: new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude),
					rankBy: google.maps.places.RankBy.DISTANCE,
					types: ['bus_station']

				}, function(results, status) {
					closestStation = results[0]["name"];
					$("#closest").html(closestStation);
					searchDepartures(closestStation);
				});
			}
			navigator.geolocation.getCurrentPosition(getPosition);

			$("input[type=radio][name=searchMethod]").change(function() {
				if ($(this).val() == 'input') {
					if ($("#searchStation").val() != "") {
						searchDepartures($("#searchStation").val());
					}
				} else {
					searchDepartures(closestStation);
				}
			});

			$("#searchStation").keypress(function() {
				$("#searchButton").fadeIn();
			})

			$("#searchSelect").submit(function(e) {
				e.preventDefault();

				if (!$("input#searchInput").prop('checked')) {
					$("input#searchInput").click();
				} else {
					$("input#searchInput").trigger('change');
				}

				$("#searchButton").fadeOut();
			});
		});
	</script>

	<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="bower_components/pure/pure-min.css" />	
	<link rel="stylesheet" type="text/css" href="bower_components/pure/grids-responsive-min.css" />

	<style>
		body { background: url("img/diagonal-noise.png");}
		.container { width: 80%; margin: 0 auto; }
		header {
			background: #65b7dd;
			padding: 1em 0;
			font-size: 120%;
		}

		form { }
		img { max-width: 100%;}

		.header-content { position: relative; top: -200px; }
		#searchSelect div:first-child { margin-bottom: 1em;}
		i.fa-spinner { width: 100%; text-align: center; }
		#results { padding-bottom: 1em; }
		.transportNumber, .transportTime { font-weight: bold;}
		.transportMode {
			box-sizing: border-box;
			padding: 1em;
		}
		.departure:nth-child(n+15) { display: none;}
		.align-right { text-align: right; }

		.transport-header { position: relative; margin-bottom: 0.5em; }
		.transport-header h2 {
			position: absolute;
			bottom: 0;
			left: 1em;
			background: rgba(255,255,255,0.6);
			padding: 0.2em;
		}


		@media(max-width: 50em) {
			.container { width: 90%; }
		}
		@media(max-width: 25em) {
			.container { width: 95%; }
		}
		@media(max-width: 14em) {
			html, body { width: 100%; overflow: hidden; font-size: 8px; word-break: break-all; word-break: break-word;}
			h1 { font-size: 70%; margin: 0;}
			h2 {margin: 0.2em 0;}
			header { display: none; }
			.departure { margin-bottom: 0.5em; }
		}
	</style>

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
	<h1></h1>
	<div id="results" class="pure-g">
		<i class="fa fa-spinner fa-spin fa-4x"></i>
	</div>
</div>
</body>
</html>