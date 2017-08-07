<div class="ln-tooltip" title="<?php echo esc_attr( $atts['description'] ); ?>">
    <label for="<?php echo esc_attr( $atts['id'] ); ?>"></label>
    <select id="<?php echo esc_attr( $atts['id'] ); ?>" name="<?php echo esc_attr( $atts['name'] ); ?>" class="form-control">
        <?php foreach ( $atts['fields'] as $key => $field ): ?>
        <option value="<?php echo $field['value']; ?>"<?php if ( $field['value'] == $atts['value']) { echo ' selected="selected"'; } elseif ( empty( $atts['value'] ) && $field['value'] == '1' ) { echo ' selected="selected"'; } ?>><?php echo $field['name']; ?></option>
        <?php endforeach;  ?>
    </select>
</div>