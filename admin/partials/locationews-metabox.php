<div class="locationews-wp-plugin">
    <?php wp_nonce_field('save_locationews_meta', 'locationews-meta-box-nonce'); ?>
    <input type="hidden" name="locationews_Id" value="<?php echo esc_attr( $locationews_meta['id'] );?>" />
    <div class="row">
        <div class="col-md-4">
            <div class="form-group locationews-form-group">
                <span class="ln-tooltip" title="<?php _e('When Locationews is enabled, the article will be automatically added to Locationews when you press the Publish. If you would like to take published article out of Locationews, change to Off and update the article.', $this->plugin_name ); ?>">
                    <label for="locationews"><?php echo __('Locationews enabled', $this->plugin_name ); ?><br>
                        <input data-size="small" data-on-color="default" data-off-color="default" data-on-text="ON" data-off-text="OFF" aria-role="checkbox" class="locationews" <?php checked( 1, $locationews_meta['on'], true ); ?> id="locationews" name="locationews" type="checkbox" value="1" />
                    </label>
                </span>
            </div>
            <?php if ( isset( $this->options['ads'] ) ): ?>
            <div class="form-group locationews-form-group">
                <span class="ln-tooltip" title="<?php _e('Enable Ads', $this->plugin_name ); ?>">
                    <label for="locationews_ads"><?php echo __('Ads', $this->plugin_name ); ?><br>
                        <input data-size="small" data-on-color="default" data-off-color="default" data-on-text="ON" data-off-text="OFF" aria-role="checkbox" class="locationews" <?php checked( 1, $locationews_meta['ads'], true ); ?> id="locationews_ads" name="locationews_ads" type="checkbox" value="1" />
                    </label>
                </span>
            </div>
            <?php endif; ?>
            <?php if ( isset( $this->options['showMore'] ) ): ?>
            <div class="form-group locationews-form-group">
                <span class="ln-tooltip" title="<?php _e('Enable show more', $this->plugin_name ); ?>">
                    <label for="locationews_showmore"><?php echo __('Show more', $this->plugin_name ); ?><br>
                        <input data-size="small" data-on-color="default" data-off-color="default" data-on-text="ON" data-off-text="OFF" aria-role="checkbox" class="locationews" <?php checked( 1, $locationews_meta['showmore'], true ); ?> id="locationews_showmore" name="locationews_showmore" type="checkbox" value="1" />
                    </label>
                </span>
            </div>
            <?php endif; ?>
            <div class="form-group locationews-form-group clear">
                <span class="ln-tooltip" title="<?php _e('Select a Locationews category for the news. This function does not affect to the WordPress categories.', $this->plugin_name ); ?>">
                <label for="locationews_category"><?php echo __('Category', $this->plugin_name ); ?></label>
                    <select id="locationews_category" name="locationews_category" class="form-control">
                        <?php foreach ( $this->locationews_categories() as $key => $cat ) {
                            echo '<option value="' . $cat['value']. '"';
                            if ( $locationews_meta['category'] == $cat['value'] ) {
                                echo ' selected="selected"';
                            }
                            echo '> ' . $cat['name'] . '</option>';
                        }
                        ?>
                    </select>
                </span>
            </div>
            <div class="form-group locationews-form-group">
                <span class="ln-tooltip" title="<?php _e("Write here your article's coordinates.", $this->plugin_name ); ?>">
                    <label for="locationews-location"><?php echo __('Coordinates', $this->plugin_name ); ?></label>
                    <input type="text" id="locationews-location" name="locationews_coordinates" value="<?php echo ( isset( $locationews_meta['latlng'] ) ? $locationews_meta['latlng'] : '' ); ?>"  class="form-control gllpLatitudeLongitude text widefat" />
                </span>
            </div>
            <?php if ( isset( $this->options['controlPanelUrl'] ) ): ?>
            <div class="form-group locationews-form-group text-center">
                <a class="ln-tooltip" href="<?php echo $this->options['controlPanelUrl']; ?>" target="blank" title="<?php _e('Show control panel', $this->plugin_name ); ?>"><button type="button" class="btn btn-danger locationews"><?php echo __('Show control panel', $this->plugin_name ); ?></button></a>
            </div>
            <?php endif; ?>
            <?php if ( isset( $this->options['newsUrl'] ) && $locationews_meta['on'] == 1 && ! empty( $locationews_meta['id'] ) ): ?>
              <div class="form-group locationews-form-group">
                <label for="locationews_newsUrl"><?php echo __('View in Locationews Desctop Applcation', $this->plugin_name ); ?></label>
                <a class="ln-tooltip" href="<?php echo esc_url( $this->options['newsUrl'] . $locationews_meta['id'] ); ?>" title="<?php echo esc_attr( $this->options['newsUrl'] . $locationews_meta['id'] ); ?>" target="_blank"><?php echo $this->options['newsUrl'] . $locationews_meta['id']; ?></a>
              </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8 ln-tooltip" title="<?php _e('Add your news location on the map by dragging the marker or double clicking on the map. You can also search location by address.', $this->plugin_name ); ?>">
          <input id="locationews-pac-input" class="controls" type="text" placeholder="<?php _e('Search location', $this->plugin_name ); ?>">
          <div id="locationews-google-map" class="locationews-google-map"></div>
            <?php if ( isset( $this->options['debug'] ) ): ?>
            <div class="form-group locationews-form-group clear">
                <label for="">Debug</label>
                <?php if ( is_array( $locationews_meta ) ) {
                    echo "<pre>Locationews meta data\n";
                    echo "publicationId : " . $this->options['id'] . "\n";
                    echo "name : " . $this->options['name'] . "\n";
                    foreach ( $locationews_meta as $key => $value ) {
                        if ( ! empty( $value ) ) {
                            echo "$key : $value\n";
                        }
                    }
                    echo "</pre>";
                } ?>
            </div>
            <?php endif; ?>
            <?php if ( strpos( $this->options['apiUrl'], 'api_dev' ) !== false ) {
                echo "<p>Posting to Dev API: " . $this->options['apiUrl'];
                if ( $locationews_meta['id'] != '' ) {
	                echo '<br>View article: <a href="' . $this->options['newsUrl'] . $locationews_meta['id'] . '" target="_blank">' . $this->options['newsUrl'] . $locationews_meta['id'] . '</a>';
                }
                echo  "</p>";
            } ?>
        </div>
    </div>
</div>
