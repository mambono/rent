@extends('layout.mainlayout') 


@section('content') 

<script type="text/javascript">
jQuery(document).ready(function()
{ 
	var id_uploaded = <?PHP echo json_encode($form['id_upload']);?>;	
	var kra_pin_uploaded = <?PHP echo json_encode($form['pin_upload']);?>;	
	var default_user_group_id = <?PHP echo json_encode($form['default_user_group']); ?>;

	if((id_uploaded != '') && (id_uploaded != null))
	{
		$("#blank_uploaded_id").hide();
		$("#uploaded_id").show();
	}
	else
	{
		$("#blank_uploaded_id").show();
		$("#uploaded_id").hide();
	}
	
	if((kra_pin_uploaded != '') && (kra_pin_uploaded != null))
	{
		$("#blank_uploaded_pin").hide();
		$("#uploaded_kra_pin").show();
	}
	else
	{
		$("#blank_uploaded_pin").show();
		$("#uploaded_kra_pin").hide();
	}

	updateDefaultgroup();	
	$('#usergroup_id').change(function()
	{
		var count = $("#usergroup_id :selected").length; 
		if(count > 1)
		{ 
			 $('#default_user_group_div').show();
			 updateDefaultgroup();
		}
	});
	
	
	
	$("#county_id").select2();
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

			county_id:{
				required:true,
			}, 


		},
		messages:{
	 
			county_id :"Please specify county", 
			usergroup_id :"Please specify usergroup", 
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
	
	$('body').on('click', '#delete_id', function(e)  
	{
		$("#blank_uploaded_id").show();
		$("#uploaded_id").hide();
		$("#delete_id_file").val(1);
        event.preventDefault();
        return false;
		 
	});
	
		
	$('body').on('click', '#delete_kra_pin', function(e)  
	{
		$("#blank_uploaded_pin").show();
		$("#uploaded_kra_pin").hide();
		$("#delete_kra_pin_file").val(1);
        event.preventDefault();
        return false;
		 
	});
	
	
});
</script>  
<style type="text/css">
  #map{ width:100%; height: 700px; }
</style>
<div style="margin-left: 30px; width:80%;"> 
	<h2>My Profile</h2>
	<form class="form-horizontal" id="frm_User"   enctype="multipart/form-data" role="form" method="post" action="<?= URL::to('/') ?>/profile/<?= $action ?>" >
		@csrf 
		<input type="hidden" id="delete_id_file" name="delete_id_file"value="" />
		<input type="hidden" id="delete_kra_pin_file" name="delete_kra_pin_file"value="" />
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
			<label class="col-md-2 control-label" for="id_number">ID Number:</label>
			<div class="col-md-8">  
				<input type="text" placeholder="ID Number" id="id_number" name="id_number"  value="<?= (isset($form['id_number'])) ? $form['id_number'] : '';  ?>" class="form-control"> 
				<span class="text-danger input-error">  </span>
			</div> 
		</div>
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="upload_id">Upload ID</label></div>
			<div class="col-md-8">
				<div id="uploaded_id"><?PHP if(isset($form['id_upload'])){echo '<a href="'. URL::to('/') .'/uploads/'.$form['id_upload'].'" target="_blank">'.$form['id_upload'].'</a> <a href="#" id="delete_id"><i class="fa fa-trash red-text"  style="color:#f00;" aria-hidden="true"></i></a>';}?></div> 
					<div id="blank_uploaded_id"><div class="custom-file">
						<input type="file" class="custom-file-input" id="upload_id" name="upload_file" 
						  aria-describedby="inputGroupFileAddon01">
						<label class="custom-file-label" for="upload_file">Choose file</label>
					</div></div> 
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 control-label" for="kra_pin">KRA Pin/Certificate of Incorporation:</label>
			<div class="col-md-8">  
				<input type="text" placeholder="ID KRA Pin" id="kra_pin" name="kra_pin"  value="<?= (isset($form['kra_pin'])) ? $form['kra_pin'] : '';  ?>" class="form-control"> 
				<span class="text-danger input-error">  </span>
			</div> 
		</div>
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="pin_upload">Upload KRA Pin/Certificate of Incorporation</label></div>
			<div class="col-md-8">
				<div id="uploaded_kra_pin"><?PHP if(isset($form['pin_upload'])){echo '<a href="'. URL::to('/') .'/uploads/'.$form['pin_upload'].'" target="_blank">'.$form['pin_upload'].'</a> <a href="#" id="delete_kra_pin"><i class="fa fa-trash red-text"  style="color:#f00;" aria-hidden="true"></i></a>';}?></div> 
					<div id="blank_uploaded_pin"><div class="custom-file">
						<input type="file" class="custom-file-input" id="pin_upload_file" name="pin_upload_file" 
						  aria-describedby="inputGroupFileAddon01">
						<label class="custom-file-label" for="upload_file">Choose file</label>
					</div></div> 
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 control-label" for="account_number">Account Number:</label>
			<div class="col-md-8">  
				<input type="text" placeholder="Account Number" id="account_number" name="account_number"  value="<?= (isset($form['account_number'])) ? $form['account_number'] : '';  ?>" class="form-control"> 
				<span class="text-danger input-error">  </span>
			</div> 
		</div>
		<div class="form-group row">
			<label class="col-md-2 control-label" for="referral_code">Referral Code:</label>
			<div class="col-md-8">  
				<input type="text" placeholder="Referral Code" id="referral_code" name="referral_code" class="form-control" value="<?= (isset($form['referral_code'])) ? $form['referral_code'] : '';  ?>" > 
				<span class="text-danger input-error">  </span>
			</div> 
		</div> 
		<div class="form-group row"> 
			<div class="col-md-2">{!! Form::Label('county_id', 'County:', ['class' => 'control-label']) !!}</div> 
			<div class="col-md-8">{!! Form::select('county_id', $counties, $form['county_id'], ['class' => 'form-control', "placeholder" => 'Please select County'])  !!}</div> 
		</div> 
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">Mobile Number:</label>
			<div class="col-md-8">  
				<input type="text" id="mobile_number" name="mobile_number" class="form-control"  value="<?= (isset($form['mobile_number'])) ? $form['mobile_number'] : '';  ?>" > 
				<span class="text-danger input-error">  </span>
			</div> 
		</div>  
		
		<div class="form-group row">
			<div class="offset-2 col-md-10">
				<button type="submit" class="btn btn-default btn-primary"> <?= __($sbt_button)?></button>
			</div>
		</div> 
		<div class="form-group row"> 
			<label class="col-md-2 control-label" for="location">Location:</label>  
			<div class="col-md-8"> 
				<iframe id="map" src="https://what3words.com/<?= (isset($form['what3words'])) ? $form['what3words'] : '';  ?>"></iframe>
			</div> 
		</div>  
</form> 
</div> 


@endsection