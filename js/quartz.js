
$(function(){


	$("div.header ul a.login").on("mouseover", function() {

		console.log("ready");

	});


	$(document)


	// Installer
	.on("input blur", "form.installer input[name=email]", function(e){
		var $this = $(this),
			uname = $this.parents("form").find("input[name=username]");

		//!uname.val().length ? uname.val($(this).val().split("@")[0]) : 0;
		uname.val($(this).val().split("@")[0]);
	})


	.on("submit", "form", function(e){
		e.preventDefault();

		var $self = $(this);

		$.ajax({
			type: $(this).attr("method"),
			url: $(this).attr("action"),
			data: "ajax=1&"+$self.serialize(),
			beforeSend: function(){

				// Remove All Errors
				$(".error", $self).hide();

				// Disable Submit Button to Prevent multiple Requests
				$("input[type='submit']", $self).attr('disabled', true);
			},
			success: function(data){
				console.log(data);

				// Activate Button
				$("input[type='submit']", $self).attr('disabled', false);

				// Confirm JSON Data
				try{ data = JSON.parse(data); } catch(e){ return false; }

				// If Inputs - eg. resetting password inputs
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

				// If jQuery Request
				if( data.hasOwnProperty('jQ') ){
					// For Each Method
					Object.keys(data.jQ).forEach(function(method){
						// For Each Replacement Element
						Object.keys(data.jQ[method]).forEach(function(rep){
							$(rep)[method](data.jQ[method][rep]);
						});
					});
				}

				// If New URL
				if( data.hasOwnProperty('url') ){
					history.pushState({}, null, data.url);
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