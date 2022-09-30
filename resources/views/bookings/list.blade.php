@extends('layouts.app') 


@section('content') 

<script type="text/javascript">
function update_bikes_table()
{

    $("#bikes-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/bookings/", function( data ) {

        populate_bikes_table(data);

    });

}
function populate_bikes_table(data)
{
    var html  = '';
    if( data.code == 0 )
    {
        html = '<p class="text-center html-text-error ">' + data.text + '</p>';
        show_messages("error", data.text);
    }
    else
    {
         
 
		
		html  += '<table id="bikes-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> ';
        html += '<th width="1%">&nbsp; </th>';
        html += '<th width="19%">Bike Name </th>'; 
        html += '<th width="10%">Vendor </th>';  
        html += '<th width="19%">Booked By</th>'; 
        html += '<th width="5%">Color </th>';  
        html += '<th width="15%">Location </th>'; 
        html += '<th width="35%">Booked Dates</th>';  
        html += '<th width="15%">Total Hours</th>'; 
        html += '<th width="10%">Cost </th>';  
        html += '</tr> <tbody>';
        


        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="bikes" recordid="' + val.id +'" id="' + val.id +'"><td>&nbsp;</td>'
			 
            html += '<td>' + val.short_name + '</td>';  
            html += '<td>' + val.vendor_name + '</td>';
            html += '<td>' + val.full_name + '</td>'; 
            html += '<td>' + val.color + '</td>';  
            html += '<td>' + val.city + '</td>';   
            html += '<td>' + val.start_date + ' - ' + val.end_date + '</td>';  
			
            html += '<td>' + val.interval + '</td>';  
            html += '<td>' + val.totalcost + '</td>';     
            html += '</tr>';
        });

        html += '</tbody></table> <br/>';
		 
		 

    }
    $("#bikes-table-div").html(html);
    $('#bikes-table').dataTable({
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

    update_bikes_table();
 
 

 

    $('body').on('click', '.bikes-delete-link', function(event) {
		
        var id = $(this).attr('bikes_editid'); 

        $.ajax( "<?= URL::to('/') ?>/bookings/delete/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Delete Booking ', body, '', null);
            })
            .fail(function() {
                body = 'Error loading Booking  Delete form';
                show_popup('Delete Booking ', body, '', null);
            });

      
        event.preventDefault();
        return false;

    });
	
	$(document).on('click', "[name='generate']", function(event)
	{
		if((($("#user_id").val() != '') || ($("#bike_id").val() != '') || ($("#vendor_id").val() != ''))  && ($("#date_range").val() != '') )
		{
			//openModal();
			$("#report-table-div").html('');
			$("#export-buttons").hide();
			var formData = {
				'_token'		: "{{ csrf_token() }}", 
				'bike_id'         	: $("#bike_id").val(),
				'vendor_id'      	: $("#vendor_id").val(),
				'user_id'      	: $("#user_id").val(),   
				'date_range'        	: $("#date_range").val()
				}; 
				 
				
				// process the form 
				$.ajax({
				type        : 'POST', 
				url         : '<?= URL::to('/') ?>/bookings/filter', 
				data        : formData,
				dataType	: 'json',
				encode      : true            
				})
				// using the done promise callback
				.done(function(data) 
				{ 
					closeModal();
					//$("#search_data_div").slideUp();
					//populate_report_table();
					populate_bikes_table(data);   
				 
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

    $('body').on('submit', '.ajax-submit', function(event) {

        var options = {
            dataType: 'json',
            success:    function(resp) {

                if( resp.code == 1 )
                {
                    $('#default-modal .modal-footer').html('<p class="text-center text-success">' + resp.text + '</p>').fadeIn();
                        if(resp.action == 'add')
                        {
                            $(':input','#frm_bikes')
                            .not(':button, :submit, :reset, :hidden')
                            .val('');
                            
                            
                            $('#name').focus();

                            setTimeout(function(){
                                $( "p" ).empty();
                            }, 5000);
                        }
                        else
                        {
                            //new $.flavr({ content: resp.text, buttons: false, autoclose: true, timeout: 3000 }); 
                            $('#default-modal').modal('hide');
                        }

                    update_bikes_table();
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
	
	$("[name='reset']").on("click", function()
    {
        $("#date_range").val("")
        $(".combo option").removeAttr("selected")
		$(".combo").val(null).trigger("change");
		$("#report_data_table").remove()
    })
	
	
});

</script>
<h2><?= __("Bike Bookings") ?></h2>

<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>										
					<th width="15%">Date</th>
					<th width="15%">Bike</th>
					<th width="15%">Vendor</th>
					<th width="15%">Customer</th> 
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 130px !important;">                                           
						<input type="text" class="form-control" id="date_range" readonly name="date_range" placeholder="From - To">                                                    
					</td> 
					<td> 
						<select id="bike_id" name="bike_id" class="form-control combo" > 
							<?PHP 
							for($i = 0;$i <= sizeof($bike_list)-1; $i ++)
							{									 
								echo '<option value="'.$bike_list[$i]['id'].'">'.$bike_list[$i]['short_name'].'</option>' ;
							}
							?> 
					   </select> 
					</td>
					<td> 
						<select id="vendor_id" name="vendor_id"  class="form-control combo"   > 
							<?PHP 
							for($i = 0;$i <= sizeof($vendors)-1; $i ++)
							{									 
								echo '<option value="'.$vendors[$i]['id'].'">'.$vendors[$i]['vendor_name'].'</option>' ;
							}
							?> 
					   </select>  
					</td>
					<td> 
						<select id="user_id" name="user_id"  class="form-control combo"  > 
							<?PHP 
							for($i = 0;$i <= sizeof($users)-1; $i ++)
							{									 
								echo '<option value="'.$users[$i]['id'].'">'.$users[$i]['full_name'].'</option>' ;
							}
							?>   
					   </select> 
					</td> 
				</tr>
				</tbody>
			</table>
		</div> 
                        
	<br><br>
	<div class="btn-toolbar" role="toolbar" aria-label="">
		<div class="btn-group" role="group" aria-label="">
			<button type="submit" id="generate" name="generate" class="btn btn-primary">Generate <i class="fas fa-play"></i></button>
		</div>                         
	</div> 
	<div id="search_data_div" style="display:none"></div> 
	<br/>
	                  
	</form>

<div style="margin-top:18px;" id="bikes-table-div"  class="data_holder">
</div>
 

@endsection