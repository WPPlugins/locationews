<div class="ln-tooltip" title="<?php esc_html_e( $atts['description'], 'locationews' ); ?>">
    <input id="locationews-pac-input" class="controls" type="text" placeholder="<?php _e('Search location', $this->plugin_name ); ?>">
    <div id="locationews-google-map" class="locationews-google-map <?php echo esc_attr( $atts['class' ]); ?>"></div>
    <input type="text" id="locationews-location" name="<?php echo esc_attr( $atts['name'] ); ?>" value="<?php echo esc_attr( $atts['value'] ); ?>"  placeholder="<?php echo esc_attr( isset( $atts['placeholders']['gllpLatitudeLongitude'] ) ? $atts['placeholders']['gllpLatitudeLongitude'] : '' ); ?>" class="form-control gllpLatitudeLongitude text widefat" />
</div>
