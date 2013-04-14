/* modals */
SM.behaviors.modals = function() {
	var $ = jQuery;
	$(".sm_modal_box .close").live("click", function() {
		SM.fn.reset_form($(".sm_modal_box form"));
		var parent = $(this).parents(".sm_modal_box");
		parent.hide();
		if ($(".sm_modal_box:visible").length == 0) {
			if (parent.hasClass("sm_modal_alerts") || parent.hasClass("sm_modal_intro")) {
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
	$(".sm_filter_table td:not(.column-id, .column-stats, .column-players_id, .column-delete, .dataTables_empty)").live("dblclick", function() {
		SM.settings.current_cell = $(this);
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
			value: $("#sm_current_input-edit_cell").val()
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			SM.settings.current_cell.html(response).focus();
			SM.fn.highlight(SM.settings.current_cell, "sm_current_cell");
			$("#sm_edit_cell_modal .close").click();
			$("#sm_edit_cell_modal .loader").hide();
			$("#sm_edit_cell_btn").show();
		});
	});

	$("#sm_edit_cell_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.edit_stats = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-stats").live("dblclick", function() {
		var sport = $(this).siblings(".column-sport").html();
		if (sport != "") {
			SM.settings.current_cell = $(this);
			var form = $("#sm_edit_stats_form");
			var rows = form.find("tr.stat");
			var inputs = rows.find("input:text");
			var value = SM.settings.current_cell.html();
			rows.hide();
			inputs.val("").removeAttr("checked").removeAttr("selected"); //clear form
			form.find("tr.stat.all, tr.stat." + sport).show();
			if (typeof value !== "undefined" && value != "") {
				var stats = jQuery.parseJSON(SM.settings.current_cell.html());
				if (typeof stats === "object") {
					$.each(stats, function(stat, value) {
						form.find("input[name=" + stat + "]").val(value);
					});
				}
			}
			$("#sm_edit_stats_modal, #sm_backdrop").show();
			inputs.first().focus();
		} else {
			$("#sm_alerts_modal, #sm_backdrop_disabled").show();
			$("#sm_alerts_modal").find(".alert").html("You first need to define a sport for this scoresheet.");
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
			$("#sm_edit_stats_modal .close").click();
			$("#sm_edit_stats_modal .loader").hide();
			$("#sm_edit_stats_btn").show();
		});
	});

	$("#sm_edit_stats_form").live("submit", function() {
		return false;
	});
}

SM.behaviors.edit_players_id = function() {
	var $ = jQuery;
	$(".sm_filter_table td.column-players_id").live("dblclick", function() {
		SM.settings.current_cell = $(this);
		var form = $("#sm_edit_players_id_form");
		var input = form.find("input:text");
		var value = SM.settings.current_cell.html();
		input.val("").removeAttr("checked").removeAttr("selected"); //clear form
		if (typeof value !== "undefined" && value != "") {
			var players_id = jQuery.parseJSON(SM.settings.current_cell.html());
			if (typeof players_id === "object") {
				$.each(players_id, function(i, id) {
					var item = form.find(".sm_autocomplete_item[data-value='" + id + "']").click();
				});
			}
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
			do: "edit",
			tab: SM.hash.tab,
			id: SM.settings.current_cell.attr("id"),
			value: SM.fn.return_array_to_json(array)
		};
		$.post(SM.settings.ajax_url, data, function(response) {
			SM.fn.load_autocomplete();
			SM.settings.current_cell.html(response);
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

SM.behaviors.add_row = function() {
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
				});
				SM.filters.filter_data.fnResetAllFilters();
				SM.filters.filter_input.val("");
				SM.filters.filter_data.fnDraw();
				SM.filters.filter_data.fnSort([[0, "desc"]]);
				if (data.tab == "leagues") $(".sm_page_menu_item").removeClass("disabled");
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
		$("#sm_plugin_intro_modal, #sm_backdrop_disabled").show();
	}

	$(".sm_show_intro").live("click", function() {
		$("#sm_plugin_intro_modal, #sm_backdrop_disabled").show();
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
