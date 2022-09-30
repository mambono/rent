@extends('layout.mainlayout') 


@section('content') 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script type="text/javascript">

function update_dashboard_table()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/installers/referralsdashboard", function( data ) {

        populate_dashboard_table(data); 
    }); 
}

function update_users_table()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/installers/referrals", function( data ) {

        populate_users_table(data); 
    }); 
}

function update_referralschart()
{

    $("#users-table-div").html('Loading...');

    $.getJSON( "<?= URL::to('/') ?>/installers/referralsdashboard", function( data ) {

        populate_referralschart(data); 
    }); 
}

function populate_referralschart(json)
{ 
//colors: ['#314A68', '#AA4643', '#89A54E', '#5D10B7', '#3D96AE',    '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92']
   
   
		Highcharts.setOptions({
 colors: ['#047704', '#FF0000', '#075099', '#314A68', '#5D10B7', '#EA5F09', '#9B8E04', '#207052']
});
		
		var options = {
			chart: {
				renderTo: 'referrals',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			}, 
			title: {
				text: 'Referrals by Status'
			},
			tooltip: {
					formatter: function() {
					return '<b>'+ this.point.name +'</b>: '+ this.y + ' ( '+ this.point.percent +'%)'; 
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true,
					dataLabels: {
						enabled: false 
				}
			}
			},
			legend: {
				itemStyle: {
					font: '19pt Trebuchet MS, Verdana, sans-serif',
					color: '#A0A0A0'
				}, 
				itemHiddenStyle: {
					color: '#444'
				},
				enabled: true, 
				labelFormatter: function() {
					return  this.name + ' ' + this.y + ' ( ' + this.percent + '%)';
				}
			},
			series: [{
				type: 'pie',
				name: 'Referrals by Status',
				data: [], 
				point:{
				  events:{
					   
				  }
			  } 
				
				
				
			}] 
		}
		 
		//$.getJSON( "<?= URL::to('/') ?>/installers/referralsdashboard", function( json ) 
		//{
			options.series[0].data = json;
			chart = new Highcharts.Chart(options);
		//}); 
		  
		//options.series[0].data = data;
			 
		 
}

function populate_dashboard_table(data)
{
    var html  = '';
    if( data.code == 0 )
    {
        html = '<p class="text-center html-text-error ">' + data.text + '</p>';
        show_messages("error", data.text);
    }
    else
    {
         
 
		
		html  += '<table id="dashboard-table" class="table-bordered datatable" width="100%" cellspacing="0" style="background-color:#ffffff;"> <thead> <tr> ';
        html += '<th width="90%">Status </th>'; 
        html += '<th width="10%">Total </th>'; 
        html += '</tr> <tbody>';
        
 
        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="users" recordid="' + val.id +'" id="' + val.id +'">'
            
            html += '<td><input type="radio" id="deleteid-' + val.id +'"  class="chkdelete" style="display:none;" name="deleteid"/>' + val.status + '</td>';   
			html += '<td>' + val.total + '</td>';  
            html += '</tr>';
        });
  
		
        html += '</tbody></table> <br/>'; 
    }
    $("#dashboard-table-div").html(html); 
    $('#dashboard-table').dataTable({
        'aaSorting':[[0,'asc']],
		'searching': false,
		'paging': false,
        'aoColumnDefs': [{
            'bSortable': true,
            'aTargets': [0] /* 1st one, start by the right */
        }], 
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
        html += '<th width="20%">Referee </th>'; 
        html += '<th width="20%">Referrer </th>';  
        html += '<th width="10%">Registration Date </th>'; 
        html += '<th width="10%">Status </th>'; 
        html += '</tr> <tbody>';
        
 
        $.each( data, function( key, val )
        {

            html += '<tr class="item" tabletype="dashboard" recordid="' + val.id +'" id="' + val.id +'"><td><input type="checkbox" id="deleteid-' + val.id +'" value="'+val.id+'" class="chkdelete" name="deleteid"/></td>'
            
            html += '<td><input type="radio" id="deleteid-' + val.id +'"  class="chkdelete" style="display:none;" name="deleteid"/><a target="_blank" href="<?= URL::to('/') ?>/user/view/' + val.id +'">' + val.full_name + '</a></td>';   
			html += '<td>' + val.referee + '</td>';  
			html += '<td>' + val.created_on + '</td>'; 
			html += '<td>' + val.status + '</td>'; 
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
	update_referralschart();
  
	$('#date_range').daterangepicker({  
         
            format: 'YYYY-MM-DD'
         
	});
	
	$(document).on('click', "#generate", function(event){
	if($("#date_range").val() != '')
	{
		openModal(); 
		var formData = {   
			'date_range'        : $("#date_range").val(), 
			'type'     : 'referrals',
			'_token'		: "{{ csrf_token() }}"
			};
			
				 
			
			
			// process the form 
			$.ajax({
			type        : 'POST', 
			url         : '<?= URL::to('/') ?>/installers/referrals', 
			data        : formData,
			encode      : true            
			})
			// using the done promise callback
			.done(function(data) 
			{  
				populate_users_table(data); 
			 
			});
			
			
			// process the form 
			$.ajax({
			type        : 'POST', 
			url         : '<?= URL::to('/') ?>/installers/referralsdashboard', 
			data        : formData,
			encode      : true            
			})
			// using the done promise callback
			.done(function(chartdata) 
			{   
				populate_referralschart(chartdata); 
			 
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
<h2><?= __("Installer Referrals") ?></h2>
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
<div style="clear: both;"></div>
    <br/><br/>  <div id="referrals"  ></div>
	        
	<br/><br/>
	<div style="margin-top:18px;" id="users-table-div"  class="data_holder">
	</div>
 

@endsection