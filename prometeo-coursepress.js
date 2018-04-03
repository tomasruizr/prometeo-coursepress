
/**************************************************/
/*Pirate Form Contact Rescue*/
/**************************************************/
$('label[for=pirate-forms-attachment]').ready(function() {
	$('label[for=pirate-forms-attachment]').text("Adjuntar archivo:");
})



$("#div-browse-courses").ready(function () {
	var $temas = getQueryVariable("tema[]");
	var $tipos = getQueryVariable("tipo[]");
	if ($tipos.length){
		$("#select-tipo").val($tipos[0]);
		// show_browse_courses(true);
		// $("#squaredCheck-tipo-todos").prop("checked", false);
 	// 	todos_click("tipo", document.getElementById("squaredCheck-tipo-todos"));
		// $('#cb-group-tipo input').each(function() {
		// 	if ($(this).attr("id") == "squaredCheck-tipo-todos") return true;
		//  	else
		//  		if ($.inArray($(this).attr("value"), $tipos) >= 0){
		//  			$(this).prop("checked", true);
		//  		}
		// });
	}
	if ($temas.length){
		$("#select-tema").val($temas[0]);
		// show_browse_courses(true);
		// $("#squaredCheck-tema-todos").prop("checked", false);
 	// 	todos_click("tema", document.getElementById("squaredCheck-tema-todos"));
		// $('#cb-group-tema input').each(function() {
		// 	if ($(this).attr("id") == "squaredCheck-tema-todos") return true;
		//  	else
		//  		if ($.inArray($(this).attr("value"), $temas) >= 0){
		//  			$(this).prop("checked", true);
		//  		}
		// });
	}
})

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    var res = [];
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
          	res.push(decodeURIComponent(pair[1]));
        }
    }
    return res;
}

var img = $(".course-video .course-media-img");
img.one("load", function() {
  if(img.height > img.width) {
        img.height = '100%';
        img.width = 'auto';
    }
}).each(function() {
  if(this.complete) $(this).load();
});

function show_browse_courses(value) {
	if ($("#div-browse-courses").css("display") == "none" || value)
		$("#div-browse-courses").css("display", "inline-block");
	else
		$("#div-browse-courses").css("display", "none");
	// if ($("#div-browse-courses").css("height") != "auto")
	// 	$("#div-browse-courses").css("height", "auto");
	// else
	// 	$("#div-browse-courses").css("height", "0px");
}

function search_courses() {
	 var tipo = [];
	 var tema = [];
	 var instructores = [];
	 // if (!$("#squaredCheck-tipo-todos").prop("checked")){
		//  $('#cb-group-tipo input:checked').each(function() {
	 // 		if ($(this).attr("id") != "squaredCheck-tipo-todos")
	 //  	 	tipo.push("tipo[]="+$(this).attr('value'));
		//  });
	 // }
	 // if (!$("#squaredCheck-tema-todos").prop("checked")){
		//  $('#cb-group-tema input:checked').each(function() {
		//  	if ($(this).attr("id") != "squaredCheck-tema-todos")
		//   	tema.push("tema[]="+$(this).attr('value'));
		//  });
	 // }
	 // if (!$("#squaredCheck-instructores-todos").prop("checked")){
		//  $('#cb-group-instructores input:checked').each(function() {
		//  	if ($(this).attr("id") != "squaredCheck-instructores-todos")
		//   	instructores.push("instructor[]="+$(this).attr('value'));
		//  });
	 // }
	 if ($("#select-tipo").val() != "todos")
	 tipo.push("tipo[]="+ $("#select-tipo").val());
	 if ($("#select-tema").val() != "todos")
	 tipo.push("tema[]="+ $("#select-tema").val());
	 var res = [];
	 if (tipo.length > 0) res.push(tipo.join("&"));
	 if (tema.length > 0) res.push(tema.join("&"));
	 if (instructores.length > 0) res.push(instructores.join("&"));
	 
	 window.location.search = res.join("&"); 
}

function todos_click(entity, el) {
	// console.log($(el).prop("checked"));
	$('#cb-group-'+entity+' input').each(function() {
		if ($(el).prop("checked")){
			if (this === el){
				$(this).next().removeClass("disabled-check").next().removeClass("disabled-check-label");
			} else {
				$(this).next().addClass("disabled-check").next().addClass("disabled-check-label");
			}
		} else {
			if (this === el){
				$(this).next().addClass("disabled-check").next().addClass("disabled-check-label");
			} else {
				$(this).next().removeClass("disabled-check").next().removeClass("disabled-check-label");
			}
		}
	 });
}

//*******************************************
// WooCommerce
//*******************************************
function wc_registred_user_login() {
	$('html,body').animate({ scrollTop: 0 }, 'slow');
	$('.showlogin').click();
}





