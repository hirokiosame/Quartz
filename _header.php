<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Quartz</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Favicon? -->

		<link rel="stylesheet/less" type="text/css" href="/css/style.less" />
		<script src="/js/less-1.6.3.min.js"></script>
		<script src="/js/jquery-2.1.0.min.js"></script>


		<script type="text/javascript">
		$(function(){


			$("div.header ul a.login").on("mouseover", function() {

				console.log("ready");

			});


			$(document)

			.on("submit", "form", function(e){
				e.preventDefault();

				alert("prevented");
				var $self = $(this);

				$.ajax({
					type: $(this).attr("method"),
					url: $(this).attr("action"),
					data: "ajax=1&"+$self.serialize(),
					success: function(data){
						alert("DAta rec");
						history.pushState({}, null, $self.attr("action"));

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
		</script>
	</head>
	<body>
		<!--[if lt IE 8]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

		<div class="header">
			<a class="header-title" href="">Quartz</a>

			<ul>
				<li><a href="#">About</a></li>
				<li><a href="#">Contact</a></li>
				<li><a class="login" href="#">Login</a></li>
				<li class="login">
					<form action="/install.php?step2" method="post">
						<input type="email" placeholder="Email">
						<input type="password" placeholder="Password">
						<input type="submit" value="&#8594;">
					</form>
				</li>
			</ul>
		</div>


