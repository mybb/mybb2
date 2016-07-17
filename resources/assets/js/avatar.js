(function ($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Avatar = function Avatar()
    {
        this.dropAvatar();
    };

    window.MyBB.Avatar.prototype.dropAvatar = function dropAvatar()
    {
        var $avatarDrop = $('#avatar-drop');
        if ($avatarDrop.length == 0 || $avatarDrop.is(':visible') == $('#avatar-drop-area').is(':visible')) {
            return;
        }

        var avatarDropUrl = $avatarDrop.attr('action');
        Dropzone.autoDiscover = false;
        var avatarDrop = new Dropzone("#avatar-drop", {
            url: avatarDropUrl + "?ajax=1",
            acceptedFiles: "image/*",
            clickable: '#file-input',
            paramName: "avatar_file",
            thumbnailWidth: null,
            thumbnailHeight: null,
            uploadMultiple: false,
            init: function () {
                $("#avatar-drop-area").show();
            },
            previewTemplate: '<div style="display:none" />'
        });

        avatarDrop.on("thumbnail", function (file, dataUrl) {
            $("#crop").find('.jcrop').find('img').attr('src', dataUrl);//TODO: use better selection
        });

        avatarDrop.on("sending", function (file) {
            MyBB.Spinner.add();
        });
        avatarDrop.on("complete", function () {
            MyBB.Spinner.remove();
        });
        avatarDrop.on("success", function (file) {
            var data = $.parseJSON(file.xhr.responseText);
            if (data.needCrop == true) {
                $("<a />").data('modal', '#crop').click((new MyBB.Modals()).toggleModal).click(); // TODO: create a method in modal.js for it
                var $jcrop = $('.modal').find('.jcrop');

                function JcropUpdateInputs(c)
                {
                    $jcrop.find('.jcrop-x').val(c.x);
                    $jcrop.find('.jcrop-y').val(c.y);
                    $jcrop.find('.jcrop-x2').val(c.x2);
                    $jcrop.find('.jcrop-y2').val(c.y2);
                    $jcrop.find('.jcrop-w').val(c.w);
                    $jcrop.find('.jcrop-h').val(c.h);
                }

                $jcrop.find('img').Jcrop({
                    onChange: JcropUpdateInputs,
                    onSelect: JcropUpdateInputs
                });

                $('.modal').find('.crop-img').click(function () {
                    MyBB.Spinner.add();
                    $.post('/account/avatar/crop?ajax=1', {
                        x: $jcrop.find('.jcrop-x').val(),
                        y: $jcrop.find('.jcrop-y').val(),
                        x2: $jcrop.find('.jcrop-x2').val(),
                        y2: $jcrop.find('.jcrop-y2').val(),
                        w: $jcrop.find('.jcrop-w').val(),
                        h: $jcrop.find('.jcrop-h').val(),
                        "_token": $('input[name="_token"]').val()
                    }).done(function (data) {
                        if (data.success) {
                            alert(data.message); // TODO: JS Message
                            $.modal.close();
                            $(".my-avatar").attr('src', data.avatar);
                        } else {
                            alert(data.error);// TODO: JS Error
                        }
                    }).always(function () {
                        MyBB.Spinner.remove();
                    });
                });
            }
        });
    }

    var avatar = new window.MyBB.Avatar();

})(jQuery, window);