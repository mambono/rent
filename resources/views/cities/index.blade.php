@extends('layouts.app') 


@section('content') 

<script type="text/javascript">
function update_cities_table()
{

    $("#cities-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/cities", function( data ) {

        populate_cities_table(data);

    });

}
function populate_cities_table(data)
{
    var html  = '';
    if( data.code == 0 )
    {
        html = '<p class="text-center html-text-error ">' + data.text + '</p>';
        show_messages("error", data.text);
    }
    else
    {
         
 
		
		html  += '<table id="cities-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> ';
        html += '<th width="1%">&nbsp; </th>';
        html += '<th width="79%">City </th>'; 
        html += '<th width="20%">Action </th>'; 
        html += '</tr> <tbody>';
        


        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="cities" recordid="' + val.id +'" id="' + val.id +'"><td>&nbsp;</td>'
            
            html += '<td>' + val.city + '</td>';  
            html += '<td><a href="<?= URL::to('/') ?>/cities/edit/' + val.id + '" cities_editid="' + val.id + '" class="btn btn-primary cities-edit-link">Edit</a> '; 
			html += '<a href="<?= URL::to('/') ?>/cities/delete/' + val.id + '" cities_editid="' + val.id + '" class="btn btn-danger cities-delete-link">Delete</a> </td>';  
            html += '</tr>';
        });

        html += '</tbody></table> <br/>';
		
         html += '<a href="<?= URL::to('/') ?>/cities/add/" id="cities-add-cities" class="btn btn-primary cities-add-link btn" >Add</a>&nbsp;';
		 

    }
    $("#cities-table-div").html(html);
    $('#cities-table').dataTable({
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

    update_cities_table();

    $('body').on('click', '.cities-add-link', function(event) {

        $.ajax( "<?= URL::to('/') ?>/cities/add/form" )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Add City ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading New City  form';
                show_popup('Add City ', body, '', 'modal-dialog');
            });
        event.preventDefault();
        return false;

        //show_popup(title, body, footer, modalsize)

    });
 


    $('body').on('click', '.cities-edit-link', function(event) {

        var id = $(this).attr('cities_editid');

        $.ajax( "<?= URL::to('/') ?>/cities/edit/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Edit City ', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading City  Edit form';
                show_popup('Edit City ', body, '', 'modal-dialog');
            });

     
        event.preventDefault();
        return false;

    });

    $('body').on('click', '.cities-delete-link', function(event) {
		
        var id = $(this).attr('cities_editid'); 

        $.ajax( "<?= URL::to('/') ?>/cities/delete/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Delete City ', body, '', null);
            })
            .fail(function() {
                body = 'Error loading City  Delete form';
                show_popup('Delete City ', body, '', null);
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
                            $(':input','#frm_cities')
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

                    update_cities_table();
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
<h2><?= __("Cities") ?></h2>

<div style="margin-top:18px;" id="cities-table-div"  class="data_holder">
</div>
 

@endsection