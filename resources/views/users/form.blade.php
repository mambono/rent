 
<script type="text/javascript">
jQuery(document).ready(function()
{ 
	    
	
	 
	$("#usergroup_id").select2(); 
	$('#frm_User').validate({

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

			 


		},
		messages:{
	  
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
	  
	
});
</script>  
<style type="text/css">
  #map{ width:100%; height: 700px; }
</style>
<div style="margin-left: 30px; width:80%;"> 
	<h2>User Management</h2>
	<form class="form-horizontal ajax-submit" id="frm_User"   role="form" method="post" action="<?= URL::to('/') ?>/user/<?= $action ?>/<?= $id ?>" >
		@csrf  
		<div class="form-group input-group" style="color:Red" id="errors2"> </div>   
		
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="email">Email:</label></div>
			<div class="col-md-8">
				<input type="email" class="form-control" id="reg_email" value="<?PHP echo $form['email']; ?>" name="email" placeholder="Email">
				<span class="text-danger input-error">  </span>
			</div>
		</div>  
		<div class="form-group row">
			<label class="col-md-2 control-label" for="first_name">First Name:</label>
			<div class="col-md-8">
			  <input type="text" placeholder="First Name" name="first_name" value="<?= (isset($form['first_name'])) ? $form['first_name'] : '';  ?>" id="first_name" class="form-control">
			   <span class="text-danger input-error">  </span>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-2 control-label" for="last_name">Last Name:</label>
			<div class="col-md-8">
			  <input type="text" placeholder="Last Name" name="last_name" value="<?= (isset($form['last_name'])) ? $form['last_name'] : '';  ?>" id="last_name" class="form-control">
			   <span class="text-danger input-error">  </span>
			</div>
		</div>  
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">Mobile Number:</label>
			<div class="col-md-8">
			  <input type="text" placeholder="Mobile Number" name="mobile_number" value="<?= $form['mobile_number'] ?>" id="mobile_number" class="form-control">
			   <span class="text-danger input-error">  </span>
			</div>
		</div>   
		<div class="form-group row"> 
			<div class="col-md-2"><label class="control-label" for="website">User Group:</label></div>  
			<div class="col-md-8"> {!! Form::select('group_id', $usergroup_list, $group_id, ['id' => 'group_id', 'class' => 'form-control', "placeholder" => 'Please select Group'])  !!}</div>  
		</div>   
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="delete_flag">Deleted :</label></div>
			<div class="col-md-8"> 
				<select id="delete_flag" name="delete_flag" class="form-control" >  
					<option value="0" <?php if($form['delete_flag'] == '0'){echo 'selected';}  
					?>>NO</option>
					<option value="1" <?php if($form['delete_flag'] == '1'){echo 'selected';}  
					?>>YES</option> 
			   </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="offset-2 col-md-10">
				<button type="submit" class="btn btn-default btn-primary ajax-submit"> <?= __($sbt_button)?></button>
			</div>
		</div>   
</form> 
</div> 
 