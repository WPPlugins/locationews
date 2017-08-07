(function ($) {
	"use strict";

    $("[type='checkbox'].locationews").bootstrapSwitch();

    $(function() {
        $.fn.invisible = function() {
            return this.each(function() {
                $(this).css("visibility", "hidden");
            });
        };
        $.fn.visible = function() {
            return this.each(function() {
                $(this).css("visibility", "visible");
            });
        };

    });

})( jQuery );
