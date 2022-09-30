@extends('layout.mainlayout') 


@section('content') 
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		
		$("#status").select2();
		$("#status").select2();
		$("#error_id").select2(); 
		$("#approved_sitevisits_id").select2();
		$("#sitevisits_id").select2();
		$('#frm_Processs').validate({

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

				comments:{
					required:true,
				}, 
				status:{
					required:true,
				}, 
 
 
			},
			messages:{
		 
				comments :"Please specify comments", 
				status :"Please specify status", 
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
		
	function update_sitevisits_table()
	{

		$("#site_visits").html('Loading...');

		$.getJSON( "<?= URL::to('/') ?>/installers/sitevisits/<?= $id ?>", function( data ) {

			populate_sitevisits_table(data);

		});

	}
	
	function update_site_inspections_table(sitevisits_id)
	{

		$("#site_inspections").html('Loading...');

		$.getJSON( "<?= URL::to('/') ?>/installers/siteinspections/siteinspections_"+sitevisits_id, function( data ) {

			populate_site_inspections_table(data);

		});

	}
	  
	
	function populate_site_inspections_table(data)
	{
		var html  = '';
		var process = "<?= (isset($process)) ? $process : '';   ?>";
		if(data.code == 0 ) 
		{
			html = '<div class="panel panel-danger"><div class="panel-body" style="color:#f00;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' + data.text + '</div>';
			$("#site_inspections").html(html); 
			new $.flavr({ content: data.text, buttons: false, autoclose: true, timeout: 3000 }); 
		}
		else
		{ 
			if(data.length > 0)
			{  
				html  += '<table id="site_inspections-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> ';
				html += '<th width="0%"># </th>'; 
				html += '<th width="20%">Question </th>'; 
				html += '<th width="60%">Response </th>'; 
				html += '<th width="20%">Response Date </th>'; 
				html += '</tr> <tbody>';
				


				$.each( data, function( key, val )
				{

					html += '<tr class="item" tabletype="site_visits">'				
					html += '<td>' + val.question_order + '</td>'; 		
					html += '<td>' + val.question + '</td>'; 
					html += '<td>' + val.answer_value + '</td>';  
					html += '<td>' + val.created_on + '</td>'; 
					html += '</tr>';
				});
		  
				
				html += '</tbody></table> <br/>'; 

				
				$("#site_inspections").html(html);
				$('#site_inspections-table').dataTable({
					'aaSorting':[[0,'asc']],
					"searching": false,
					'aoColumnDefs': [{
						'bSortable': false,
						'aTargets': [0] /* 1st one, start by the right */
					}], 
				});
				$("#site_inspections").show();
				$("#siteinspection_form").hide();
			}
			else
			{
				if(process == 'disabled')
				{  
					new $.flavr({ content: 'No Data was retrieved.', buttons: false, autoclose: true, timeout: 3000 }); 
					$("#site_inspections").html('<div class="panel panel-danger"><div class="panel-body" style="color:#f00;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No Data was retrieved.</div>');
				}
				else
				{
					$('#frm_SiteInspection').trigger("reset");
					$("#siteinspection_form").show();
					$("#site_inspections").hide();
				}
				
				
			}
		}
	}
	
	function populate_sitevisits_table(data)
	{
		var html  = '';
		var process = "<?= (isset($process)) ? $process : '';   ?>";
		if(data.code == 0 ) 
		{
			html = '<div class="panel panel-danger"><div class="panel-body" style="color:#f00;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' + data.text + '</div>';
			$("#site_visits").html(html); 
			new $.flavr({ content: data.text, buttons: false, autoclose: true, timeout: 3000 }); 
		}
		else
		{ 
			  
			html  += '<table id="site_visits-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> ';
			 
			html += '<th width="30%">Visit Date </th>'; 
			html += '<th width="60%">Comments </th>'; 
			html += '<th width="10%">Cancel </th>'; 
			html += '</tr> <tbody>';
			
 

			$.each( data, function( key, val )
			{

				html += '<tr class="item" tabletype="site_visits">'		
				html += '<td>' + val.visit_date + '</td>';  
				html += '<td>' + val.comments + '</td>'; 
				html += '<td><i class="fa fa-trash cancel-visit" cancel_id="' + val.id + '"  style="color:#f00;" aria-hidden="true"></i></td>'; 
				html += '</tr>';
			});
	  
			
			html += '</tbody></table> <br/>'; 

			
			$("#site_visits").html(html);
			$('#site_visits-table').dataTable({
				'aaSorting':[[0,'asc']],
				"searching": false,
				'aoColumnDefs': [{
					'bSortable': false,
					'aTargets': [0] /* 1st one, start by the right */
				}], 
			});  
		}
	}
	
	function populate_sitevisits_select()
	{
		$('#sitevisits_id').select2('data', null);
		var dropdown = document.getElementById("sitevisits_id");
		dropdown.options.length = 0;
		 
		$.ajax({
			type: "GET",
			url: "<?= URL::to('/') ?>/installers/sitevisits/<?= $id ?>",
			contentType: "application/json",
			 dataType: "json",
			success: function(data, status) 
			{
				var option = document.createElement("option");
				option.text = '--Please Select--';
				option.value = '';
				dropdown.add(option); 
				$.each( data, function( key, val ) 
				{
					var option = document.createElement("option");
					option.text = val.visit_date;
					option.value = val.id;
					dropdown.add(option);
				});
				
				
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{ 
				//alert("Status: " + textStatus); alert("Error: " + errorThrown); 
			}  
			
				
		});
		
	}
	
	function populate_approved_sitevisits_select()
	{
		<?PHP if((Session::get('usergroupname') == 'County Ambassador')  && ($process != 'disabled'))
		{?> 
		$('#approved_sitevisits_div').show();
		$('#approved_sitevisits_id').select2('data', null);
		var dropdown = document.getElementById("approved_sitevisits_id");
		dropdown.options.length = 0;
		 
		$.ajax({
			type: "GET",
			url: "<?= URL::to('/') ?>/installers/sitevisits/<?= $id ?>",
			contentType: "application/json",
			 dataType: "json",
			success: function(data, status) 
			{
				var option = document.createElement("option");
				option.text = '--Please Select--';
				option.value = '';
				dropdown.add(option); 
				$.each( data, function( key, val ) 
				{
					if(val.answered ==1)
					{
						var option = document.createElement("option");
						option.text = val.visit_date;
						option.value = val.id;
						dropdown.add(option);
					}
				});
				
				$("#approved_sitevisits_id").select2();
				
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{ 
				//alert("Status: " + textStatus); alert("Error: " + errorThrown); 
			}  
			
				
		});
		<?PHP } ?>
		
	}
	 
	$('#btn-visit_date').click(function(e) 
	{
        e.preventDefault();  
		e.stopPropagation()
		var post_data = 1  ;
		var visit_date = $('#visit_date').val();
		if(visit_date == '')   
		{ 
			post_data = 0;
			post_error = 'Please specify the Site Visit Date';  
		}
		if(post_data == 1)
		{ 
			 $.ajax({
					url: "<?= URL::to("/") ?>/installers/addsitevisit/<?= $id ?>",
					method: "POST",
					data: {
						'visit_date'	: $('#visit_date').val(),
						'comments'		: $('#site_visit_comments').val(),
						'_token'		: "{{ csrf_token() }}",
					},
					dataType: 'json',
					success: function(resp) 
					{
						new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 });  
						update_sitevisits_table();
						populate_sitevisits_select();
						populate_approved_sitevisits_select();
						$("#visit_date").val('');   
						$("#site_visit_comments").val('');   
					}
			});
			 
	         
		}
		else
		{
			//toastr.error('Please select a file to  upload', 'Error');
			 
			if(post_error != '')
			{ 
				new $.flavr({ content: post_error, buttons: false, autoclose: true, timeout: 3000 }); 
			} 
			
		}  
	});
	 
	$('body').on('click', '.cancel-visit', function(e)  
	{
	 
        e.preventDefault();  
		e.stopPropagation();
		var id = $(this).attr('cancel_id');
		 $.ajax( "<?= URL::to('/') ?>/installers/cancelvisit/"+id )
            .done(function(resp) {
                
				//new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 });   
				update_sitevisits_table();  
                
            })
            .fail(function() {
               // body = 'Error deleting site visit'; 
				new $.flavr({ content: 'Error deleting site visit', buttons: false, autoclose: true, timeout: 3000 }); 
            });
        event.preventDefault();
        return false;
		 
	});
	
	$('#sitevisits_id').change(function()
	{	
		var sitevisits_id = $("#sitevisits_id").val(); 
		$("#record_id").val(sitevisits_id);
		update_site_inspections_table(sitevisits_id); 
		
	});
	
	
	 $( "#frm_Processs").submit(function( event ) 
	 {
		openModal(); 
		$('#process-registration').prop("disabled", true);  
	});

	update_sitevisits_table();
	populate_sitevisits_select();
	populate_approved_sitevisits_select();
	
	$('body').on('submit', '.ajax-submit', function(event) 
	{
		openModal();
        var options = {
            dataType: 'json',
            success:    function(resp) 
			{
				closeModal(); 
                if( resp.code == 1 )
                {
                    $('#default-modal .modal-footer').html('<p class="text-center text-success">' + resp.text + '</p>').fadeIn();
                        if(resp.action == 'process')
                        {
                           var sitevisits_id = $("#sitevisits_id").val();
						   update_site_inspections_table(sitevisits_id);
                            
                        }
                        else
                        {
                            new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 }); 
                            $('#default-modal').modal('hide');
                        }
						populate_sitevisits_select();
						populate_approved_sitevisits_select();

                }
                else
                {
                    $('#default-modal .modal-footer').html('<p class="text-center text-danger">' + resp.text + '</p>').fadeIn();;
                }
            }
        };

        $(this).ajaxSubmit(options);
        // return false to prevent normal browser submit and page navigation 
        event.preventDefault();
        return false;

    });
	
	
		 
});
</script> 
 <ul class="nav nav-tabs">
	<li ><a class="active" data-toggle="tab" href="#rep-Approvals"><strong>Approval</strong></a></li>
	<li ><a data-toggle="tab" href="#rep-Visits"><strong>Site Visits</strong></a></li> 
	<li ><a data-toggle="tab" href="#rep-Inspections"><strong>Site Inspections</strong></a></li> 
</ul>
	<div class="tab-content" style="height: 80%; margin-left:20px;"> 
		<div id="rep-Approvals" class="tab-pane fade active in show">  
			<?PHP   if($process == '') {   ?>
			<form class="form-horizontal" id="frm_Processs" role="form" method="post" action="<?= URL::to('/') ?>/installers/<?= $action ?>/<?= $id ?>" >
				@csrf
				<div style="color:Red" id="errors2"> </div> 
				<div class="form-group row"> 
					 <div class="col-md-3"><label class="control-label" for="comments">Installer:</label></div>
					<div class="col-md-8">
						<?= (isset($form['full_name'])) ? $form['full_name'] : '';   ?>
					</div>
				</div>  
				<div class="form-group row"> 
					 <div class="col-md-3"><label class="control-label" for="comments">Registration Date:</label></div>
					<div class="col-md-8">
						<?= (isset($form['created_on'])) ? $form['created_on'] : '';   ?>
					</div>
				</div> 
				<div class="form-group row"> 
					 <div class="col-md-3"><label class="control-label" for="comments">County:</label></div>
					<div class="col-md-8">
						<?= (isset($form['county'])) ? $form['county'] : '';   ?>
					</div>
				</div>
				<div class="form-group required row"> 
					 <div class="col-md-3"><label class="control-label" for="comments">Comments:</label></div>
					<div class="col-md-8">
					 <textarea class="form-control" id="comments" name="comments"  <?= (isset($process)) ? $process : '';   ?> placeholder="Comments"><?= (isset($form['comments'])) ? $form['comments'] : '';   ?></textarea>
					</div>
				</div> 
				<div class="form-group required row"> 
					<div class="col-md-3"><label class="control-label" for="status">Status:</label></div>
					<div class="col-md-8">
						<select id="status" name="status" class="form-control" <?= (isset($process)) ? $process : '';   ?>>  
							<option value="">-- Select Status --</option>
							<?PHP
							if(sizeof($workflow_process) > 0)
							{ 
								for($i = 0;$i <= sizeof($workflow_process[0]['status_options'])-1; $i ++)
								{									 
									echo '<option value="'.$workflow_process[0]['status_options'][$i].'"  >'.$workflow_process[0]['status_options'][$i].'</option>' ;
								}
							}
							?>  
						</select>
					</div>
				</div> 
				<?PHP if(Session::get('usergroupname') == 'County Ambassador') {?><?PHP }?>
				<div id="approved_sitevisits_div" style="display:none;" > 
					<div class="form-group required row"> 
						<div class="col-md-3">Approved Site Visit :</div> 
						<div class="col-md-8">
							<select id="approved_sitevisits_id" name="approved_sitevisits_id" class="form-control">  
								<option value="">-- Select Site Visit --</option>
							</select>
					   </div> 
					</div> 				
				</div> 
				<div class="form-group row"> 
					<div class="col-md-3">Errors:</div> 
					<div class="col-md-8">
						<select id="error_id" name="error_id[]" multiple="multiple"  <?= (isset($process)) ? $process : '';   ?> class="form-control" size="5" > 
							<?PHP
							for($i = 0;$i <= sizeof($errors)-1; $i ++)
							{									 
								echo '<option value="'.$errors[$i]['id'].'"  >'.$errors[$i]['error_text'].'</option>' ;
							}
							?> 
					   </select> 
				   </div> 
				</div> 
				<div id="errorlist" class="row"> </div> 
				 
			  <div class="form-group row">
				<div class="offset-3 col-md-10">
				  <button type="submit" class="btn btn-default btn-primary ajax-submit" <?= (isset($process)) ? $process : '';   ?>> <?= __($sbt_button)?></button>
				</div>
			  </div> 
			  <?PHP if(sizeof($workflow_process) > 0){ ?>
			  <div class="form-group row">
				<div class="offset-3 col-md-10">
					<?PHP if($workflow_process[0]['submit_button_text']){?>
					<button type="submit" class="btn btn-default btn-primary" <?= (isset($process)) ? $process : '';   ?>> <?= $workflow_process[0]['submit_button_text']?></button>
					<?PHP } ?>
				</div>
			  </div> 
			  <?PHP } ?>
		</form> 
		<?PHP } ?>
		<div class="form-group required row">
			<div class="col-md-3"></div> 
			<div class="col-md-8"> 
				<?= (isset($process_message)) ? $process_message : '';   ?>
			</div> 
		</div> 
		<div class="form-group row">
			<div class="offset-1 col-md-2"><h3>Approvals</h3></div> 
		</div> 
		<div class="form-group row">
			<div class="col-md-1"></div> 
			<div class="col-md-8">
				<?PHP
				for($i = 0;$i <= sizeof($approvals)-1; $i ++)
				{
				?>
				<div class="form-group row">
					<div class="col-md-4"><b>Status:</b> <?= $approvals[$i]['status'];?> <br/><b>Actioned By:</b> <?= $approvals[$i]['actor'];?> <br/><b>Date:</b> <?= $approvals[$i]['created_on'];?></div> 
					<div class="col-md-7"> 
						<?= $approvals[$i]['comments'];?>
					</div> 
				</div> 
				<hr>
				<?PHP	
				}?>
			</div>
		</div>
	</div>
	<div id="rep-Visits" class="tab-pane fade in">
		<h4>Site Visits</h4> 
		<div class="col-sm-12">
			<div id="site_visits" class="data_holder"></div>
			<?PHP 
			if(($process == '') && (($workflow_process[0]['workflow_action'] == 'Site Visit Submitted') || ($workflow_process[0]['workflow_action'] == 'Site visited')))
				{   ?>
				<div class="form-group row"> 
					<div class="col-md-3"><label class="control-label" for="visit_date">Add New Site Visit Date:</label></div>
					<div class="col-md-3">
						<input type="text" class="form-control" id="visit_date" name="visit_date" value="<?= (isset($form['visit_date'])) ? $form['visit_date'] : '';   ?>"  placeholder="Start Date">
					</div>
					<div class="col-md-6">
						 <input type="text" class="form-control" id="site_visit_comments" name="site_visit_comments" value="<?= (isset($form['site_visit_comments'])) ? $form['site_visit_comments'] : '';   ?>"  placeholder="Comments">						
					</div>
				</div> 
				
				<div class="form-group row"> 
					<div class="col-md-3"></div>
					<div class="col-md-8">
						<button type="submit" id="btn-visit_date" class="btn btn-default btn-primary ajax-submit" > Add</button>						
					</div>
				</div> 
			<?PHP } ?>
			<script type="text/javascript">  
			 $(document).ready(function()
			 {
				$('#visit_date').datetimepicker(
				{ 
					dayOfWeekStart : 1,
					lang:'en',
					timepicker:true,
					format:'d-m-Y H:i',
					 minDate : 0
				});
			});
			 
			</script> 
		</div>
	</div>
	<div id="rep-Inspections" class="tab-pane fade in">
		<h4>Site Inspections</h4> 
		<div class="form-group required row"> 
			<div class="col-md-3"><label class="control-label" for="sitevisits_id">Site Visit:</label></div>
			<div class="col-md-8">
				<select id="sitevisits_id" name="sitevisits_id" class="form-control">  
					<option value="">-- Select Site Visit --</option>
				</select>
			</div>
		</div> 
		<div id="siteinspection_form" style="display:none;">
			<form class="form-horizontal" id="frm_SiteInspection" role="form" method="post" action="<?= URL::to('/') ?>/installers/addsiteinspection/<?= $id ?>" >
				@csrf 
				<input type="hidden" name="record_id"  id="record_id" value="">
				<?PHP echo  $inspectionquestions; ?>
				<div class="form-group row">
					<div class="offset-3 col-md-10">
					  <button type="submit" id="process-registration" class="btn btn-default btn-primary"> <?= __($sbt_button)?></button>
					</div>
				</div> 
			</form>
		</div>
		<div id="site_inspections" class="data_holder"></div>
	</div>
	
</div>

@endsection