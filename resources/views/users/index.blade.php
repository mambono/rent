@extends('layouts.app') 


@section('content') 

<script type="text/javascript">
function update_users_table()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/user", function( data ) {

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
        html += '<th width="1%">&nbsp; </th>';
        html += '<th width="15%">Name </th>'; 
        html += '<th width="15%">Email </th>';  
        html += '<th width="20%">Action </th>';   
        html += '</tr> <tbody>';
        
		
		 


        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="users" recordid="' + val.id +'" id="' + val.id +'"><td><input type="checkbox" id="deleteid-' + val.id +'" value="'+val.id+'" class="chkdelete" name="deleteid"/></td>'
            
            html += '<td><input type="radio" id="deleteid-' + val.id +'"  class="chkdelete" style="display:none;" name="deleteid"/>' + val.full_name + '</td>';   
			html += '<td>' + val.email + '</td>';
			html += '<td><a href="<?= URL::to('/') ?>/user/edit/' + val.id + '" user_editid="' + val.id + '" class="btn btn-primary users-edit-link">Edit</a> '; 
			html += '<a href="<?= URL::to('/') ?>/user/delete/' + val.id + '" user_editid="' + val.id + '" class="btn btn-danger users-delete-link">Delete</a> </td>';    
            html += '</tr>';
        });
  
		
        html += '</tbody></table> <br/>'; 

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

    $('body').on('click', '.users-add-link', function(event) {

        $.ajax( "<?= URL::to('/') ?>/user/add/form" )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Add User', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading New User form';
                show_popup('Add User', body, '', 'modal-dialog');
            });
        event.preventDefault();
        return false;

        //show_popup(title, body, footer, modalsize)

    });

    $('body').on('click', '.users-edit-link', function(event) {

        var id = $(this).attr('user_editid');

        $.ajax( "<?= URL::to('/') ?>/user/edit/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Edit User', body, '', 'modal-dialog');
            })
            .fail(function() {
                body = 'Error loading User Edit form';
                show_popup('Edit User', body, '', 'modal-dialog');
            });

     
        event.preventDefault();
        return false;

    });
	 
 

    $('body').on('click', '.users-delete-link', function(event) {
        var id = $(this).attr('user_editid');

        $.ajax( "<?= URL::to('/') ?>/user/delete/" + id )
            .done(function(resp) {
                body = resp ;
                if( resp.code == 0 )
                {
                    body = '<p class="text-center text-error">' + resp.text + '</p>';
                }
                show_popup('Delete User', body, '', null);
            })
            .fail(function() {
                body = 'Error loading User Delete form';
                show_popup('Delete User', body, '', null);
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
 
});

</script>
<h2> <?= __($header)?></h2>

<div style="margin-top:18px;" id="users-table-div"  class="data_holder">
</div>
 

@endsection