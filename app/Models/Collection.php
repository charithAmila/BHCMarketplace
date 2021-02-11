<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Collection extends Model
{
    use HasFactory;


    public function getCollections(){
    	$q = Collection::where('user_id', null);
        if (Auth::check()) {
            $q->orWhere('user_id', Auth::user()->id);
        }
        $collections = $q->get();

        $data = [];

        foreach ($collections as $key => $collection) {
            $default = 0;
            if ($collection->image == 'default/logo.png') {
                $default = 1;
            }
            $data[] = [
                'id' => $collection->id,
                'image' => $collection->image,
                'symbol' => $collection->symbol,
                'display_name' => $collection->display_name,
                'default' => $default,
            ];
        }

        return $data;
    }
}
