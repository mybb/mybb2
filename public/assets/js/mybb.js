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

window.mybb = {
    cookie: new Cookie(),
    spinner: new Spinner()
};

}());

//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjpudWxsLCJzb3VyY2VzIjpbIi9Vc2Vycy9ldWFuL015QkIvMi4wL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC9jb29raWUudHMiLCIvVXNlcnMvZXVhbi9NeUJCLzIuMC9yZXNvdXJjZXMvYXNzZXRzL3R5cGVzY3JpcHQvc3Bpbm5lci50cyIsIi9Vc2Vycy9ldWFuL015QkIvMi4wL3Jlc291cmNlcy9hc3NldHMvdHlwZXNjcmlwdC9teWJiLnRzIl0sInNvdXJjZXNDb250ZW50IjpbImV4cG9ydCBkZWZhdWx0IGNsYXNzIENvb2tpZSB7XG4gICAgLyoqXG4gICAgICogQ3JlYXRlIGEgbmV3IGluc3RhbmNlIG9mIHRoZSBjb29raWUgY29udGFpbmVyLlxuICAgICAqXG4gICAgICogQHBhcmFtIHByZWZpeCBUaGUgcHJlZml4IHRvIGFwcGx5IHRvIGFsbCBjb29raWVzLlxuICAgICAqIEBwYXJhbSBwYXRoIFRoZSBwYXRoIHRoYXQgYWxsIGNvb2tpZXMgc2hvdWxkIGJlIGFzc2lnbmVkIHRvLlxuICAgICAqIEBwYXJhbSBkb21haW4gVGhlIGRvbWFpbiB0aGF0IGFsbCBjb29raWVzIHNob3VsZCBiZSBhc3NpZ25lZCB0by5cbiAgICAgKiBAcGFyYW0gc2VjdXJlIFdoZXRoZXIgY29va2llcyBzaG91bGQgYmUgc2V0IGFzIHNlY3VyZSAoSFRUUFMpLlxuICAgICAqL1xuICAgIGNvbnN0cnVjdG9yKHByb3RlY3RlZCBwcmVmaXg6IHN0cmluZyA9IFwiXCIsXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIHBhdGg6IHN0cmluZyA9IFwiL1wiLFxuICAgICAgICAgICAgICAgIHByb3RlY3RlZCBkb21haW46IHN0cmluZyA9IFwiXCIsXG4gICAgICAgICAgICAgICAgcHJvdGVjdGVkIHNlY3VyZTogYm9vbGVhbiA9IGZhbHNlKSB7XG4gICAgICAgIC8vIFRPRE86IEVuc3VyZSB0aGUgcGF0aCBpcyBhYnNvbHV0ZSwgcmVsYXRpdmUgcGF0aHMgYXJlIG5vdCBzdXBwb3J0ZWQhXG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogR2V0IHRoZSB2YWx1ZSBvZiB0aGUgY29va2llIHdpdGggdGhlIGdpdmVuIG5hbWUuXG4gICAgICpcbiAgICAgKiBAcGFyYW0gbmFtZSBUaGUgbmFtZSBvZiB0aGUgY29va2llIHRvIHJldHJpZXZlLlxuICAgICAqXG4gICAgICogQHJldHVybiBUaGUgdmFsdWUgb2YgdGhlIGNvb2tpZSwgb3IgbnVsbCBpZiBpdCBkb2Vzbid0IGV4aXN0LlxuICAgICAqL1xuICAgIHB1YmxpYyBnZXQobmFtZTogc3RyaW5nKTogc3RyaW5nIHtcbiAgICAgICAgbmFtZSA9IGVuY29kZVVSSUNvbXBvbmVudCh0aGlzLnByZWZpeCArIG5hbWUpO1xuICAgICAgICBuYW1lID0gbmFtZS5yZXBsYWNlKC8lKDIzfDI0fDI2fDJCfDVFfDYwfDdDKS9nLCBkZWNvZGVVUklDb21wb25lbnQpO1xuXG4gICAgICAgIGxldCB2YWx1ZSA9IFwiOyBcIiArIGRvY3VtZW50LmNvb2tpZTtcbiAgICAgICAgbGV0IHBhcnRzID0gdmFsdWUuc3BsaXQoXCI7IFwiICsgbmFtZSArIFwiPVwiKTtcbiAgICAgICAgaWYgKHBhcnRzLmxlbmd0aCA9PSAyKSB7XG4gICAgICAgICAgICByZXR1cm4gcGFydHMucG9wKCkuc3BsaXQoXCI7XCIpLnNoaWZ0KCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBTZXQgYSBuZXcgY29va2llLlxuICAgICAqIEBwYXJhbSBuYW1lIFRoZSBuYW1lIG9mIHRoZSBjb29raWUgdG8gc2V0LlxuICAgICAqIEBwYXJhbSB2YWx1ZSBUaGUgdmFsdWUgb2YgdGhlIGNvb2tpZSB0byBzZXQuXG4gICAgICogQHBhcmFtIGV4cGlyZXMgRWl0aGVyIGEgZGF0ZSB0byBleHBpcmUgdGhlIGNvb2tpZSBhdCwgb3IgYSBudW1iZXIgb2YgZGF5cyBmb3IgdGhlIGNvb2tpZSB0byBsYXN0IChkZWZhdWx0OiA1IHllYXJzKS5cbiAgICAgKi9cbiAgICBwdWJsaWMgc2V0KG5hbWU6IHN0cmluZywgdmFsdWU6IHN0cmluZywgZXhwaXJlczogbnVtYmVyIHwgRGF0ZSA9IDE1NzY4MDAwMCk6IHZvaWQge1xuICAgICAgICBuYW1lID0gZW5jb2RlVVJJQ29tcG9uZW50KHRoaXMucHJlZml4ICsgbmFtZSk7XG4gICAgICAgIG5hbWUgPSBuYW1lLnJlcGxhY2UoLyUoMjN8MjR8MjZ8MkJ8NUV8NjB8N0MpL2csIGRlY29kZVVSSUNvbXBvbmVudCk7XG5cbiAgICAgICAgdmFyIGNvb2tpZUVudHJ5ID0gbmFtZS50cmltKCkgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2YWx1ZSk7XG5cbiAgICAgICAgaWYgKHRoaXMucGF0aC5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgcGF0aD1cIiArIHRoaXMucGF0aDtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0aGlzLmRvbWFpbi5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgZG9tYWluPVwiICsgdGhpcy5kb21haW47XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodGhpcy5zZWN1cmUpIHtcbiAgICAgICAgICAgIGNvb2tpZUVudHJ5ICs9IFwiOyBzZWN1cmVcIjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChleHBpcmVzICE9PSBudWxsKSB7XG4gICAgICAgICAgICB2YXIgZXhwaXJlc1N0cmluZzogc3RyaW5nO1xuXG4gICAgICAgICAgICBpZiAoZXhwaXJlcyBpbnN0YW5jZW9mIERhdGUpIHtcbiAgICAgICAgICAgICAgICBleHBpcmVzU3RyaW5nID0gZXhwaXJlcy50b1VUQ1N0cmluZygpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvLyBleHBpcmVzIGlzIGEgbnVtYmVyXG4gICAgICAgICAgICAgICAgbGV0IGV4cGlyZXNEYXRlID0gbmV3IERhdGUoKTtcbiAgICAgICAgICAgICAgICBleHBpcmVzRGF0ZS5zZXRNaWxsaXNlY29uZHMoZXhwaXJlc0RhdGUuZ2V0TWlsbGlzZWNvbmRzKCkgKyAoZXhwaXJlcyAqIDg2NGUrNSkpO1xuICAgICAgICAgICAgICAgIGV4cGlyZXNTdHJpbmcgPSBleHBpcmVzRGF0ZS50b1VUQ1N0cmluZygpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjb29raWVFbnRyeSArPSBcIjsgZXhwaXJlcz1cIiArIGV4cGlyZXNTdHJpbmc7XG4gICAgICAgIH1cblxuICAgICAgICBkb2N1bWVudC5jb29raWUgPSBjb29raWVFbnRyeTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBSZW1vdmUgdGhlIGdpdmVuIGNvb2tpZS5cbiAgICAgKlxuICAgICAqIEBwYXJhbSBuYW1lIFRoZSBuYW1lIG9mIHRoZSBjb29raWUgdG8gcmVtb3ZlLlxuICAgICAqL1xuICAgIHB1YmxpYyB1bnNldChuYW1lOiBzdHJpbmcpOiB2b2lkIHtcbiAgICAgICAgdGhpcy5zZXQobmFtZSwgXCJcIiwgLTEpO1xuICAgIH1cblxuICAgIC8qKlxuICAgICAqIENoZWNrIGlmIHRoZSBnaXZlbiBjb29raWUgaXMgc2V0LlxuICAgICAqXG4gICAgICogQHBhcmFtIG5hbWUgVGhlIG5hbWUgb2YgdGhlIGNvb2tpZSB0byBjaGVjayBmb3IuXG4gICAgICpcbiAgICAgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIGlmIHRoZSBjb29raWUgaXMgc2V0LCBmYWxzZSBvdGhlcndpc2UuXG4gICAgICovXG4gICAgcHVibGljIGhhcyhuYW1lOiBzdHJpbmcpOiBib29sZWFuIHtcbiAgICAgICAgcmV0dXJuICh0aGlzLmdldChuYW1lKSAhPT0gbnVsbCk7XG4gICAgfVxufVxuIiwiZXhwb3J0IGRlZmF1bHQgY2xhc3MgU3Bpbm5lciB7XG4gICAgcHJvdGVjdGVkIHN0YXRpYyBudW1JblByb2dyZXNzOiBudW1iZXIgPSAwO1xuXG4gICAgY29uc3RydWN0b3IocHJvdGVjdGVkIHNwaW5uZXJUZXh0OiBzdHJpbmcgPSBcIkxvYWRpbmcuLi5cIikge31cblxuICAgIHByaXZhdGUgYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMoKTogSFRNTEVsZW1lbnQge1xuICAgICAgICB2YXIgZWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInNwaW5uZXJcIik7XG5cbiAgICAgICAgaWYgKGVsICE9PSBudWxsKSB7XG4gICAgICAgICAgICByZXR1cm4gZWw7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBUT0RPOiBTaG91bGQgd2UgY3JlYXRlIHRoZSBkZWZhdWx0IHNwaW5uZXI/XG4gICAgICAgIGVsID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgICAgICAgZWwuaWQgPSBcInNwaW5uZXJcIjtcblxuICAgICAgICBsZXQgaWNvbk5vZGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiaVwiKTtcbiAgICAgICAgaWNvbk5vZGUuY2xhc3NOYW1lID0gXCJmYSBmYS1zcGlubmVyIGZhLXB1bHNlXCI7XG5cbiAgICAgICAgbGV0IHRleHROb2RlID0gZG9jdW1lbnQuY3JlYXRlVGV4dE5vZGUodGhpcy5zcGlubmVyVGV4dCk7XG5cbiAgICAgICAgZWwuYXBwZW5kQ2hpbGQoaWNvbk5vZGUpO1xuICAgICAgICBlbC5hcHBlbmRDaGlsZCh0ZXh0Tm9kZSk7XG5cbiAgICAgICAgZG9jdW1lbnQuYm9keS5pbnNlcnRCZWZvcmUoZWwsIGRvY3VtZW50LmJvZHkuZmlyc3RDaGlsZCk7XG5cbiAgICAgICAgcmV0dXJuIGVsO1xuICAgIH1cblxuICAgIHB1YmxpYyBhZGQoKSB7XG4gICAgICAgIGxldCBudW1JblByb2dyZXNzID0gU3Bpbm5lci5udW1JblByb2dyZXNzICsgMTtcbiAgICAgICAgaWYgKG51bUluUHJvZ3Jlc3MgPT09IDEpIHtcbiAgICAgICAgICAgIFNwaW5uZXIubnVtSW5Qcm9ncmVzcyA9IG51bUluUHJvZ3Jlc3M7XG5cbiAgICAgICAgICAgIGxldCBzcGlubmVyRWxlbWVudCA9IHRoaXMuYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMoKTtcbiAgICAgICAgICAgIHNwaW5uZXJFbGVtZW50LnN0eWxlLmRpc3BsYXkgPSBcImJsb2NrXCI7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICBwdWJsaWMgcmVtb3ZlKCkge1xuICAgICAgICBsZXQgbnVtSW5Qcm9ncmVzcyA9IFNwaW5uZXIubnVtSW5Qcm9ncmVzcyAtIDE7XG4gICAgICAgIGlmIChudW1JblByb2dyZXNzID09PSAwKSB7XG4gICAgICAgICAgICBTcGlubmVyLm51bUluUHJvZ3Jlc3MgPSBudW1JblByb2dyZXNzO1xuXG4gICAgICAgICAgICBsZXQgc3Bpbm5lckVsZW1lbnQgPSB0aGlzLmFzc2VydFNwaW5uZXJFbGVtZW50RXhpc3RzKCk7XG4gICAgICAgICAgICBzcGlubmVyRWxlbWVudC5zdHlsZS5kaXNwbGF5ID0gXCJub25lXCI7XG4gICAgICAgIH1cbiAgICB9XG59XG4iLCJpbXBvcnQgQ29va2llIGZyb20gXCIuL2Nvb2tpZVwiO1xuaW1wb3J0IFNwaW5uZXIgZnJvbSBcIi4vc3Bpbm5lclwiO1xuXG53aW5kb3cubXliYiA9IHtcbiAgICBjb29raWU6IG5ldyBDb29raWUoKSxcbiAgICBzcGlubmVyOiBuZXcgU3Bpbm5lcigpLFxufTtcbiJdLCJuYW1lcyI6WyJwcmVmaXgiLCJwYXRoIiwiZG9tYWluIiwic2VjdXJlIiwibmFtZSIsImVuY29kZVVSSUNvbXBvbmVudCIsInJlcGxhY2UiLCJkZWNvZGVVUklDb21wb25lbnQiLCJ2YWx1ZSIsImRvY3VtZW50IiwiY29va2llIiwicGFydHMiLCJzcGxpdCIsImxlbmd0aCIsInBvcCIsInNoaWZ0IiwiZXhwaXJlcyIsImNvb2tpZUVudHJ5IiwidHJpbSIsImV4cGlyZXNTdHJpbmciLCJEYXRlIiwidG9VVENTdHJpbmciLCJleHBpcmVzRGF0ZSIsInNldE1pbGxpc2Vjb25kcyIsImdldE1pbGxpc2Vjb25kcyIsInNldCIsImdldCIsInNwaW5uZXJUZXh0IiwiZWwiLCJnZXRFbGVtZW50QnlJZCIsImNyZWF0ZUVsZW1lbnQiLCJpZCIsImljb25Ob2RlIiwiY2xhc3NOYW1lIiwidGV4dE5vZGUiLCJjcmVhdGVUZXh0Tm9kZSIsImFwcGVuZENoaWxkIiwiYm9keSIsImluc2VydEJlZm9yZSIsImZpcnN0Q2hpbGQiLCJudW1JblByb2dyZXNzIiwiU3Bpbm5lciIsInNwaW5uZXJFbGVtZW50IiwiYXNzZXJ0U3Bpbm5lckVsZW1lbnRFeGlzdHMiLCJzdHlsZSIsImRpc3BsYXkiLCJ3aW5kb3ciLCJteWJiIiwiQ29va2llIl0sIm1hcHBpbmdzIjoiOzs7QUFBZTs7Ozs7Ozs7O21CQVNYLENBQXNCQSxNQUF0QixFQUNzQkMsSUFEdEIsRUFFc0JDLE1BRnRCLEVBR3NCQyxNQUh0Qjs2QkFBWTt1QkFBQTs7MkJBQ0E7c0JBQUE7OzZCQUNBO3VCQUFBOzs2QkFDQTswQkFBQTs7bUJBSFUsR0FBQUgsTUFBQTtpQkFDQSxHQUFBQyxJQUFBO21CQUNBLEdBQUFDLE1BQUE7bUJBQ0EsR0FBQUMsTUFBQTs7Ozs7Ozs7OztvQkFXZixJQUFBLEdBQVAsVUFBV0MsSUFBWDtlQUNXQyxtQkFBbUIsS0FBS0wsTUFBTCxHQUFjSSxJQUFqQyxDQUFQO2VBQ09BLEtBQUtFLE9BQUwsQ0FBYSwwQkFBYixFQUF5Q0Msa0JBQXpDLENBQVA7WUFFSUMsUUFBUSxPQUFPQyxTQUFTQyxNQUE1QjtZQUNJQyxRQUFRSCxNQUFNSSxLQUFOLENBQVksT0FBT1IsSUFBUCxHQUFjLEdBQTFCLENBQVo7WUFDSU8sTUFBTUUsTUFBTixJQUFnQixDQUFwQixFQUF1QjttQkFDWkYsTUFBTUcsR0FBTixHQUFZRixLQUFaLENBQWtCLEdBQWxCLEVBQXVCRyxLQUF2QixFQUFQOztlQUdHLElBQVA7S0FWRzs7Ozs7OztvQkFtQkEsSUFBQSxHQUFQLFVBQVdYLElBQVgsRUFBeUJJLEtBQXpCLEVBQXdDUSxPQUF4Qzs4QkFBd0M7K0JBQUE7O2VBQzdCWCxtQkFBbUIsS0FBS0wsTUFBTCxHQUFjSSxJQUFqQyxD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