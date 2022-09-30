<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <div id="container">     
        <main class="py-4">
			@include('layouts.partials.nav') 
            @yield('content')
        </main>
    </div>
	@include('layouts.partials.footer')
	@include('layouts.partials.footer-scripts')
<script type="text/javascript">

			
			jQuery(document).ready(function($)
			{ 
				//$('#spinner-modal').hide(); 
				//$('#modal-spinner-modal').hide();
			});
			function openModal() {
				document.getElementById('spinner-modal').style.display = 'block';
				document.getElementById('fade').style.display = 'block';
			}

			function closeModal() {
				document.getElementById('spinner-modal').style.display = 'none';
				document.getElementById('fade').style.display = 'none';
			}

			function openModalSpinner() {
				document.getElementById('modal-spinner-modal').style.display = 'block';
				document.getElementById('modal-fade').style.display = 'block';
			}

			function closeModalSpinner() {
				document.getElementById('modal-spinner-modal').style.display = 'none';
				document.getElementById('modal-fade').style.display = 'none';
			}

			function show_messages(msg_type, msg_txt)
			{
				toastr_message(msg_type, msg_txt);
			}
			
			
			function show_popup(title, body, footer, modalsize)
			{
				
				if (modalsize == 'modal-dialog-custom')
				{
					$('#default-modal .modal-dialog').removeClass().addClass('modal-dialog-custom modal-md');
				}
				else if (modalsize == 'modal-dialog')
				{
					$('#default-modal .modal-dialog').removeClass().addClass('modal-dialog modal-lg');
				}
				else if (modalsize == 'original-modal-dialog-custom')
				{
					$('#default-modal .modal-dialog').removeClass().addClass('modal-dialog-custom modal-lg');
				}
				else if (modalsize == 'modal-dialog-small')
				{
					$('#default-modal .modal-dialog').removeClass().addClass('modal-dialog modal-dialog-small modal-sm');
				}
	 
				$('#default-modal .modal-title').html(title);
				$('#default-modal .modal-body').html(body);
				$('#default-modal').addClass('show');
				$('#default-modal').removeClass('fade');
				$('#default-modal').css({'display':'block'});
				if (footer=="")
				{
					$('#default-modal .modal-footer').css({'display':'none'});
				}
				else
				{
					$('#default-modal .modal-footer').css({'display':'block'});
					$('#default-modal .modal-footer').html(footer);
				}
				$('#default-modal').modal({backdrop:'static',keyboard:true});
				$("html, body").animate({ scrollTop: 0 }, "slow");
			}
			 

		</script>
		<input type="hidden" id="refreshed" value="no">  
<div class="modal fade" id="default-modal" style="display:none;" >
	<div class="modal-dialog" style="margin: 20px auto;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">

			</div>
			<div id="modal-fade" class="page-fade"></div>
			<div id="modal-spinner-modal" class="page-spinner-modal" >
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- FOOTER -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <!-- UPPER FOOTER -->
                <div class="upper-footer">
                    <div class="col-md-12">
                        <!--<a class="logo page-scroll" href="#page-top" style="font-size: 30px; color: #fff; margin-left: -90px;"><i class="icon-check"></i></a><br>-->
                        <p></p>
                    </div> 
                    <div class="col-md-3"> 
                    </div>
                    
                </div>
                <!-- END UPPER FOOTER -->
            </div>
        </div>
    </footer>
<!-- END FOOTER --> 
<div id="fade" class="page-fade"></div>
<div id="spinner-modal" class="page-spinner-modal">
	
</div>
</body>
</html>
