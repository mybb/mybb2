(function ($, window) {
    window.MyBB = window.MyBB || {};
    
    window.MyBB.Polls = function Polls()
    {
        this.optionElement = $('#option-simple').clone().attr('id', '').removeClass('hidden').addClass('poll-option').hide();
        $('#option-simple').remove();

        this.removeOption($('#add-poll .poll-option'));

        $('#new-option').click($.proxy(this.addOption, this));

        $('#poll-maximum-options').hide();

        $('#poll-is-multiple').change($.proxy(this.toggleMaxOptionsInput, this)).change();

        var $addPollButton = $("#add-poll-button");
        $addPollButton.click($.proxy(this.toggleAddPoll, this));
        if ($addPollButton.length) {
            if ($('#add-poll-input').val() === '1') {
                $('#add-poll').slideDown();
            }
        }

        this.timePicker();
    };

    window.MyBB.Polls.prototype.timePicker = function timePicker()
    {
        $('#poll-end-at').datetimepicker({
            format: 'Y-m-d H:i:s',
            lang: 'mybb',
            i18n: {
                mybb: {
                    months: [
                        Lang.get('general.months.january'),
                        Lang.get('general.months.february'),
                        Lang.get('general.months.march'),
                        Lang.get('general.months.april'),
                        Lang.get('general.months.may'),
                        Lang.get('general.months.june'),
                        Lang.get('general.months.july'),
                        Lang.get('general.months.august'),
                        Lang.get('general.months.september'),
                        Lang.get('general.months.october'),
                        Lang.get('general.months.november'),
                        Lang.get('general.months.december')
                    ],
                    dayOfWeek: [
                        Lang.get('general.dayOfWeek.sun'),
                        Lang.get('general.dayOfWeek.mon'),
                        Lang.get('general.dayOfWeek.tue'),
                        Lang.get('general.dayOfWeek.wed'),
                        Lang.get('general.dayOfWeek.thu'),
                        Lang.get('general.dayOfWeek.fri'),
                        Lang.get('general.dayOfWeek.sat')
                    ]
                }
            },
            minDate: 0
        });
    };

    window.MyBB.Polls.prototype.toggleAddPoll = function toggleAddPoll()
    {
        if ($('#add-poll-input').val() === '1') {
            $('#add-poll-input').val(0);
            $('#add-poll').slideUp();
        } else {
            $('#add-poll-input').val(1);
            $('#add-poll').slideDown();
        }
        return false;
    };

    window.MyBB.Polls.prototype.addOption = function addOption(event)
    {
        var num_options = $('#add-poll .poll-option').length;
        if (num_options >= 10) { // TODO: settings
            alert(Lang.choice('poll.errorManyOptions', 10)); // TODO: JS Error
            return false;
        }
        var $option = this.optionElement.clone();
        $option.find('input').attr('name', 'option['+(num_options+1)+']');
        $('#add-poll .poll-option').last().after($option);
        $option.slideDown();
        this.removeOption($option);
        return false;
    };

    window.MyBB.Polls.prototype.removeOption = function bindRemoveOption($parent)
    {
        $parent.find('.remove-option').click($.proxy(function (event) {
            var $me = $(event.target),
                $myParent = $me.parents('.poll-option');
            if ($('.poll-option').length <= 2) { // TODO: settings
                alert(Lang.choice('poll.errorFewOptions', 2)); // TODO: JS Error
                return false;
            }

            $myParent.slideUp(500);

            setTimeout($.proxy(function () {
                $myParent.remove();
                this.fixOptionsName();
            }, this), 500);
        }, this));
        if (!Modernizr.touch) {
            $parent.find('.remove-option').powerTip({ placement: 's', smartPlacement: true });
        }
    };

    window.MyBB.Polls.prototype.fixOptionsName = function () {
        var i = 0;
        $('#add-poll .poll-option').each(function () {
            i++;
            $(this).find('input').attr('name', 'option['+i+']');
        });
    };

    window.MyBB.Polls.prototype.toggleMaxOptionsInput = function toggleMaxOptionsInput(event)
    {
        me = event.target;
        if ($(me).is(':checked')) {
            $('#poll-maximum-options').slideDown();
        } else {
            $('#poll-maximum-options').slideUp();
        }
    };

    var polls = new window.MyBB.Polls();

})(jQuery, window);