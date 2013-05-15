/* autocomplete */
SM.behaviors.autocomplete = function() {
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
			if (cell.hasClass("column-players_id") || cell.hasClass("column-captains_id")) var modal = $("#sm_edit_players_id_modal");
			$.each(SM.settings.autocomplete.arrays[array], function(i, v) {
				var blank = modal.find(".sm_autocomplete_item.blank").clone();
				blank.attr("data-value", v.value).html(v.label).removeClass("blank");
				$(".sm_autocomplete_container").append(blank);
			});
			if (array == "yes_no" || array == "game_type") {
				$(".sm_autocomplete_item:not(.blank)").css("display", "inline-block");
			}
		}
	});

	$(".sm_current_input").live("keyup", function() {
		if (SM.settings.current_array != "game_type" && SM.settings.current_array != "inactive" && SM.settings.current_array != "yes_no") {
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
