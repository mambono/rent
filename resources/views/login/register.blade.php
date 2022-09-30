@extends('layouts.app') 


@section('content')  

 
<script type="text/javascript">
jQuery(document).ready(function()
{
  
		$('#frm_profile').validate({

				onkeyup: false,
				onfocusout: false,
				errorElement: "div",
				errorPlacement: function(error, element) {
					error.appendTo("div#errors2");
				},
				success: function (label, element) {
					$(element).parent().removeClass('has-error');
				},

				ignore: 'input[type=hidden]',
				rules:{ 
  
					email:{
						required:true,
						email: true
					},  
					reg_password:{
						required:true, 
						minlength : 8
					}, 
					password2:{
						required:true,
						minlength : 8,
						equalTo : "#reg_password"
					}, 
					first_name:{
						required:true,
					}, 
					last_name:{
						required:true,
					},    
					mobile_number:{
						required:true,
					},    
				},
				messages:{
			 
					
					email :"Please enter email &nbsp; &nbsp; ", 
					reg_password :"Password has to be more than 8 characters &nbsp; &nbsp;",
					password2 :"Confirm Password should be the same as Password and not less than 8 characters&nbsp; &nbsp; ",
					first_name :"Please enter first name&nbsp; &nbsp; ",
					last_name :"Please enter last name", 
					 
				}
				,
				invalidHandler: function(form, validator) {
					//new $.flavr({ content: 'Please fill all required fields', buttons: false, autoclose: true, timeout: 3000 }); 
				},

				highlight: function (element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function (element) {
					$(element).closest('.form-group').removeClass('has-error');
				}
		});
		
		
		$('body').on('submit', '.ajax-submit', function(event) {
			//openModal();
			var options = {
				dataType: 'json',
				success:    function(resp) {

					if( resp.code == 1 )
					{ 
						$('#reg_email').val('');
						$('#reg_password').val('');
						$('#password2').val('');
						$('#first_name').val('');
						$('#last_name').val('');   
						$('#mobile_number').val('');   
					} 
					closeModal();
					$('#default-modal').modal('hide');
					//new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 });
				}
			};

			$(this).ajaxSubmit(options);
			// return false to prevent normal browser submit and page navigation 
			event.preventDefault();
			return false;

		});
		
		 var inputs = document.querySelectorAll( '.inputfile' );
		Array.prototype.forEach.call( inputs, function( input )
		{
			var label	 = input.nextElementSibling,
				labelVal = label.innerHTML;

			input.addEventListener( 'change', function( e )
			{
				var fileName = '';
				if( this.files && this.files.length > 1 )
				{
					fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace( '{count}', this.files.length );
				}
				else
				{
					//fileName = e.target.value.split('\').pop();
				}

				if( fileName )
				{
					label.querySelector('span').innerHTML = fileName;
				}
				else
				{
					label.innerHTML = labelVal;
				}
			});
		});
		
		//input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		//input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
 
	
	$('#terms_checkbox').change(function()
	{
		if($('#terms_checkbox').prop("checked") == true)
		{  
			$('.user-form-sbt').removeClass("disabled"); 
			
		}
		else
		{ 
			$('.user-form-sbt').addClass('disabled');
		
		}
	});
	
  $('#reload').click(function () 
  {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
	
	
});
</script>
<div class=" col-md-12">
	<div id="profile_form offset-1 col-md-8"> 
		<h2>Register Here</h2>
		<form class="form-horizontal" id="frm_profile"   enctype="multipart/form-data" role="form" method="post" action="<?= URL::to('/') ?>/register/add" >
			@csrf
			<h3 class="error"><?= $msg_text ?></h3>
			 <input type="hidden" name="mode" id="mode" value="<?PHP echo $mode; ?>"  /> 
			<div class="form-group input-group offset-2" style="color:Red" id="errors2"> </div>  
			<input type="hidden" id="user_id" name="user_id" value="<?= $user['id'] ?>"> 
			
			<div class="form-group row">
				<div class="col-md-2"><label class="control-label" for="email">Email:</label></div>
				<div class="col-md-8">
					<input type="email" class="form-control" id="reg_email" value="<?PHP echo $user['email']; ?>" name="email" placeholder="Email">
					<span class="text-danger input-error">  </span>
				</div>
			</div> 
			 
			<div class="form-group row">
				<label class="col-md-2 control-label" for="password">Password:</label>
				<div class="col-md-8">
				  <input type="password" placeholder="Password" name="reg_password"  id="reg_password" class="form-control">
				   <span class="text-danger input-error">  </span>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-md-2 control-label" for="password">Confirm Password:</label>
				<div class="col-md-8">
				  <input type="password" placeholder="Confirm Password" name="password2"  id="password2" class="form-control">
				   <span class="text-danger input-error">  </span>
				</div>
			</div> 
			<div class="form-group row">
				<label class="col-md-2 control-label" for="first_name">First Name:</label>
				<div class="col-md-8">
				  <input type="text" placeholder="First Name" name="first_name" value="<?= $user['first_name'] ?>" id="first_name" class="form-control">
				   <span class="text-danger input-error">  </span>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="last_name">Last Name:</label>
				<div class="col-md-8">
				  <input type="text" placeholder="Last Name" name="last_name" value="<?= $user['last_name'] ?>" id="last_name" class="form-control">
				   <span class="text-danger input-error">  </span>
				</div>
			</div>  
			<div class="form-group row">
				<label class="col-md-2 control-label" for="mobile_number">Mobile Number:</label>
				<div class="col-md-8">
				  <input type="text" placeholder="Mobile Number" name="mobile_number" value="<?= $user['mobile_number'] ?>" id="mobile_number" class="form-control">
				   <span class="text-danger input-error">  </span>
				</div>
			</div>   
			  
			<div class="form-group row">
				<div class="offset-2 col-md-8">
					<button class="btn btn-primary user-form-sbt"  type="submit">Register</button>
				</div>
			</div>   
		</form>
	</div>  
</div>  

@endsection