<script type="text/javascript">
	jQuery(document).ready(function()
	{
		$('#frm_Bikes').validate({

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

				short_name:{
					required:true,
				}, 
 
			},
			messages:{
		 
				short_name :"Please specify Name", 
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
<div style="margin-top:18px;" id="Bikes-table-div">
</div>
<br/>
<form class="form-horizontal ajax-submit" id="frm_Bikes" role="form" method="post" action="<?= URL::to('/') ?>/bikes/<?= $action ?>/<?= $id ?>" >
	@csrf
	<div style="color:Red" id="errors2"> </div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Name:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="short_name" name="short_name" value="<?= (isset($form['short_name'])) ? $form['short_name'] : '';   ?>"  placeholder="Name">
		</div>
	</div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Location:</label></div>
		<div class="col-md-8">{!! Form::select('city_id', $cities, $city_id, ['id' => 'city_id', 'class' => 'form-control', "placeholder" => 'Please select Location'])  !!}</div>
	</div>
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Vendor:</label></div> 
		<div class="col-md-8">{!! Form::select('vendor_id', $vendors, $vendor_id, ['id' => 'vendor_id', 'class' => 'form-control', "placeholder" => 'Please select Vendor'])  !!}</div>
	</div>
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Color:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="color" name="color" value="<?= (isset($form['color'])) ? $form['color'] : '';   ?>"  placeholder="Color">
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Hourly Cost(KES):</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="hourly_cost" name="hourly_cost" value="<?= (isset($form['hourly_cost'])) ? $form['hourly_cost'] : '';   ?>"  placeholder="Hourly Cost">
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Size:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="size" name="size" value="<?= (isset($form['size'])) ? $form['size'] : '';   ?>"  placeholder="Size">
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Electric:</label></div>
		<div class="col-md-8">
		 <select id="electric" name="electric" class="form-control" >  
			<option value="0" <?php if($electric == '0'){echo 'selected';}  
			?>>NO</option>
			<option value="1" <?php if($electric == '1'){echo 'selected';}  
			?>>YES</option> 
	   </select>
			    
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Gear Speed:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="gear_speed" name="gear_speed" value="<?= (isset($form['gear_speed'])) ? $form['gear_speed'] : '';   ?>"  placeholder="Gear Speed">
		</div>
	</div>
	 
	
  <div class="form-group row">
    <div class="col-md-3"></div>
	<div class="col-md-8">
      <button type="submit" class="btn btn-default btn-primary ajax-submit"> <?= __($sbt_button)?></button>
    </div>
  </div> 
</form> 