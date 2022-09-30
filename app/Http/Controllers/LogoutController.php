<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Controller;
use App\Http\models\User;
use App\Http\models\Counties;
use App\Http\models\Systemconfig;  
use Cookie;
use Auth;
use DB;
use Redirect;
use Request; 
use Session;
use URL;
use View;

class LogoutController extends Controller
{
	 
    public function index()  
	{
		
		Auth::logout(); 
		Session::flush(); 
		Session::forget('username');
		Session::forget('usergroupname');
		Session::forget('usergroup_id');
		return Redirect::to('/login');
		 
	  
	} 
	

}