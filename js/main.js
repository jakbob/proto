$(document).ready(function() {
	var closestStation;

	function searchDepartures(stationName) {
		$("#results").html('<i class="fa fa-spinner fa-spin fa-4x"></i>')
		$.getJSON("trafiklab.php?api=platsuppslag&q="+stationName, function(data) {
			var siteId = data["ResponseData"][0]["SiteId"];

			$("h1").html(data["ResponseData"][0]["Name"]);

			$.getJSON("trafiklab.php?api=realtidsinformation&q="+siteId, function(data) {
				var transports = $(Mustache.render($("#main-template").html(), data, {
						departure: $("#departure-template").html()
				})).filter(".transportMode");

				console.log(transports);

				$('.page-indicator').empty();
				transports.each(function() {
					$('.page-indicator').append('<li>');
				})
				transports.first().addClass("active");
				$('.page-indicator li').first().addClass('active');

				$("#results").html(transports);
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
			if (location.hash) {
				searchDepartures(location.hash.replace("#", ""));
			} else {
				searchDepartures(closestStation);
			}
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