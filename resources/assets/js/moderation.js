(function ($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Moderation = function Moderation()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // inline moderation click handling
        $('a[data-moderate]').click($.proxy(function (e) {
            e.preventDefault();

            MyBB.Spinner.add();

            var moderation_content = $('[data-moderation-content]').first();
            $.post('/moderate', {
                moderation_name: $(e.currentTarget).attr('data-moderate'),
                moderation_content: moderation_content.attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds(),
                moderation_source_type: moderation_content.attr('data-moderation-source-type'),
                moderation_source_id: moderation_content.attr('data-moderation-source-id')
            }, function (response) {
                document.location.reload();
            });
        }, this));

        // inline reverse moderation click handling
        $('a[data-moderate-reverse]').click($.proxy(function (e) {
            e.preventDefault();

            MyBB.Spinner.add();

            var moderation_content = $('[data-moderation-content]').first();
            $.post('/moderate/reverse', {
                moderation_name: $(e.currentTarget).attr('data-moderate-reverse'),
                moderation_content: moderation_content.attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds(),
                moderation_source_type: moderation_content.attr('data-moderation-source-type'),
                moderation_source_id: moderation_content.attr('data-moderation-source-id')
            }, function (response) {
                document.location.reload();
            });
        }, this));

        // moderation bar clear selection handling
        $('.clear-selection a').click(function (e) {
            $('[data-moderation-content] input[type=checkbox]:checked').removeAttr('checked');
            $('[data-moderation-content] .highlight').removeClass('highlight');
            $('.inline-moderation').removeClass('floating');
        });

        // post level inline moderation checkbox handling
        $(".post :checkbox").change(function () {
            $(this).closest(".post").toggleClass("highlight", this.checked);

            var checked_boxes = $('.highlight').length;

            if (checked_boxes == 1) {
                $('.inline-moderation').addClass('floating');
            }

            if (checked_boxes > 1) {
                $('li[data-moderation-multi]').show();
            } else {
                $('li[data-moderation-multi]').hide();
            }

            if (checked_boxes == 0) {
                $('.inline-moderation').removeClass('floating');
            }

            $('.inline-moderation .selection-count').text(' ('+checked_boxes+')')
        });

        // topic level inline moderation checkbox handling
        $(".topic-list .topic :checkbox").change(function () {
            $(this).closest(".topic").toggleClass("highlight", this.checked);

            var checked_boxes = $('.highlight').length;

            if (checked_boxes == 1) {
                $('.inline-moderation').addClass('floating');
            }

            if (checked_boxes > 1) {
                $('li[data-moderation-multi]').show();
            } else {
                $('li[data-moderation-multi]').hide();
            }

            if (checked_boxes == 0) {
                $('.inline-moderation').removeClass('floating');
            }

            $('.inline-moderation .selection-count').text(' ('+checked_boxes+')')
        });

        $('li[data-moderation-multi]').hide();
    };

    // get the IDs of elements currently selected
    window.MyBB.Moderation.getSelectedIds = function getSelectedIds()
    {
        return $('input[type=checkbox][data-moderation-id]:checked').map(function () {
            return $(this).attr('data-moderation-id');
        }).get();
    };

    // grab the current selection and inject it into the modal so we can submit through a normal form
    window.MyBB.Moderation.injectModalParams = function injectFormData(element)
    {
        var moderation_content = $('[data-moderation-content]').first();
        $(element).attr('data-modal-params', JSON.stringify({
            moderation_content: moderation_content.attr('data-moderation-content'),
            moderation_ids: window.MyBB.Moderation.getSelectedIds(),
            moderation_source_type: moderation_content.attr('data-moderation-source-type'),
            moderation_source_id: moderation_content.attr('data-moderation-source-id')
        }));
    };

    var moderation = new window.MyBB.Moderation();

})(jQuery, window);
