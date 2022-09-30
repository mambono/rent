<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\models\Bikes;  
use App\models\Bookings; 
use App\models\User;
use Auth;
use DB;
use Redirect;
use Request;
use Route;
use URL;
use View;


class ApiController  
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
	  
    public function bookings($id)
    { 
		if($id == 'all')
		{
			$bikes = Bookings::select_all(); 
			
			 
			for($i = 0;$i <= sizeof($bikes)-1; $i ++)
			{ 
				$bikes[$i]['electric'] = $bikes[$i]['electric'] == 1 ?  'YES'  : 'NO';
				
				$bikes[$i]['total_hours'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
				 
				
				$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
				$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
				$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
				
				$bikes[$i]['totalcost'] = number_format($bikes[$i]['total_hours']*$bikes[$i]['hourly_cost'], 2);
			}    
			 
			$json = json_encode($bikes);  
			return response($json, 200)->header('Content-Type', 'application/json;'); 			
		}			
    }
	
	public function vendorbikes($id)
    { 
		 
		$bikes = Bookings::select_vendor_bikes($id); 
		 
		 
		for($i = 0;$i <= sizeof($bikes)-1; $i ++)
		{ 
			
			$bikes[$i]['electric'] = $bikes[$i]['electric'] == 1 ?  'YES'  : 'NO';
				
			$bikes[$i]['total_hours'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
			 
			
			$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
			$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
			$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
			
			$bikes[$i]['totalcost'] = number_format($bikes[$i]['total_hours']*$bikes[$i]['hourly_cost'], 2);
		}    
		 
		$json = json_encode($bikes);  
		return response($json, 200)->header('Content-Type', 'application/json;'); 		
    }
	
	public function booking_status($id)
    { 
		if($id == 'booked')
		{
			$bikes = Bookings::select_all(); 
			
			 
			for($i = 0;$i <= sizeof($bikes)-1; $i ++)
			{ 
				$bikes[$i]['electric'] = $bikes[$i]['electric'] == 1 ?  'YES'  : 'NO';
				
				$bikes[$i]['total_hours'] =  (strtotime($bikes[$i]['end_date']) - strtotime($bikes[$i]['start_date']))/3600;
				 
				
				$bikes[$i]['hourly_cost'] = number_format($bikes[$i]['hourly_cost'], 2); 
				$bikes[$i]['start_date'] = date('d M Y H:i', strtotime($bikes[$i]['start_date']));
				$bikes[$i]['end_date'] = date('d M Y H:i', strtotime($bikes[$i]['end_date']));
				
				$bikes[$i]['totalcost'] = number_format($bikes[$i]['total_hours']*$bikes[$i]['hourly_cost'], 2);
			}    
			 
			$json = json_encode($bikes);  
			return response($json, 200)->header('Content-Type', 'application/json;'); 			
		}			
    }
	
	public function show()
    {
	}
	

}