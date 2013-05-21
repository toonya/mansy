jQuery(function($){
/* 	alert("test"); */
	$("#section-content .section_repeatable").children().not(":first").hide();
	$("#section-title li").click(function(){
		$tar = $(this).index();
		$("#section-content .section_repeatable").children().hide().eq($tar).show();
			autoSetHeight();
	})
})