<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\models\Bikes;  
use App\models\Bookings; 
use App\models\User;
use App\models\Vendors;
use Auth;
use DB;
use Redirect;
use Request;
use Route;
use URL;
use View;


class BookingsController extends Controller
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
			$bikes = Bookings::select_all(); 
		}
		elseif($usergroupname =='Vendor')
		{
			$bikes = Bookings::select_my_bikes(Auth::id());
		}		
		
  
		$vendors = Vendors::select_all();
		$bike_list = Bikes::select_all();
		$users = User::select_vendors('Standard'); 		
		
		$bike_id = null;
		$vendor_id = null;
		$user_id = null; 
				
		$data = array();
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$date_range = $form_data["date_range"];
			$date_range = explode(" - ", $date_range); 
			$start_date = date('Y-m-d', strtotime($date_range[0])); 
			$end_date = date('Y-m-d', strtotime($date_range[1])); 
			if(isset($form_data["bike_id"]))
			{				
				$bike_id = $form_data["bike_id"];
			}
			if(isset($form_data["vendor_id"]))
			{				
				$vendor_id = $form_data["vendor_id"];
			}
			if(isset($form_data["user_id"]))
			{				
				$user_id = $form_data["user_id"];
			}
			
			$bikes = Bookings::select_filtered($bike_id, $vendor_id, $user_id, $start_date, $end_date); 
		}
		
		for($i = 0;$i <= sizeof($bikes)-1; $i ++)
		{ 
			
			$bikes[$i]['interval'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
			 
			
			$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
			$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
			$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
			
			$bikes[$i]['totalcost'] = number_format($bikes[$i]['interval']*$bikes[$i]['hourly_cost'], 2);
		}    
		 
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($bikes);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('bookings.list') 
            ->with('bikes', $bikes)
			->with('bike_list',$bike_list)  
			->with('users',$users) 
			->with('vendors',$vendors)  ;
		} 
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function vendor()
    { 
		$usergroups = User::select_single(Auth::id()); //dd(DB::getQueryLog());
					
		$usergroup_id = $usergroups[0]['group_id']; 
		$usergroupname = $usergroups[0]['group_name'];
		
	    if($usergroupname =='Administrator')
		{
			$bikes = Bookings::select_all(); 
		}
		elseif($usergroupname =='Vendor')
		{
			$bikes = Bookings::select_my_bikes(Auth::id());
		}		
		 
		 
		for($i = 0;$i <= sizeof($bikes)-1; $i ++)
		{ 
			
			$bikes[$i]['interval'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
			 
			
			$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
			$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
			$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
			
			$bikes[$i]['totalcost'] = number_format($bikes[$i]['interval']*$bikes[$i]['hourly_cost'], 2);
		}    
		 
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($bikes);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('bookings.vendor')
            ->with('bikes', $bikes);
		} 
    }
	
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function my()
    { 
 
	    $bikes = Bookings::select_my(Auth::id()); 
		for($i = 0;$i <= sizeof($bikes)-1; $i ++)
		{ 
			
			$bikes[$i]['interval'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
			 
			
			$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
			$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
			$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
			
			$bikes[$i]['totalcost'] = number_format($bikes[$i]['interval']*$bikes[$i]['hourly_cost'], 2);
		}   
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($bikes);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('bookings.my')
            ->with('bikes', $bikes);
		} 
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function book()
    { 
		$start_date = '';
		$end_date = '';
		
		$bike_id = null;
		$vendor_id = null;
		$user_id = null; 
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			unset($form_data['_token']);
			$date_range = $form_data["date_range"];
			$date_range = explode(" - ", $date_range); 
			$start_date = date('Y-m-d', strtotime($date_range[0])); 
			$end_date = date('Y-m-d', strtotime($date_range[1])); 
		} 
		
	    $bikes = Bookings::select_filtered($bike_id, $vendor_id, $user_id, $start_date, $end_date);//dd(DB::getQueryLog()); 
		
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
			return View::make('bookings.index')
            ->with('action', 'book')
            ->with('bikes', $bikes);
		} 
    }
	 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function add($id)
    { 
	  
		$bikes = Bikes::select_single($id)->toArray();  
		$bikes = $bikes[0];  
		  
		$vendor_id  = $bikes['vendor_id'];
		$city_id  = $bikes['city_id']; 
		
		$bikes['electric'] = $bikes['electric'] == 1 ?  'YES'  : 'NO';
		
		$bikes['hourly_cost'] = number_format($bikes['hourly_cost'], 2); 
		
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['booked_by'] = Auth::id(); 
			$form_data['booked_on'] = date('Y-m-d H:i:s');
			$form_data['bike_id'] = $id;
			$form_data['delete_flag'] = 0;
			unset($form_data['_token']);
			
			if($form_data['start_date'])
			{
				$form_data['start_date'] = date('Y-m-d H:i:s', strtotime($form_data['start_date']));  
			} 
			if($form_data['end_date'])
			{
				$form_data['end_date'] = date('Y-m-d H:i:s', strtotime($form_data['end_date']));  
			} 
			try 
			{
				DB::table('bike_bookings')->insert($form_data); 

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'action'=>'add',
					'redirect_url'=>URL::to('/').'/bookings/my',
					'text'=>'Booking added sucessfully');
					
					//$redirect_url = URL::to('/').'/bookings/my' ;
					
					//return Redirect::to($redirect_url); 
				 
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
			 return View::make('bookings.form' )
			->with('action','add')
			->with('sbt_button','Book')
			->with('id',$id)  
			->with('form',$bikes)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('bookings.form' )
			->with('action','add')
			->with('sbt_button','Book')
			->with('id',$id)  
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