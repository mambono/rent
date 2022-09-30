@extends('layout.mainlayout') 


@section('content') 

<script type="text/javascript">
function update_users_table()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/user/show", function( data ) {

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
        html += '<th width="10%">ID Number </th>'; 
        html += '<th width="15%">Phone Number </th>'; 
        html += '<th width="15%">Last Login</th>'; 
        html += '<th width="10%">Status </th>'; 
        html += '</tr> <tbody>';
        


        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="users" recordid="' + val.id +'" id="' + val.id +'"><td><input type="checkbox" id="deleteid-' + val.id +'" value="'+val.id+'" class="chkdelete" name="deleteid"/></td>'
            
            html += '<td><input type="radio" id="deleteid-' + val.id +'"  class="chkdelete" style="display:none;" name="deleteid"/>' + val.full_name + '</td>';   
			html += '<td>' + val.email + '</td>'; 
			html += '<td>' + val.id_number + '</td>'; 
			html += '<td>' + val.mobile_number + '</td>'; 
			html += '<td>' + val.last_login + '</td>'; 
			html += '<td>' + val.status + '</td>'; 
            html += '</tr>';
        });
  
		
        html += '</tbody></table> <br/>';
		
        html += '<a href="<?= URL::to('/') ?>/user/add/" id="users-add-users" class="btn btn-primary users-add-link btn" >Add</a>&nbsp;';
		
        html += '<a href="<?= URL::to('/') ?>/user/edit/" id="users-edit-users" class="btn btn-primary users-edit-link btn editrecord disabled" users_editid="" >Edit</a>&nbsp;';
		html += '<a href="<?= URL::to('/') ?>/user/process/" id="users-view-users" class="btn btn-primary users-view-link btn processrecord disabled" users_processid="" >Process</a>&nbsp;';

        html += '<a href="<?= URL::to('/') ?>/user/delete/" id="users-delete-users" class="users-delete-link btn btn-danger delete disabled" users-deleteid="">Delete </a>&nbsp;';

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
    $.getJSON( "<?= URL::to('/') ?>/user/add/form", function( data )
    {
        if( data.code == 0 )
        {
            $("#users-add-users").hide();
        }
    });
}

$(function() {

    $.ajaxSetup({ cache: false });

    update_users_table();
 
	
	$('body').on('click', '.users-view-link', function(event) {

        var id = $(this).attr('users_processid');

        $.ajax( "<?= URL::to('/') ?>/user/process/" + id )
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
                show_popup('Edit Process Registration', body, '', 'modal-dialog');
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
 
        $("#users-view-users").attr("href", "<?= URL::to('/') ?>/users/view/"+RecordID);
        $("#users-view-users").attr("users_processid", RecordID);
        $("#users-delete-users").attr("href", "<?= URL::to('/') ?>/users/delete/"+RecordID);

    });

    $(document).on('dblclick', '.item', function(e)
    {
        var RecordID = $(this).attr('recordid');
        //window.location.href = "<?= URL::to('/') ?>/users/view/" + RecordID;
    });


    $('body').on('click', '.users-delete-link', function(event) {
        var id = 0;
        var deleteid = [];
        
        $('input:checkbox[name=deleteid]:checked').each(function()
        {
            deleteid.push($(this).val());
        })
        if(deleteid.length > 0)
        {
            id = deleteid.toString();
            id = id.replace(/,/g , "_");
        }
        else
        {
            id = $(this).attr('users-deleteid');
        }
        
        //var 

        $.ajax( "<?= URL::to('/') ?>/users/delete/" + id )
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