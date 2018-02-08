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

    // ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // toastr
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "1200",
        "isReload": true
    }

    $('.delete-table').on('click', function(){

        var dataUrl = $(this).data('url');
        bootbox.dialog({
            message: $(this).data('tips'),
            title: '删除',
            className: 'modal-danger modal-center',
            buttons: {
                danger: {
                    label: '点错了',
                    className: 'btn-default'
                },
                success: {
                    label: '狠心删除',
                    className: 'btn-outline',
                    callback: function() {
                        window.location = dataUrl;
                    }
                }
            }
        });
    });

    // back top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#back-top').addClass('show animated pulse');
        } else {
            $('#back-top').removeClass('show animated pulse');
        }
    });
    // scroll body to 0px on click
    $('#back-top').click(function () {
        // Add sound

        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
});