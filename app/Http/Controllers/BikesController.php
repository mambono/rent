<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\models\Bikes;  
use App\models\Cities; 
use App\models\User;
use App\models\Vendors;
use Auth;
use DB;
use Request;
use Route;
use View;


class BikesController extends Controller
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
		$usergroups = User::select_single(Auth::id()); //dd(DB::getQueryLog());
					
		$usergroup_id = $usergroups[0]['group_id']; 
		$usergroupname = $usergroups[0]['group_name'];
		
	    if($usergroupname =='Administrator')
		{
			$bikes = Bikes::select_all(); 
		}
		elseif($usergroupname =='Vendor')
		{
			$bikes = Bikes::select_my_bikes(Auth::id());
		}		
		 
		 
	     
		for($i = 0;$i <= sizeof($bikes)-1; $i ++)
		{
			$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
		}  
		 
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($bikes);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('bikes.index')
            ->with('bikes', $bikes);
		} 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add($id)
    { 
		$bikes = Bikes::select_single($id); 
		$cities = Cities::where('delete_flag',0)->orderBy('city')->pluck('city', 'id');	   
		$vendors = Vendors::select_all()->pluck('vendor_name', 'id');
		$city_id  = 0;
		$vendor_id  = 0;
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
				  
				DB::table('bikes')->insert($form_data); 

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Bike added sucessfully');
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
			return View::make('bikes.form', compact('id', 'cities', 'vendors') )
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('city_id',$city_id)
			->with('vendor_id',$vendor_id)
			->with('electric',$electric)
			->with('form',$bikes)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('bikes.form', compact('id', 'cities', 'vendors'))
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('city_id',$city_id)
			->with('vendor_id',$vendor_id)
			->with('electric',$electric)
			->with('form',$bikes)  
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
	  
		$bikes = Bikes::select_single($id)->toArray();  
		$bikes = $bikes[0];  
		$cities = Cities::where('delete_flag',0)->orderBy('city')->pluck('city', 'id');	  
		$vendors = Vendors::select_all()->pluck('vendor_name', 'id');
		$vendor_id  = $bikes['vendor_id'];
		$city_id  = $bikes['city_id'];
		$electric  = $bikes['electric'];
		
		$bikes['hourly_cost'] = number_format($bikes['hourly_cost'], 2); 
		
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['modified_by'] = Auth::id();
			$form_data['modified_on'] = date('Y-m-d H:i:s');
			unset($form_data['_token']);
			
			try 
			{
				Bikes::whereId($id)->update($form_data);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Bike updated sucessfully');
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
			 return View::make('bikes.form', compact('id', 'cities', 'vendors'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('city_id',$city_id)
			->with('vendor_id',$vendor_id)
			->with('electric',$electric)
			->with('form',$bikes)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('bikes.form', compact('id', 'cities', 'vendors'))
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('city_id',$city_id)
			->with('vendor_id',$vendor_id)
			->with('electric',$electric)
			->with('form',$bikes)  
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
        $bikes = Bikes::select_single($id); 
		 
		$bikes = $bikes[0]; 
		
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
				Bikes::soft_delete($deleteid, $form_data['modified_by'], $form_data['modified_on']);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'Bike(ies) deleted sucessfully');
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
			return View::make('bikes.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$bikes) 
			->with('msg',$msg) ;  
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('bikes.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$bikes) 
			->with('msg',$msg) ; 
		} 
    }
	
	public function show()
    {
	}
	

}