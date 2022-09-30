<script type="text/javascript">
	jQuery(document).ready(function()
	{
		$('#frm_Cities').validate({

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

				city:{
					required:true,
				}, 
 
			},
			messages:{
		 
				city :"Please specify city", 
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
<div style="margin-top:18px;" id="Cities-table-div">
</div>
<br/>
<form class="form-horizontal ajax-submit" id="frm_Cities" role="form" method="post" action="<?= URL::to('/') ?>/cities/<?= $action ?>/<?= $id ?>" >
	@csrf
	<div style="color:Red" id="errors2"> </div> 
	<div class="form-group required row"> 
		<div class="col-md-3"><label class="control-label" for="city">City:</label></div>
		<div class="col-md-8">
		 <input type="text" class="form-control" id="city" name="city" value="<?= (isset($form['city'])) ? $form['city'] : '';   ?>"  placeholder="City">
		</div>
	</div> 
  <div class="form-group row">
    <div class="col-md-3"></div>
	<div class="col-md-8">
      <button type="submit" class="btn btn-default btn-primary ajax-submit"> <?= __($sbt_button)?></button>
    </div>
  </div> 
</form> 