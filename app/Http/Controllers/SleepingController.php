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
use App\Sleeping;
use App\Http\Controllers\Controller;

class SleepingController extends Controller {	

	// /sleeping
	public function index(){
		$sleep_progress = false;
		
		if(Auth::check())
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
		
			if (DB::table('sleeping_temp')->where('username', Auth::user()->username)->count()>0){
				$d1 = date('Y-m-d H:i:s');
				$d2 = DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('sleep_ending');
				if ($d1 >= $d2){
					$sleep_progress = false;
					DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => NULL]);
					if (DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('type') == '4'){
						DB::table('characters')->where('username', Auth::user()->username)->increment('energy', 0.4);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') > 1)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 1]);
					} else if (DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('type') == '6'){
						DB::table('characters')->where('username', Auth::user()->username)->increment('energy', 0.6);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') > 1)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 1]);
					} else if (DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('type') == '8'){
						DB::table('characters')->where('username', Auth::user()->username)->increment('energy', 0.8);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') > 1)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 1]);
					} else {
						DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 1]);
					}
					DB::table('sleeping_temp')->where('username', Auth::user()->username)->delete();
				}
				else{
					$sleep_progress = true;
				}
			}
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = date('Y-m-d H:i:s');
		if (Auth::check())
			$d2 = DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('sleep_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'sleep_progress' => $sleep_progress,
				'time_left' => $diff,
				'action' => $action
			);
		return view('sleeping')->with('data', $data);
	}
	
	public function store(){
		
	}
	
	public function postSleep(){
		if (Input::get('sleep_type') == '4'){
			$sleep_end = new DateTime(date('Y-m-d H:i:s'));
			$sleep_end->modify('+240 minutes');
		} else if (Input::get('sleep_type') == '6'){
			$sleep_end = new DateTime(date('Y-m-d H:i:s'));
			$sleep_end->modify('+360 minutes');
		} else if (Input::get('sleep_type') == '8'){
			$sleep_end = new DateTime(date('Y-m-d H:i:s'));
			$sleep_end->modify('+480 minutes');
		} else {
			$sleep_end = new DateTime(date('Y-m-d H:i:s'));
			$sleep_end->modify('+600 minutes');
		}
	
		Sleeping::create(array(
			'username' => Auth::user()->username,
			'type' => Input::get('sleep_type'),
			'sleep_ending' => $sleep_end
		));
		
		DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => 'sleeping']);
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = DB::table('sleeping_temp')->where('username', Auth::user()->username)->pluck('sleep_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'sleep_progress' => true,
				'time_left' => $diff,
				'action' => $action
			);
		
		return view('sleeping')->with('data', $data);
	}
}