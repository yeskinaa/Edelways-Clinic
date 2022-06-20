(function(){
    'use-strict';

    const hamburger = $('#hamburger');
    const header = $('#header');
    const nav = $('#nav');
    const navToggle = $('#nav-toggle');

    /*=============== SHOW&HIDE NAVIGATION ===============*/
    let isActiveMenu = false;
    navToggle.click(function() {
        if (isActiveMenu) {
            hamburger.removeClass('nav-toggle__hamburger--active');
            nav.removeClass('nav--active');
            $('body').removeClass('overflow-hidden');

            setTimeout(() => header.removeClass('header--active'), 250);
        } else {
            hamburger.addClass('nav-toggle__hamburger--active');
            header.addClass('header--active');
            nav.addClass('nav--active');
            $('body').addClass('overflow-hidden');
        }

        isActiveMenu = !isActiveMenu;
    });

    /*=============== MASKED INPUT HEIGHT ===============*/
    $("#contactform-phone").mask("+38 (099) 999-9999");
    $("#doctorinfo-working_time").mask("99:99-99:99");
    $("#events-date_time").mask("9999-99-99 99:99");
    $("#updateuserinfoform-birthday").mask("9999-99-99");
    $("#updateuserinfoform-phone").mask("+38 (099) 999-9999");

    /*=============== SELECT2 ===============*/
    $('#doctorinfo-user_id').select2({
        theme: 'bootstrap4',
        language: "uk"
    });

    $('#doctorinfo-speciality_id').select2({
        theme: 'bootstrap4',
        language: "uk"
    });

    /*=============== SELECT SPECIALITY ===============*/
    $('.booking-form #events-speciality').change(function() {
        let speciality = $('.booking-form #events-speciality').val();

        if (speciality != '') {
            $.ajax({
                type: 'POST',
                url: '/user/get-doctors',
                data: 'speciality=' + speciality,
                success: function(res){
                    $('.booking-form #events-doctor_id').html(res);
                },
            });

            $('.booking-form #events-doctor_id').removeAttr('disabled');
        } else {
            $('.booking-form #events-doctor_id').html('<option value="">Оберіть лікаря...</option>');
            $('.booking-form #events-doctor_id').attr('disabled', 'disabled');
            $('.booking-form #events-date_time').attr('disabled', 'disabled');
            $('.booking-form #events-date_time').val('');
            $('.booking-form #events-room').val('');
            $('.booking-form__alert').html('');
        }
    });


    /*=============== SELECT DOCTOR ===============*/
    $('.booking-form #events-doctor_id').change(function() {
        let doctor = $('.booking-form #events-doctor_id').val();

        if (doctor != '') {
            $.ajax({
                type: 'POST',
                url: '/user/get-doctor-room',
                data: 'doctor=' + doctor,
                success: function(res){
                    $('.booking-form #events-room').val(res);
                },
            });

            $.ajax({
                type: 'POST',
                url: '/user/get-date-time-info',
                data: 'doctor=' + doctor,
                success: function(res){
                    $('.booking-form__alert').html(res);
                },
            });

            $('.booking-form #events-date_time').removeAttr('disabled');
        } else {
            $('.booking-form #events-date_time').attr('disabled', 'disabled');
            $('.booking-form #events-date_time').val('');
            $('.booking-form #events-room').val('');
            $('.booking-form__alert').html('');
        }
    });

})(jQuery);