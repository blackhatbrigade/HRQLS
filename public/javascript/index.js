function initAutocomplete() {

    var heatMapData = [];


    var styleArray = [
        {
            "featureType": "road.arterial",
            "stylers": [
                { "hue": "#00ccff" }
            ]
        },
        {
            "stylers": [
                { "visibility": "simplified" },
                { "hue": "#0099ff" },
                { "weight": 0.7 }
            ]
        },
        {
            "featureType": "poi.school",
            "stylers": [
                { "hue": "#cc00ff" }
            ]
        },
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 36.9487874, lng: -76.2121092},
        zoom: 11,
        styles: styleArray,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    //console.log(heatMapData);
    var heatmap = new google.maps.visualization.HeatmapLayer({
        data: heatMapData
    });
    heatmap.setMap(map);

    $('#crimeSlider,#colSlider,#foodSlider').change(function(){
        heatMapData = [];
        var data = {};
        data.sliderValue = $(this).text();
        $.ajax({
            method: 'GET',
            url: '/crime',
            data: data,
            dataType: 'json',
            success: function(crimeData) {
                for (var elem = 0, max = crimeData.length; elem < max; elem++) {
                    heatMapData.push({location: new google.maps.LatLng(crimeData[elem].latitude, crimeData[elem].longitude), weight: crimeData[elem].severity});
                }
                heatmap = new google.maps.visualization.HeatmapLayer({
                    data: heatMapData,
                    maxIntensity: 12,
                    dissipate: true
                });
                heatmap.setMap(map);
            }
        });
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });


    var markers = [];
    // [START region_getplaces]
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    // [END region_getplaces]

}