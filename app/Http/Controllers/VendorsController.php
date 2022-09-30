<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\models\Vendors;  
use App\models\Cities; 
use App\models\User;
use Auth;
use DB;
use Request;
use Route;
use View;


class VendorsController extends Controller
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
 
	    $vendors = Vendors::select_all();  
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($vendors);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('vendors.index')
            ->with('vendors', $vendors);
		} 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add($id)
    { 
		$vendors = Vendors::select_single($id); 
		$cities = Cities::where('delete_flag',0)->orderBy('city')->pluck('city', 'id');	   	
		$users = User::select_vendors('Vendor')->pluck('full_name', 'id');
		$city_id  = 0;
		$user_id  = 0;
		$electric  = 0;
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['created_by'] = Auth::id();
			$form_data['created_on'] = date('Y-m-d H:i:s');
			$form_data['delete_flag'] = 0;
			unset($form_data['_token']);
			
			try 
			{
				  
				DB::table('vendors')->insert($form_data); 

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Vendor added sucessfully');
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
			return View::make('vendors.form', compact('id', 'cities', 'users') )
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('city_id',$city_id)
			->with('user_id',$user_id)
			->with('form',$vendors)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('vendors.form', compact('id', 'cities', 'users'))
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('city_id',$city_id)
			->with('user_id',$user_id)
			->with('form',$vendors)  
			->with('msg',$msg) ; 
		}
    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    { 
	  
		$vendors = Vendors::select_single($id)->toArray();   //dd(DB::getQueryLog());	  
		$vendors = $vendors[0];  
		$cities = Cities::where('delete_flag',0)->orderBy('city')->pluck('city', 'id');	  
		$users = User::select_vendors('Vendor')->pluck('full_name', 'id');	 	 	
 		
		$user_id  = $vendors['user_id'];
		$city_id  = $vendors['city_id']; 
		  
		
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['modified_by'] = Auth::id();
			$form_data['modified_on'] = date('Y-m-d H:i:s');
			unset($form_data['_token']);
			
			try 
			{
				Vendors::whereId($id)->update($form_data);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Vendor updated sucessfully');
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
			 return View::make('vendors.form', compact('id', 'cities', 'users'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('city_id',$city_id)
			->with('user_id',$user_id) 
			->with('form',$vendors)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('vendors.form', compact('id', 'cities', 'users'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('city_id',$city_id)
			->with('user_id',$user_id) 
			->with('form',$vendors)  
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
        $vendors = Vendors::select_single($id); 
		 
		$vendors = $vendors[0]; 
		
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
				Vendors::soft_delete($deleteid, $form_data['modified_by'], $form_data['modified_on']);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Vendor(ies) deleted sucessfully');
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
			return View::make('vendors.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$vendors) 
			->with('msg',$msg) ;  
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('vendors.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$vendors) 
			->with('msg',$msg) ; 
		} 
    }
	
	public function show()
    {
	}
	

}