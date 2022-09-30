<script type="text/javascript">
	jQuery(document).ready(function()
	{
		$('#frm_Vendors').validate({

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

				vendor_name:{
					required:true,
				}, 
 
			},
			messages:{
		 
				vendor_name :"Please specify Name", 
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
	});
</script> 
<div style="margin-top:18px;" id="Vendors-table-div">
</div>
<br/>
<form class="form-horizontal ajax-submit" id="frm_Vendors" role="form" method="post" action="<?= URL::to('/') ?>/vendors/<?= $action ?>/<?= $id ?>" >
	@csrf
	<div style="color:Red" id="errors2"> </div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Name:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="vendor_name" name="vendor_name" value="<?= (isset($form['vendor_name'])) ? $form['vendor_name'] : '';   ?>"  placeholder="Name">
		</div>
	</div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Location:</label></div>
		<div class="col-md-8">{!! Form::select('city_id', $cities, $city_id, ['id' => 'city_id', 'class' => 'form-control', "placeholder" => 'Please select Location'])  !!}</div>
	</div>
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Owner:</label></div> 
		<div class="col-md-8">{!! Form::select('user_id', $users, $user_id, ['id' => 'user_id', 'class' => 'form-control', "placeholder" => 'Please select Vendor'])  !!}</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Address:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="address" name="address" value="<?= (isset($form['address'])) ? $form['address'] : '';   ?>"  placeholder="Address">
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Email:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="email" name="email" value="<?= (isset($form['email'])) ? $form['email'] : '';   ?>"  placeholder="Email">
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="vendor_name">Telephone Number:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="telephone_number" name="telephone_number" value="<?= (isset($form['telephone_number'])) ? $form['telephone_number'] : '';   ?>"  placeholder="Telephone Number">
		</div>
	</div>	
  <div class="form-group row">
    <div class="col-md-3"></div>
	<div class="col-md-8">
      <button type="submit" class="btn btn-default btn-primary ajax-submit"> <?= __($sbt_button)?></button>
    </div>
  </div> 
</form> 