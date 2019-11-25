document.addEventListener("DOMContentLoaded", function() {

	$(".question-var").click(function() {
		$(this).parent().children(".question-var").removeClass("active");
		$(this).addClass("active");
		var parentId = $(this).parent().attr("id");
		var text = $(this).text();
		$('#form-' + parentId).val(text);
	});
});