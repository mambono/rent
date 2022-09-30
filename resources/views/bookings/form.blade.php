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
<form class="form-horizontal ajax-submit" id="frm_Bikes" role="form" method="post" action="<?= URL::to('/') ?>/bookings/<?= $action ?>/<?= $id ?>" >
	@csrf
	<div style="color:Red" id="errors2"> </div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Name:</label></div>
		<div class="col-md-8"><?= (isset($form['short_name'])) ? $form['short_name'] : '';   ?>	</div>
	</div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Location:</label></div>
		<div class="col-md-8"><?= (isset($form['city'])) ? $form['city'] : '';   ?></div>
	</div>
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Vendor:</label></div> 
		<div class="col-md-8"><?= (isset($form['vendor_name'])) ? $form['vendor_name'] : '';   ?></div>
	</div>
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Color:</label></div>
		<div class="col-md-8"><?= (isset($form['color'])) ? $form['color'] : '';   ?></div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Hourly Cost(KES):</label></div>
		<div class="col-md-8"><?= (isset($form['hourly_cost'])) ? $form['hourly_cost'] : '';   ?></div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Size:</label></div>
		<div class="col-md-8"><?= (isset($form['size'])) ? $form['size'] : '';   ?></div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Electric:</label></div>
		<div class="col-md-8"><?= (isset($form['electric'])) ? $form['electric'] : '';   ?> 
		</div>
	</div>
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="short_name">Gear Speed:</label></div>
		<div class="col-md-8"><?= (isset($form['gear_speed'])) ? $form['gear_speed'] : '';   ?></div>
	</div>
	 
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="start_date">Start Date:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="start_date" name="start_date" value="<?= (isset($form['start_date'])) ? $form['start_date'] : '';   ?>"  placeholder="Start Date">
		</div>
	</div>
	
	
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="end_date">End Date:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="end_date" name="end_date" value="<?= (isset($form['end_date'])) ? $form['end_date'] : '';   ?>"  placeholder="End Date">
		</div>
	</div>
  <div class="form-group row">
    <div class="col-md-3"></div>
	<div class="col-md-8">
      <button type="submit" class="btn btn-default btn-primary ajax-submit"> <?= __($sbt_button)?></button>
    </div>
  </div> 
</form> 
<script type="text/javascript">  
 $(document).ready(function()
 {
    $('#start_date').datetimepicker(
	{ 
		dayOfWeekStart : 1,
		lang:'en',
		timepicker:false,
		format:'d-m-Y H:i'
	});
});
 $(document).ready(function()
 {
    $('#end_date').datetimepicker(
	{ 
		dayOfWeekStart : 1,
		lang:'en',
		timepicker:false,
		format:'d-m-Y H:i'
	});
});


</script>  