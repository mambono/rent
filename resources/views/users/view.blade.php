@extends('layout.mainlayout') 


@section('content') 
 
  
<style type="text/css">
  #map{ width:100%; height: 700px; }
</style>
<div style="margin-left: 30px; padding-top:40px; width:80%;"> 
	<h2>User Details View</h2>  
		<div class="form-group input-group" style="color:Red" id="errors2"> </div>   
		
		<div class="form-group row">
			<div class="col-md-2"> </div>
			<div class="col-md-8">
				<a href="<?= URL::to('/') ?>/user/edit/<?= $id?>" id="users-edit-users" class="btn btn-primary">Edit</a>
			</div>
		</div> 
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="email">Email:</label></div>
			<div class="col-md-8">
				<?= (isset($form['email'])) ? $form['email'] : '';  ?> 
			</div>
		</div> 
		<div class="form-group row">
			<label class="col-md-2 control-label" for="first_name">First Name:</label>
			<div class="col-md-8">
			 <?= (isset($form['first_name'])) ? $form['first_name'] : '';  ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-2 control-label" for="last_name">Last Name:</label>
			<div class="col-md-8">
				<?= (isset($form['last_name'])) ? $form['last_name'] : '';  ?>
			</div>
		</div> 
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">ID Number:</label>
			<div class="col-md-8">  
				 <?= (isset($form['id_number'])) ? $form['id_number'] : '';  ?>
			</div> 
		</div>
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="upload_id">Uploaded ID</label></div>
			<div class="col-md-8">
				<div id="uploaded_id"><?PHP if(isset($form['id_upload'])){echo '<a href="'. URL::to('/') .'/uploads/'.$form['id_upload'].'" target="_blank">'.$form['id_upload'].'</a>';}?></div> 
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">KRA Pin Number/Certificate of Incorporation:</label>
			<div class="col-md-8">  
				 <?= (isset($form['kra_pin'])) ? $form['kra_pin'] : '';  ?>
			</div> 
		</div>
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="upload_id">Uploaded KRA Pin/Certificate of Incorporation</label></div>
			<div class="col-md-8">
				<div id="uploaded_id"><?PHP if(isset($form['pin_upload'])){echo '<a href="'. URL::to('/') .'/uploads/'.$form['pin_upload'].'" target="_blank">'.$form['pin_upload'].'</a>';}?></div> 
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">Account Number:</label>
			<div class="col-md-8">  
				 <?= (isset($form['account_number'])) ? $form['account_number'] : '';  ?>
			</div> 
		</div>
		<div class="form-group row">
			<label class="col-md-2 control-label" for="referral_code">Referral Code:</label>
			<div class="col-md-8">  
				<?= (isset($form['referral_code'])) ? $form['referral_code'] : '';  ?>
			</div> 
		</div> 
		<div class="form-group row"> 
			<div class="col-md-2">County:</div> 
			<div class="col-md-8"><?= (isset($form['county'])) ? $form['county'] : '';  ?></div> 
		</div> 
		<div class="form-group row">
			<label class="col-md-2 control-label" for="mobile_number">Mobile Number:</label>
			<div class="col-md-8">  
				<?= (isset($form['mobile_number'])) ? $form['mobile_number'] : '';  ?>
			</div> 
		</div>    
		<div class="form-group row">
			<div class="col-md-2"><label class="control-label" for="activated">Activated :</label></div>
			<div class="col-md-8">
				<?= (isset($form['activated'])) ? $form['activated'] : '';  ?> 
			</div>
		</div> 
		<div class="form-group row"> 
			<label class="col-md-2 control-label" for="location">Location:</label>  
			<div class="col-md-8"> 
				<iframe id="map" src="https://what3words.com/<?= (isset($form['what3words'])) ? $form['what3words'] : '';  ?>"></iframe>
			</div> 
		</div>   
</div> 


@endsection