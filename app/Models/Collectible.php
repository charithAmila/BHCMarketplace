<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Collectible extends Model
{
    use HasFactory;

    protected $fillable = [
        'nft',
        'aop',
        'aop_link',
        'collection_id',
        'type',
        'name',
        'royalties',
        'copies',
        'pos',
        'isp',
        'price',
        'currency',
        'category_id',
        'legend_id',
    ];


    public function getAvailable($copies){
    	$available = $copies.' of '.$copies;
    	return $available;

    }

    public function getCollectibles($items, $isp = 0){
        $data = [];

        foreach ($items as $item) {
            $collectible = Collectible::where('id', $item->nft_id);
            if ($isp == 1) {
            	$collectible = $collectible->where('isp', 1);
            }
            $collectible = $collectible->first();

            if (!empty($collectible)) {
                $ext = pathinfo($collectible->nft, PATHINFO_EXTENSION);
                $type = $this->checkFileType($ext);
                $price = $collectible->price != null ? $collectible->price : 'Not for sale';
                $currency = $price == 'Not for sale' ? '' : $collectible->currency;
                $new_price = $price .' '. $currency;
                $copies = $collectible->copies. ' of '.$collectible->copies;

                $legend = Legend::find($collectible->legend_id);

                $data[] = [
                    'legend' => $legend->legend,
                    'icon' => $legend->icon,
                    'nft' => $collectible->nft,
                    'price' => $new_price,
                    'name' => $collectible->name,
                    'slug' => $collectible->slug,
                    'type' => $type,
                    'copies' => $copies,
                    'isp' => $collectible->isp,
                ];
            }
        }

        return $data;
    }

    public function checkFileType($extension){
        $extension = strtolower($extension);
        $images = ['gif', 'png', 'jpg'];
        $videos = ['mp4', 'webp', 'mp3'];
        $type = in_array($extension, $images) ? 'image' : 'video';
        return $type;
    }

    public function getAllCollectibles($collectibles){
        $data = [];
        foreach ($collectibles as  $collectible) {
            $ext = pathinfo($collectible->nft, PATHINFO_EXTENSION);
            $type = $this->checkFileType($ext);
            $price = $collectible->price != null ? $collectible->price : 'Not for sale';
            $currency = $price == 'Not for sale' ? '' : $collectible->currency;
            $new_price = $price .' '. $currency;
            $copies = $collectible->copies. ' of '.$collectible->copies;

            $legend = Legend::find($collectible->legend_id);

            $data[] = [
                'legend' => $legend->legend,
                'icon' => $legend->icon,
                'nft' => $collectible->nft,
                'price' => $new_price,
                'name' => $collectible->name,
                'slug' => $collectible->slug,
                'type' => $type,
                'copies' => $copies,
                'isp' => $collectible->isp,
            ];
        }
        return $data;
    }


    public function getOwners($nft_id){
        $ownership = Ownership::where('nft_id', $nft_id)->get();

        $owners = [];
        foreach ($ownership as $item) {
            $user = User::find($item->owner_id);
            $asset_url = 'storage/user/photo/'.$user->display_photo;
            if ($user->display_photo == 'default.png') {
                $asset_url = 'user/photo/'.$user->display_photo;
            }

            $owners[] = [
                'owner_id' => $item->owner_id,
                'owner_name' => $user->name != null ? $user->name : $user->wallet,
                'owner_url' => $user->short_url != null ? $user->short_url : $user->wallet,
                'asset_url' => $asset_url,
            ];
        }
        return $owners;
    }

    public function getDesigner($nft_id){
        $designer = Ownership::where('nft_id', $nft_id)->first();
        $designer = User::find($designer->owner_id);

        $asset_url = 'storage/user/photo/'.$designer->display_photo;
        if ($designer->display_photo == 'default.png') {
            $asset_url = 'user/photo/'.$designer->display_photo;
        }

        $designer_name = $designer->name != null ? $designer->name : $designer->wallet;

        $data = [
            'asset_url' => $asset_url,
            'designer_id' => $designer->id,
            'designer_name' => $designer_name,
            'designer_profile' => $designer->short_url != null ? $designer->short_url : $designer->wallet,
        ];

        return $data;
    }

    public function getCollection($collection_id){
        $collection = Collection::find($collection_id);
        $collection_img =  $collection->image == 'default/logo.png' ? 'collections/'.$collection->image : 'storage/collections/'.$collection->image;

        $data = [
            'collection_name' => $collection->display_name,
            'collection_image' => $collection_img,
        ];

        return $data;
    }

    public function getProductPrice($isp, $price, $currency){
        $price = $isp == 0 ? 'Not for sale' : $price.' '.$currency;
        return $price;
    }


    public function getSpecificCollectible($collectible){
        $legend = Legend::find($collectible->legend_id);
        $category = Category::find($collectible->category_id);

        $owners = $this->getOwners($collectible->id);
        $designer = $this->getDesigner($collectible->id);
        $collection = $this->getCollection($collectible->collection_id);

        $ext = pathinfo($collectible->nft, PATHINFO_EXTENSION);
        $type = $this->checkFileType($ext);

        $data = [
            'nft_slug' => $collectible->slug,
            'nft' => $collectible->nft,
            'type' => $type,
            'legend' => $legend->legend,
            'icon' => $legend->icon,
            'name' => $collectible->name,
            'price' => $this->getProductPrice($collectible->isp, $collectible->price, $collectible->currency),
            'raw_price' => $collectible->price,
            'description' => $collectible->description,
            'asset_url' => $designer['asset_url'],
            'designer' => $designer['designer_name'],
            'designer_id' => $designer['designer_id'],
            'designer_profile' => $designer['designer_profile'],
            'collection' => $collection['collection_name'],
            'collection_image' => $collection['collection_image'],
            'available' => $this->getAvailable($collectible->copies),
            'quantity' => $collectible->copies,
            'isp' => $collectible->isp,
            'owners' => $owners,

        ];
        return $data;
    }

    public function getTopUsers($day, $type){
        $data = [];
        $prev_day = $day != 'all' ? $day : 365;
        $start_date = date("Y-m-d", strtotime('-'.$prev_day.' days'))." 00:00:00";
        $end_date = date('Y-m-d')." 23:59:59";
        $transaction = Transaction::where('type', $type)
                            ->whereRaw("created_at BETWEEN (?) AND (?)", [$start_date, $end_date])
                            ->groupBy('user_id')
                            ->orderByRaw('SUM(price) DESC')
                            ->select(DB::raw("SUM(price) as totalPrice"), 'user_id')
                            ->take(15)
                            ->get();
        

        foreach ($transaction as $item) {
            $user = User::find($item->user_id);
            $asset_url = 'storage/user/photo/'.$user->display_photo;
            if ($user->display_photo == 'default.png') {
                $asset_url = 'user/photo/'.$user->display_photo;
            }
            $data[] = [
                'totalPrice' => $item->totalPrice,
                'display_name' => $user->name != null ? $user->name : $user->wallet,
                'asset_url' => $asset_url,
            ];
        }
        return $data;
    }
}
