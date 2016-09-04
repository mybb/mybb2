export default class Cookie {
    /**
     * Create a new instance of the cookie container.
     *
     * @param prefix The prefix to apply to all cookies.
     * @param path The path that all cookies should be assigned to.
     * @param domain The domain that all cookies should be assigned to.
     * @param secure Whether cookies should be set as secure (HTTPS).
     */
    constructor(protected prefix: string = "",
                protected path: string = "/",
                protected domain: string = "",
                protected secure: boolean = false) {
        // TODO: Ensure the path is absolute, relative paths are not supported!
    }

    /**
     * Get the value of the cookie with the given name.
     *
     * @param name The name of the cookie to retrieve.
     *
     * @return The value of the cookie, or null if it doesn't exist.
     */
    get(name: string): string {
        name = encodeURIComponent(this.prefix + name);
        name = name.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);

        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length == 2) {
            return parts.pop().split(";").shift();
        }

        return null;
    }

    /**
     * Set a new cookie.
     * @param name The name of the cookie to set.
     * @param value The value of the cookie to set.
     * @param expires Either a date to expire the cookie at, or a number of days for the cookie to last (default: 5 years).
     */
    set(name: string, value: string, expires: number | Date = 157680000): void {
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
            var expiresString: string;

            if (expires instanceof Date) {
                expiresString = expires.toUTCString();
            } else {
                // expires is a number
                let expiresDate = new Date();
                expiresDate.setMilliseconds(expiresDate.getMilliseconds() + (expires * 864e+5));
                expiresString = expiresDate.toUTCString();
            }

            cookieEntry += "; expires=" + expiresString;
        }

        document.cookie = cookieEntry;
    }

    /**
     * Remove the given cookie.
     *
     * @param name The name of the cookie to remove.
     */
    unset(name: string): void {
        this.set(name, "", -1);
    }
}
