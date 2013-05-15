/**
 * functions
 */
/* navigation */
SM.fn.highlight = function(element, style, delay) {
	if (typeof delay === "undefined") delay = 2000;
	element.addClass(style);
	setTimeout(function() {
		element.removeClass(style);
	}, delay);
}

SM.fn.highlight_required = function() {
	var $ = jQuery;
	var cells = $(".sm_filter_table td.required");
	cells.removeClass("invalid");
	cells.each(function() {
		var html = $(this).html();
		if (html == "" || html == "0" || html == "?") {
			$(this).addClass("invalid");
		}
	});
}

SM.fn.init_filter_tables = function() {
	var $ = jQuery;
	SM.filters.filter_container = $(".sm_filter_container");
	SM.filters.filter_table = $(".sm_filter_table");
	if (SM.filters.filter_table.length > 0) {
		SM.filters.filter_data = SM.filters.filter_table.dataTable({
			aaSorting: [[0, "desc"]],
			fnPreDrawCallback: function() {
				//
			},
			fnDrawCallback: function() {
				if (!$(":focus").is("input")) {
					$(".sm_filter_table").find("td").first().focus();
				}
			},
			oLanguage: {
				sSearch: ""
			}
		});
	}
	SM.filters.filter_container.slideDown(100, function() { //slideDown(600);
		SM.filters.filter_input = $(".dataTables_filter input");
		SM.filters.filter_table.find("td").first().focus()
	});
}

SM.fn.load_autocomplete = function() {
	var $ = jQuery;
	if (SM.hash.tab != "") {
		var data = {
			action: "sm_autocomplete",
			"do": "load",
			tab: SM.hash.tab
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.settings.autocomplete.arrays = jQuery.parseJSON(response);
		});
	};
}

SM.fn.reset_form = function(form) {
    form.find("input:text, input:password, input:file, select").val("");
    form.find("input:radio, input:checkbox").removeAttr("checked").removeAttr("selected");
}

SM.fn.switch_intro_indicator = function(i) {
	var $ = jQuery;
	if (typeof i !== "undefined") {
		$(".sm_intro_step_indicator td").removeClass("active");
		$(".sm_intro_step_indicator td[data-step='" + i + "']").addClass("active");
	}
}

SM.fn.switch_intro_step = function(direction) {
	var $ = jQuery;
	var active = $(".sm_intro_step.active");
	if (direction == "next") {
		var i = active.removeClass("active").next(".sm_intro_step").addClass("active").attr("data-step");
	} else if (direction == "previous") {
		var i = active.removeClass("active").prev(".sm_intro_step").addClass("active").attr("data-step");
	} else if (SM.fn.is_number(direction)) {
		var i = active.removeClass("active").siblings(".sm_intro_step[data-step='" + direction + "']").addClass("active").attr("data-step");
	} else {
		var i = false;
	}
	return i;
}

SM.fn.switch_tab = function(tab) {
	var $ = jQuery;
	if (tab != "home") {
		SM.hash.tab = tab;
	} else {
		SM.hash.tab = "";
	}
	SM.fn.update_address(SM.hash);
	$("#sm_page_loading_modal, #sm_backdrop_disabled").show();
	$(".sm_main_container").load(SM.settings.SPORTSMANAGER_ADMIN_URL_PREFIX + "&tab=" + tab + " .sm_inner_container", function() {
		SM.fn.init_filter_tables();
		SM.fn.highlight_required();
		SM.fn.load_autocomplete();
		$("#sm_page_loading_modal").hide();
		if ($(".sm_modal_box").filter(":visible").length == 0) {
			$("#sm_backdrop_disabled").hide();
		}
	});
}

/* return */
SM.fn.return_date_str = function() {
	var d = new Date();
	var year = d.getFullYear();
	var month = d.getMonth();
	month = month + 1;
	if (month < 10) month = "0" + month;
	var day = d.getDate();
	if (day < 10) day = "0" + day;
	var hours = d.getHours();
	if (hours < 10) hours = "0" + hours;
	var minutes = d.getMinutes();
	if (minutes < 10) minutes = "0" + minutes;
	var seconds = d.getSeconds();
	if (seconds < 10) seconds = "0" + seconds;
	d = {
		Y: String(year),
		M: String(month),
		D: String(day),
		H: String(hours),
		m: String(minutes),
		S: String(seconds)
	};
	return d;
}

SM.fn.return_array_to_json = function(array, default_value) {
	var $ = jQuery;
	if (typeof default_value === "undefined") default_value = "";
	var json = "";
	$.each(array.sort(), function(k, v) {
		if (v == "") v = default_value;
		if (v != "") json += '"' + v + '",';
	});
	json = json.replace(/,$/gi, "");
	if (json != "") json = "[" + json + "]";
	return json;
}

SM.fn.return_object_to_json = function(object, default_value) {
	var $ = jQuery;
	if (typeof default_value === "undefined") default_value = "";
	var json = "";
	$.each(object, function(k, v) {
		if (v == "") v = default_value;
		if (v != "") json += '"' + k + '":"' + v + '",';
	});
	json = json.replace(/,$/gi, "");
	if (json != "") json = "{" + json + "}";
	return json;
}

SM.fn.return_form_to_json = function(form) {
	var $ = jQuery;
	var o = {};
	var a = form.serializeArray();
	$.each(a, function() {
		if (typeof o[this.name] !== "undefined") {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || "");
		} else {
			o[this.name] = this.value || "";
		}
	});
	return SM.fn.return_object_to_json(o);
}

SM.fn.return_clean_str = function(s) {
	var r = s.toLowerCase();
	r = r.replace(new RegExp("[àáâãäå]", "g"), "a");
	r = r.replace(new RegExp("æ", "g"), "ae");
	r = r.replace(new RegExp("ç", "g"), "c");
	r = r.replace(new RegExp("[èéêë]", "g"), "e");
	r = r.replace(new RegExp("[ìíîï]", "g"), "i");
	r = r.replace(new RegExp("ñ", "g"), "n");	    
	r = r.replace(new RegExp("[òóôõö]", "g"), "o");
	r = r.replace(new RegExp("œ", "g"), "oe");
	r = r.replace(new RegExp("[ùúûü]", "g"), "u");
	r = r.replace(new RegExp("[ýÿ]", "g"), "y");
	r = r.replace(/<[^>]+>/g, " ");
	r = r.replace(/\s+/g, " ");
	r = r.replace(/^\s/g, "");
	return r;
}

/* boolean */
SM.fn.is_number = function(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

/* datatables */
jQuery.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw) {
	for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
		oSettings.aoPreSearchCols[ iCol ].sSearch = "";
	}
	oSettings.oPreviousSearch.sSearch = "";
	if (typeof bDraw === "undefined") bDraw = true;
	if (bDraw) this.fnDraw();
}
