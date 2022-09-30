<div id="mast">
	<div class="col-md-4">
		<a class="navbar-brand" href="{{ url('/') }}">ACME Bike Rentals</a> 
	</div> 

	<nav class="navbar navbar-expand-lg navbar-light  shadow-sm btco-hover-menu nav-menu">
						 

		
		
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">

			<span class="navbar-toggler-icon"></span>

		</button>

		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<!-- Left Side Of Navbar -->
			
			
			<ul class="navbar-nav">
				<?PHP if($usergroupname == 'Standard')
					{
						?> 
						<li><a href="<?= URL::to('/')?>/bookings/book">Book a Bike</a></li>  | 
						<li><a href="<?= URL::to('/')?>/bookings/my">My Bookings</a></li>  | 
						<?PHP
					}
					if($usergroupname == 'Administrator')
					{
						?>
						
						<li><a href="<?= URL::to('/')?>/bikes">Bikes</a></li> | 
						<li><a href="<?= URL::to('/')?>/vendors">Vendors</a></li>  | 
						<li><a href="<?= URL::to('/')?>/user">Users</a></li>  | 
						<li><a href="<?= URL::to('/')?>/bookings">Bookings</a></li>  | 
						<?PHP
					}
					
					if($usergroupname == 'Vendor')
					{
						?>
						<li><a href="<?= URL::to('/')?>/bikes">Bikes</a></li> | 
						<li><a href="<?= URL::to('/')?>/bookings/vendor">Bike Bookings</a></li>  |
						<li><a href="<?= URL::to('/')?>/profile">My Profile</a></li> | 
						<?PHP
					}
					if((!$usergroupname ) || ($usergroupname == 'Guest'))
					{
						?> 
						<li><a href="<?= URL::to('/')?>/login">Login</a></li>  | 
						<li><a href="<?= URL::to('/')?>/register">Register</a></li>  
						<?PHP
					}
					else
					{
						?> 
						<li><a href="<?= URL::to('/')?>/logout">Logout</a></li> 
						<?PHP
					}	
			  ?>
				 

			</ul>
			
			<!-- Right Side Of Navbar -->
						<ul class="navbar-nav ml-auto profilemenu">
							<!-- Authentication Links -->
							 
						</ul>

		</div>
	  
		 
		 
	</nav> 
</div>