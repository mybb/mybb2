(function ($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Spinner = {
        inProgresses: 0,
        add: function () {
            this.inProgresses++;
            if (this.inProgresses == 1) {
                $("#spinner").show();
            }
        },
        remove: function () {
            this.inProgresses--;
            if (this.inProgresses == 0) {
                $("#spinner").hide();
            }
        }
    }
})
(jQuery, window);