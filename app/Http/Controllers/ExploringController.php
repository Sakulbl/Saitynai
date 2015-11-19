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
use App\Exploring;
use App\Http\Controllers\Controller;

class ExploringController extends Controller {	

	// /exploring
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
	
		$explore_progress = false;
		$explore_message_food = 'During your recent exploration, you have found food: ';
		$show_message = false;
		
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
		
			if (DB::table('exploring_temp')->where('username', Auth::user()->username)->count()>0){
				$d1 = date('Y-m-d H:i:s');
				$d2 = DB::table('exploring_temp')->where('username', Auth::user()->username)->pluck('explore_ending');
				if ($d1 >= $d2){
					$explore_progress = false;
					DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => NULL]);
					$show_message = true;
					$num = rand(1, 100);
					if (DB::table('exploring_temp')->where('username', Auth::user()->username)->pluck('type') == '1'){
						if ($num <= 70){
							$explore_message_food = $explore_message_food.'some nuts and berries... You reduced 25 % hunger but you wish you had 
														spent that time hunting instead.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.25);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						} else if ($num > 70 && $num <= 90){
							$explore_message_food = $explore_message_food.'some fish washed out by the sea. You reduced 50 % hunger and you are happy
														with your exploration.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.5);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}	else {
							$explore_message_food = $explore_message_food.'a red deer that just died. You reduced 75 % hunger and you are sure that today 
														was a very lucky today.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.75);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}
					} else if (DB::table('exploring_temp')->where('username', Auth::user()->username)->pluck('type') == '2'){
						if ($num <= 50){
							$explore_message_food = $explore_message_food.'some nuts and berries... You reduced 25 % hunger but you wish you had 
														spent that time hunting instead.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.25);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						} else if ($num > 50 && $num <= 80){
							$explore_message_food = $explore_message_food.'some fish washed out by the sea. You reduced 50 % hunger and you are happy
														with your exploration.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.5);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}	else {
							$explore_message_food = $explore_message_food.'a red deer that just died. You reduced 75 % hunger and you are sure that today 
														was a very lucky today.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.75);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}
					} else {
						if ($num <= 30){
							$explore_message_food = $explore_message_food.'some nuts and berries... You reduced 25 % hunger but you wish you had 
														spent that time hunting instead.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.25);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						} else if ($num > 30 && $num <= 60){
							$explore_message_food = $explore_message_food.'some fish washed out by the sea. You reduced 50 % hunger and you are happy
														with your exploration.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.5);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}	else {
							$explore_message_food = $explore_message_food.'a red deer that just died. You reduced 75 % hunger and you are sure that today 
														was a very lucky today.';
							DB::table('characters')->where('username', Auth::user()->username)->decrement('hunger', 0.75);
							if (DB::table('characters')->where('username', Auth::user()->username)->pluck('hunger') < 0)
								DB::table('characters')->where('username', Auth::user()->username)->update(['hunger' => 0]);
						}
					}
					DB::table('exploring_temp')->where('username', Auth::user()->username)->delete();
				}
				else{
					$explore_progress = true;
				}
			}
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = date('Y-m-d H:i:s');
		if (Auth::check())
			$d2 = DB::table('exploring_temp')->where('username', Auth::user()->username)->pluck('explore_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'explore_progress' => $explore_progress,
				'time_left' => $diff,
				'explore_message_food' => $explore_message_food,
				'show_message' => $show_message,
				'action' => $action
			);
		return view('exploring')->with('data', $data);
	}
	
	public function store(){
		
	}
	
	public function postExplore(){
		if (Input::get('explore_type') == '1'){
			$explore_end = new DateTime(date('Y-m-d H:i:s'));
			$explore_end->modify('+60 minutes');
		} else if (Input::get('sleep_type') == '2'){
			$explore_end = new DateTime(date('Y-m-d H:i:s'));
			$explore_end->modify('+120 minutes');
		} else {
			$explore_end = new DateTime(date('Y-m-d H:i:s'));
			$explore_end->modify('+180 minutes');
		}
	
		Exploring::create(array(
			'username' => Auth::user()->username,
			'type' => Input::get('explore_type'),
			'explore_ending' => $explore_end
		));
		
		DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => 'exploring']);
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = DB::table('exploring_temp')->where('username', Auth::user()->username)->pluck('explore_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$explore_message_food = '';
		$show_message = false;
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'explore_progress' => true,
				'time_left' => $diff,
				'explore_message_food' => $explore_message_food,
				'show_message' => $show_message,
				'action' => $action
			);
		
		return view('exploring')->with('data', $data);
	}
}