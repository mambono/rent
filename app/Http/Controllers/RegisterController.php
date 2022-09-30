<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Controller;
use App\models\User;
use App\models\Usergroups; 
use Cookie;
use Auth;
use DB;
use Redirect;
use Request; 
use Session;
use URL;
use View;


class RegisterController extends Controller
{
	private function getPostData()
	{
		  
		 return Request::post(); 
	} 
	/**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

	public function index($id = null)
    {
 
 
		$params = explode('_' , $id);
		$action  = 'register';
		$referral_code = '';
		$id = $params[0];
		
		 
		 
		if($id > 0)
		{
			$user = User::select_by_id($id);  
			$user = $user[0];
			$action = 'confirm';
		}
		else{
			$msg_text = $params[0];
		}
		 
 
		$user = array('full_name' => '', 'id' => 0, 'username' =>  '', 'first_name' =>  '', 'last_name' =>  '', 'email' =>  '', 'mobile_number' =>  '', 'activated' =>  0 ) ;
	  
		 
		  
		if(Request::ajax()) 
		{ 
			//return response($json, 200);//->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the nerds 
			return View::make('login.'.$action)
            ->with('user', $user)
			->with('id', $id) 
			->with('msg_text', $msg_text)  
            ->with('mode', '')
			->with('action','')	 ;
		} 
	}	

	public function add()
    { 
		 
		$msg = array(); 
		  
		
		if (Request::isMethod('post'))
		{
			// start with a blank code
			$ota_code = "";
			
			// define possible characters
			$possible = "0123456789"; 

			// set up a counter
			$i = 0; 
			// add random characters to $password until $length is reached

			while ($i < 8)
			{ 			

				// pick a random character from the possible ones
				$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

				// we don't want this character if it's already in the password
				if (!strstr($ota_code, $char)) 
				{ 
				  $ota_code .= $char;
				  $i++;
				}	
			} 
			
			$form_data =  $this->getPostData(); 
			$form_data['created_on'] = date('Y-m-d H:i:s');
			$form_data['delete_flag'] = 0; 
			  
			$password = $form_data['reg_password']; 
			unset($form_data['_token']);
			unset($form_data['mode']);
			unset($form_data['user_id']);
			unset($form_data['reg_password']);
			unset($form_data['password2']); 
			unset($form_data['created_by']);  
			
			$form_data['password'] = Hash::make($password);
			 
			
			try 
			{
				  
				
				$checkemail = User::select_by_email($form_data['email']); 
				if(sizeof($checkemail) == 0)
				{
					$usergroups = Usergroups::select_by_name('Standard');
					$usergroup_id = $usergroups[0]['id'];
					$form_data['group_id'] = $usergroup_id;
					Auth::logout(); 
					
				 
					
					$user_id = DB::table('users')->insertgetid($form_data);   
					$msg_text = 'Registration submitted sucessfully'; 
					    
					 $redirect_url = URL::to('/').'/register/confirmation/'.$user_id.'_'.$msg_text;
					  
					return Redirect::to($redirect_url);  
					 
					
				}
				else
				{ 
					$msg_text = 'Email address already taken';  
					
					$redirect_url = URL::to('/').'/register/'.$msg_text;
					
					return Redirect::to($redirect_url); 
				} 
			
			

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>$msg_text);
			} 
			catch (\Exception  $e)
			{
				DB::rollback();

				$msg=array(
					'type'=>'error',
					'code'=>0,
					'text'=>$e->getMessage());
			}
		}
 
		
		
		
		if((Request::ajax()) && (Request::isMethod('post')))
		{ 
			$json = json_encode($msg);  
			
			return response($json, 200);//->header('Content-Type', 'application/json;');
		}  
    } 
	
	 /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function confirmation($id)
    { 
        $params = explode('_' , $id);
		 
		 $id = $params[0];
		 if(sizeof($params) > 1)
		 {
			 $msg_text = $params[1];
		 }
		$user = User::select_single($id);  
		$user = $user[0];
		 

		$msg = array(); 
		
		return View::make('login.confirm')
		->with('id', $id)
		->with('user',$user) 
		->with('msg_text',$msg_text)  ;
		 
    } 
	 
	public function show($id)
    {
		    
		 
		$params = explode('_' , $id);
		$action  = 'login';
		$referral_code = '';
		$id = $params[0];
		if(sizeof($params) > 1)
		{
			 $referral_code = $params[1];
		}
		if($id == 'refcode')
		{ 
			$action = 'login';
		}
		elseif($id > 0)
		{
			$user = User::select_by_id($id);  
			$user = $user[0];
			$action = 'confirm';
		}
		else{
			
		}
		 
 
		$user = array('full_name' => '', 'id' => 0, 'username' =>  '', 'first_name' =>  '', 'last_name' =>  '', 'email' =>  '', 'mobile_number' =>  '', 'activated' =>  0 ) ;
	   
		if(Request::ajax()) 
		{ 
			 
		}
		else
		{ 
			 // load the view and pass the variables 
			return View::make('login.'.$action )
            ->with('user', $user)
			->with('id', $id) 
            ->with('mode', '')
			->with('action','')	 ;
		} 
	}		
	  
}