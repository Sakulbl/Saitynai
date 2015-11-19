<?php

namespace App\Http\Controllers;

use Validator;
use Input;
use Redirect;
use Hash;
use Auth;
use DB;
use DateTime;
use App\User;
use App\Character;
use App\Inventions;
use App\Http\Controllers\Controller;

class InventionsController extends Controller {	

	// /inventions
	public function index(){
		// If energy is 0 and hunger is 100, redirect to sleeping
		if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') == 0 &&
			DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') == 100)
			return redirect('sleeping');
	
		// If energy is 0, redirect to sleeping
		if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') == 0)
			return redirect('sleeping');
			
		// If hunger is 100, redirect to hunting
			return redirect('hunting');
	
		$invention_progress = false;
		
		if (Auth::check())
			// Update time when user was last seen on site and energy and hunger values			
			$d1 = DB::table('characters')->where('username', Auth::user()->username)->pluck('last_updated');
			$d2 = date('Y-m-d H:i:s');
			$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
			$reduce_energy = ($diff/20)/100;
			$increase_hunger = ($diff/4)/100;;
			DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', $reduce_energy);
			if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
				DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
			DB::table('characters')->where('username', Auth::user()->username)->increment('hunger', $increase_hunger);
			if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') > 1)
				DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 1]);
			DB::table('characters')->where('username', Auth::user()->username)->update(['last_updated' => date('Y-m-d H:i:s')]);
		
			if (DB::table('users_inventions')->where('user_id', Auth::user()->id)->count()>0)
			{
				if (DB::table('users_inventions')->where(function ($query) {
						$query->where('user_id', '=', Auth::user()->id)
							  ->where('invention_ending', '<>', '0000-00-00 00:00:00');
					})
					->get())
				{
					$d1 = date('Y-m-d H:i:s');
					$d2 = DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('invention_ending');
					if ($d1 >= $d2){
						$invention_progress = false;
						DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => NULL]);
						DB::table('users_inventions')->where('user_id', Auth::user()->id)->increment('last_invention_id', 1);
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.5);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('intellect', 1);
						DB::table('characters')->where('username', Auth::user()->username)->increment('level', 0.5);
						DB::table('users_inventions')->where('user_id', Auth::user()->id)->update(['invention_ending' => 'NULL']);
						DB::table('users_inventions')->where('user_id', Auth::user()->id)->update(['invention_in_progress' => false]);
					}
					else{
						$invention_progress = true;
					}
				}
			}
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = date('Y-m-d H:i:s');
		if (Auth::check())
			$d2 = DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('invention_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$invented_count = DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id');
		
		$invented = array();
		for ($i = 0; $i < $invented_count; $i++){
			$invented[] = DB::table('inventions')->where('id', $i+1)->pluck('invention_name');
		}
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'invention_in_progress' => $invention_progress,
				'time_left' => $diff,
				'all_invented' => false,
				'inventions' => $invented,
				'action' => $action
			);
	
		return view('inventing')->with('data', $data);
	}
	
	public function store(){
		
	}
	
	public function postInvent(){
		$invention_progress = true;
		if(DB::table('users_inventions')->where('user_id', Auth::user()->id)->count()>0){
			if(DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id') > 0 &&
			DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id') < 10)
			{
				$invention_end = new DateTime(date('Y-m-d H:i:s'));
				$invention_end->modify('+300 minutes');
				$invention_progress = true;
				DB::table('users_inventions')->where('user_id', Auth::user()->id)->update(['invention_in_progress' => true]);
				DB::table('users_inventions')->where('user_id', Auth::user()->id)->update(['invention_ending' => $invention_end]);
			}
			else {
					$data = array(
					'invention_in_progress' => false,
					'time_left' => 0,
					'all_invented' => true
					);
					return view('inventing')->with('data', $data);
			}
		}
		else{
			$invention_end = new DateTime(date('Y-m-d H:i:s'));
			$invention_end->modify('+300 minutes');
			$invention_progress = true;
			Inventions::create(array(
				'user_id' => Auth::user()->id,
				'last_invention_id' => 0,
				'invention_in_progress' => true,
				'invention_ending' => $invention_end
			));
		}
		
		DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => 'inventing']);
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('invention_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		$invented = array();
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'invention_in_progress' => $invention_progress,
				'time_left' => $diff,
				'all_invented' => false,
				'inventions' => $invented,
				'action' => $action
			);
			
		return view('inventing')->with('data', $data);
	}
}