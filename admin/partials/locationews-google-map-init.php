<script type="text/javascript">
    (function( $ ) {
        'use strict';
        var locationews_marker;
        function initLocationews() {
            <?php
            $lat = 60.261617082844595;

            $lng = 24.071044921875;

            if ( isset( $locationews_meta ) && ! empty( $locationews_meta['latlng'] ) ) {

                list( $lat, $lng ) = explode(',', $locationews_meta['latlng'] );

            } elseif ( isset( $this->user_options['location'] ) ) {

	              list( $lat, $lng ) = explode(',', $this->user_options['location'] );

            } elseif ( isset( $this->front_options['location'] ) ) {

                list( $lat, $lng ) = explode(',', $this->options['location'] );

            }
            ?>
            var locationews_latitude  =  <?php echo $lat; ?>;
            var locationews_longitude = <?php echo $lng; ?>;
            var locationews_location  = new google.maps.LatLng( locationews_latitude, locationews_longitude );
            var locationews_map_options   = {
                zoom: <?php echo isset( $this->front_options['gZoom'] ) ? (int) $this->front_options['gZoom'] : 9; ?>,
                center: locationews_location,
                disableDoubleClickZoom: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                zoomControl: true
            };
            $('#locationews-location').val( locationews_latitude + ',' + locationews_longitude );
            var locationews_map = new google.maps.Map( document.getElementById('locationews-google-map'), locationews_map_options );
            placeLocationewsMarker( locationews_location, '', locationews_map );
            locationews_map.addListener('dblclick', function( event ) {
                placeLocationewsMarker( event.latLng, '', locationews_map );
            });
        }
        function placeLocationewsMarker( location, title, map ) {
            if ( locationews_marker ) {
                locationews_marker.setPosition( location );
            } else {
                locationews_marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: title,
                    draggable: true,
                    icon: '<?php echo isset( $this->front_options['gIcon'] ) ? $this->front_options['gIcon'] : plugin_dir_url( dirname( __FILE__ ) ) . 'img/locationewsmerkkinormaali.png'; ?>'
                });
            }
            $('#locationews-location').val( location.lat() + ',' + location.lng() );
            locationews_marker.addListener('drag', function( event ) {
                $('#locationews-location').val( event.latLng.lat() + ',' + event.latLng.lng() );
            });
            locationews_marker.addListener('dragend', function( event ) {
                $('#locationews-location').val( event.latLng.lat() + ',' + event.latLng.lng() );
            });
        }
        initLocationews();
    })( jQuery );
</script>