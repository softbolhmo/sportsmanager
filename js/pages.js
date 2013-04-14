/* pages */
SM.behaviors.filters = function() {
	SM.fn.init_filter_tables();

	$(".sm_filter_table th").live("click", function(e) {
		var i = $(this).index();
		$(".sm_filter_table td").eq(i).focus();
	});
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
