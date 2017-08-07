<div class="locationews-wp-plugin">
    <div class="wrap">
        <div id="ln-block">
            <div id="ln-title">
                <h1>Locationews</h1>
                <p>
                    <?php _e('Locationews plugin publish your news to Locationews service. With these settings, you can specify the basic functions on the map selector which appear in the article edit view.', $this->plugin_name ); ?>
                </p>
                <?php if ( $this->options['jwt'] == 'plugintest' ): ?>
                <p>
                    <?php _e('Register your free account at <a href="https://locationews.com/en/" target="_blank">Locationews.com</a> and start publishing.', $this->plugin_name ); ?>
                </p>
                <?php endif; ?>
            </div>
            <div id="ln-logo">
                <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'img/locationewslogo.png'; ?>" alt="Locationews" id="locationewslogo" class="pull-x-right" />
            </div>
        </div>
    </div>
    <?php if ( isset( $showerror ) ): ?>
    <div class="error below-h2">
        <p><?php echo __('Missing required fields', $this->plugin_name ) . ':<br>' . implode('<br>', $field ) .'.'; ?></p>
    </div>
    <?php endif; ?>
    <form method="post" action="options.php">
        <?php
        settings_fields( $this->plugin_name . '_user' );
        do_settings_sections( $this->plugin_name );
        ?>
        <input type="submit" id="locationews-save-btn" class="locationews btn btn-danger" value="<?php echo __('Save Settings', 'locationews'); ?>">
        <?php if ( isset( $this->options['debug'] ) ): ?>
            <p>&nbsp;</p>
            <div class="form-group locationews-form-group clear">
                <label for="">Debug</label>
                <?php if ( is_array( $this->user_options ) ) {
                    echo "<pre>Locationews user options\n";
                    foreach ( $this->user_options as $key => $value ) {
                        if ( is_array( $value ) ) {
                            echo "$key : " . implode(', ', array_keys( $value ) ) . "\n";
                        }  else {
                            if (!empty($value)) {
                                echo "$key : $value\n";
                            }
                        }
                    }
                    echo "</pre>";
                } ?>
            </div>
        <?php endif; ?>
    </form>
</div>