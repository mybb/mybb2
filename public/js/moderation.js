(function($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Moderation = function Moderation()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('a[data-moderate]').click($.proxy(function (e) {
            e.preventDefault();

            $.post('/moderate', {
                moderation_name: $(e.currentTarget).attr('data-moderate'),
                moderation_content: $('[data-moderation-content]').first().attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds()
            }, function (response) {
                document.location.reload();
            });
        }, this));

        $('a[data-moderate-reverse]').click($.proxy(function (e) {
            e.preventDefault();

            $.post('/moderate/reverse', {
                moderation_name: $(e.currentTarget).attr('data-moderate-reverse'),
                moderation_content: $('[data-moderation-content]').first().attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds()
            }, function (response) {
                document.location.reload();
            });
        }, this));
    };

    window.MyBB.Moderation.getSelectedIds = function getSelectedIds()
    {
        return $('input[type=checkbox][data-moderation-id]:checked').map(function () {
            return $(this).attr('data-moderation-id');
        }).get();
    };

    window.MyBB.Moderation.injectModalParams = function injectFormData(element)
    {
        $(element).attr('data-modal-params', JSON.stringify({
            moderation_content: $('[data-moderation-content]').first().attr('data-moderation-content'),
            moderation_ids: window.MyBB.Moderation.getSelectedIds()
        }));
    };

    var moderation = new window.MyBB.Moderation();

})(jQuery, window);
