<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function getPastTransactions($nft_id){
    	$userModel = new User;
    	$transactions = Transaction::where('nft_id', $nft_id)->get();
    	$data = [];
    	foreach ($transactions as $item) {
    		$user = User::find($item->user_id);
    		$data[] = [
    			"action" => $this->getAction($item->type),
    			"price" => "$item->price $item->currency",
    			"sub_context" => $this->getSubContext($item->type),
    			"time_ago" => $this->timeAgo($item->created_at),
    			"user_name" => $user->name != null ? $user->name : $user->wallet,
    			"user_image" => $userModel->getDisplayPhoto($user->display_photo),
    			"user_profile" => $user->short_url != null ? $user->short_url : $user->wallet,
    		];
    	}
    	return $data;
    }


    public function getAction($type){
    	if ($type == 'sell') {
    		return "Put on sale for";
    	}
    	if ($type == 'buy'){
    		return "Bought for";
    	}
    	if ($type == 'offer'){
    		return "Offered";
    	}
    	if ($type == 'offer_cancel'){
    		return "Offer cancelled";
    	}
    }

    public function getSubContext($type){
    	if ($type == 'offer_cancel'){
    		return "for 1 edition ";
    	}
    	return null;
    }


    public function timeAgo($created_at){
    	$date1 = strtotime($created_at);
		$date2 = strtotime(date('Y-m-d H:i:s'));
		$seconds_diff = $date2 - $date1;

		$timeDiff = $this->timeDiff($seconds_diff);
		$key = array_key_first($timeDiff);
		$text = ngettext($key, $key."s", $timeDiff[$key]);

		return $timeDiff[$key]." ".$text;
    }

    public function timeDiff($mins_diff) {
	    if (!$mins_diff) return 0;
	    $periods = ['week' => 604800,'day' => 86400,'hour' => 3600,'minute' => 60, 'second' => 1];
	    $output = [];
	    foreach ($periods as $period_name => $period) {
	        $num_periods = floor($mins_diff / $period);
	        if ($num_periods > 1) {
	            $output[$period_name] = intval($num_periods);
	        }
	        elseif ($num_periods > 0) {
	            $output[$period_name] = intval($num_periods);
	        }

	        $mins_diff -= $num_periods * $period;
	    }
	    return $output;
	}
}
