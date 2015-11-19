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
use App\Fighting;
use App\Http\Controllers\Controller;

class FightingController extends Controller {
	// /fighting
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
	
		$fight_progress = false;
		$fight_message = 'You recently finished a fight: ';
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
			
			if (DB::table('fighting_temp')->where('username', Auth::user()->username)->count()>0){
				$d1 = date('Y-m-d H:i:s');
				$d2 = DB::table('fighting_temp')->where('username', Auth::user()->username)->pluck('fight_ending');
				if ($d1 >= $d2){
					$fight_progress = false;
					$show_message = true;
					
					$opponent = DB::table('fighting_temp')->where('username', Auth::user()->username)->pluck('opponent_username');
					
					DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => NULL]);
					
					if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') >
						DB::table('characters')->where('username', $opponent)->pluck('energy') && 
						DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') >
						DB::table('characters')->where('username', $opponent)->pluck('strength')){
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.3);
						DB::table('characters')->where('username', $opponent)->decrement('energy', 0.3);
						if (DB::table('characters')->where('username', $opponent)->pluck('energy') < 0)
							DB::table('characters')->where('username', $opponent)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						$fight_message = $fight_message.'you won.';
					} else if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') >
						DB::table('characters')->where('username', $opponent)->pluck('energy') && 
						DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') <
						DB::table('characters')->where('username', $opponent)->pluck('strength')){
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						DB::table('characters')->where('username', $opponent)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', $opponent)->pluck('energy') < 0)
							DB::table('characters')->where('username', $opponent)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						$fight_message = $fight_message.'you tied.';
					} else if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') <
						DB::table('characters')->where('username', $opponent)->pluck('energy') && 
						DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') >
						DB::table('characters')->where('username', $opponent)->pluck('strength')){
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						DB::table('characters')->where('username', $opponent)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', $opponent)->pluck('energy') < 0)
							DB::table('characters')->where('username', $opponent)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						$fight_message = $fight_message.'you tied.';
					} else if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') <
						DB::table('characters')->where('username', $opponent)->pluck('energy') && 
						DB::table('characters')->where('username', Auth::user()->username)->pluck('strength') <
						DB::table('characters')->where('username', $opponent)->pluck('strength')){
						DB::table('characters')->where('username', Auth::user()->username)->decrement('energy', 0.30);
						if (DB::table('characters')->where('username', Auth::user()->username)->pluck('energy') < 0)
							DB::table('characters')->where('username', Auth::user()->username)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.15);
						DB::table('characters')->where('username', $opponent)->decrement('energy', 0.15);
						if (DB::table('characters')->where('username', $opponent)->pluck('energy') < 0)
							DB::table('characters')->where('username', $opponent)->update(['energy' => 0]);
						DB::table('characters')->where('username', Auth::user()->username)->increment('strength', 0.30);
						$fight_message = $fight_message.'you lost.';
					}
					
					DB::table('fighting_temp')->where('username', Auth::user()->username)->delete();
				}
				else{
					$fight_progress = true;
				}
			}
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = date('Y-m-d H:i:s');
		if (Auth::check())
			$d2 = DB::table('fighting_temp')->where('username', Auth::user()->username)->pluck('fight_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$online = array();
		$num = 0;
		$row_characters = DB::table('characters')->get();
		
		foreach($row_characters as $character){
			$d3 = $character->last_updated;
			$d4 = date('Y-m-d H:i:s');
			$diff2 = round(abs(strtotime($d4) - strtotime($d3)) / 60);
			if ($diff2 <= 5 && $character->username != Auth::user()->username){
				$online[] = $character;
				$num++;
			}
			if ($num > 5)
				break;
		}
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'fight_progress' => $fight_progress,
				'time_left' => $diff,
				'fight_message' => $fight_message,
				'show_message' => $show_message,
				'online' => $online,
				'action' => $action
			);
		return view('fighting')->with('data', $data);
	}
	
	public function store(){
		
	}
	
	public function getFight($name){
		$fight_end = new DateTime(date('Y-m-d H:i:s'));
		$fight_end->modify('+10 minutes');
	
		Fighting::create(array(
			'username' => Auth::user()->username,
			'opponent_username' => $name,
			'fight_ending' => $fight_end
		));
		
		DB::table('characters')->where('username', Auth::user()->username)->update(['action_name' => 'fighting']);
		
		$d1 = date('Y-m-d H:i:s');
		$d2 = DB::table('fighting_temp')->where('username', Auth::user()->username)->pluck('fight_ending');
		$diff = round(abs(strtotime($d2) - strtotime($d1)) / 60);
		
		$fight_message = '';
		$show_message = false;
		$online = array();
		
		$action = DB::table('characters')->where('username', Auth::user()->username)->pluck('action_name');
		
		$data = array(
				'fight_progress' => true,
				'time_left' => $diff,
				'fight_message' => $fight_message,
				'show_message' => $show_message,
				'online' => $online,
				'action' => $action
			);
		
		return redirect('fighting')->with('data', $data);
	}
}