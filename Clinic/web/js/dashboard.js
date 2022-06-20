(function(){
    'use-strict';

    let isActiveMenu = false;
    $('#dashboard-hamburger').click(function() {
        if (isActiveMenu) {
            $('#dashboard-hamburger').removeClass('active');
            $('#dashboard-menu').removeClass('show');
        } else {
            $('#dashboard-hamburger').addClass('active');
            $('#dashboard-menu').addClass('show');
        }

        isActiveMenu = !isActiveMenu;
    });

})(jQuery);