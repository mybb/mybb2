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

//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjpudWxsLCJzb3VyY2VzIjpbIi9BcHBsaWNhdGlvbnMvWEFNUFAveGFtcHBmaWxlcy9odGRvY3MvbXliYjIvcmVzb3VyY2VzL2Fzc2V0cy90eXBlc2NyaXB0L2Nvb2tpZS50cyIsIi9BcHBsaWNhdGlvbnMvWEFNUFAveGFtcHBmaWxlcy9odGRvY3MvbXliYjIvcmVzb3VyY2VzL2Fzc2V0cy90eXBlc2NyaXB0L3NwaW5uZXIudHMiLCIvQXBwbGljYXRpb25zL1hBTVBQL3hhbXBwZmlsZXMvaHRkb2NzL215YmIyL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC91dGlscy50cyIsIi9BcHBsaWNhdGlvbnMvWEFNUFAveGFtcHBmaWxlcy9odGRvY3MvbXliYjIvcmVzb3VyY2VzL2Fzc2V0cy90eXBlc2NyaXB0L3Bvc3QudHMiLCIvQXBwbGljYXRpb25zL1hBTVBQL3hhbXBwZmlsZXMvaHRkb2NzL215YmIyL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC9teWJiLnRzIl0sInNvdXJjZXNDb250ZW50IjpbImV4cG9ydCBkZWZhdWx0IGNsYXNzIENvb2tpZSB7XG4gICAgLyoqXG4gICAgICogQ3JlYXRlIGEgbmV3IGluc3RhbmNlIG9mIHRoZSBjb29raWUgY29udGFpbmVyLlxuICAgICAqXG4gICAgICogQHBhcmFtIHByZWZpeCBUaGUgcHJlZml4IHRvIGFwcGx5IHRvIGFsbCBjb29raWVzLlxuICAgICAqIEBwYXJhbSBwYXRoIFRoZSBwYXRoIHRoYXQgYWxsIGNvb2tpZXMgc2hvdWxkIGJlIGFzc2lnbmVkIHRvLlxuICAgICAqIEBwYXJhbSBkb21haW4gVGhlIGRvbWFpbiB0aGF0IGFsbCBjb29raWVzIHNob3VsZCBiZSBhc3NpZ25lZCB0by5cbiAgICAgKiBAcGFyYW0gc2VjdXJlIFdoZXRoZXIgY29va2llcyBzaG91bGQgYmUgc2V0IGFzIHNlY3VyZSAoSFRUUFMpLlxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yKHByb3RlY3RlZCBwcmVmaXg6IHN0cmluZyA9IFwiXCIsXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIHBhdGg6IHN0cmluZyA9IFwiL1wiLFxuICAgICAgICAgICAgICAgIHByb3RlY3RlZCBkb21haW46IHN0cmluZyA9IFwiXCIsXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIHNlY3VyZTogYm9vbGVhbiA9IGZhbHNlKSB7XG4gICAgICAgIC8vIFRPRE86IEVuc3VyZSB0aGUgcGF0aCBpcyBhYnNvbHV0ZSwgcmVsYXRpdmUgcGF0aHMgYXJlIG5vdCBzdXBwb3J0ZWQhXG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogR2V0IHRoZSB2YWx1ZSBvZiB0aGUgY29va2llIHdpdGggdGhlIGdpdmVuIG5hbWUuXG4gICAgICpcbiAgICAgKiBAcGFyYW0gbmFtZSBUaGUgbmFtZSBvZiB0aGUgY29va2llIHRvIHJldHJpZXZlLlxuICAgICAqXG4gICAgICogQHJldHVybiBUaGUgdmFsdWUgb2YgdGhlIGNvb2tpZSwgb3IgbnVsbCBpZiBpdCBkb2Vzbid0IGV4aXN0LlxuICAgICAqL1xuICAgIHB1YmxpYyBnZXQobmFtZTogc3RyaW5nKTogc3RyaW5nIHtcbiAgICAgICAgbmFtZSA9IGVuY29kZVVSSUNvbXBvbmVudCh0aGlzLnByZWZpeCArIG5hbWUpO1xuICAgICAgICBuYW1lID0gbmFtZS5yZXBsYWNlKC8lKDIzfDI0fDI2fDJCfDVFfDYwfDdDKS9nLCBkZWNvZGVVUklDb21wb25lbnQpO1xuXG4gICAgICAgIGxldCB2YWx1ZSA9IFwiOyBcIiArIGRvY3VtZW50LmNvb2tpZTtcbiAgICAgICAgbGV0IHBhcnRzID0gdmFsdWUuc3BsaXQoXCI7IFwiICsgbmFtZSArIFwiPVwiKTtcbiAgICAgICAgaWYgKHBhcnRzLmxlbmd0aCA9PSAyKSB7XG4gICAgICAgICAgICByZXR1cm4gcGFydHMucG9wKCkuc3BsaXQoXCI7XCIpLnNoaWZ0KCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBTZXQgYSBuZXcgY29va2llLlxuICAgICAqIEBwYXJhbSBuYW1lIFRoZSBuYW1lIG9mIHRoZSBjb29raWUgdG8gc2V0LlxuICAgICAqIEBwYXJhbSB2YWx1ZSBUaGUgdmFsdWUgb2YgdGhlIGNvb2tpZSB0byBzZXQuXG4gICAgICogQHBhcmFtIGV4cGlyZXMgRWl0aGVyIGEgZGF0ZSB0byBleHBpcmUgdGhlIGNvb2tpZSBhdCwgb3IgYSBudW1iZXIgb2YgZGF5cyBmb3IgdGhlIGNvb2tpZSB0byBsYXN0IChkZWZhdWx0OiA1IHllYXJzKS5cbiAgICAgKi9cbiAgICBwdWJsaWMgc2V0KG5hbWU6IHN0cmluZywgdmFsdWU6IHN0cmluZywgZXhwaXJlczogbnVtYmVyIHwgRGF0ZSA9IDE1NzY4MDAwMCk6IHZvaWQge1xuICAgICAgICBuYW1lID0gZW5jb2RlVVJJQ29tcG9uZW50KHRoaXMucHJlZml4ICsgbmFtZSk7XG4gICAgICAgIG5hbWUgPSBuYW1lLnJlcGxhY2UoLyUoMjN8MjR8MjZ8MkJ8NUV8NjB8N0MpL2csIGRlY29kZVVSSUNvbXBvbmVudCk7XG5cbiAgICAgICAgdmFyIGNvb2tpZUVudHJ5ID0gbmFtZS50cmltKCkgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2YWx1ZSk7XG5cbiAgICAgICAgaWYgKHRoaXMucGF0aC5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgcGF0aD1cIiArIHRoaXMucGF0aDtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLmRvbWFpbi5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgZG9tYWluPVwiICsgdGhpcy5kb21haW47XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5zZWN1cmUpIHtcbiAgICAgICAgICAgIGNvb2tpZUVudHJ5ICs9IFwiOyBzZWN1cmVcIjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChleHBpcmVzICE9PSBudWxsKSB7XG4gICAgICAgICAgICB2YXIgZXhwaXJlc1N0cmluZzogc3RyaW5nO1xuXG4gICAgICAgICAgICBpZiAoZXhwaXJlcyBpbnN0YW5jZW9mIERhdGUpIHtcbiAgICAgICAgICAgICAgICBleHBpcmVzU3RyaW5nID0gZXhwaXJlcy50b1VUQ1N0cmluZygpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvLyBleHBpcmVzIGlzIGEgbnVtYmVyXG4gICAgICAgICAgICAgICAgbGV0IGV4cGlyZXNEYXRlID0gbmV3IERhdGUoKTtcbiAgICAgICAgICAgICAgICBleHBpcmVzRGF0ZS5zZXRNaWxsaXNlY29uZHMoZXhwaXJlc0RhdGUuZ2V0TWlsbGlzZWNvbmRzKCkgKyAoZXhwaXJlcyAqIDg2NGUrNSkpO1xuICAgICAgICAgICAgICAgIGV4cGlyZXNTdHJpbmcgPSBleHBpcmVzRGF0ZS50b1VUQ1N0cmluZygpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgZXhwaXJlcz1cIiArIGV4cGlyZXNTdHJpbmc7XG4gICAgICAgIH1cblxuICAgICAgICBkb2N1bWVudC5jb29raWUgPSBjb29raWVFbnRyeTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBSZW1vdmUgdGhlIGdpdmVuIGNvb2tpZS5cbiAgICAgKlxuICAgICAqIEBwYXJhbSBuYW1lIFRoZSBuYW1lIG9mIHRoZSBjb29raWUgdG8gcmVtb3ZlLlxuICAgICAqL1xuICAgIHB1YmxpYyB1bnNldChuYW1lOiBzdHJpbmcpOiB2b2lkIHtcbiAgICAgICAgdGhpcy5zZXQobmFtZSwgXCJcIiwgLTEpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIENoZWNrIGlmIHRoZSBnaXZlbiBjb29raWUgaXMgc2V0LlxuICAgICAqXG4gICAgICogQHBhcmFtIG5hbWUgVGhlIG5hbWUgb2YgdGhlIGNvb2tpZSB0byBjaGVjayBmb3IuXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIGlmIHRoZSBjb29raWUgaXMgc2V0LCBmYWxzZSBvdGhlcndpc2UuXG4gICAgICovXG4gICAgcHVibGljIGhhcyhuYW1lOiBzdHJpbmcpOiBib29sZWFuIHtcbiAgICAgICAgcmV0dXJuICh0aGlzLmdldChuYW1lKSAhPT0gbnVsbCk7XG4gICAgfVxufVxuIiwiZXhwb3J0IGRlZmF1bHQgY2xhc3MgU3Bpbm5lciB7XG4gICAgcHJvdGVjdGVkIHN0YXRpYyBudW1JblByb2dyZXNzOiBudW1iZXIgPSAwO1xuXG4gICAgY29uc3RydWN0b3IocHJvdGVjdGVkIHNwaW5uZXJUZXh0OiBzdHJpbmcgPSBcIkxvYWRpbmcuLi5cIikge31cblxuICAgIHByaXZhdGUgYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMoKTogSFRNTEVsZW1lbnQge1xuICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInNwaW5uZXJcIik7XG5cbiAgICAgICAgaWYgKGVsICE9PSBudWxsKSB7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBUT0RPOiBTaG91bGQgd2UgY3JlYXRlIHRoZSBkZWZhdWx0IHNwaW5uZXI/XG4gICAgICAgIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgICAgICAgZWwuaWQgPSBcInNwaW5uZXJcIjtcblxuICAgICAgICBsZXQgaWNvbk5vZGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiaVwiKTtcbiAgICAgICAgaWNvbk5vZGUuY2xhc3NOYW1lID0gXCJmYSBmYS1zcGlubmVyIGZhLXB1bHNlXCI7XG5cbiAgICAgICAgbGV0IHRleHROb2RlID0gZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUodGhpcy5zcGlubmVyVGV4dCk7XG5cbiAgICAgICAgZWwuYXBwZW5kQ2hpbGQoaWNvbk5vZGUpO1xuICAgICAgICBlbC5hcHBlbmRDaGlsZCh0ZXh0Tm9kZSk7XG5cbiAgICAgICAgZG9jdW1lbnQuYm9keS5pbnNlcnRCZWZvcmUoZWwsIGRvY3VtZW50LmJvZHkuZmlyc3RDaGlsZCk7XG5cbiAgICAgICAgcmV0dXJuIGVsO1xuICAgIH1cblxuICAgIHB1YmxpYyBhZGQoKSB7XG4gICAgICAgIGxldCBudW1JblByb2dyZXNzID0gU3Bpbm5lci5udW1JblByb2dyZXNzICsgMTtcbiAgICAgICAgaWYgKG51bUluUHJvZ3Jlc3MgPT09IDEpIHtcbiAgICAgICAgICAgIFNwaW5uZXIubnVtSW5Qcm9ncmVzcyA9IG51bUluUHJvZ3Jlc3M7XG5cbiAgICAgICAgICAgIGxldCBzcGlubmVyRWxlbWVudCA9IHRoaXMuYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMoKTtcbiAgICAgICAgICAgIHNwaW5uZXJFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSBcImJsb2NrXCI7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBwdWJsaWMgcmVtb3ZlKCkge1xuICAgICAgICBsZXQgbnVtSW5Qcm9ncmVzcyA9IFNwaW5uZXIubnVtSW5Qcm9ncmVzcyAtIDE7XG4gICAgICAgIGlmIChudW1JblByb2dyZXNzID09PSAwKSB7XG4gICAgICAgICAgICBTcGlubmVyLm51bUluUHJvZ3Jlc3MgPSBudW1JblByb2dyZXNzO1xuXG4gICAgICAgICAgICBsZXQgc3Bpbm5lckVsZW1lbnQgPSB0aGlzLmFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzKCk7XG4gICAgICAgICAgICBzcGlubmVyRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gXCJub25lXCI7XG4gICAgICAgIH1cbiAgICB9XG59XG4iLCJleHBvcnQgZGVmYXVsdCBjbGFzcyBVdGlscyB7XG4gICAgZm9yRWFjaChhcnJheSwgY2FsbGJhY2ssIHNjb3BlKSB7XG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgYXJyYXkubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoc2NvcGUsIGksIGFycmF5W2ldKTsgLy8gcGFzc2VzIGJhY2sgc3R1ZmYgd2UgbmVlZFxuICAgICAgICB9XG4gICAgfVxufVxuIiwiaW1wb3J0IFV0aWxzIGZyb20gXCIuL3V0aWxzXCI7XG5cbmNvbnN0IHV0aWxzID0gbmV3IFV0aWxzKCksXG4gICAgICBMYW5nID0gKDxhbnk+d2luZG93KS5MYW5nIHx8IHt9O1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBQb3N0IHtcbiAgICAvKipcbiAgICAgKiBDcmVhdGUgY29udHJvbCBmdW5jdGlvbmFsaXR5IGZvciBwb3N0c1xuICAgICAqIFxuICAgICAqIEBwYXJhbSBwb3N0VG9nZ2xlcyBFbGVtZW50cyB1c2VkIHRvIHRvZ2dsZSBoaWRpbmcvc2hvd2luZyBhIHBvc3RcbiAgICAgKiBAcGFyYW0gcG9zdERlbGV0ZXMgRWxlbWVudHMgdXNlZCB0byBkZWxldGUgYSBwb3N0XG4gICAgICovXG4gICAgY29uc3RydWN0b3IocHJvdGVjdGVkIHBvc3RUb2dnbGVzOiBOb2RlTGlzdE9mPEVsZW1lbnQ+ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLnBvc3RfX3RvZ2dsZScpLFxuICAgICAgICAgICAgICAgIHByb3RlY3RlZCBwb3N0RGVsZXRlczogTm9kZUxpc3RPZjxFbGVtZW50PiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5kZWxldGUgYScpKSB7XG4gICAgICAgIGNvbnN0IF90aGlzID0gdGhpcztcblxuICAgICAgICBpZiAocG9zdFRvZ2dsZXMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgdXRpbHMuZm9yRWFjaChwb3N0VG9nZ2xlcywgZnVuY3Rpb24oaSwgdG9nZ2xlKSB7XG4gICAgICAgICAgICAgICAgdG9nZ2xlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgX3RoaXMudG9nZ2xlUG9zdCk7XG4gICAgICAgICAgICB9LCB0aGlzKTtcbiAgICAgICAgfVxuICAgICAgICBcbiAgICAgICAgaWYgKHBvc3REZWxldGVzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIHV0aWxzLmZvckVhY2gocG9zdERlbGV0ZXMsIGZ1bmN0aW9uKGksIHRvZ2dsZSkge1xuICAgICAgICAgICAgICAgIHRvZ2dsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIF90aGlzLnRvZ2dsZVBvc3QpO1xuICAgICAgICAgICAgfSwgdGhpcyk7XG4gICAgICAgIH1cbiAgICB9XG4gICAgXG4gICAgLyoqXG4gICAgICogVG9nZ2xlIHRoZSBjdXJyZW50IHN0YXRlIG9mIGEgcG9zdFxuICAgICAqXG4gICAgICogQHBhcmFtIGV2ZW50IEV2ZW50IG9mIGNsaWNrZWQgaXRlbSwgdXNlZCB0byBkZXRlcm1pbmUgdGhlIHBvc3RcbiAgICAgKiBAcmV0dXJuIHtzdHJpbmd9IEN1cnJlbnQgc3RhdGUgb2YgdGhyZWFkIChoaWRkZW4gb3IgdmlzaWJsZSlcbiAgICAgKi9cbiAgICBwdWJsaWMgdG9nZ2xlUG9zdChldmVudDogRXZlbnQpOiBzdHJpbmcge1xuICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBsZXQgc3RhdGU6IHN0cmluZyA9ICd2aXNpYmxlJztcbiAgICAgICAgY29uc3QgY3VycmVudFBvc3QgPSAoPEVsZW1lbnQ+ZXZlbnQudGFyZ2V0KS5jbG9zZXN0KCcucG9zdCcpLFxuICAgICAgICAgICAgICBwb3N0SWQgPSBjdXJyZW50UG9zdC5nZXRBdHRyaWJ1dGUoJ2RhdGEtcG9zdC1pZCcpO1xuXG4gICAgICAgIC8vIEFyZSB3ZSBtaW5pbWl6ZWQgb3Igbm90P1xuICAgICAgICBpZiAoY3VycmVudFBvc3QuY2xhc3NMaXN0LmNvbnRhaW5zKCdwb3N0LS1oaWRkZW4nKSkge1xuICAgICAgICAgICAgY3VycmVudFBvc3QuY2xhc3NMaXN0LnJlbW92ZSgncG9zdC0taGlkZGVuJyk7XG4gICAgICAgICAgICBzdGF0ZSA9ICdoaWRkZW4nO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY3VycmVudFBvc3QuY2xhc3NMaXN0LmFkZCgncG9zdC0taGlkZGVuJyk7XG4gICAgICAgIH1cbiAgICAgICAgXG4gICAgICAgIHJldHVybiBzdGF0ZTtcbiAgICB9XG4gICAgXG4gICAgLyoqXG4gICAgICogU2hvdyBjb25maXJtYXRpb24gZGlhbG9nIGZvciBkZWxldGVcbiAgICAgKlxuICAgICAqIEBwYXJhbSBldmVudCBFdmVudCBvZiBjbGlja2VkIGl0ZW1cbiAgICAgKiBAcmV0dXJuIHtib29sZWFufSB0cnVlIGlmIHVzZXIgY29uZmlybXMsIGZhbHNlIG90aGVyd2lzZVxuICAgICAqL1xuICAgIHB1YmxpYyBjb25maXJtRGVsZXRlKGV2ZW50OiBFdmVudCk6IGJvb2xlYW4ge1xuICAgICAgICByZXR1cm4gY29uZmlybShMYW5nLmdldCgndG9waWMuY29uZmlybURlbGV0ZScpKTtcbiAgICB9XG59XG4iLCJpbXBvcnQgQ29va2llIGZyb20gXCIuL2Nvb2tpZVwiO1xuaW1wb3J0IFNwaW5uZXIgZnJvbSBcIi4vc3Bpbm5lclwiO1xuaW1wb3J0IFBvc3QgZnJvbSBcIi4vcG9zdFwiO1xuXG4oPGFueT53aW5kb3cpLm15YmIgPSB7XG4gICAgTGFuZzogKDxhbnk+d2luZG93KS5MYW5nIHx8IHt9LCAvLyBUT0RPOiBNYWtlIGFuIEVTNiBtb2R1bGVcbiAgICBjb29raWU6IG5ldyBDb29raWUoKSxcbiAgICBzcGlubmVyOiBuZXcgU3Bpbm5lcigpLFxuICAgIHBvc3Q6IG5ldyBQb3N0KClcbn07XG4iXSwibmFtZXMiOlsicHJlZml4IiwicGF0aCIsImRvbWFpbiIsInNlY3VyZSIsIm5hbWUiLCJlbmNvZGVVUklDb21wb25lbnQiLCJyZXBsYWNlIiwiZGVjb2RlVVJJQ29tcG9uZW50IiwidmFsdWUiLCJkb2N1bWVudCIsImNvb2tpZSIsInBhcnRzIiwic3BsaXQiLCJsZW5ndGgiLCJwb3AiLCJzaGlmdCIsImV4cGlyZXMiLCJjb29raWVFbnRyeSIsInRyaW0iLCJleHBpcmVzU3RyaW5nIiwiRGF0ZSIsInRvVVRDU3RyaW5nIiwiZXhwaXJlc0RhdGUiLCJzZXRNaWxsaXNlY29uZHMiLCJnZXRNaWxsaXNlY29uZHMiLCJzZXQiLCJnZXQiLCJzcGlubmVyVGV4dCIsImVsIiwiZ2V0RWxlbWVudEJ5SWQiLCJjcmVhdGVFbGVtZW50IiwiaWQiLCJpY29uTm9kZSIsImNsYXNzTmFtZSIsInRleHROb2RlIiwiY3JlYXRlVGV4dE5vZGUiLCJhcHBlbmRDaGlsZCIsImJvZHkiLCJpbnNlcnRCZWZvcmUiLCJmaXJzdENoaWxkIiwibnVtSW5Qcm9ncmVzcyIsIlNwaW5uZXIiLCJzcGlubmVyRWxlbWVudCIsImFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzIiwic3R5bGUiLCJkaXNwbGF5IiwiYXJyYXkiLCJjYWxsYmFjayIsInNjb3BlIiwiaSIsImNhbGwiLCJ1dGlscyIsIlV0aWxzIiwiTGFuZyIsIndpbmRvdyIsInBvc3RUb2dnbGVzIiwicG9zdERlbGV0ZXMiLCJxdWVyeVNlbGVjdG9yQWxsIiwiX3RoaXMiLCJmb3JFYWNoIiwidG9nZ2xlIiwiYWRkRXZlbnRMaXN0ZW5lciIsInRvZ2dsZVBvc3QiLCJldmVudCIsInByZXZlbnREZWZhdWx0Iiwic3RhdGUiLCJjdXJyZW50UG9zdCIsInRhcmdldCIsImNsb3Nlc3QiLCJwb3N0SWQiLCJnZXRBdHRyaWJ1dGUiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsInJlbW92ZSIsImFkZCIsImNvbmZpcm0iLCJteWJiIiwiQ29va2llIiwiUG9zdCJdLCJtYXBwaW5ncyI6Ijs7O0FBQWU7Ozs7Ozs7OzttQkFTWCxDQUFzQkEsTUFBdEIsRUFDc0JDLElBRHRCLEVBRXNCQyxNQUZ0QixFQUdzQkMsTUFIdEI7NkJBQVk7dUJBQUE7OzJCQUNBO3NCQUFBOzs2QkFDQTt1QkFBQTs7NkJBQ0E7MEJBQUE7O21CQUhVLEdBQUFILE1BQUE7aUJBQ0EsR0FBQUMsSUFBQTttQkFDQSxHQUFBQyxNQUFBO21CQUNBLEdBQUFDLE1BQUE7Ozs7Ozs7Ozs7b0JBV2YsSUFBQSxHQUFQLFVBQVdDLElBQVg7ZUFDV0MsbUJBQW1CLEtBQUtMLE1BQUwsR0FBY0ksSUFBakMsQ0FBUDtlQUNPQSxLQUFLRSxPQUFMLENBQWEsMEJBQWIsRUFBeUNDLGtCQUF6QyxDQUFQO1lBRUlDLFFBQVEsT0FBT0Ms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