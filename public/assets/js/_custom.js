document.addEventListener("DOMContentLoaded", function() {

	$(".question-var").click(function() {
		$(this).parent().children(".question-var").removeClass("active");
		$(this).addClass("active");
	});
});
