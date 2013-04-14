/*
 * behaviors
 */
SM.behaviors.menu = function() {
	var $ = jQuery;
	$(".sm_page_menu_item").live("click", function() {
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
			if (SM.fn.is_number(response)) {
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
				$("#sm_add_row_modal .close").click();
			}
		});
	});
}

SM.behaviors.filter_autocomplete = function() {
	var $ = jQuery;
	$(".sm_filter_table td:not(.column-id, .column-delete, .dataTables_empty)").live("dblclick", function() {
		var cell = $(this);
		SM.settings.current_array = "";
		$.each(SM.settings.autocomplete.map, function(k, v) {
			if (cell.hasClass("column-" + k)) {
				SM.settings.current_array = v;
			}
		});
		var array = SM.settings.current_array;
		$(".sm_autocomplete_item:not(.blank), .sm_autocomplete_added_item:not(.blank)").remove();
		if (array != "" && typeof SM.settings.autocomplete.arrays[array] !== "undefined") {
			var modal = $("#sm_edit_cell_modal");
			if (cell.hasClass("column-players_id")) var modal = $("#sm_edit_players_id_modal");
			$.each(SM.settings.autocomplete.arrays[array], function(k, v) {
				var blank = modal.find(".sm_autocomplete_item.blank").clone();
				blank.attr("data-value", v.value).html(v.label).removeClass("blank");
				$(".sm_autocomplete_container").append(blank);
			});
			if (array == "yes-no") {
				$(".sm_autocomplete_item:not(.blank)").css("display", "inline-block");
			};
		}
	});

	$(".sm_current_input").live("keyup", function() {
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

	$(".sm_autocomplete_item:not(.added)").live("click", function() {
		var form = $(this).parents("form");
		if ($(this).hasClass("sm_add_it")) {
			$(this).addClass("added");
			var blank = form.find(".sm_autocomplete_added_item.blank").clone();
			blank.attr("data-value", $(this).attr("data-value")).html($(this).html()).removeClass("blank");
			$(".sm_autocomplete_added_container").append(blank);
		} else {
			var value = $(this).attr("data-value");
			form.find(".sm_current_input").val(value).focus();
		};
	});

	$(".sm_autocomplete_item.added").live("click", function() {
		var form = $(this).parents("form");
		var value = $(this).attr("data-value");
		form.find(".sm_autocomplete_added_item[data-value='" + value + "']").remove();
		$(this).removeClass("added");
	});
}

SM.behaviors.filter_modals = function() {
	var $ = jQuery;

	//edit_cell modal
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

	//edit_stats modal
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

	//edit_players_id modal
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

	//add_row modal
	$(".sm_filter_table td.dataTables_empty").live("dblclick", function() {
		$("#sm_add_row_modal, #sm_backdrop").show();
	});

	$("#sm_add_row_btn").live("click", function() {
		$(this).hide();
		$(this).siblings(".loader").show();
		$(".sm_add_row_btn").click();
	});

	//delete_row modal
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
					$(".sm_page_menu a[data-tab='" + v + "']").addClass("disabled");
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

	//set_session modal
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

	//all modals
	$(".sm_modal_box .close").live("click", function() {
		SM.fn.reset_form($(".sm_modal_box form"));
		var parent = $(this).parents(".sm_modal_box");
		parent.hide();
		if ($(".sm_modal_box:visible").length == 0) {
			if (parent.hasClass("sm_modal_alerts")) {
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

SM.behaviors.home_settings = function() {
	var $ = jQuery;
	$("#sm_save_options_form").live("submit", function () {
		var form = $(this);
		var data = {
			action: "sm_db",
			do: "set_options",
			value: form.serialize()
		};
		$.get(SM.settings.ajax_url, data, function(response) {
			if (response == "") {
				$("#sm_options_ajax_return").html("Options have been saved!");
				SM.fn.highlight(form, "sm_current_cell");
			} else {
				$("#sm_options_ajax_return").html("Error: " + response);
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
	$("#sm_import_type").live("change", function() {
		var type = $("select option:selected").val();
		$(".sm_import_type_info").removeClass("active");
		$(".sm_import_type_info[data-type='" + type + "']").addClass("active");
	});

	$(".sm_import_blanks a.disabled").live("click", function() {
		return false;
	});

	$(".sm_import_upload_file").live("click", function() {
		window.send_to_editor = function(html) {
			var url = $(html).attr("href");
			$("#sm_import_file_url").val(url);
			tb_remove();
		}
		tb_show('', SM.settings.WP_ADMIN_URL + 'media-upload.php?type=image&tab=type&TB_iframe=true');
		return false;
	});

	$("#sm_import_form").live("submit", function() {
		var form = $(this);
		if ($("#sm_import_type").val() == "") {
			$("#sm_import_type").focus();
		} else if ($("#sm_import_file_url").val() == "" || $("#sm_import_file_url").val().slice(-4) != ".csv") {
			$("#sm_import_file_url").focus();
		} else if ($("#sm_import_delimiter").val() == "") {
			$("#sm_import_delimiter").focus();
		} else {
			$("#sm_import_btn").hide();
			var data = {
				action: "sm_import",
				do: $("#sm_import_type").val(),
				value: form.serialize()
			};
			$.get(SM.settings.ajax_url, data, function(response) {
				if (response == "") {
					$("#sm_import_ajax_return").html("Data added!").show();
					SM.fn.highlight(form, "sm_current_cell");
				} else {
					$("#sm_import_ajax_return").html("Error: " + response).show();
				};
			});
		};
		return false;
	});
}

SM.behaviors.intro = function() {
	var $ = jQuery;
	if (SM.settings.intro.disabled != "disabled") {
		$("#sm_plugin_intro_modal").show();
	}

	$(".sm_show_intro").live("click", function() {
		$("#sm_plugin_intro_modal").show();
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

SM.behaviors.navigation = function() {
	var $ = jQuery;
	$(".sm_left_menu_item:not(.active)").live("click", function() {
		var tab = $(this).attr("data-tab");
		$(".sm_left_menu_item").removeClass("active");
		$(this).addClass("active");
		$(".sm_right_tab").removeClass("active");
		$(".sm_right_tab[data-tab='" + tab + "']").addClass("active");
	});
}
