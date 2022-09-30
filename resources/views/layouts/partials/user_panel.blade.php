<?PHP if($user_names ?? '')
	{
		if(in_array(1, $usergroups))
		{
		
			echo '<a href="'.URL::to('/') .'/contactus"><i class="fa-custom fa-email"></i>
            <span class="badge badge-info">'.$unread.'</span></a>';
		}
   
		//echo '<a href="'.URL::to('/') .'/profile"><i class="fa fa-cog"></i> My Profile </a> &nbsp; &nbsp; ';
		 
		 
	  

					
 
 
		 
		if(sizeof($usergroups) > 1)
		{
			echo '<ul class="navbar-nav" style="margin-top:-8px;">';
			echo '<li class="nav-item dropdown">';
			echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Switch Profile </a> <ul class="dropdown-menu " aria-labelledby="navbarDropdownMenuLink">';
			for($i=0; $i<=sizeof($usergroups)-1; $i++)
			{
				 
				echo '<li><a class="dropdown-item" href="'.URL::to('/') .'/profile/switch/'.$usergroups[$i]['usergroup_id'].'" style="border:1px solid #ccc;">'.$usergroups[$i]['group_name'].'</a></li>';
				
			}
			echo '</ul>';
			echo '</li>';
			echo '</ul>';
		}
		
		 
		echo '<br/><div style="text-align:right; ">'.$user_names.' - '.Session::get('usergroupname').'</div>';
	}
	 
	else
	{
		if(strtolower($controller_name) != 'logincontroller')
		{			
			echo '<li class="nav-item"><a class="nav-link" href="'.URL::to('/') .'/login"> Login</a></li> ';
		}
	}
	?>  