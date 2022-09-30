@extends('layouts.app') 


@section('content') 

<script type="text/javascript">
function update_vendors_table()
{

    $("#vendors-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/vendors", function( data ) {

        populate_vendors_table(data);

    });

}
function populate_vendors_table(data)
{
    var html  = '';
    if( data.code == 0 )
    {
        html = '<p class="text-center html-text-error ">' + data.text + '</p>';
        show_messages("error", data.text);
    }
    else
    {
         
 
		
		html  += '<table id="vendors-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> '; 
        html += '<th width="30%">Vendor Name </th>';
        html += '<th width="15%">Location </th>';  
        html += '<th width="20%">Owner </th>';  
        html += '<th width="15%">Telephone Number </th>';
        html += '<th width="20%">Action </th>';  
        html += '</tr> <tbody>';
        		
	

        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="vendors" recordid="' + val.id +'" id="' + val.id +'"> '
			 
            html += '<td>' + val.vendor_name + '</td>';
            html += '<td>' + val.city + '</td>';   
            html += '<td>' + val.vendor + '</td>';  
            html += '<td>' + val.telephone_number + '</td>';  
            html += '<td><a href="<?= URL::to('/') ?>/vendors/edit/' + val.id + '" vendors_editid="' + val.id + '" class="btn btn-primary vendors-edit-link">Edit</a> '; 
			html += '<a href="<?= URL::to('/') ?>/vendors/delete/' + val.id + '" vendors_editid="' + val.id + '" class="btn btn-danger vendors-delete-link">Delete</a> </td>';  
            html += '</tr>';
        });

        html += '</tbody></table> <br/>';
		
         html += '<a href="<?= URL::to('/') ?>/vendors/add/" id="vendors-add-vendors" class="btn btn-primary vendors-add-link btn" >Add</a>&nbsp;';
		 

    }
    $("#vendors-table-div").html(html);
    $('#vendors-table').dataTable({
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

    update_vendors_table();

    $('body').on('click', '.vendors-add-link', function(event) {

        $.ajax( "<?= URL::to('/') ?>/vendors/add/form" )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Add Vendor ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading New Vendor  form';
                show_popup('Add Vendor ', body, '', 'modal-dialog');
            });
        event.preventDefault();
        return false;

        //show_popup(title, body, footer, modalsize)

    });
 


    $('body').on('click', '.vendors-edit-link', function(event) {

        var id = $(this).attr('vendors_editid');

        $.ajax( "<?= URL::to('/') ?>/vendors/edit/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Edit Vendor ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading Vendor  Edit form';
                show_popup('Edit Vendor ', body, '', 'modal-dialog');
            });

     
        event.preventDefault();
        return false;

    });

    $('body').on('click', '.vendors-delete-link', function(event) {
		
        var id = $(this).attr('vendors_editid'); 

        $.ajax( "<?= URL::to('/') ?>/vendors/delete/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Delete Vendor ', body, '', null);
            })
            .fail(function() {
                body = 'Error loading Vendor  Delete form';
                show_popup('Delete Vendor ', body, '', null);
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
                            $(':input','#frm_vendors')
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

                    update_vendors_table();
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
<h2><?= __("Vendors") ?></h2>

<div style="margin-top:18px;" id="vendors-table-div"  class="data_holder">
</div>
 

@endsection