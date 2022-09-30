<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller; 
use App\models\Cities;  
use App\models\User;
use Auth;
use DB;
use Request;
use Route;
use View;


class CitiesController extends Controller
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
 
	    $cities = Cities::select_all(); 
		for($i = 0;$i <= sizeof($cities)-1; $i ++)
		{
			$cities[$i]['created_on'] = date('d M Y', strtotime($cities[$i]['created_on'])); 
		}  
		 
		  
		if(Request::ajax()) 
		{ 
			$json = json_encode($cities);  
			
			return response($json, 200)->header('Content-Type', 'application/json;'); 
		}
		else
		{ 
			 // load the view and pass the variables
			return View::make('cities.index')
            ->with('cities', $cities);
		} 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add($id)
    { 
		$cities = Cities::select_single($id);  //dd(DB::getQueryLog());	 
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
				  
				DB::table('cities')->insert($form_data); 

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'City added sucessfully');
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
			return View::make('cities.form' )
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('form',$cities)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('cities.form')
			->with('action','add')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('form',$cities)  
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
	  
		$cities = Cities::select_single($id)->toArray();  
		$cities = $cities[0];  
	  
		$msg = array(); 
		
		
		if (Request::isMethod('post'))
		{
			$form_data=  $this->getPostData(); 
			$form_data['modified_by'] = Auth::id();
			$form_data['modified_on'] = date('Y-m-d H:i:s');
			unset($form_data['_token']);
			
			try 
			{
				Cities::whereId($id)->update($form_data);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'City updated sucessfully');
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
			 return View::make('cities.form')
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id)
			->with('form',$cities)  
			->with('msg',$msg) ; 
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('cities.form')
			->with('action','edit')
			->with('sbt_button','Save')
			->with('id',$id) 
			->with('form',$cities)  
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
        $cities = Cities::select_single($id); 
		 
		$cities = $cities[0]; 
		
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
				Cities::soft_delete($deleteid, $form_data['modified_by'], $form_data['modified_on']);
				

				$msg=array(
					'type'=>'Success',
					'code'=>1,
					'text'=>'City(ies) deleted sucessfully');
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
			return View::make('cities.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$cities) 
			->with('msg',$msg) ;  
		} 
		else 
		{
		 
			 // load the view and pass the variables
			return View::make('cities.form_delete')
			->with('action','delete')
			->with('sbt_button','Delete')
			->with('id',$id)
			->with('form',$cities) 
			->with('msg',$msg) ; 
		} 
    }
	
	public function show()
    {
	}
	

}