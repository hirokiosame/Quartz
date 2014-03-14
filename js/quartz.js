
$(function(){


	$("div.header ul a.login").on("mouseover", function() {

		console.log("ready");

	});


	$(document)

	.on("submit", "form", function(e){
		e.preventDefault();

		var $self = $(this);

		$.ajax({
			type: $(this).attr("method"),
			url: $(this).attr("action"),
			data: "ajax=1&"+$self.serialize(),
			beforeSend: function(){
				$(".error", $self).hide();
			},
			success: function(data){
				console.log(data);

				try{ data = JSON.parse(data); } catch(e){ return false; }


				// If Inputs
				if( data.hasOwnProperty('inputs') ){

					// I like to scope my iterations
					Object.keys(data.inputs).forEach(function(inputName){

						// Get Target
						$("input[name='"+inputName+"']", $self).val(data.inputs[inputName]);
					});
				}

				// If Error
				if( data.hasOwnProperty('errors') ){
					Object.keys(data.errors).forEach(function(errClass){

						// Get Target -- Last Child
						var target = $(".error."+errClass).children("td");
						while( target.length ){ target = target.children("td"); }

						// Using HTML instead of Text because sometimes there are links
						target.end().first().html(data.errors[errClass]).parents(".error").show(100);
					});
				}

				// If HTML
				if( data.hasOwnProperty('html') ){
					var method = Object.keys(data['jQ'])[0];
					$( data['jQ'][method] )[method]( data['html'] );
				}

				// If New URL
				if( data.hasOwnProperty('url') ){
					history.pushState({}, null, data['action']);
				}
			}
		});
	});


	// HTML5 History
	//$("a").on("click", function(e){
	//	e.preventDefault();
	//	history.pushState({}, "page 2", "bar.html");
	//});

});