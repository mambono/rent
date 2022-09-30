@extends('layouts.app')  
@section('content')  
 
<script type="text/javascript">
    var baseurl = '<?= URL::to('/') ?>/';
  
	jQuery(document).ready(function($)
	{ 
		 
		function validateEmail($email) {
		  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,10})?$/;
		  return ( $email.length > 0 && emailReg.test($email))
		}
		
		$("body").on('click', '.toggle-password', function() {
		  $(this).toggleClass("fa-eye fa-eye-slash");
		  var input = $("#password");
		  if (input.attr("type") === "password") {
			input.attr("type", "text");
		  } else {
			input.attr("type", "password");
		  }

		});
		 
		
		$(document).on('click', '#btn-login', function(event)
		{ 
			 
				
			if($('#login').val() == '')
			{
				new $.flavr({ content: "Please enter email", buttons: false, autoclose: true, timeout: 3000 });
				
				event.preventDefault(); 
				return false; 
			}
			if( !validateEmail($('#login').val())) 
			{ 
				new $.flavr({ content: "Please enter a valid email", buttons: false, autoclose: true, timeout: 3000 });
				event.preventDefault(); 
				return false; 
			}
			if($('#password').val() == '')
			{
				new $.flavr({ content: "Please enter password", buttons: false, autoclose: true, timeout: 3000 });
				
				event.preventDefault(); 
				return false; 
			}
			 
			if(($('#login').val() != '') && ($('#password').val() != ''))
			{
				openModal();
				var formData = {
				'email'       : $('#login').val(),
				'password'       : $('#password').val(),
				"_token": "{{ csrf_token() }}",
				}; 
			  				
				// process the form 
				$.ajax({
				type        : 'POST', 
				url         : '<?= URL::to('/')?>/login/authenticate', 
				data        : formData,
				dataType    : 'json', 
				encode      : true            
				})
				
				.done(function(data) 
				{ 
					var code = data.code;
					var text = data.text;
				   
					  
					if(code == 0)
					{
						 
						//new $.flavr({ content: data.text, buttons: false, autoclose: true, timeout: 3000 });
						closeModal();
					}
					else
					{
						var redirect_url = baseurl;
						new $.flavr({ content: data.text, buttons: false, autoclose: true, timeout: 3000 });
						closeModal();
						if(data.redirect_url && data.redirect_url.length)
						{
							redirect_url = data.redirect_url;
						}

						window.location.href = redirect_url;
					}
					 
					 
				});
			}
			event.preventDefault(); 
			return false; 
		 
		
	});

	$('body').on('submit', '.ajax-submit', function(event) {
			//openModal();
			var options = {
				dataType: 'json',
				success:    function(resp) {
 
					closeModal();
					closeModalSpinner();
					$('#default-modal').modal('hide');
					new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 });
				}
			};

			$(this).ajaxSubmit(options);
			// return false to prevent normal browser submit and page navigation 
			event.preventDefault();
			return false;

		});
 
		

});	
	
	
	
	
</script>
 
	<div class="row input-group" style="margin-left: 10px; " >
		<div class="login-page col-sm-offset-4  col-md-4"> 
				<h2>Login Here</h2> 
				 
					<div class="login-form-main">  
						<!-- Errors container -->
						<div id="errors" style="color:#f00; margin-top: 20px; margin: 0 auto;background-color: #fff;;width:70%;" class="errors-container"> 
						</div>

						<!-- Add class "fade-in-effect" for login form effect -->
						<form method="post" role="form" id="frm_login" class=" fade-in-effect ajax-submit"> 
						 <input type="hidden" name="mode" id="login_mode" value="<?PHP echo $mode; ?>"  />
						 @csrf
							<div class="g-signin2" data-onsuccess="onSignIn"></div> 
							 
							<div class="form-group">
								<label class="control-label" for="email"> </label>
								<input type="text" required="required" class="form-control" name="login" id="login" placeholder="Email" />
							</div>

							<div class="form-group">
								<label class="control-label" for="passwd"> </label>
								<input type="password" class="form-control " name="password" id="password" placeholder="Password" />
								<span toggle="#password-field"  id="bt" class="fa fa-fw fa-eye field-icon toggle-password"></span> 
							</div>

							<div class="form-group">
								<button  type="submit" id="btn-login" class="btn btn-login-info btn-icon btn-icon-standalone">  <span> Log In &nbsp;&nbsp;&nbsp;&nbsp;</span> </button> 
							</div>
							<div class="form-group ">
								<a href="<?= URL::to('/')?>/register"><span id="forgotpassword"  type="submit" class="btn btn-login-info btn-icon btn-icon-standalone ">  <span> Register Here&nbsp;&nbsp;&nbsp;&nbsp;</span> </span></a>
								 
							</div>
						</form>
					</div> 
		</div> 
	</div> 
</div>  

@endsection