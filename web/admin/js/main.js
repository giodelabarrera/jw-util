$(document).ready(function() {

    $('.btn-filter').on('click', function(e) {

        e.preventDefault();
        // $(e).toggleClass('active');
        // var $filterContainer = '#' + $(this).data('filter');
        // $($filterContainer).slideToggle();
        $('.filter-form').slideToggle();
    });

    jQuery('.js-datetimepicker').each(function() {

        var $input = $(this);

        $input.datetimepicker({
            format: $input.data('format') ? $input.data('format') : false,
            useCurrent: $input.data('use-current') ? $input.data('use-current') : false,
            locale: moment.locale('' + ($input.data('locale') ? $input.data('locale') : '') +''),
            showTodayButton: $input.data('show-today-button') ? $input.data('show-today-button') : false,
            showClear: $input.data('show-clear') ? $input.data('show-clear') : false,
            showClose: $input.data('show-close') ? $input.data('show-close') : false,
            sideBySide: $input.data('side-by-side') ? $input.data('side-by-side') : false,
            inline: $input.data('inline') ? $input.data('inline') : false,
            allowInputToggle: $input.data('allow-input-toggle') ? $input.data('allow-input-toggle') : true,
            icons: {
                //time: 'si si-clock',
                //date: 'si si-calendar',
                //up: 'si si-arrow-up',
                //down: 'si si-arrow-down',
                //previous: 'si si-arrow-left',
                //next: 'si si-arrow-right',
                //today: 'si si-size-actual',
                //clear: 'si si-trash',
                //close: 'si si-close'
            }
        });

        $input.on('dp.change', function() {
            $input.find('input').trigger('change');
        });
    });

    $('.js-colorpicker').colorpicker({
        format: 'hex',
        inline: false
    });

    $('.select2').each(function() {

        var $input = $(this);

        if (!$input.hasClass("select2-hidden-accessible")) {
            $input.select2();
        }
    });

    $('a.img-link').magnificPopup({type:'image'});
});