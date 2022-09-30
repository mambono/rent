@extends('layout.mainlayout') 


@section('content') 

<script type="text/javascript">
function update_users_table()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/installers", function( data ) {

        populate_users_table(data);

    });

}
function populate_users_table(data)
{
    var html  = '';
    if( data.code == 0 )
    {
        html = '<p class="text-center html-text-error ">' + data.text + '</p>';
        show_messages("error", data.text);
    }
    else
    {
         
 
		
		html  += '<table id="users-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> '; 
        html += '<th width="20%">Name </th>'; 
        html += '<th width="20%">Email </th>'; 
        html += '<th width="15%">ID Number </th>'; 
        html += '<th width="15%">Phone Number </th>'; 
        html += '<th width="10%">Registration Date </th>'; 
        html += '<th width="10%">Status </th>'; 
        html += '</tr> <tbody>';
        
 
        $.each( data, function( key, val )
        {

            //html += '<tr class="item" tabletype="users" recordid="' + val.id +'" id="' + val.id +'"><td><input type="checkbox" id="deleteid-' + val.id +'" value="'+val.id+'" class="chkdelete" name="deleteid"/></td>';
             html += '<tr class="item" tabletype="users" recordid="' + val.id +'" id="' + val.id +'">';
            html += '<td><input type="radio" id="deleteid-' + val.id +'"  class="chkdelete" style="display:none;" name="deleteid"/><a target="_blank" href="<?= URL::to('/') ?>/user/view/' + val.id +'">' + val.full_name + '</a></td>';   
			html += '<td>' + val.email + '</td>'; 
			html += '<td>' + val.id_number + '</td>'; 
			html += '<td>' + val.mobile_number + '</td>';
			html += '<td>' + val.created_on + '</td>'; 
			html += '<td>' + val.status + '</td>'; 
            html += '</tr>';
        });
  
		
        html += '</tbody></table> <br/>';
		html += '<a href="<?= URL::to('/') ?>/installers/process/" id="users-view-users" class="btn btn-primary users-view-link btn processrecord disabled" users_processid="" >Process</a>&nbsp;';

    }
    $("#users-table-div").html(html);
    $('#users-table').dataTable({
        'aaSorting':[[1,'asc']],
        'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': [0] /* 1st one, start by the right */
        }],
        "oLanguage": {
            "sSearch": "Search/Filter:"
        }
    }); 
}

$(function() {

    $.ajaxSetup({ cache: false });

    update_users_table();
 
	$('body').on('click', '.2users-view-link', function(event) {

        var id = $(this).attr('users_processid');
		event.preventDefault();  
		event.stopPropagation();
        $.ajax( "<?= URL::to('/') ?>/installers/process/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Process Registration ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading Process Registration form';
                show_popup('Process Registration', body, '', 'modal-dialog');
            });

     
        event.preventDefault();
        return false;

    });

    $(document).on('click', '.item', function(e)
    {
        var elem = $(this).closest('.item');
        var RecordID = $(this).attr('recordid');
        var tabletype = $(this).attr('tabletype');


        var rowIndexNumber = ($(this).closest("tbody tr")[0].rowIndex);
        $(this).find('input:radio').attr('checked', true);
        var isChecked = $(this).find('input:radio').is(':checked');
 
        $("#users-view-users").attr("href", "<?= URL::to('/') ?>/installers/process/"+RecordID);
        $("#users-view-users").attr("users_processid", RecordID); 

    });

    $(document).on('dblclick', '.item', function(e)
    {
        var RecordID = $(this).attr('recordid');
        //window.location.href = "<?= URL::to('/') ?>/users/view/" + RecordID;
    });
 

    $('body').on('submit', '.ajax-submit', function(event) {

        var options = {
            dataType: 'json',
            success:    function(resp) {

                if( resp.code == 1 )
                {
                    $('#default-modal .modal-footer').html('<p class="text-center text-success">' + resp.text + '</p>').fadeIn();
                        if(resp.action == 'add')
                        {
                            $(':input','#frm_users')
                            .not(':button, :submit, :reset, :hidden')
                            .val('');
                            
                            
                            $('#name').focus();

                            setTimeout(function(){
                                $( "p" ).empty();
                            }, 5000);
                        }
                        else
                        {
                            new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 }); 
                            $('#default-modal').modal('hide');
                        }

                    update_users_table();
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

	$('#date_range').daterangepicker({  
         
            format: 'YYYY-MM-DD'
         
	});
	
	$(document).on('click', "#generate", function(event)
	{
		if($("#date_range").val() != '')
		{
			openModal(); 
			var formData = {   
				'date_range'        : $("#date_range").val(), 
				'type'     : 'installers',
				'_token'		: "{{ csrf_token() }}"
				};
				
					 
				
				
				// process the form 
				$.ajax({
				type        : 'POST', 
				url         : '<?= URL::to('/') ?>/installers/filter', 
				data        : formData,
				encode      : true            
				})
				// using the done promise callback
				.done(function(data) 
				{ 
					closeModal(); 
					populate_users_table(data); 
				 
				});
			
		  
			event.preventDefault(); 
			return false; 
		}
		else
		{  
			new $.flavr({ content: 'Please select all filters', buttons: false, autoclose: true, timeout: 3000 }); 
			return false;
		}
	}); 
});

</script>
<h2><?= __("Installer Applications") ?></h2>
<div class="table-responsive col-md-7">
	<table class="table table-striped col-md-12">
		<thead>
			<tr>										
				<th width="15%">Registration Date</th> 
			</tr>
		</thead>
		<tbody>
			<tr>
				<td >                                           
					<input type="text" class="form-control" id="date_range" readonly name="date_range" placeholder="From - To">                                                    
				</td>  
			</tr>
		</tbody>
	</table>        
	<div class="btn-toolbar col-md-3" role="toolbar" aria-label="">
		<div class="btn-group" role="group" aria-label="">
			<button type="submit" id="generate" name="generate" class="btn btn-primary">Filter </button>
		</div>                         
	</div>
</div> 
                
	<br/><br/>
	<div style="margin-top:18px;" id="users-table-div"  class="data_holder">
	</div>
 

@endsection