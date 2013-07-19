/* modals */
SM.behaviors.modals = function() {
	var $ = jQuery;
	$(".sm_modal_box .close").live("click", function() {
		var parent = $(this).parents(".sm_modal_box");
		SM.fn.reset_form(parent.find("form"));
		parent.hide();
		if ($(".sm_modal_box").filter(":visible").length == 0) {
			if (parent.is(".sm_modal_alerts, .sm_modal_intro")) {
				$("#sm_backdrop_disabled").hide();
			} else if (parent.hasClass("sm_modal_edit_info")) {
				$("#sm_backdrop").hide();
			}
		};
		if (SM.settings.current_cell != "") {
			SM.settings.current_cell.focus();
		}
	});

	/*
	$("#sm_backdrop").live("click", function() {
		SM.fn.reset_form($(".sm_modal_box form"));
		$(".sm_modal_box, #sm_backdrop").hide();
		SM.settings.current_cell.focus();
	});
	*/
}

SM.behaviors.edit_cell = function() {
	var $ = jQuery;
	$(".sm_filter_table td:not(.column-id, .column-description, .column-small_logo_url, .column-large_logo_url, .column-stats, .column-infos, .column-players_id, .column-captains_id, .column-delete, .dataTables_empty)").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var league_id = $(this).siblings(".column-league_id").html();
		if ($(this).is(".column-home_team_id, .column-away_team_id, .column-winner_team_id") && (league_id == "" || league_id == "0")) {
			$("#sm_alerts_modal, #sm_backdrop_disabled").show();
			$("#sm_alerts_modal").find(".alert").html("You first need to define a league ID for this game.");
		} else {
			var input = $("#sm_edit_cell_modal input[name=current_cell]");
			var value = "";
			var mask_value = SM.settings.current_cell.attr("data-mask-value");
			if (mask_value == "1") {
				value = SM.settings.current_cell.attr("data-value");
			} else if (mask_value == "0") {
				value = SM.settings.current_cell.html();
			};
			input.val("").removeAttr("checked").removeAttr("selected"); //clear form
			if (value != "") {
				input.val(value);
			}
			$("#sm_edit_cell_modal, #sm_backdrop").show();
			input.keyup().select();
		}
	});

	$("#sm_edit_cell_btn:not(.disabled)").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var restricted_limit = SM.settings.current_cell.attr("data-restricted-limit");
		if (typeof restricted_limit !== "undefined" && restricted_limit != "" && SM.fn.is_number(restricted_limit)) {
			var input = $("#sm_current_input-edit_cell");
			var count = input.val().length;
			if (count > restricted_limit) {
				$("#sm_alerts_modal").show();
				$("#sm_alerts_modal").find(".alert").html("The maximum number of characters for this cell is " + restricted_limit + ".");
				$("#sm_edit_cell_modal .loader").hide();
				$("#sm_edit_cell_btn").show().addClass("disabled");
				SM.fn.highlight(input, "invalid");
				return false;
			}
		}
		var data = {
			action: "sm_row",
			"do": "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: $("#sm_current_input-edit_cell").val()
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			var returned = {name: "", value: ""};
			try {
				var returned = jQuery.parseJSON(response);
			} catch (e) {};
			SM.fn.load_autocomplete();
			SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
			SM.fn.highlight_required();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			SM.filters.filter_table.fnDraw(); //doesn't do the trick...
			$("#sm_edit_cell_modal .close").click();
			$("#sm_edit_cell_modal .loader").hide();
			$("#sm_edit_cell_btn").show();
		});
	});

	$("#sm_edit_cell_form").live("submit", function() {
		return false;
	});

	$("#sm_current_input-edit_cell").live("keyup", function(e) {
		$("#sm_edit_cell_btn").removeClass("disabled");
		$(this).removeClass("invalid");
		var restricted_type = SM.settings.current_cell.attr("data-restricted-type");
		if (typeof restricted_type !== "undefined" && restricted_type != "") {
			if (restricted_type == "slug") {
				var slug = $(this).val();
				if (slug != "" && slug.search(/[^-_a-zA-Z0-9]/) != -1) {
					$("#sm_edit_cell_btn").addClass("disabled");
					SM.fn.highlight($(this), "invalid");
					return false;
				}
			}
		}
	});
}

SM.behaviors.edit_description = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-description").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_description_form");
		var textareas = form.find("textarea");
		var value = SM.settings.current_cell.html();
		textareas.val("").removeAttr("checked").removeAttr("selected"); //clear form
		if (typeof value !== "undefined" && value != "") {
			try {
				var descriptions = jQuery.parseJSON(SM.settings.current_cell.html());
				$.each(descriptions, function(lang, value) {
					form.find("textarea[data-lang='" + lang + "']").val(value); //or html()?
				});
			} catch (e) {};
		}
		$("#sm_edit_description_modal, #sm_backdrop").show();
		form.find(".sm_right_tab.active textarea").select();
	});

	$("#sm_edit_description_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var form = $("#sm_edit_description_form");
		var textareas = form.find("textarea");
		var descriptions = {};
		$.each(textareas, function() {
			var lang = $(this).attr("data-lang");
			descriptions[lang] = $(this).val(); //html()?
		});
		var data = {
			action: "sm_row",
			"do": "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_object_to_json(descriptions)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			var returned = {name: "", value: ""};
			try {
				var returned = jQuery.parseJSON(response);
			} catch (e) {};
			SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_description_modal .close").click();
			$("#sm_edit_description_modal .loader").hide();
			$("#sm_edit_description_btn").show();
		});
	});

	$("#sm_edit_description_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.edit_logo_url = function() {
	var $ = jQuery;
	var file_frame;
	$(".sm_filter_table td.column-small_logo_url, .sm_filter_table td.column-large_logo_url").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		if (file_frame) {
			file_frame.open();
			return;
		}
		file_frame = wp.media.frames.file_frame = wp.media({
			title: "Sports Manager",
			button: {
				//text: "Use image"
			},
			multiple: false
		});
		file_frame.on("select", function() { //callback when an image is selected
			attachment = file_frame.state().get("selection").first().toJSON();
			var data = {
				action: "sm_row",
				"do": "edit",
				tab: SM.hash.tab,
				id: SM.settings.current_cell.attr("id"),
				value: attachment.url
			};
			$.post(SM.settings.ajax_url, data, function(response) {
				var returned = {name: "", value: ""};
				try {
					var returned = jQuery.parseJSON(response);
				} catch (e) {};
				SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
				SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			});
		});
		file_frame.open();
		return false;
	});
}

SM.behaviors.edit_players_id = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-players_id, .sm_filter_table td.column-captains_id").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_players_id_form");
		var input = form.find("input:text");
		var value = SM.settings.current_cell.html();
		input.val("").removeAttr("checked").removeAttr("selected"); //clear form
		if (typeof value !== "undefined" && value != "") {
			try {
				var players_id = jQuery.parseJSON(SM.settings.current_cell.html());
				$.each(players_id, function(i, id) {
					var item = form.find(".sm_autocomplete_item[data-value='" + id + "']").click();
				});
			} catch (e) {};
		}
		$("#sm_edit_players_id_modal, #sm_backdrop").show();
		input.select();
	});

	$("#sm_edit_players_id_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var modal = $(this).parents(".sm_modal_box");
		var added_items = modal.find(".sm_autocomplete_added_item:not(.blank)");
		var array = [];
		$.each(added_items, function() {
			array.push($(this).attr("data-value"));
		});
		var data = {
			action: "sm_row",
			"do": "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_array_to_json(array)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			var returned = {name: "", value: ""};
			try {
				var returned = jQuery.parseJSON(response);
			} catch (e) {};
			SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_players_id_modal .close").click();
			$("#sm_edit_players_id_modal .loader").hide();
			$("#sm_edit_players_id_btn").show();
		});
	});

	$("#sm_edit_players_id_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.edit_stats = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-stats").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var sport = $(this).siblings(".column-sport").attr("data-value");
		if (sport == "") {
			$("#sm_alerts_modal, #sm_backdrop_disabled").show();
			$("#sm_alerts_modal").find(".alert").html("You first need to define a sport for this scoresheet.");
		} else {
			var form = $("#sm_edit_stats_form");
			var rows = form.find("tr.stat");
			var inputs = rows.find("input:text");
			var value = SM.settings.current_cell.html();
			rows.hide();
			inputs.val("").removeAttr("checked").removeAttr("selected"); //clear form
			form.find("tr.stat.all, tr.stat." + sport).show();
			if (typeof value !== "undefined" && value != "") {
				try {
					var stats = jQuery.parseJSON(SM.settings.current_cell.html());
					$.each(stats, function(stat, value) {
						form.find("input[name='" + stat + "']").val(value);
					});
				} catch (e) {};
			}
			$("#sm_edit_stats_modal, #sm_backdrop").show();
			inputs.first().focus();
		};
	});

	$("#sm_edit_stats_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		//only include data from visible inputs!!
		var form = $("#sm_edit_stats_form");
		var rows = form.find("tr.stat").filter(":visible");
		var inputs = rows.find("input:text");
		var stats = {};
		$.each(inputs, function() {
			var name = $(this).attr("name");
			stats[name] = $(this).val();
		});
		var data = {
			action: "sm_row",
			"do": "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_object_to_json(stats, "0")
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			var returned = {name: "", value: ""};
			try {
				var returned = jQuery.parseJSON(response);
			} catch (e) {};
			SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_stats_modal .close").click();
			$("#sm_edit_stats_modal .loader").hide();
			$("#sm_edit_stats_btn").show();
		});
	});

	$("#sm_edit_stats_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.edit_infos = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-infos").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_infos_form");
		var rows = form.find("tr.info");
		var inputs = rows.find("input:text");
		var value = SM.settings.current_cell.html();
		rows.hide();
		inputs.val("").removeAttr("checked").removeAttr("selected"); //clear form
		form.find("tr.info.all, tr.info." + SM.hash.tab).show();
		if (typeof value !== "undefined" && value != "") {
			try {
				var infos = jQuery.parseJSON(SM.settings.current_cell.html());
				$.each(infos, function(info, value) {
					form.find("input[name='" + info + "']").val(value);
				});
			} catch (e) {};
		}
		$("#sm_edit_infos_modal, #sm_backdrop").show();
		inputs.filter(":visible").first().focus();
	});

	$("#sm_edit_infos_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		//only include data from visible inputs!!
		var form = $("#sm_edit_infos_form");
		var rows = form.find("tr.info").filter(":visible");
		var inputs = rows.find("input:text");
		var infos = {};
		$.each(inputs, function() {
			var name = $(this).attr("name");
			infos[name] = $(this).val();
		});
		var data = {
			action: "sm_row",
			"do": "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_object_to_json(infos)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			var returned = {name: "", value: ""};
			try {
				var returned = jQuery.parseJSON(response);
			} catch (e) {};
			SM.settings.current_cell.attr("data-value", returned.value).html(returned.name).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_infos_modal .close").click();
			$("#sm_edit_infos_modal .loader").hide();
			$("#sm_edit_infos_btn").show();
		});
	});

	$("#sm_edit_infos_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.add_row = function() {
	var $ = jQuery;
	$(".sm_add_row_btn").live("click", function() {
		var btn = $(this);
		var data = {
			action: "sm_row",
			"do": "add_new",
			tab: SM.hash.tab
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			var headers = SM.filters.filter_table.find("thead tr").first().find("th");
			var new_row = {
				filter: headers.first().attr("data-filter"),
				id: response,
				data: [response],
				columns: []
			};
			$.each(headers, function() {
				new_row.columns.push($(this).attr("data-column"));
			});
			for (i = 1; i < new_row.columns.length; i++) {
				new_row.data.push("");
			}
			if (SM.fn.is_number(response) && parseFloat(response) > 0) {
				new_row.data[new_row.data.length - 1] = '<button class="sm_delete_row_btn"><img src="' + SM.settings.SPORTSMANAGER_URL + 'images/icon_trashcan.png" /></button>';
				var i = SM.filters.filter_data.fnAddData(new_row.data, false);
				var added_row = $(SM.filters.filter_data.fnGetNodes(i));
				added_row.attr("id", new_row.filter + "-" + new_row.id).attr("data-row", new_row.id);
				added_row.find("td").each(function(i, cell) {
					$(cell).attr("id", new_row.filter + "-" + new_row.id + "-" + new_row.columns[i]).addClass("column-" + new_row.columns[i]).attr("tabindex", 1);
					if (SM.filters.filter_table.find("th.column-" + new_row.columns[i]).hasClass("required")) $(cell).addClass("required");
				});
				SM.filters.filter_data.fnResetAllFilters();
				SM.filters.filter_input.val("");
				SM.filters.filter_data.fnDraw();
				SM.filters.filter_data.fnSort([[0, "desc"]]);
				if (data.tab == "leagues") $(".sm_page_menu_item").removeClass("disabled");
				SM.fn.highlight_required();
				$("#sm_add_row_modal .close").click();
			}
		});
	});

	$(".sm_filter_table td.dataTables_empty").live("dblclick", function() {
		$("#sm_add_row_modal, #sm_backdrop").show();
	});

	$("#sm_add_row_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		$(".sm_add_row_btn").click();
	});

	$(".sm_add_rows_btn").live("click", function() {
		$("#sm_add_rows_modal, #sm_backdrop").show();
	});

	$("#sm_add_rows_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		alert("Multiple rows"); //$(".sm_add_row_btn").click();
	});
}

SM.behaviors.delete_row = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-delete").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var id = $(this).parents("tr").first().attr("data-row");
		$("#sm_delete_row_id").val(id);
		$("#sm_delete_row_modal, #sm_backdrop").show();
	});

	$("#sm_delete_row_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var data = {
			action: "sm_row",
			"do": "delete",
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
					$(".sm_page_menu_item[data-tab='" + v + "']").addClass("disabled");
				});
			}
			$("#sm_delete_row_modal .close").click();
			$("#sm_delete_row_modal .loader").hide();
			$("#sm_delete_row_btn").show();
		});
	});

	$("#sm_delete_row_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.set_session = function() {
	var $ = jQuery;
	$(".sm_change_session_btn").live("click", function() {
		$("#sm_set_session_modal, #sm_backdrop").show();
		var form = $("#sm_set_session_form");
		var selects = ["session_leagues", "session_seasons", "session_sports"];
		form.find("option:not(.blank)").remove();
		$.each(selects, function(i, k) {
			var select = form.find("select[data-select='" + k + "']");
			if (k != "" && typeof SM.settings.autocomplete.arrays[k] !== "undefined") {
				$.each(SM.settings.autocomplete.arrays[k], function(i, v) {
					var blank = select.find("option.blank").clone();
					blank.val(v.value).html(v.label).removeClass("blank");
					select.append(blank);
				});
			}
		});
		$("#sm_set_session_modal select").first().select();
	});

	$("#sm_set_session_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		var form = $("#sm_set_session_form");
		$.get(SM.settings.ajax_url, form.serialize(), function(response) {
			$("#sm_set_session_modal .close").click();
			$("#sm_set_session_modal .loader").hide();
			$("#sm_set_session_btn").show();
			SM.fn.switch_tab(unescape(SM.hash.tab));
		});
	});

	$("#sm_set_session_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.intro = function() {
	var $ = jQuery;
	if (SM.settings.intro.disabled != "disabled") {
		$("#sm_intro_modal, #sm_backdrop_disabled").show();
	}

	$(".sm_show_intro").live("click", function() {
		$("#sm_intro_modal, #sm_backdrop_disabled").show();
		return false;
	});

	$(".sm_intro_step_indicator td:not(.active)").live("click", function() {
		var i = $(this).attr("data-step");
		SM.fn.switch_intro_step(i);
		SM.fn.switch_intro_indicator(i);
	});

	$(".sm_intro_step_container .arrow").live("click", function() {
		var active = $(".sm_intro_step.active");
		if ($(this).hasClass("next") && !active.hasClass("last")) {
			var i = SM.fn.switch_intro_step("next");
		} else if ($(this).hasClass("previous") && !active.hasClass("first")) {
			var i = SM.fn.switch_intro_step("previous");
		}
		SM.fn.switch_intro_indicator(i);
		return false;
	});
}
