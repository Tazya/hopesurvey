document.addEventListener("DOMContentLoaded", function() {

	$(".question-var--type-one").click(function() {
		$(this).parent().children(".question-var--type-one").removeClass("active");
		$(this).addClass("active");
		var parentId = $(this).parent().attr("id");
		var text = $(this).text();
		$('#form-' + parentId).val(text);
	});

	$(".wrapper--type-two").click(function() {
		$(this).parent().children(".wrapper--type-two").children(".question-var--type-two").removeClass("active");
		$(this).children(".question-var--type-two").addClass("active");
		var parentId = $(this).parent().attr("id");
		var text = $(this).children(".question-var").text();
		$('#form-' + parentId).val(text);
	});

	$(".question-type-three--right").click(function() {
		$(".question-type-three--button").removeClass("active");
		$(this).children(".question-type-three--button").addClass("active");
		var text = $(this).children(".question-type-three--button").attr("data-result");
		$('.form-check-input').val(text);
	});

	$(".question-var--type-six").click(function() {
		$(this).parent().children(".question-var--type-six").removeClass("active");
		$(this).addClass("active");
		var parentId = $(this).parent().attr("id");
		var text = $(this).attr("data-result");
		$('#form-' + parentId).val(text);
	});

	var $select = $('.selectize').selectize({
		persist: false,
		create: false
	});

	$(".selectize-input input").attr('readonly','readonly');

	$(".submit-button").click(function() {
		var formId = "survey-form";
		document.forms[formId].submit();
	});

});