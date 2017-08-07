<div class="ln-tooltip" title="<?php echo esc_attr( $atts['description'] ); ?>">
    <?php
    $all = false;
    foreach ( $atts['fields'] as $key => $field ):
        if ( $atts['id'] == 'defaultCategories'): ?>
            <?php
            if ( in_array( 'all', array_keys( $this->user_options['defaultCategories'] ) ) ) {
                $all = true;
            }
            if ($all === true) {
                $this->user_options[ $atts['id'] ][ $field['value'] ] = 1;
            } ?>
        <?php endif; ?>
    <label for="<?php echo esc_attr( $field['id'] ); ?>">
        <input data-size="small" data-label-text="<?php echo esc_html_e( $field['description'] ); ?>" data-on-color="default" data-off-color="default" data-on-text="ON" data-off-text="OFF" aria-role="checkbox" class="locationews locationews-<?php echo $atts['id']; ?>" <?php if ( isset( $this->user_options[ $atts['id'] ][$field['value']] ) ? checked( 1, $this->user_options[ $atts['id'] ][$field['value']], true ) : '' ); ?> id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" type="checkbox" value="1" />
    </label>
    <?php endforeach; ?>
</div>