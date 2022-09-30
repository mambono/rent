<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\models\User;
use App\models\Usergroups;
use Auth;
use Cookie;
use DB;
use URL;
use Illuminate\Support\Facades\View;
use Redirect; 
use Request;
use Route;
use Session;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	 
	public function __construct()
    {
		
		$this->middleware(function ($request, $next) 
		{
			$requestTypeHeader = \strtolower($request->header('RequestType'));
			$tokenHeader = $request->header('Token');
			$isValidToken = false;
			$usergroupname = '';
			$username = Session::get('username'); 
			$user = Auth::User();  
			$user_id = Auth::id();
			$accessible = 0; 
			$action = app('request')->route()->getAction();

			$controller = class_basename($action['controller']); 

			list($controller_name, $action) = explode('@', $controller);
			
			if(!$username)
			{
				if($user_id)
				{
					$usergroups = User::select_single($user_id); //dd(DB::getQueryLog());
					
					$usergroup_id = $usergroups[0]['group_id']; 
					$usergroupname = $usergroups[0]['group_name'];  
					Session::put('username', $user->username);
					Session::put('usergroupname', $usergroupname);
					Session::put('usergroup_id', $usergroup_id);
				}
				else
				{
					if((strtolower($controller_name) != 'logincontroller' ) AND (strtolower($controller_name) != 'registercontroller' ))
					{
						Auth::logout(); 
						return redirect('/login');
					}
				}
			}
			
			
			if(!$user_id)
			{
				$user_id = 2;
			}  
			
			$user_names ='';
			DB::enableQueryLog(); // Enable query log
			 
			$usergroups = User::select_single($user_id)->toArray(); //dd(DB::getQueryLog());
			if(Session::get('usergroup_id'))
			{
				$usergroup_id = Session::get('usergroup_id');
			}
			else
			{
				$usergroup_id = $usergroups[0]['group_id'];
			}  
			  
			
			
			if(sizeof($usergroups) > 0)
			{
				$usergroupname = $usergroups[0]['group_name']; 
			}
			
			  
		
			
			if((strtolower($controller_name) == 'logincontroller' ) || (strtolower($controller_name) == 'homecontroller' )  || (strtolower($controller_name) == 'registercontroller' ) || (strtolower($controller_name) == 'apicontroller' )|| (strtolower($controller_name) == 'logoutcontroller' ))
			{
				$accessible = 1;
			}  
			if($usergroupname == 'Standard')
			{
				if((strtolower($controller_name) == 'applicationscontroller' ) || (strtolower($controller_name) == 'homecontroller' )  || (strtolower($controller_name) == 'registercontroller' ) || (strtolower($controller_name) == 'bookingscontroller' ) || (strtolower($controller_name) == 'logoutcontroller' ))
				{
					$accessible = 1;
				}  
			}
			if($usergroupname == 'Administrator')
			{
				$accessible = 1; 
			}
			
			if($usergroupname == 'Vendor')
			{
				if((strtolower($controller_name) == 'bikescontroller' ) ||(strtolower($controller_name) == 'applicationscontroller' ) || (strtolower($controller_name) == 'homecontroller' )  || (strtolower($controller_name) == 'registercontroller' ) || (strtolower($controller_name) == 'bookingscontroller' )|| (strtolower($controller_name) == 'logoutcontroller' ))
				{
					$accessible = 1;
				}   
			}
			 
			
			
			if (  Auth::check()   ) //Check if user is logged in
			{ 

				$user_names = $user['first_name'] .' '. $user['last_name'];
				if($accessible == 0)
				{
					return redirect(URL::to('/login'));
				}
				elseif(strtolower($controller_name) == 'logincontroller' )
				{
					 return redirect(URL::to('/home'));
				}

			}
    
			else if( ( ! Auth::check() AND !$isValidToken AND strtolower($controller_name) != 'logincontroller' AND  strtolower($controller_name) != 'logincontroller' AND strtolower($controller_name) != 'registercontroller' ) || ($accessible == 0))
			{   
				if($controller_name != 'login')
				{ 
					return redirect('/login');
				}  
			} 
			
			// push variables to navigation			
			View::share([ 'usergroupname' => $usergroupname, 'controller_name' => $controller_name, 'usergroup_id' => $usergroup_id, 'user_names' => $user_names, 'usergroups' => $usergroups]); 
			return $next($request);
		}); 

            
		
    }
}
