<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Controller;
use App\models\User;
use App\models\Usergroups;  
use Illuminate\Support\Str;
use Cookie;
use Auth;
use DB;
use Redirect;
use Request; 
use Session;
use URL;
use View;

class LoginController extends Controller
{
	private function getPostData()
	{
		  
		 return Request::post(); 
	} 

	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$id = 1;
		
 
		$user = array('full_name' => '', 'id' => 0, 'username' =>  '', 'first_name' =>  '', 'last_name' =>  '', 'email' =>  '', 'mobile_number' =>  '', 'activated' =>  0 ) ;
	  
		
		  
		if(Request::ajax()) 
		{ 
			//return response($json, 200);//->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the nerds
			return View::make('login.login')
            ->with('user', $user)
            ->with('mode', '')
			->with('action','');
		} 
    }
	
	public function authenticate()
    {
		if((Request::ajax()) && (Request::isMethod('post')))
		{
			$mode = '';
			$redirect_url = '';
			$form_data = $this->getPostData();
			$email = $form_data['email']; 
			$password = $form_data['password']; 
			$remember = 1;
 
			
			if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) 
			{
				// Get the currently authenticated user...
				$user = Auth::user();
				if($user->delete_flag == 0)
				{
					// Get the currently authenticated user's ID...
					$id = Auth::id();
					$user = Auth::User();
					
					//$usergroups = User::select_groups_by_userid($id); //dd(DB::getQueryLog());
					$usergroups = User::select_single($id); //dd(DB::getQueryLog());
					
					$usergroup_id = $usergroups[0]['group_id']; 
					$usergroupname = Usergroups::select_by_id($usergroup_id); 
					$usergroupname = $usergroupname[0]['name']; 
					 
				
					Session::put('username', $user->email);
					Session::put('usergroupname', $usergroupname);
					Session::put('usergroup_id', $usergroup_id);
							
					// The user is active, not suspended, and exists.
					 $msg = array(
						'type'=>'Success', 
						'code'=>1,
						'text'=>'Login sucessful'); 
					 
					$msg['redirect_url'] = $redirect_url; 
					$msg['userId'] = $user["id"];
					$msg['username'] = $user->username;
					$msg['email'] = $email;
				}
				else
				{
					Auth::logout(); 
					Session::flush(); 
					Session::forget('username');
					Session::forget('usergroupname');
					Session::forget('usergroup_id');
					$msg = array(
						'type'=>'Error', 
						'code'=>0,
						'text'=>'Your account has not been activated!');
				}
			}
			else
			{
				$msg = array(
					'type'=>'Error', 
					'code'=>0,
					'text'=>'Invalid credentials entered');
			}
			$json = json_encode($msg);  
		
			return response($json, 200);//->header('Content-Type', 'application/json;');
		}
	}
	
	
	public function forgotpassword()
    { 
		
		$msg = array(); 
		if((Request::ajax()) && (Request::isMethod('post')))
		{
			
			$form_data = $this->getPostData();
			$email = $form_data['email']; 

			// start with a blank password
			$password = "";
			
			// define possible characters
			$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 

			// set up a counter
			$i = 0; 
			// add random characters to $password until $length is reached

			while ($i < 8)
			{ 			

				// pick a random character from the possible ones
				$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

				// we don't want this character if it's already in the password
				if (!strstr($password, $char)) 
				{ 
				  $password .= $char;
				  $i++;
				}	
			}
			 
			 
			
			$hashedpassword = Hash::make($password);
			
			try 
			{  
				$checkemail = User::select_by_email($email); 
		 
				if(sizeof($checkemail) > 0)
				{ 
					User::change_password(array($email), $hashedpassword);  
					
					$systemconfig = Systemconfig::select_single(1);  
					$website_url = $systemconfig[0]['website_path'];
					$website_name = $systemconfig[0]['website_name'];					
					$smtp_mail_server = $systemconfig[0]['smtp_mail_server'];
					$smtp_username = $systemconfig[0]['smtp_username'];
					$smtp_password = $systemconfig[0]['smtp_password'];
					$smtp_mail_server_port = $systemconfig[0]['smtp_mail_server_port'];
					$from_email_address = $systemconfig[0]['site_email_address'];
					$created_on = date('Y-m-d H:i:s');
					
					 
					
					$recipientusers[0]['user_id'] = $checkemail[0]['id'];
					$recipientusers[0]['user_names'] = $checkemail[0]['first_name'].' '.$checkemail[0]['last_name'];

					$recipientusers[0]['full_name'] = $checkemail[0]['first_name'].' '.$checkemail[0]['last_name'];
					$recipientusers[0]['email'] = $checkemail[0]['email'];
					
					$recordID['password'] = $password; 
				
				
					$emailtemplates = Emailtemplates::select_by_subject('Forgot Password');
					$emailtemplates = $emailtemplates[0];  
					  
					$id = $checkemail[0]['id'];
					$recordID['id'] = $checkemail[0]['id']; 
					 /*
					if(sizeof($emailtemplates) > 0)
					{
						$template_content = $emailtemplates[0]['template_content']; 
						$recipient_template_data['template_name'] = $emailtemplates[0]['template_name']; 
					 
								
			 
						$template_content = str_replace('{{RecipientName}}',  $recipientusers[0]['user_names'], $template_content);
						
						$template_content = str_replace('{{website_name}}',  $website_name, $template_content);
						$template_content = str_replace('{{WebsiteURL}}',  $website_url, $template_content);
						
						$template_content = str_replace('{{password}}',  $password, $template_content); 

						//$recipient_template_data['template_content'] = $template_content;						
					}*/
					
					 
					$this->send_email_notification($emailtemplates, $recipientusers, '', $id, $recordID);
					
					//$this->send_email_notification($emailtemplates, $recipientusers, '', $id, $recordID);
					
					$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Password reset sucessfully. New password has been sent to your email address.');
  
   
					
				}
				else
				{
					$msg = array(
					'type'=>'Error',
					'code'=>0,
					'text'=>'Email address provided does not exist in the database.');
				}
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
		else
		{ 
			 // load the view and pass data
			return View::make('login.forgotpassword')
			->with('action','forgotpassword')
			->with('msg',$msg);
		} 
	}
	
	
	public function switch($group_id)
    { 
		
		$id = Auth::id();
		$user = Auth::User();
		 
		Session::forget('usergroupname' );  
		Session::forget('usergroup_id' );  
		session()->flush();
		
		$usergroups = User::select_groups_by_userid($id); //dd(DB::getQueryLog());
		$usergroup_id = $usergroups[0]['usergroup_id']; 
		$usergroupname = Usergroups::select_by_id($group_id);
		$usergroupname = $usergroupname[0]['name']; 
		
		 
		Session::put('usergroupname', $usergroupname);
		Session::put('usergroup_id', $group_id);
		 
		Auth::login($user);
		 
		//return Redirect::to(url()->previous());
		
		
		
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
			$form_data['created_by'] = Auth::id();
			$form_data['created_on'] = date('Y-m-d H:i:s');
			$form_data['delete_flag'] = 0;
			$form_data['activated'] = 0;
			$form_data['ota_code'] = $ota_code;
			$form_data['mobile_number'] = '+'.$form_data['mobile_number_phoneCode'].$form_data['mobile_number'];
			$form_data['username'] = $form_data['email'];
			$password = $form_data['reg_password'];
			unset($form_data['_token']);
			unset($form_data['mode']);
			unset($form_data['user_id']);
			unset($form_data['reg_password']);
			unset($form_data['password2']);
			unset($form_data['mobile_number_phoneCode']);
			unset($form_data['created_by']);
			 
			
			$form_data['password'] = Hash::make($password);
			
			$systemconfig = Systemconfig::select_single(1); 
			$what3words_api = $systemconfig[0]['what3words_api']; 
			$google_maps_api = $systemconfig[0]['google_maps_api'];
			$website_url = $systemconfig[0]['website_path'];
			$website_name = $systemconfig[0]['website_name'];					
			$smtp_mail_server = $systemconfig[0]['smtp_mail_server'];
			$smtp_username = $systemconfig[0]['smtp_username'];
			$smtp_password = $systemconfig[0]['smtp_password'];
			$smtp_mail_server_port = $systemconfig[0]['smtp_mail_server_port'];
			$from_email_address = $systemconfig[0]['site_email_address'];
				 
			$form_data['referral_code'] =  $form_data['first_name'][0].$form_data['last_name'][0].$ota_code;
			
			try 
			{
				  
				
				 $checkemail = User::select_by_email($form_data['email']); 
		
					
				if(sizeof($checkemail) == 0)
				{
					 
					$id = DB::table('users')->insertgetid($form_data);  
					$msg_text = 'Registration submitted sucessfully';  
					 
					
					$redirect_url = URL::to('/').'/register/confirmation/'.$id.'_'.$msg_text;
					return Redirect::to($redirect_url);
				}
				else
				{ 
					$msg_text = 'Email address already taken';  
					$redirect_url = URL::to('/').'/login/'.$msg_text;
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
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}  
    } 
	 
	public function logout()
	{ 
		Session::flush();
		Auth::logout();
		return Redirect::to('/login');
	  
	}
		 
	public function show()
	{
		
		return Redirect::to('/login');
	}
	

}