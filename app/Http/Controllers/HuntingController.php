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
use App\Hunting;
use App\Http\Controllers\Controller;

class HuntingController extends Controller {	

	// /hunting
	public function index(){
		// If energy is 0, redirect to sleeping
		if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') == 0)
			return redirect('sleeping');
		
		$hunt_progress = false;
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
		
			if (DB::table('hunting_temp')->where('username', Auth::user()->username)->count()>0){
				$d1 = date('Y-m-d H:i:s');
				$d2 = DB::table('hunting_temp')->where('username', Auth::user()->username)->pluck('hunt_ending');
				if ($d1 >= $d2){
					$hunt_progress = false;
					DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => NULL]);
					if (DB::table('hunting_temp')->where('username', Auth::user()->username)->pluck('type') == 'S'){
						if (DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id') >= 2)
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.25);
						else
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.2);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.1);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.1);
						DB::table('characters')->where('username', Auth::user()->username)->increment('level', 0.05);
					} else if (DB::table('hunting_temp')->where('username', Auth::user()->username)->pluck('type') == 'M'){
						if (DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id') >= 2)
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.55);
						else
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.5);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.25);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.25);
						DB::table('characters')->where('username', Auth::user()->username)->increment('level', 0.12);
					} else {
						if (DB::table('users_inventions')->where('user_id', Auth::user()->id)->pluck('last_invention_id') >= 2)
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.85);
						else
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.8);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.4);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0.00]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.4);
						DB::table('characters')->where('username', Auth::user()->username)->increment('level', 0.2);
					}
					DB::table('hunting_temp')->where('username', Auth::user()->username)->delete();
				}
				else{
					$hunt_progress = true;
				}
			}
		$d1 = date('Y-m-d H:i:s');
		$d2 = date('Y-m-d H:i:s');
		if (Auth::check())
			$d2 = DB::table('hunting_temp')->where('username', Auth::user()->username)->pluck('hunt_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'hunt_progress' => $hunt_progress,
				'time_left' => $diff,
				'action' => $action
			);
		
		return view('hunting')->with('data', $data);
	}
	
	public function store(){
		
	}
	
	public function postHunt(){
		if (Input::get('hunt_type') == 'S'){
			$hunt_end = new DateTime(date('Y-m-d H:i:s'));
			if(DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') >= 1) {
				$duration = 30/DB::table('characters')->where('username', Auth::user()->username)->pluck('strength');
				$hunt_end->modify("+{$duration} minutes");
			}else
				$hunt_end->modify('+30 minutes');
		} else if (Input::get('hunt_type') == 'M'){
			$hunt_end = new DateTime(date('Y-m-d H:i:s'));
			if(DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') >= 1) {
				$duration = 60/DB::table('characters')->where('username', Auth::user()->username)->pluck('strength');
				$hunt_end->modify("+{$duration} minutes");
			}else
			$hunt_end->modify('+60 minutes');
		} else {
			$hunt_end = new DateTime(date('Y-m-d H:i:s'));
			if(DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') >= 1) {
				$duration = 90/DB::table('characters')->where('username', Auth::user()->username)->pluck('strength');
				$hunt_end->modify("+{$duration} minutes");
			}else
			$hunt_end->modify('+90 minutes');
		}
	
		Hunting::create(array(
			'username' => Auth::user()->username,
			'type' => Input::get('hunt_type'),
			'hunt_ending' => $hunt_end
		));
		
		DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => 'hunting']);
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = DB::table('hunting_temp')->where('username', Auth::user()->username)->pluck('hunt_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'hunt_progress' => true,
				'time_left' => $diff,
				'action' => $action
			);
		
		return view('hunting')->with('data', $data);
	}
}