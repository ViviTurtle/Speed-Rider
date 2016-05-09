/*Locked Header Function after scrolling down*/


(function ( $ ) {
    $.fn.lockedheader = function (options) {

        // Override defaults with specified options
        options = $.extend({}, $.fn.lockedheader.options, options);

        // store the object
        var elem = $(this);


        // showScrollPoint option
        if (options.showScrollPoint) {
            console.log(options.scrollPoint);
            $('body').append('<div id="lockedheader-scroll-point" style="height: 0px; width: 100%; border-bottom: 2px solid red; position: absolute; z-index: 99999; left: 0; top: ' + options.scrollPoint + 'px;"></div>');
        }


        // document.scroll() event
        $(document).on('ready scroll', function () {

            // if scrolled past the scroll point
            if ($(document).scrollTop() > options.scrollPoint) {

                // if the object does not already have .is-stuck
                if (!elem.hasClass('is-stuck')) {

                    // assign the class
                    elem.addClass('is-stuck');

                    // if parentOffset is turned on, apply the padding fix
                    if (options.parentOffset) {
                        elem.parent().css('padding-top', elem.outerHeight(true));
                    }
                }
            }

            // if not scrolled past the scroll point
            else {

                // if the object still has .is-stuck
                if (elem.hasClass('is-stuck')) {

                    // remove the class and padding fix
                    elem.removeClass('is-stuck');

                    // if parentOffset is turned on, remove the padding fix
                    if (options.parentOffset) {
                        elem.parent().removeAttr('style');
                    }
                }
            }
        });
    };


    // Default the defaults
    $.fn.lockedheader.options = {
        scrollPoint: 10,
        showScrollPoint: false,
        parentOffset: false
    };
})(jQuery);


/*Login Box Function*/



$(document).ready(function(){

    
    $('#login-content').hide();

    $('#login-trigger').click(function(){

        $(this).next('#login-content').slideToggle();
        $(this).toggleClass('active');

        if ($(this).hasClass('active')) $(this).find('span').html('&#x25BC;')
        else $(this).find('span').html('&#x25B2;')
    })
    
    
});









