(function ($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Posts = function Posts()
    {
        // Show and hide posts
        $(".postToggle").on("click", this.togglePost).bind(this);


        // Confirm Delete
        $(".delete a").on("click", $.proxy(this.confirmDelete, this));
    };

    // Show and hide posts
    window.MyBB.Posts.prototype.togglePost = function togglePost(event)
    {
        event.preventDefault();
        // Are we minimized or not?
        if ($(event.target).hasClass("fa-minus")) {
        // Perhaps instead of hide, apply a CSS class?
            $(event.target).parent().parent().parent().addClass("post--hidden");
            // Make button a plus sign for expanding
            $(event.target).addClass("fa-plus");
            $(event.target).removeClass("fa-minus");

        } else {
            // We like this person again
            $(event.target).parent().parent().parent().removeClass("post--hidden");
            // Just in case we change our mind again, show the hide button
            $(event.target).addClass("fa-minus");
            $(event.target).removeClass("fa-show");
        }
    };

    // Confirm Delete
    window.MyBB.Posts.prototype.confirmDelete = function confirmDelete(event)
    {
        return confirm(Lang.get('topic.confirmDelete'));
    };

    var posts = new window.MyBB.Posts();

})(jQuery, window);