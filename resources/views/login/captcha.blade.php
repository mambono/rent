 

<style type="text/css">
  #map{ width:700px; height: 500px; }
</style> 
<link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function()
{
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
	
	  $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: '<?= URL::to('/') ?>/captchaservice/reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
});
</script>
	<div id="profile_form  col-md-12"> 
	<h2>Register Here</h2> 
	<form class="form-horizontal" id="frm_profile"   enctype="multipart/form-data" role="form" method="post" action="{{url('captcha-validation')}}">
		@csrf
		 
		<div class="form-group input-group" style="color:Red" id="errors2"> </div>  
		 @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />
        @endif 
		 
		<div class="form-group row">
			<label class="col-sm-4 control-label" for="mobile_number">Enter Captcha:</label>
			<div class="col-md-4">  
				<input type="text" placeholder="Enter Captcha" id="captcha" name="captcha" class="form-control" > 
				<span class="text-danger input-error">  </span>
			</div> 
			<div class="col-md-4">
				<div class="captcha">
                      <span>{!! captcha_img() !!}{!! Captcha::img() !!}{!!captcha_src() !!}</span>
                    <button type="button" class="btn btn-danger" class="reload" id="reload">
                        &#x21bb;
                    </button>
                </div>
			</div>
		</div>	 
		 
		<div class="form-group row">
			<div class="col-sm-offset-4 col-md-8">
				<button class="btn btn-primary user-form-sbt "  type="submit">Register</button>
			</div>
		</div>   
	</form>
</div> 




<script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

</script>

</html>