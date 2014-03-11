
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

				// If Error
				if( data.hasOwnProperty('errors') ){
					Object.keys(data['errors']).forEach(function(errClass){

						// Get Target
						var target = $(".error."+errClass).children();
						while( target.length ){
							target = target.children();
						}
						target.end().first().text(data['errors'][errClass]).parents(".error").show(100);
					});					
				}

				if( data.hasOwnProperty('html') ){

					// If Sent back a new Page
					history.pushState({}, null, $self.attr("action"));

					var method = Object.keys(data['jQ'])[0];
					$( data['jQ'][method] )[method]( data['html'] );

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