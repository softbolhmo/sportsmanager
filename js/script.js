/*
 * SPORTS MANAGER
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

/*
 * is js enabled?
 */
SM.fn.jsEnabled = document.getElementsByTagName && document.createElement && document.createTextNode && document.documentElement && document.getElementById;

/*
 * load behaviors
 */
SM.fn.load_behaviors = function(context) {
	var $ = jQuery;
	context = context || document;
	if (SM.fn.jsEnabled) {
		$.each(SM.behaviors, function() {
			this(context);
		});
	}
}

/*
 * init address
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

/*
 * init
 */
if (SM.fn.jsEnabled) {
	jQuery(document.documentElement).addClass("js");
	jQuery(document).ready(function() {
		SM.fn.address();
		SM.fn.load_behaviors(this);
	})
}

/*
 * behaviors
 */
SM.behaviors.init = function() {
	//init
}

SM.behaviors.menu = function() {
	var $ = jQuery;
	$(".sm_page_menu a").live("click", function() {
		if (!$(this).hasClass("disabled")) {
			var tab = $(this).attr("data-tab");
			if (typeof tab === "undefined" || tab == "") {
				tab = "home";
			}
			SM.fn.switch_tab(unescape(tab));
		} else {
			$("#sm_alerts_modal, #sm_backdrop_disabled").show();
			$("#sm_alerts_modal").find(".alert").html("You must create a league first.");
		}
		return false;
	});
}

SM.behaviors.filters = function() {
	SM.fn.init_filter_tables();
}

SM.behaviors.filter_sorting = function() {
	var $ = jQuery;
	$(".sm_filter_table th").live("click", function(e) {
		var i = $(this).index();
		$(".sm_filter_table td").eq(i).focus();
	});
}

SM.behaviors.filter_navigation = function() {
	var $ = jQuery;
	$(".sm_filter_table td").live("click", function() {
		$(this).focus()
	});

	$(document).on("keydown", function(e) {
		if ((e.target.nodeName == "TD" || e.target.nodeName == "TH") && $(e.target).parents("table").first().hasClass("sm_filter_table")) {
			if (e.keyCode == SM.settings.keycodes.enter) {
				if (e.target.nodeName == "TD") {
					$(e.target).dblclick();
				} else if (e.target.nodeName == "TH") {
					var row = $(e.target).parents("tr").first();
					var index = row.find("td, th").index(e.target);
					$(e.target).click();
					row.parents(".sm_filter_table").find("tbody tr").first().find("td").eq(index).focus();
				}
				return false;
			}
			if (e.keyCode == SM.settings.keycodes.top) {
				var row = $(e.target).parents("tr").first();
				var index = row.find("td, th").index(e.target);
				if (e.target.nodeName == "TD" && row.index() != 0) {
					row.prev().find("td").eq(index).focus();
				} else if (e.target.nodeName == "TD" && row.index() == 0) {
					row.parents(".sm_filter_table").find("thead th").eq(index).focus();
				}
				return false;
			}
			if (e.keyCode == SM.settings.keycodes.bottom) {
				var row = $(e.target).parents("tr").first();
				var index = row.find("td, th").index(e.target);
				if (e.target.nodeName == "TD") {
					row.next().find("td").eq(index).focus();
				} else if (e.target.nodeName == "TH") {
					row.parents(".sm_filter_table").find("tbody tr").first().find("td").eq(index).focus();
				}
				return false;
			}
			if (e.keyCode == SM.settings.keycodes.right) {
				$(e.target).next(".sm_filter_table td, .sm_filter_table th").focus();
				return false;
			}
			if (e.keyCode == SM.settings.keycodes.left) {
				$(e.target).prev(".sm_filter_table td, .sm_filter_table th").focus();
				return false;
			}
		}
		if (e.target.nodeName == "INPUT" && $(e.target).parents("div").first().hasClass("dataTables_filter")) {
			if (e.keyCode == SM.settings.keycodes.enter) {
				$(".sm_filter_table").find("td").first().focus();
			}
			if (e.keyCode == SM.settings.keycodes.esc) {
				SM.filters.filter_data.fnResetAllFilters();
				SM.filters.filter_input.val("");
			}
		}
		if (e.target.nodeName == "TABLE" && $(e.target).parents("div").first().hasClass("sm_modal_footer")) {
			if (e.keyCode == SM.settings.keycodes.enter) {
				if ($(e.target).hasClass("save")) {
					$(e.target).click();
				} else if ($(e.target).hasClass("close")) {
					$(e.target).click();
				}
			}
		}
		if (e.keyCode == SM.settings.keycodes.esc) {
			$(".sm_modal_box .close").click();
			return false;
		}
	});
}

SM.behaviors.filter_add_row = function() {
	var $ = jQuery;
	$(".sm_add_row_btn").live("click", function() {
		var btn = $(this);
		var data = {
			action: "sm_row",
			do: "add_new",
			tab: SM.hash.tab
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			var headers = SM.filters.filter_table.find("thead tr").first().find("th");
			var new_row = {
				filter: headers.first().data("filter"),
				id: response,
				data: [response],
				columns: []
			};
			$.each(headers, function() {
				new_row.columns.push($(this).data("column"));
			});
			for (i = 1; i < new_row.columns.length; i++) {
				new_row.data.push("");
			}
			if (is_number(response)) {
				new_row.data[new_row.data.length - 1] = '<button class="sm_delete_row_btn"><img src="' + SM.settings.SPORTSMANAGER_URL + 'images/icon_trashcan.png" /></button>';
				var i = SM.filters.filter_data.fnAddData(new_row.data, false);
				var added_row = $(SM.filters.filter_data.fnGetNodes(i));
				added_row.attr("id", new_row.filter + "-" + new_row.id).attr("data-row", new_row.id);
				added_row.find("td").each(function(i, cell) {
					$(cell).attr("id", new_row.filter + "-" + new_row.id + "-" + new_row.columns[i]).addClass("column-" + new_row.columns[i]).attr("tabindex", 1);
				});
				SM.filters.filter_data.fnResetAllFilters();
				SM.filters.filter_input.val("");
				SM.filters.filter_data.fnDraw();
				SM.filters.filter_data.fnSort([[0, "desc"]]);
				if (data.tab == "leagues") $(".sm_page_menu a").removeClass("disabled");
			}
		});
	});
}

SM.behaviors.filter_autocomplete = function() {
	var $ = jQuery;
	$(".sm_filter_table td").live("dblclick", function() {
		var cell = $(this);
		SM.settings.current_array = "";
		$.each(SM.settings.autocomplete.map, function(k, v) {
			if (cell.hasClass("column-" + k)) {
				SM.settings.current_array = v;
			}
		});
		var array = SM.settings.current_array;
		$(".sm_autocomplete_item:not(.blank)").remove();
		if (array != "" && typeof SM.settings.autocomplete.arrays[array] !== "undefined") {
			$.each(SM.settings.autocomplete.arrays[array], function(k, v) {
				var blank = $(".sm_autocomplete_item.blank").clone();
				blank.attr("data-value", v.value).html(v.label).removeClass("blank");
				$("#sm_autocomplete").append(blank);
			});
			if (array == "yes-no") {
				$(".sm_autocomplete_item:not(.blank)").css("display", "inline-block");
			};
		}
	});

	$("#sm_current_cell").live("keyup", function() {
		if (SM.settings.current_array != "yes-no") {
			var needle = SM.fn.return_clean_str($(this).val());
			var items = $(".sm_autocomplete_item:not(.blank)");
			items.hide();
			if (needle != "") {
				$.each(items, function() {
					var item = $(this);
					var label = $(this).html();
					var string = SM.fn.return_clean_str(label);
					if (string.search(needle) != -1) {
						item.css("display", "inline-block");
					}
				});
			}
		};
	});

	$(".sm_autocomplete_item").live("click", function() {
		var value = $(this).data("value");
		$("#sm_current_cell").val(value).focus();
	});
}

SM.behaviors.filter_modals = function() {
	var $ = jQuery;

	//edit_cell modal
	$(".sm_filter_table td:not(.column-id, .column-stats, .column-players_id, .column-delete, .dataTables_empty)").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_stats_form");
		var input = $("input[name=current_cell]");
		var value = SM.settings.current_cell.html();
		input.val("").removeAttr("checked").removeAttr("selected"); //clear form
		if (value != "") {
			input.val(value);
		}
		$("#sm_edit_cell_modal, #sm_backdrop").show();
		input.select();
	});

	$("#sm_edit_cell_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var data = {
			action: "sm_row",
			do: "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: $("#sm_current_cell").val()
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			SM.settings.current_cell.html(response).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_cell_modal, #sm_backdrop").hide();
			$("#sm_edit_cell_modal .loader").hide();
			$("#sm_edit_cell_btn").show();
		});
	});

	$("#sm_edit_cell_form").live("submit", function() {
		return false;
	});

	//edit_stats modal
	$(".sm_filter_table td.column-stats").live("dblclick", function() {
		var sport = $(this).siblings(".column-sport").html();
		if (sport != "") {
			SM.settings.current_cell = $(this);
			var form = $("#sm_edit_stats_form");
			var rows = form.find("tr.stat");
			var inputs = rows.find("input:text");
			rows.hide();
			inputs.val("").removeAttr("checked").removeAttr("selected"); //clear form
			form.find("tr.stat.all, tr.stat." + sport).show();
			if (SM.settings.current_cell.html() != "") {
				var stats = jQuery.parseJSON(SM.settings.current_cell.html());
				if (typeof stats === "object") {
					$.each(stats, function(stat, value) { //insert value in form
						form.find("input[name=" + stat + "]").val(value);
					});
				}
			}
			$("#sm_edit_stats_modal, #sm_backdrop").show();
			inputs.first().focus();
		} else {
			alert("You first need to define a sport for this scoresheet.");
			$(this).siblings(".column-sport").focus();
		};
	});

	$("#sm_edit_stats_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		//only include data from visible inputs!!
		var form = $("#sm_edit_stats_form");
		var rows = form.find("tr.stat:visible");
		var inputs = rows.find("input:text");
		var stats = {};
		$.each(inputs, function() {
			var name = $(this).attr("name");
			stats[name] = $(this).val();
		});
		var data = {
			action: "sm_row",
			do: "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_object_to_json(stats)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			SM.settings.current_cell.html(response);
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_stats_modal, #sm_backdrop").hide();
			$("#sm_edit_stats_modal .loader").hide();
			$("#sm_edit_stats_btn").show();
		});
	});

	$("#sm_edit_stats_form").live("submit", function() {
		return false;
	});

	//edit_players_id modal
	$(".sm_filter_table td.column-players_id").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_players_id_form");
		var input = form.find("input:text");
		input.val("").removeAttr("checked").removeAttr("selected"); //clear form
		if (SM.settings.current_cell.html() != "") {
			var players_id = jQuery.parseJSON(SM.settings.current_cell.html());
			if (typeof players_id === "object") {
				$.each(players_id, function(i, id) { //insert value in form
					//fill autocomplete with names
				});
			}
		}
		$("#sm_edit_players_id_modal, #sm_backdrop").show();
		input.focus();
	});

	$("#sm_edit_players_id_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		/*
		var data = {
			action: "sm_row",
			do: "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_object_to_json(stats)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			SM.settings.current_cell.html(response);
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_stats_modal, #sm_backdrop").hide();
			$("#sm_edit_stats_modal .loader").hide();
			$("#sm_edit_stats_btn").show();
		});
		*/
	});

	$("#sm_edit_players_id_form").live("submit", function() {
		return false;
	});

	//delete_row modal
	$(".sm_filter_table td.column-delete").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var id = $(this).parents("tr").first().data("row");
		$("#sm_delete_row_id").val(id);
		$("#sm_delete_row_modal, #sm_backdrop").show();
	});

	$("#sm_delete_row_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var data = {
			action: "sm_row",
			do: "delete",
			tab: SM.hash.tab,
			id: $("#sm_delete_row_id").val()
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			var row = SM.settings.current_cell.parents("tr").first();
			var i = SM.filters.filter_data.fnGetPosition(row.get(0));
			SM.filters.filter_data.fnDeleteRow(i);
			SM.filters.filter_data.fnDraw();
			if (data.tab == "leagues" && response == 0) {
				$.each(["clubs", "games", "locations", "players", "scoresheets", "teams"], function(i, v) {
					$(".sm_page_menu a[data-tab='" + v + "']").addClass("disabled");
				});
			}
			$("#sm_delete_row_modal, #sm_backdrop").hide();
			$("#sm_delete_row_modal .loader").hide();
			$("#sm_delete_row_btn").show();
		});
	});

	$("#sm_delete_row_form").live("submit", function() {
		return false;
	});

	//set_session modal
	$(".sm_change_session_btn").live("click", function() {
		$("#sm_set_session_modal, #sm_backdrop").show();
		$("#sm_set_session_modal select").first().focus();
	});

	$("#sm_set_session_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var form = $("#sm_set_session_form");
		$.get(SM.settings.ajax_url, form.serialize(), function(response) {
			$("#sm_set_session_modal, #sm_backdrop").hide();
			$("#sm_set_session_modal .loader").hide();
			$("#sm_set_session_btn").show();
			SM.fn.switch_tab(unescape(SM.hash.tab));
		});
	});

	$("#sm_set_session_form").live("submit", function() {
		return false;
	});

	//all modals
	$(".sm_modal_box .close, #sm_backdrop").live("click", function() {
		SM.fn.reset_form($(".sm_modal_box form"));
		$(".sm_modal_box, #sm_backdrop, #sm_backdrop_disabled").hide();
		SM.settings.current_cell.focus();
	});
}

SM.behaviors.home_settings = function() {
	var $ = jQuery;
	$("#sm_save_options_form").live("submit", function () {
		var form = $(this);
		var data = {
			action: "sm_db",
			do: "backup_db",
			tab: "",
			date_time: d.Y + "-" + d.M + "-" + d.D + "-" + d.H + "-" + d.M + "-" + d.S,
			value: form.serialize()
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			if (response == "") {
				$("#sm_options_ajax_return").html("Options have been saved!");
				SM.fn.highlight(form, "sm_current_cell");
			}
		});
		return false;
	});

	$("#sm_do_backup_form").live("submit", function () {
		var form = $(this);
		var d = SM.fn.return_date_str();
		var data = {
			action: "sm_db",
			do: "backup_db",
			filter: "",
			date_time: d.Y + "-" + d.M + "-" + d.D + "-" + d.H + "-" + d.M + "-" + d.S
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			if (response == "") {
				$("#sm_backup_ajax_return").html("Check your emails!").show(); //html(data).hide();
				SM.fn.highlight(form, "sm_current_cell");
			} else {
				$("#sm_backup_ajax_return").html("Error: " + response).show();
			};
		});
		return false;
	});
}

SM.behaviors.import = function() {
	var $ = jQuery;
	$("#sm_import_scoresheet_form").live("submit", function() {
		if ($("#delimiter").val() == "" || $("#uploaded_file").val() == "") {
			alert("Complete form before importing file.");
		} else {
			alert("Not ready yet...");
		};
		return false;
	});
}

SM.behaviors.intro = function() {
	var $ = jQuery;
	if (SM.settings.intro.disabled != "disabled") {
		$("#sm_plugin_intro_modal").show();
	}
}

/*
 * navigation functions
 */
SM.fn.highlight = function(e, style, delay) {
	if (typeof delay === "undefined") delay = 2000;
	e.addClass(style);
	setTimeout(function() {
		e.removeClass(style);
	}, delay);
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
			do: "load",
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
		SM.fn.load_autocomplete();
		$("#sm_page_loading_modal, #sm_backdrop_disabled").hide();
	});
}

/*
 * return functions
 */
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

SM.fn.return_object_to_json = function(object) {
	var $ = jQuery;
	var json = "{";
	$.each(object, function(k, v) {
		if (v == "") v = 0;
		json += '"' + k + '":"' + v + '",';
	});
	json = json.replace(/,$/gi, "");
	json += "}";
	return json;
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

/*
 * boolean functions
 */
is_number = function(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

/*
 * datatables api
 */
jQuery.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw) {
	for (iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
		oSettings.aoPreSearchCols[ iCol ].sSearch = "";
	}
	oSettings.oPreviousSearch.sSearch = "";
	if (typeof bDraw === "undefined") bDraw = true;
	if (bDraw) this.fnDraw();
}
