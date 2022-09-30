@extends('layouts.app') 


@section('content') 

<script type="text/javascript">
function update_bikes_table()
{

    $("#bikes-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/bikes", function( data ) {

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
        html += '<th width="19%">Bikes Name </th>'; 
        html += '<th width="10%">Color </th>';  
        html += '<th width="15%">Location </th>'; 
        html += '<th width="15%">Gear Speed </th>'; 
        html += '<th width="20%">Hourly Cost </th>';
        html += '<th width="20%">Action </th>';  
        html += '</tr> <tbody>';
        


        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="bikes" recordid="' + val.id +'" id="' + val.id +'"><td>&nbsp;</td>'
			 
            html += '<td>' + val.short_name + '</td>';  
            html += '<td>' + val.color + '</td>';  
            html += '<td>' + val.city + '</td>';  
            html += '<td>' + val.gear_speed + '</td>';  
            html += '<td>' + val.hourly_cost + '</td>';  
            html += '<td><a href="<?= URL::to('/') ?>/bikes/edit/' + val.id + '" bikes_editid="' + val.id + '" class="btn btn-primary bikes-edit-link">Edit</a> '; 
			html += '<a href="<?= URL::to('/') ?>/bikes/delete/' + val.id + '" bikes_editid="' + val.id + '" class="btn btn-danger bikes-delete-link">Delete</a> </td>';  
            html += '</tr>';
        });

        html += '</tbody></table> <br/>';
		
         html += '<a href="<?= URL::to('/') ?>/bikes/add/" id="bikes-add-bikes" class="btn btn-primary bikes-add-link btn" >Add</a>&nbsp;';
		 

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

    $('body').on('click', '.bikes-add-link', function(event) {

        $.ajax( "<?= URL::to('/') ?>/bikes/add/form" )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Add Bike ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading New Bike  form';
                show_popup('Add Bike ', body, '', 'modal-dialog');
            });
        event.preventDefault();
        return false;

        //show_popup(title, body, footer, modalsize)

    });
 


    $('body').on('click', '.bikes-edit-link', function(event) {

        var id = $(this).attr('bikes_editid');

        $.ajax( "<?= URL::to('/') ?>/bikes/edit/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Edit Bike ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading Bike  Edit form';
                show_popup('Edit Bike ', body, '', 'modal-dialog');
            });

     
        event.preventDefault();
        return false;

    });

    $('body').on('click', '.bikes-delete-link', function(event) {
		
        var id = $(this).attr('bikes_editid'); 

        $.ajax( "<?= URL::to('/') ?>/bikes/delete/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Delete Bike ', body, '', null);
            })
            .fail(function() {
                body = 'Error loading Bike  Delete form';
                show_popup('Delete Bike ', body, '', null);
            });

      
        event.preventDefault();
        return false;

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


});

</script>
<h2><?= __("Bikes") ?></h2>

<div style="margin-top:18px;" id="bikes-table-div"  class="data_holder">
</div>
 

@endsection