(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Cookie = {
		cookiePrefix: '',
		cookiePath: '/',
		cookieDomain: '',

		init: function () {
			MyBB.Settings = MyBB.Settings || {};
			if (typeof MyBB.Settings.cookiePrefix != 'undefined') {
				this.cookiePrefix = MyBB.Settings.cookiePrefix;
			}
			if (typeof MyBB.Settings.cookiePath != 'undefined') {
				this.cookiePath = MyBB.Settings.cookiePath;
			}
			if (typeof MyBB.Settings.cookieDomain != 'undefined') {
				this.cookieDomain = MyBB.Settings.cookieDomain;
			}
		},

		get: function (name) {
			this.init();

			name = this.cookiePrefix + name;
			return $.cookie(name);
		},

		set: function (name, value, expires) {
			this.init();

			name = this.cookiePrefix + name;
			if (!expires) {
				expires = 157680000; // 5*365*24*60*60 => 5 years
			}

			expire = new Date();
			expire.setTime(expire.getTime() + (expires * 1000));

			options = {
				expires: expire,
				path: this.cookiePath,
				domain: this.cookieDomain
			};

			return $.cookie(name, value, options);
		},

		unset: function (name) {
			this.init();

			name = this.cookiePrefix + name;

			options = {
				path: this.cookiePath,
				domain: this.cookieDomain
			};
			return $.removeCookie(name, options);
		}
	}
})
(jQuery, window);