jQuery(function($){
	$(".section_repeatable li:first").addClass("active");
	$("#section-page-content").val($("#sec_textarea").val());  //优先级很低，所以用这个方法设置。
	
	$(".repeatable-save ").on("click",function(){
		$(".active #sec_textarea").val(tinyMCE.activeEditor.getContent());
	})
	$("#post").submit(function() {
		  $(".repeatable-save ").click();
	});
	
	//add a section area.
	$(".repeatable-add").on("click",function(){
		$sectionfiled = $(this).parent().find(".section_repeatable li:last").clone();
		$sectionfiled
		.insertAfter($(this).parent().find(".section_repeatable li:last"))
		.find('.section-title span:first').text("")
		.parent().hide()
		.next().show()
		.find( ":first-child" ).val("");
		$name_title = $(".section_repeatable li:last").find(".section-title-edit :first-child").attr("name");
		$name_content = $(".section_repeatable li:last").find("textarea").attr("name");
		$new_name_title = $name_title.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;});
		$new_name_content = $name_content.replace(/(\d+)/, function(fullMatch, n) {
				return Number(n) + 1;});		
		$(".section_repeatable li:last").find(".section-title-edit :first-child").attr("name",$new_name_title).val("");
		$(".section_repeatable li:last").find("textarea").attr("name",$new_name_content).val("");
		$(".section_repeatable li:last .section-content-edit").click();
		tinyMCE.activeEditor.setContent("Please enter the new section content here.");

		return false;
	});
	
	//add edit function of title edit
	$(".section-title :first-child").live("click",function(){
		$(this).parent().hide().next().show().find("#sec_text").focus();
		$(this).parent().prev().click();
	});
	$(".section-title-save").live("click",function(){
		$new_title = $(this).prev().val();
		$(this).parent().hide().prev().show().find("span:first").text($new_title);
	});
	
	//delete item
	$(".repeatable-remove").live("click",function(){
		$(this).closest("li").remove();
	});
	
	//edit content
	$(".section-content-edit").live("click",function(){
		$(".repeatable-save ").click();		
		$(".section_repeatable li").removeClass("active");
		$(this).closest("li").addClass("active");
		tinyMCE.activeEditor.setContent($(".active #sec_textarea").val());
	});
	$("#sec_text").live("focusout",function(){
		$(this).next().click();
	});
})
