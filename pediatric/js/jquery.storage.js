(function($) {
	let storage_method = 'cookie';

	// Get/set/delete data (localStorage with cookie failover)
	let cookie = {
		set: function(name, value) {
			this.delete(name);
			this.delete('_gat_UA-26259646-1');
			// console.log('set cookie: ', name);
			let secure = location.protocol == 'https:' ? 'secure' : '',
				cookie = [name, '=', JSON.stringify(value), '; domain=.', window.location.host.toString(), '; path=/; expires=Thu, 01-Jan-2090 00:00:01 GMT;' + secure].join('');
			// console.log('JSON.stringify(value)', JSON.stringify(value));
			// console.log('cookie', cookie);
			document.cookie = cookie;
			return cookie;
		},
		get: function(name) {
			// console.log('get cookie: ', name);
			let result = document.cookie.match(new RegExp(name + '=([^;]+)'));
			// console.log('result', result);
			if (result) {
				try {
					result = JSON.parse(result[1]);
				} catch(e) {
					result = result[1] || false;
				}
				// console.log('result', result);
				return result;
			}
			return false;
		},
		delete: function(name) {
			// console.log('delete cookie: ', name);
			document.cookie = [name, '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/; domain=.', window.location.host.toString()].join('');
			return true;
		}
	}
	cookie.remove = cookie.delete;
	window.cookie = cookie;

	window.get_data = function(key) {
		if (storage_method != 'cookie') {
			try {
				let data = localStorage.getItem(key);
				return data || false;
			} catch(e) {
				let data = cookie.get(key);
				return data || false;
			}
		}
		let data = cookie.get(key);
		return data || false;
	}

	window.set_data = function(key, value) {
		if (storage_method != 'cookie') {
			try {
				localStorage.setItem(key, value);
				return localStorage.getItem(key);
			} catch(e) {
				cookie.set(key, value);
				return cookie.get(key);
			}
		}
		cookie.set(key, value);
		return cookie.get(key);
	}

	window.delete_data = function(key) {
		if (storage_method != 'cookie') {
			try {
				let deleted = localStorage.removeItem(key);
				return deleted;
			} catch(e) {
				cookie.delete(key);
				return true;
			}
		}
		cookie.delete(key);
		return true;
	}
})(jQuery);
