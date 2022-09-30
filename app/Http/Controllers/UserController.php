<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller; 
use App\models\Counties;
use App\models\User;  
use App\models\Usergroups; 
use Auth;
use DB;
use Mail;
use Redirect;
use Request;
use Route;
use Session;
use URL;
use View;

class UserController extends Controller
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
 
	    $users = User::select_all_admin(); //dd(DB::getQueryLog());die;
		for($i = 0;$i <= sizeof($users)-1; $i ++)
		{
			 
			$users[$i]['created_on'] = date('d M Y', strtotime($users[$i]['created_on']));  
		}  
		 
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($users);  
			
			return response($json, 200);//->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the nerds
			return View::make('users.index')
            ->with('header', 'Users')
            ->with('users', $users);
		} 
    }
	 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id=null)
    { 		
		
		if(Auth::check())
		{
			$user_id = Auth::id();
			 
			$user_data = User::select_single($user_id);
			$usergroup_id = Session::get('usergroup_id');   
			$usergroup_name = Session::get('usergroupname');
			if(!$id)
			{
				$id = $user_id;
			}
		 
		}
		
		
		$users = User::select_single($id);    
		
		$usergroup_list = Usergroups::where('delete_flag',0)->orderBy('name')->pluck('name', 'id');	  
		$users = $users[0];  
		$group_id = $users['group_id'] ; 
		 
		$msg = array();  
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['modified_by'] = Auth::id();
			$form_data['modified_on'] = date('Y-m-d H:i:s'); 
			unset($form_data['_token']);
			 
			try 
			{
				
				User::whereId($id)->update($form_data);
				 
	

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'User data updated sucessfully');
				
				 
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
		if(Request::ajax())
		{ 
			 // load the view and pass the variables
			 return View::make('users.form', compact('id', 'usergroup_list'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('group_id',$group_id) 
			->with('form',$users)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('users.form', compact('id', 'usergroup_list'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('group_id',$group_id) 
			->with('form',$users)  
			->with('msg',$msg) ; 
		}
		 
    }
	
	 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $users = User::select_single($id); 
		 
		$users = $users[0]; 
		
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['modified_by'] = Auth::id();
			$form_data['modified_on'] = date('Y-m-d H:i:s');
			$form_data['delete_flag'] = 1;
			$form_data['id'] = $id;
			unset($form_data['_token']);
			
			try 
			{
				$deleteid = explode("_", $id);
				User::soft_delete($deleteid, $form_data['modified_by'], $form_data['modified_on']);
				
				$old_value =  json_encode($users);

				$new_value = json_encode($form_data);	
				$route = Route::current();
	
	
				$this->userAuditLog(Auth::id(), 'User Deletion', $old_value, $new_value,  $route, date("Y-m-d H:i:s") );
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'User(s) deleted sucessfully');
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
		if(Request::ajax())
		{ 
			 // load the view and pass the variables
			return View::make('users.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$users) 
			->with('msg',$msg) ;  
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('users.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$users) 
			->with('msg',$msg) ; 
		} 
    }
	 
	
public function show()
{
	
}	
	 

}