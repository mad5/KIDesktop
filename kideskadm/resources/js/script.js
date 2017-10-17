$(function() {
	$(".searchinput").on("keyup", function () {
		if ($(this).val() != "") {
			$("#searchclear").fadeIn();
		} else {
			$("#searchclear").fadeOut();
		}
	});
	$("#searchclear").click(function () {
		$(".searchinput").val('');
		$(".searchinput").closest("form").submit();
	});

	if ($(".searchinput").val() != "") {
		$("#searchclear").fadeIn();
	} else {
		$("#searchclear").fadeOut();
	}
});
String.prototype.replaceAll = function(search, replacement) {
	var target = this;
	return target.split(search).join(replacement);
};