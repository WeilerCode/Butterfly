$(function () {
    'use strict';

    function ButterflyCropper() {
        this.$cropperClick = $('.butterfly-cropper-click');
        this.$cropperCanvas = $('.butterfly-cropper-canvas');
        this.$cropperThumbCanvas = $('.butterfly-cropper-thumb-canvas');
        this.$cropperInputFile = $('.butterfly-cropper-file');
        this.$cropperInputData = $('.butterfly-cropper-data');
        this.$cropperEventClick = $('.butterfly-cropper-event');
        this.$cropperForm = $('.butterfly-cropper-form');
        this.$thisClick = null;
        this.butterflyCropper = null;
        this.init();
    }

    ButterflyCropper.prototype = {
        constructor: ButterflyCropper,

        config: {
            modal: '#butterfly-cropper-modal',
            click: false,
            canvasWidth: 645,
            canvasHeight: 375,
            canvasThumbWidth: 195,
            windowWidth: $(window).width(),
            aspectRatioWidth: $('#aspectRatioWidth').val(),
            aspectRatioHeight: $('#aspectRatioHeight').val(),
            imgUrl: ''
        },

        init: function () {
            this.changeCanvas();
            this.listener();
            // this.$cropperThumbCanvas.html('<img src="">');
        },

        listener: function () {
            this.$cropperClick.on('click', $.proxy(this.showModal, this));
            this.$cropperInputFile.on('change', $.proxy(this.initImg, this));
            this.$cropperForm.on('submit', $.proxy(this.uploadImg, this));
            $(window).on('resize', $.proxy(this.changeCanvas, this));
            this.removeModalPadding();
            this.modalListener();
            this.$cropperEventClick.on('click', $.proxy(this.cropperAction, this));
        },

        modalListener: function () {
            var _this = this;
            $(this.config.modal).on('hidden.bs.modal', function () {
                _this.$cropperThumbCanvas.empty();
                _this.stopCropper();
            });
        },

        // 移除modal的左侧padding 17px
        removeModalPadding: function () {
            $('.modal').on('show.bs.modal', function () {
                if ($(document).height() > $(window).height()) {
                    // no-scroll
                    $('body').addClass("modal-open-noscroll");
                }
                else {
                    $('body').removeClass("modal-open-noscroll");
                }
            });
            $('.modal').on('hide.bs.modal', function () {
                $('body').removeClass("modal-open-noscroll");
            });
        },

        showModal: function (e) {
            $(this.config.modal).modal('show');
            this.config.click = true;
        },

        initImg: function () {
            var files, file;
            files = this.$cropperInputFile.prop('files');
            if (files.length > 0) {
                file = files[0];
                if (this.isImg(file)) {
                    this.config.imgUrl = URL.createObjectURL(file);
                    this.initCropper();
                }
            }
        },

        isImg: function (file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif)$/.test(file);
            }
        },

        // 启动cropper
        initCropper: function () {
            var _this = this;
            var img = $('<img src="' + this.config.imgUrl + '">');
            if (this.butterflyCropper) {
                this.butterflyCropper.replace(this.config.imgUrl);
            } else {
                this.$cropperCanvas.empty().html(img);
                this.butterflyCropper = new Cropper(img[0], {
                    aspectRatio: Number(this.config.aspectRatioWidth) / Number(this.config.aspectRatioHeight),
                    preview: '.butterfly-cropper-thumb-canvas',
                    strict: false,
                    crop: function (e) {
                        var data = e.detail;
                        var json = [
                            '{"x":' + data.x,
                            '"y":' + data.y,
                            '"height":' + data.height,
                            '"width":' + data.width,
                            '"rotate":' + data.rotate + '}'
                        ].join();
                        _this.$cropperInputData.val(json);
                    }
                });
            }
        },

        // cropper 动作
        cropperAction: function (e) {
            var buttonTarget = $(e.currentTarget);
            var method = buttonTarget.data('method');
            if (this.butterflyCropper)
                this.butterflyCropper.setDragMode(method);
        },


        stopCropper: function () {
            if (this.butterflyCropper) {
                this.butterflyCropper.destroy();
                this.$cropperCanvas.empty();
                this.$cropperInputFile.val('');
                this.butterflyCropper = null;
            }
        },

        changeCanvas: function () {
            this.config.windowWidth = $(window).width();
            var canvasWidth, canvasHeight;
            var canvasThumbWidth, canvasThumbHeight;
            var ratio = this.config.aspectRatioWidth / this.config.aspectRatioHeight;
            canvasThumbWidth = this.config.canvasThumbWidth;
            canvasThumbHeight = canvasThumbWidth * ratio;
            if (this.config.windowWidth >= 992) {
                canvasWidth = this.config.canvasWidth;
                canvasHeight = this.config.canvasHeight;
            } else if (this.config.windowWidth >= 768 && this.config.windowWidth < 992) {
                canvasWidth = 570;
                canvasHeight = this.config.canvasHeight / (this.config.canvasWidth / canvasWidth);
            } else {
                canvasWidth = 717 - (767 - this.config.windowWidth);
                canvasHeight = this.config.canvasHeight / (this.config.canvasWidth / canvasWidth);

                canvasThumbWidth = canvasWidth / (645 / 195);
                canvasThumbHeight = canvasThumbWidth * ratio;
            }
            this.$cropperCanvas.css({"width": canvasWidth + "px" ,"height": canvasHeight + "px"});
            this.$cropperThumbCanvas.css({"width": canvasThumbWidth + "px" ,"height": canvasThumbHeight + "px"})
            // 缩略图canvas

        },

        // 上传
        uploadImg: function (e) {
            var thisForm = $(e.currentTarget),
                _this = this;
            $.ajax({
                url: thisForm[0].action,
                type: 'POST',
                cache: false,
                data: new FormData(thisForm[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    console.log('上传中')
                },
                success: function (data) {
                    _this.uploadSuccess(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    _this.errorAlert(textStatus || errorThrown);
                },
                complete: function () {
                    console.log('完成')
                }
            });
            return false;
        },

        uploadSuccess: function (data) {
            if ($.isPlainObject(data) && data.result === 'OK') {
                if (data.data.path) {
                    toastr.success(data.msg);
                    $('#imgShow').attr('src', data.data.path+'?'+Math.ceil(Math.random()*10));
                    $(this.config.modal).modal('hide');
                    if (toastr.options.isReload && this.config.click)
                    {
                        this.config.click = false;
                        setTimeout("window.location.reload()",1200);
                    }
                } else {
                    toastr.error(data.message);
                }
            } else {
                this.errorAlert(data.msg);
            }
        },

        errorAlert: function (msg) {
            var html = '<div class="alert alert-error alert-dismissible">\n' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                '<h5><i class="icon fa fa-exclamation-circle"></i>Error</h5>\n' +
                msg + '.\n' +
                '</div>';
            $(this.config.modal).find('.modal-body').prepend(html);
        }

    };

    new ButterflyCropper();
});