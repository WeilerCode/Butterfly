$(function () {

    'use strict';

    // 是否移动端
    var nav = navigator.userAgent;
    if (!!nav.match(/AppleWebKit.*Mobile.*/))
    {

    } else {
        // tooltip
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'bottom'
        });
    }


    // toastr
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "1200",
        "isReload": true
    }
});