/**
 * Sports Manager
 * Copyright (c) 2013
 */
var SM = SM || {
	address: {},
	behaviors: {},
	filters: {},
	fn: {},
	hash: {},
	query: {},
	settings: {}
};

/* is js enabled? */
SM.fn.is_js_enabled = document.getElementsByTagName && document.createElement && document.createTextNode && document.documentElement && document.getElementById;

/* load behaviors */
SM.fn.load_behaviors = function(context) {
	var $ = jQuery;
	context = context || document;
	if (SM.fn.is_js_enabled) {
		$.each(SM.behaviors, function() {
			this(context);
		});
	}
}

/**
 * address
 */
SM.fn.address = function() {
	SM.fn.clean_address();
	if ("onhashchange" in window) { //if event is supported
		window.onhashchange = function () {
			SM.fn.address_change();
		}
	}
	if (document.location.href.search(/#/) == -1) {
		window.location.hash = "#/!/";
	} else {
		SM.fn.address_change("init");
	}
}

SM.fn.clean_address = function() {
	var $ = jQuery;
	SM.query = {};
	var q = window.location.search.substring(1);
	var pairs = q.split("&");
	$.each(pairs, function(i, pair) {
		pair = pair.split("=");
		pair = {
			k: pair[0],
			v: pair[1]
		};
		SM.query[pair.k] = pair.v;
	});
	if (typeof SM.query.tab !== "undefined") {
		window.location.hash = "#/!/tab=" + SM.query.tab;
	}
}

SM.fn.address_change = function(trigger) {
	var $ = jQuery;
	SM.hash = {};
	var hash = window.location.hash.substring(4);
	var pairs = hash.split("&");
	$.each(pairs, function(i, pair) {
		if (pair != "") {
			pair = pair.split("=");
			pair = {
				k: pair[0],
				v: pair[1]
			};
			SM.hash[pair.k] = pair.v;
		}
	});
	if (typeof SM.hash.tab !== "undefined") {
		SM.fn.switch_tab(unescape(SM.hash.tab));
	}
	if (typeof trigger !== "undefined" && trigger == "init") {
		//init
	}
}

SM.fn.update_address = function(query) {
	var $ = jQuery;
	var hash = "";
	$.each(query, function(k, v) {
		if (v != "") {
			hash += k + "=" + v + "&";
		}
	});
	hash = hash.replace(/&$/, "");
	window.location.hash = "#/!/" + hash;
}

/**
 * init
 */
if (SM.fn.is_js_enabled) {
	jQuery(document.documentElement).addClass("js");
	jQuery(document).ready(function() {
		SM.fn.address();
		SM.fn.load_behaviors(this);
	})
}
