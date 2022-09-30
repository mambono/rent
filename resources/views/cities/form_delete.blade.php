
	<p>Are you sure you want to delete <?//= $form['id'] ?></p>

	<form class="form-horizontal ajax-submit" role="form" method="post" action="<?= URL::to('/') ?>/cities/<?= $action ?>/<?= $id ?>" >
	  @csrf
	  <input type="hidden" id="id" name="id" value="<?= $id ?>" />
	  
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-default btn-danger "> <?= __($sbt_button)?></button>
		  &nbsp;&nbsp;
		  <a class="btn btn-default btn-success cancel-delete" href="<?= URL::to('/') ?>/cities">Cancel Delete</a>
		</div>
	  </div>
	</form>

