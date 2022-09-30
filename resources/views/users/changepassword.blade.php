<script type="text/javascript">
	jQuery(document).ready(function()
	{
		$.ajaxSetup({ cache: false });

		$('#frm_password').validate({

				onkeyup: false,
				onfocusout: false,
				errorElement: "div",
				errorPlacement: function(error, element) {
					error.appendTo("div#errors3");
				},
				success: function (label, element) {
					$(element).parent().removeClass('has-error');
				},

			ignore: 'input[type=hidden]',
			rules:{
				password:{
					required:true, 
					minlength : 8
				}, 
				password2:{
					required:true,
					minlength : 8,
					equalTo : "#password"
				}, 


			},
			messages:{

				password :"Password has to be more than 8 characters",
				password2 :"Confirm Password should be the same as Password and not less than 8 characters",

			}
			,
			invalidHandler: function(form, validator) {
				new $.flavr({ content: 'Please fill all required fields', buttons: false, autoclose: true, timeout: 3000 }); 
			},

			highlight: function (element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
			}
		});
		
		 $('body').on('submit', '.ajax-submit', function(event) 
		 {
			openModal();
			var options = {
				dataType: 'json',
				success:    function(resp) {

					if( resp.code == 1 )
					{
						closeModal();
						$('#default-modal .modal-footer').html('<p class="text-center text-success">' + resp.text + '</p>').fadeIn();
						new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 }); 
						$('#default-modal').modal('hide');
							 
					}
					else
					{
						closeModal();	
						$('#default-modal .modal-footer').html('<p class="text-center text-danger">' + resp.text + '</p>').fadeIn();
					}
				}
			};

			$(this).ajaxSubmit(options);
			// return false to prevent normal browser submit and page navigation 
			event.preventDefault();
			return false;

		});
		
		$('body').on('click', '#btn_changepassword', function(event) 
		{ 
			$('#btn_changepassword').prop("disabled", true);  
		
		});
	});
	
	
</script>  

<form class="form-horizontal ajax-submit" id="frm_password" role="form" method="post" action="<?= URL::to('/') ?>/user/<?= $action ?>/<?= $id ?>" >
 @csrf
<div class="form-group input-group" style="color:Red" id="errors3"> </div>
	<div class="form-group required row">
		<div class="col-md-3"><label class="control-label" for="password">New Password:</label></div>
		<div class="col-md-7">
			<input type="password" class="form-control" id="password" name="password" placeholder="New Password">
			<span class="text-danger input-error">  </span>
		</div>
	</div> 
		 	
	<div class="form-group required row">
		<div class="col-md-3"><label class="control-label" for="password">Retype Password:</label></div>
		<div class="col-md-7">
			<input type="password" class="form-control" id="password2" name="password2" placeholder="Retype Password">
		</div>
	</div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default btn-primary ajax-submit" id="btn_changepassword">Submit</button>
    </div>
  </div>
</form> 