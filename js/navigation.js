/**
 * behaviors
 */
/* navigation */
SM.behaviors.menu = function() {
	var $ = jQuery;
	$(".sm_page_menu_item:not(.active)").live("click", function() {
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

	$(".sm_left_menu_item:not(.active)").live("click", function() {
		var tab = $(this).attr("data-tab");
		$(".sm_left_menu_item").removeClass("active");
		$(this).addClass("active");
		$(".sm_right_tab").removeClass("active");
		$(".sm_right_tab[data-tab='" + tab + "']").addClass("active");
		$(".sm_right_tab.active [tabindex='1']").focus();
	});
}

SM.behaviors.navigation = function() {
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
