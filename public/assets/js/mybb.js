(function () {
'use strict';

var Cookie = function () {
    /**
     * Create a new instance of the cookie container.
     *
     * @param prefix The prefix to apply to all cookies.
     * @param path The path that all cookies should be assigned to.
     * @param domain The domain that all cookies should be assigned to.
     * @param secure Whether cookies should be set as secure (HTTPS).
     */
    function Cookie(prefix, path, domain, secure) {
        if (prefix === void 0) {
            prefix = "";
        }
        if (path === void 0) {
            path = "/";
        }
        if (domain === void 0) {
            domain = "";
        }
        if (secure === void 0) {
            secure = false;
        }
        this.prefix = prefix;
        this.path = path;
        this.domain = domain;
        this.secure = secure;
        // TODO: Ensure the path is absolute, relative paths are not supported!
    }
    /**
     * Get the value of the cookie with the given name.
     *
     * @param name The name of the cookie to retrieve.
     *
     * @return The value of the cookie, or null if it doesn't exist.
     */
    Cookie.prototype.get = function (name) {
        name = encodeURIComponent(this.prefix + name);
        name = name.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) {
            return parts.pop().split(";").shift();
        }
        return null;
    };
    /**
     * Set a new cookie.
     * @param name The name of the cookie to set.
     * @param value The value of the cookie to set.
     * @param expires Either a date to expire the cookie at, or a number of days for the cookie to last (default: 5 years).
     */
    Cookie.prototype.set = function (name, value, expires) {
        if (expires === void 0) {
            expires = 157680000;
        }
        name = encodeURIComponent(this.prefix + name);
        name = name.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
        var cookieEntry = name.trim() + "=" + encodeURIComponent(value);
        if (this.path.length > 0) {
            cookieEntry += "; path=" + this.path;
        }
        if (this.domain.length > 0) {
            cookieEntry += "; domain=" + this.domain;
        }
        if (this.secure) {
            cookieEntry += "; secure";
        }
        if (expires !== null) {
            var expiresString;
            if (expires instanceof Date) {
                expiresString = expires.toUTCString();
            } else {
                // expires is a number
                var expiresDate = new Date();
                expiresDate.setMilliseconds(expiresDate.getMilliseconds() + expires * 864e+5);
                expiresString = expiresDate.toUTCString();
            }
            cookieEntry += "; expires=" + expiresString;
        }
        document.cookie = cookieEntry;
    };
    /**
     * Remove the given cookie.
     *
     * @param name The name of the cookie to remove.
     */
    Cookie.prototype.unset = function (name) {
        this.set(name, "", -1);
    };
    /**
     * Check if the given cookie is set.
     *
     * @param name The name of the cookie to check for.
     *
     * @return {boolean} True if the cookie is set, false otherwise.
     */
    Cookie.prototype.has = function (name) {
        return this.get(name) !== null;
    };
    return Cookie;
}();

var Spinner = function () {
    function Spinner(spinnerText) {
        if (spinnerText === void 0) {
            spinnerText = "Loading...";
        }
        this.spinnerText = spinnerText;
    }
    Spinner.prototype.assertSpinnerElementExists = function () {
        var el = document.getElementById("spinner");
        if (el !== null) {
            return el;
        }
        // TODO: Should we create the default spinner?
        el = document.createElement("div");
        el.id = "spinner";
        var iconNode = document.createElement("i");
        iconNode.className = "fa fa-spinner fa-pulse";
        var textNode = document.createTextNode(this.spinnerText);
        el.appendChild(iconNode);
        el.appendChild(textNode);
        document.body.insertBefore(el, document.body.firstChild);
        return el;
    };
    Spinner.prototype.add = function () {
        var numInProgress = Spinner.numInProgress + 1;
        if (numInProgress === 1) {
            Spinner.numInProgress = numInProgress;
            var spinnerElement = this.assertSpinnerElementExists();
            spinnerElement.style.display = "block";
        }
    };
    Spinner.prototype.remove = function () {
        var numInProgress = Spinner.numInProgress - 1;
        if (numInProgress === 0) {
            Spinner.numInProgress = numInProgress;
            var spinnerElement = this.assertSpinnerElementExists();
            spinnerElement.style.display = "none";
        }
    };
    Spinner.numInProgress = 0;
    return Spinner;
}();

var Utils = function () {
    function Utils() {}
    Utils.prototype.forEach = function (array, callback, scope) {
        for (var i = 0; i < array.length; i++) {
            callback.call(scope, i, array[i]); // passes back stuff we need
        }
    };
    return Utils;
}();

var utils = new Utils();
var Lang = window.Lang || {};
var Post = function () {
    /**
     * Create control functionality for posts
     *
     * @param postToggles Elements used to toggle hiding/showing a post
     * @param postDeletes Elements used to delete a post
     */
    function Post(postToggles, postDeletes) {
        if (postToggles === void 0) {
            postToggles = document.querySelectorAll('.post__toggle');
        }
        if (postDeletes === void 0) {
            postDeletes = document.querySelectorAll('.delete a');
        }
        this.postToggles = postToggles;
        this.postDeletes = postDeletes;
        var _this = this;
        if (postToggles.length > 0) {
            utils.forEach(postToggles, function (i, toggle) {
                toggle.addEventListener('click', _this.togglePost);
            }, this);
        }
        if (postDeletes.length > 0) {
            utils.forEach(postDeletes, function (i, toggle) {
                toggle.addEventListener('click', _this.togglePost);
            }, this);
        }
    }
    /**
     * Toggle the current state of a post
     *
     * @param event Event of clicked item, used to determine the post
     * @return {string} Current state of thread (hidden or visible)
     */
    Post.prototype.togglePost = function (event) {
        event.preventDefault();
        var state = 'visible';
        var currentPost = event.target.closest('.post'),
            postId = currentPost.getAttribute('data-post-id');
        // Are we minimized or not?
        if (currentPost.classList.contains('post--hidden')) {
            currentPost.classList.remove('post--hidden');
            state = 'hidden';
        } else {
            currentPost.classList.add('post--hidden');
        }
        return state;
    };
    /**
     * Show confirmation dialog for delete
     *
     * @param event Event of clicked item
     * @return {boolean} true if user confirms, false otherwise
     */
    Post.prototype.confirmDelete = function (event) {
        return confirm(Lang.get('topic.confirmDelete'));
    };
    return Post;
}();

window.mybb = {
    Lang: window.Lang || {},
    cookie: new Cookie(),
    spinner: new Spinner(),
    post: new Post()
};

}());

//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjpudWxsLCJzb3VyY2VzIjpbIi9ob21lL3ZhZ3JhbnQvQ29kZS9teWJiMi9yZXNvdXJjZXMvYXNzZXRzL3R5cGVzY3JpcHQvY29va2llLnRzIiwiL2hvbWUvdmFncmFudC9Db2RlL215YmIyL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC9zcGlubmVyLnRzIiwiL2hvbWUvdmFncmFudC9Db2RlL215YmIyL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC91dGlscy50cyIsIi9ob21lL3ZhZ3JhbnQvQ29kZS9teWJiMi9yZXNvdXJjZXMvYXNzZXRzL3R5cGVzY3JpcHQvcG9zdC50cyIsIi9ob21lL3ZhZ3JhbnQvQ29kZS9teWJiMi9yZXNvdXJjZXMvYXNzZXRzL3R5cGVzY3JpcHQvbXliYi50cyJdLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgZGVmYXVsdCBjbGFzcyBDb29raWUge1xyXG4gICAgLyoqXHJcbiAgICAgKiBDcmVhdGUgYSBuZXcgaW5zdGFuY2Ugb2YgdGhlIGNvb2tpZSBjb250YWluZXIuXHJcbiAgICAgKlxyXG4gICAgICogQHBhcmFtIHByZWZpeCBUaGUgcHJlZml4IHRvIGFwcGx5IHRvIGFsbCBjb29raWVzLlxyXG4gICAgICogQHBhcmFtIHBhdGggVGhlIHBhdGggdGhhdCBhbGwgY29va2llcyBzaG91bGQgYmUgYXNzaWduZWQgdG8uXHJcbiAgICAgKiBAcGFyYW0gZG9tYWluIFRoZSBkb21haW4gdGhhdCBhbGwgY29va2llcyBzaG91bGQgYmUgYXNzaWduZWQgdG8uXHJcbiAgICAgKiBAcGFyYW0gc2VjdXJlIFdoZXRoZXIgY29va2llcyBzaG91bGQgYmUgc2V0IGFzIHNlY3VyZSAoSFRUUFMpLlxyXG4gICAgICovXHJcbiAgICBjb25zdHJ1Y3Rvcihwcm90ZWN0ZWQgcHJlZml4OiBzdHJpbmcgPSBcIlwiLFxyXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIHBhdGg6IHN0cmluZyA9IFwiL1wiLFxyXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIGRvbWFpbjogc3RyaW5nID0gXCJcIixcclxuICAgICAgICAgICAgICAgIHByb3RlY3RlZCBzZWN1cmU6IGJvb2xlYW4gPSBmYWxzZSkge1xyXG4gICAgICAgIC8vIFRPRE86IEVuc3VyZSB0aGUgcGF0aCBpcyBhYnNvbHV0ZSwgcmVsYXRpdmUgcGF0aHMgYXJlIG5vdCBzdXBwb3J0ZWQhXHJcbiAgICB9XHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiBHZXQgdGhlIHZhbHVlIG9mIHRoZSBjb29raWUgd2l0aCB0aGUgZ2l2ZW4gbmFtZS5cclxuICAgICAqXHJcbiAgICAgKiBAcGFyYW0gbmFtZSBUaGUgbmFtZSBvZiB0aGUgY29va2llIHRvIHJldHJpZXZlLlxyXG4gICAgICpcclxuICAgICAqIEByZXR1cm4gVGhlIHZhbHVlIG9mIHRoZSBjb29raWUsIG9yIG51bGwgaWYgaXQgZG9lc24ndCBleGlzdC5cclxuICAgICAqL1xyXG4gICAgcHVibGljIGdldChuYW1lOiBzdHJpbmcpOiBzdHJpbmcge1xyXG4gICAgICAgIG5hbWUgPSBlbmNvZGVVUklDb21wb25lbnQodGhpcy5wcmVmaXggKyBuYW1lKTtcclxuICAgICAgICBuYW1lID0gbmFtZS5yZXBsYWNlKC8lKDIzfDI0fDI2fDJCfDVFfDYwfDdDKS9nLCBkZWNvZGVVUklDb21wb25lbnQpO1xyXG5cclxuICAgICAgICBsZXQgdmFsdWUgPSBcIjsgXCIgKyBkb2N1bWVudC5jb29raWU7XHJcbiAgICAgICAgbGV0IHBhcnRzID0gdmFsdWUuc3BsaXQoXCI7IFwiICsgbmFtZSArIFwiPVwiKTtcclxuICAgICAgICBpZiAocGFydHMubGVuZ3RoID09IDIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHBhcnRzLnBvcCgpLnNwbGl0KFwiO1wiKS5zaGlmdCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgcmV0dXJuIG51bGw7XHJcbiAgICB9XHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiBTZXQgYSBuZXcgY29va2llLlxyXG4gICAgICogQHBhcmFtIG5hbWUgVGhlIG5hbWUgb2YgdGhlIGNvb2tpZSB0byBzZXQuXHJcbiAgICAgKiBAcGFyYW0gdmFsdWUgVGhlIHZhbHVlIG9mIHRoZSBjb29raWUgdG8gc2V0LlxyXG4gICAgICogQHBhcmFtIGV4cGlyZXMgRWl0aGVyIGEgZGF0ZSB0byBleHBpcmUgdGhlIGNvb2tpZSBhdCwgb3IgYSBudW1iZXIgb2YgZGF5cyBmb3IgdGhlIGNvb2tpZSB0byBsYXN0IChkZWZhdWx0OiA1IHllYXJzKS5cclxuICAgICAqL1xyXG4gICAgcHVibGljIHNldChuYW1lOiBzdHJpbmcsIHZhbHVlOiBzdHJpbmcsIGV4cGlyZXM6IG51bWJlciB8IERhdGUgPSAxNTc2ODAwMDApOiB2b2lkIHtcclxuICAgICAgICBuYW1lID0gZW5jb2RlVVJJQ29tcG9uZW50KHRoaXMucHJlZml4ICsgbmFtZSk7XHJcbiAgICAgICAgbmFtZSA9IG5hbWUucmVwbGFjZSgvJSgyM3wyNHwyNnwyQnw1RXw2MHw3QykvZywgZGVjb2RlVVJJQ29tcG9uZW50KTtcclxuXHJcbiAgICAgICAgdmFyIGNvb2tpZUVudHJ5ID0gbmFtZS50cmltKCkgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2YWx1ZSk7XHJcblxyXG4gICAgICAgIGlmICh0aGlzLnBhdGgubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgcGF0aD1cIiArIHRoaXMucGF0aDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICh0aGlzLmRvbWFpbi5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIGNvb2tpZUVudHJ5ICs9IFwiOyBkb21haW49XCIgKyB0aGlzLmRvbWFpbjtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICh0aGlzLnNlY3VyZSkge1xyXG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgc2VjdXJlXCI7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBpZiAoZXhwaXJlcyAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICB2YXIgZXhwaXJlc1N0cmluZzogc3RyaW5nO1xyXG5cclxuICAgICAgICAgICAgaWYgKGV4cGlyZXMgaW5zdGFuY2VvZiBEYXRlKSB7XHJcbiAgICAgICAgICAgICAgICBleHBpcmVzU3RyaW5nID0gZXhwaXJlcy50b1VUQ1N0cmluZygpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgLy8gZXhwaXJlcyBpcyBhIG51bWJlclxyXG4gICAgICAgICAgICAgICAgbGV0IGV4cGlyZXNEYXRlID0gbmV3IERhdGUoKTtcclxuICAgICAgICAgICAgICAgIGV4cGlyZXNEYXRlLnNldE1pbGxpc2Vjb25kcyhleHBpcmVzRGF0ZS5nZXRNaWxsaXNlY29uZHMoKSArIChleHBpcmVzICogODY0ZSs1KSk7XHJcbiAgICAgICAgICAgICAgICBleHBpcmVzU3RyaW5nID0gZXhwaXJlc0RhdGUudG9VVENTdHJpbmcoKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29va2llRW50cnkgKz0gXCI7IGV4cGlyZXM9XCIgKyBleHBpcmVzU3RyaW5nO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZG9jdW1lbnQuY29va2llID0gY29va2llRW50cnk7XHJcbiAgICB9XHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiBSZW1vdmUgdGhlIGdpdmVuIGNvb2tpZS5cclxuICAgICAqXHJcbiAgICAgKiBAcGFyYW0gbmFtZSBUaGUgbmFtZSBvZiB0aGUgY29va2llIHRvIHJlbW92ZS5cclxuICAgICAqL1xyXG4gICAgcHVibGljIHVuc2V0KG5hbWU6IHN0cmluZyk6IHZvaWQge1xyXG4gICAgICAgIHRoaXMuc2V0KG5hbWUsIFwiXCIsIC0xKTtcclxuICAgIH1cclxuXHJcbiAgICAvKipcclxuICAgICAqIENoZWNrIGlmIHRoZSBnaXZlbiBjb29raWUgaXMgc2V0LlxyXG4gICAgICpcclxuICAgICAqIEBwYXJhbSBuYW1lIFRoZSBuYW1lIG9mIHRoZSBjb29raWUgdG8gY2hlY2sgZm9yLlxyXG4gICAgICpcclxuICAgICAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgaWYgdGhlIGNvb2tpZSBpcyBzZXQsIGZhbHNlIG90aGVyd2lzZS5cclxuICAgICAqL1xyXG4gICAgcHVibGljIGhhcyhuYW1lOiBzdHJpbmcpOiBib29sZWFuIHtcclxuICAgICAgICByZXR1cm4gKHRoaXMuZ2V0KG5hbWUpICE9PSBudWxsKTtcclxuICAgIH1cclxufVxyXG4iLCJleHBvcnQgZGVmYXVsdCBjbGFzcyBTcGlubmVyIHtcclxuICAgIHByb3RlY3RlZCBzdGF0aWMgbnVtSW5Qcm9ncmVzczogbnVtYmVyID0gMDtcclxuXHJcbiAgICBjb25zdHJ1Y3Rvcihwcm90ZWN0ZWQgc3Bpbm5lclRleHQ6IHN0cmluZyA9IFwiTG9hZGluZy4uLlwiKSB7fVxyXG5cclxuICAgIHByaXZhdGUgYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMoKTogSFRNTEVsZW1lbnQge1xyXG4gICAgICAgIHZhciBlbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwic3Bpbm5lclwiKTtcclxuXHJcbiAgICAgICAgaWYgKGVsICE9PSBudWxsKSB7XHJcbiAgICAgICAgICAgIHJldHVybiBlbDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIFRPRE86IFNob3VsZCB3ZSBjcmVhdGUgdGhlIGRlZmF1bHQgc3Bpbm5lcj9cclxuICAgICAgICBlbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XHJcbiAgICAgICAgZWwuaWQgPSBcInNwaW5uZXJcIjtcclxuXHJcbiAgICAgICAgbGV0IGljb25Ob2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImlcIik7XHJcbiAgICAgICAgaWNvbk5vZGUuY2xhc3NOYW1lID0gXCJmYSBmYS1zcGlubmVyIGZhLXB1bHNlXCI7XHJcblxyXG4gICAgICAgIGxldCB0ZXh0Tm9kZSA9IGRvY3VtZW50LmNyZWF0ZVRleHROb2RlKHRoaXMuc3Bpbm5lclRleHQpO1xyXG5cclxuICAgICAgICBlbC5hcHBlbmRDaGlsZChpY29uTm9kZSk7XHJcbiAgICAgICAgZWwuYXBwZW5kQ2hpbGQodGV4dE5vZGUpO1xyXG5cclxuICAgICAgICBkb2N1bWVudC5ib2R5Lmluc2VydEJlZm9yZShlbCwgZG9jdW1lbnQuYm9keS5maXJzdENoaWxkKTtcclxuXHJcbiAgICAgICAgcmV0dXJuIGVsO1xyXG4gICAgfVxyXG5cclxuICAgIHB1YmxpYyBhZGQoKSB7XHJcbiAgICAgICAgbGV0IG51bUluUHJvZ3Jlc3MgPSBTcGlubmVyLm51bUluUHJvZ3Jlc3MgKyAxO1xyXG4gICAgICAgIGlmIChudW1JblByb2dyZXNzID09PSAxKSB7XHJcbiAgICAgICAgICAgIFNwaW5uZXIubnVtSW5Qcm9ncmVzcyA9IG51bUluUHJvZ3Jlc3M7XHJcblxyXG4gICAgICAgICAgICBsZXQgc3Bpbm5lckVsZW1lbnQgPSB0aGlzLmFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzKCk7XHJcbiAgICAgICAgICAgIHNwaW5uZXJFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSBcImJsb2NrXCI7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIHB1YmxpYyByZW1vdmUoKSB7XHJcbiAgICAgICAgbGV0IG51bUluUHJvZ3Jlc3MgPSBTcGlubmVyLm51bUluUHJvZ3Jlc3MgLSAxO1xyXG4gICAgICAgIGlmIChudW1JblByb2dyZXNzID09PSAwKSB7XHJcbiAgICAgICAgICAgIFNwaW5uZXIubnVtSW5Qcm9ncmVzcyA9IG51bUluUHJvZ3Jlc3M7XHJcblxyXG4gICAgICAgICAgICBsZXQgc3Bpbm5lckVsZW1lbnQgPSB0aGlzLmFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzKCk7XHJcbiAgICAgICAgICAgIHNwaW5uZXJFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSBcIm5vbmVcIjtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuIiwiZXhwb3J0IGRlZmF1bHQgY2xhc3MgVXRpbHMge1xyXG4gICAgcHVibGljIGZvckVhY2goYXJyYXksIGNhbGxiYWNrLCBzY29wZSkge1xyXG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgYXJyYXkubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgY2FsbGJhY2suY2FsbChzY29wZSwgaSwgYXJyYXlbaV0pOyAvLyBwYXNzZXMgYmFjayBzdHVmZiB3ZSBuZWVkXHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcbiIsImltcG9ydCBVdGlscyBmcm9tIFwiLi91dGlsc1wiO1xyXG5cclxuY29uc3QgdXRpbHMgPSBuZXcgVXRpbHMoKSxcclxuICAgICAgTGFuZyA9ICg8YW55PndpbmRvdykuTGFuZyB8fCB7fTtcclxuXHJcbmV4cG9ydCBkZWZhdWx0IGNsYXNzIFBvc3Qge1xyXG4gICAgLyoqXHJcbiAgICAgKiBDcmVhdGUgY29udHJvbCBmdW5jdGlvbmFsaXR5IGZvciBwb3N0c1xyXG4gICAgICogXHJcbiAgICAgKiBAcGFyYW0gcG9zdFRvZ2dsZXMgRWxlbWVudHMgdXNlZCB0byB0b2dnbGUgaGlkaW5nL3Nob3dpbmcgYSBwb3N0XHJcbiAgICAgKiBAcGFyYW0gcG9zdERlbGV0ZXMgRWxlbWVudHMgdXNlZCB0byBkZWxldGUgYSBwb3N0XHJcbiAgICAgKi9cclxuICAgIGNvbnN0cnVjdG9yKHByb3RlY3RlZCBwb3N0VG9nZ2xlczogTm9kZUxpc3RPZjxFbGVtZW50PiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5wb3N0X190b2dnbGUnKSxcclxuICAgICAgICAgICAgICAgIHByb3RlY3RlZCBwb3N0RGVsZXRlczogTm9kZUxpc3RPZjxFbGVtZW50PiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5kZWxldGUgYScpKSB7XHJcbiAgICAgICAgY29uc3QgX3RoaXMgPSB0aGlzO1xyXG5cclxuICAgICAgICBpZiAocG9zdFRvZ2dsZXMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICB1dGlscy5mb3JFYWNoKHBvc3RUb2dnbGVzLCAoaSwgdG9nZ2xlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0b2dnbGUuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBfdGhpcy50b2dnbGVQb3N0KTtcclxuICAgICAgICAgICAgfSwgdGhpcyk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIFxyXG4gICAgICAgIGlmIChwb3N0RGVsZXRlcy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHV0aWxzLmZvckVhY2gocG9zdERlbGV0ZXMsIChpLCB0b2dnbGUpID0+IHtcclxuICAgICAgICAgICAgICAgIHRvZ2dsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIF90aGlzLnRvZ2dsZVBvc3QpO1xyXG4gICAgICAgICAgICB9LCB0aGlzKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICBcclxuICAgIC8qKlxyXG4gICAgICogVG9nZ2xlIHRoZSBjdXJyZW50IHN0YXRlIG9mIGEgcG9zdFxyXG4gICAgICpcclxuICAgICAqIEBwYXJhbSBldmVudCBFdmVudCBvZiBjbGlja2VkIGl0ZW0sIHVzZWQgdG8gZGV0ZXJtaW5lIHRoZSBwb3N0XHJcbiAgICAgKiBAcmV0dXJuIHtzdHJpbmd9IEN1cnJlbnQgc3RhdGUgb2YgdGhyZWFkIChoaWRkZW4gb3IgdmlzaWJsZSlcclxuICAgICAqL1xyXG4gICAgcHVibGljIHRvZ2dsZVBvc3QoZXZlbnQ6IEV2ZW50KTogc3RyaW5nIHtcclxuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgIGxldCBzdGF0ZTogc3RyaW5nID0gJ3Zpc2libGUnO1xyXG4gICAgICAgIGNvbnN0IGN1cnJlbnRQb3N0ID0gKDxFbGVtZW50PmV2ZW50LnRhcmdldCkuY2xvc2VzdCgnLnBvc3QnKSxcclxuICAgICAgICAgICAgICBwb3N0SWQgPSBjdXJyZW50UG9zdC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcG9zdC1pZCcpO1xyXG5cclxuICAgICAgICAvLyBBcmUgd2UgbWluaW1pemVkIG9yIG5vdD9cclxuICAgICAgICBpZiAoY3VycmVudFBvc3QuY2xhc3NMaXN0LmNvbnRhaW5zKCdwb3N0LS1oaWRkZW4nKSkge1xyXG4gICAgICAgICAgICBjdXJyZW50UG9zdC5jbGFzc0xpc3QucmVtb3ZlKCdwb3N0LS1oaWRkZW4nKTtcclxuICAgICAgICAgICAgc3RhdGUgPSAnaGlkZGVuJztcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICBjdXJyZW50UG9zdC5jbGFzc0xpc3QuYWRkKCdwb3N0LS1oaWRkZW4nKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgXHJcbiAgICAgICAgcmV0dXJuIHN0YXRlO1xyXG4gICAgfVxyXG4gICAgXHJcbiAgICAvKipcclxuICAgICAqIFNob3cgY29uZmlybWF0aW9uIGRpYWxvZyBmb3IgZGVsZXRlXHJcbiAgICAgKlxyXG4gICAgICogQHBhcmFtIGV2ZW50IEV2ZW50IG9mIGNsaWNrZWQgaXRlbVxyXG4gICAgICogQHJldHVybiB7Ym9vbGVhbn0gdHJ1ZSBpZiB1c2VyIGNvbmZpcm1zLCBmYWxzZSBvdGhlcndpc2VcclxuICAgICAqL1xyXG4gICAgcHVibGljIGNvbmZpcm1EZWxldGUoZXZlbnQ6IEV2ZW50KTogYm9vbGVhbiB7XHJcbiAgICAgICAgcmV0dXJuIGNvbmZpcm0oTGFuZy5nZXQoJ3RvcGljLmNvbmZpcm1EZWxldGUnKSk7XHJcbiAgICB9XHJcbn1cclxuIiwiaW1wb3J0IENvb2tpZSBmcm9tIFwiLi9jb29raWVcIjtcclxuaW1wb3J0IFNwaW5uZXIgZnJvbSBcIi4vc3Bpbm5lclwiO1xyXG5pbXBvcnQgUG9zdCBmcm9tIFwiLi9wb3N0XCI7XHJcblxyXG4oPGFueT53aW5kb3cpLm15YmIgPSB7XHJcbiAgICBMYW5nOiAoPGFueT53aW5kb3cpLkxhbmcgfHwge30sIC8vIFRPRE86IE1ha2UgYW4gRVM2IG1vZHVsZVxyXG4gICAgY29va2llOiBuZXcgQ29va2llKCksXHJcbiAgICBzcGlubmVyOiBuZXcgU3Bpbm5lcigpLFxyXG4gICAgcG9zdDogbmV3IFBvc3QoKVxyXG59O1xyXG4iXSwibmFtZXMiOlsicHJlZml4IiwicGF0aCIsImRvbWFpbiIsInNlY3VyZSIsIm5hbWUiLCJlbmNvZGVVUklDb21wb25lbnQiLCJyZXBsYWNlIiwiZGVjb2RlVVJJQ29tcG9uZW50IiwidmFsdWUiLCJkb2N1bWVudCIsImNvb2tpZSIsInBhcnRzIiwic3BsaXQiLCJsZW5ndGgiLCJwb3AiLCJzaGlmdCIsImV4cGlyZXMiLCJjb29raWVFbnRyeSIsInRyaW0iLCJleHBpcmVzU3RyaW5nIiwiRGF0ZSIsInRvVVRDU3RyaW5nIiwiZXhwaXJlc0RhdGUiLCJzZXRNaWxsaXNlY29uZHMiLCJnZXRNaWxsaXNlY29uZHMiLCJzZXQiLCJnZXQiLCJzcGlubmVyVGV4dCIsImVsIiwiZ2V0RWxlbWVudEJ5SWQiLCJjcmVhdGVFbGVtZW50IiwiaWQiLCJpY29uTm9kZSIsImNsYXNzTmFtZSIsInRleHROb2RlIiwiY3JlYXRlVGV4dE5vZGUiLCJhcHBlbmRDaGlsZCIsImJvZHkiLCJpbnNlcnRCZWZvcmUiLCJmaXJzdENoaWxkIiwibnVtSW5Qcm9ncmVzcyIsIlNwaW5uZXIiLCJzcGlubmVyRWxlbWVudCIsImFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzIiwic3R5bGUiLCJkaXNwbGF5IiwiYXJyYXkiLCJjYWxsYmFjayIsInNjb3BlIiwiaSIsImNhbGwiLCJ1dGlscyIsIlV0aWxzIiwiTGFuZyIsIndpbmRvdyIsInBvc3RUb2dnbGVzIiwicG9zdERlbGV0ZXMiLCJxdWVyeVNlbGVjdG9yQWxsIiwiX3RoaXMiLCJmb3JFYWNoIiwidG9nZ2xlIiwiYWRkRXZlbnRMaXN0ZW5lciIsInRvZ2dsZVBvc3QiLCJldmVudCIsInByZXZlbnREZWZhdWx0Iiwic3RhdGUiLCJjdXJyZW50UG9zdCIsInRhcmdldCIsImNsb3Nlc3QiLCJwb3N0SWQiLCJnZXRBdHRyaWJ1dGUiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsInJlbW92ZSIsImFkZCIsImNvbmZpcm0iLCJteWJiIiwiQ29va2llIiwi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