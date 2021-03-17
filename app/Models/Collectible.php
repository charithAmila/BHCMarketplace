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


    public function getCollectionNft($collection_id, $isp=0,  $category=null, $sortBy='updated_at', $order='asc'){
        $records = Record::join('collectibles', 'records.nft_id', '=', 'collectibles.id')->where('copies_left', '>', 0);
        $records = $records->where('collectibles.collection_id', $collection_id);
        if ($isp == 1) {
            $records = $records->where('records.selling', $isp);
        }
        $records = $records->get('records.*');

        $data = $this->setNftData($records, $category, $sortBy, $order);

        return $data;
    }


    public function fetchAllCollectibles($category=null, $sortBy='updated_at', $order='asc', $search=null){
        if ($search == null) {
            $records = Record::join('collectibles', 'records.nft_id', '=', 'collectibles.id')
                ->where('copies_left', '>', 0);
            if ($sortBy == 'updated_at') {
                $records = $records->orderBy('records.updated_at', 'desc');
            }else{
                $records = $records->orderBy('collectibles.price', $order);
            }

            $records = $records->get('records.*');
        }
        else{
            if ($search!=null) {
                $searchArr = [];
                for ($i=strlen($search); $i >= 1 ; $i--) {
                    $q = substr($search, 0, $i);
                    $records = Record::join('collectibles', 'records.nft_id', '=', 'collectibles.id')
                                ->where('copies_left', '>', 0);
                    $records = $records->where('collectibles.name', 'LIKE', "%{$q}%");
                    $searchArr[] = $records->get('records.*');
                }

                $records = $searchArr[0];
                foreach ($searchArr as $collection) {
                    $records = $records->merge($collection);
                }
            }
        }

        $data = $this->setNftData($records, $category, $sortBy, $order);

        return $data;
        
    }

    public function profileNft($user_id, $isp = 0, $created = 0, $category = null, $sortBy = null, $order = null, $operand= '>'){

        // $records = Record::where('copies_left', '>', 0)->where('owner_id', $user_id)->get();

        $records = Record::join('collectibles', 'records.nft_id', '=', 'collectibles.id');
        if ($created) {
            $records = $records->join('transactions', 'transactions.record_id', '=', 'records.id');
            $operand = '=';
        }
        $records = $records->where('records.copies_left', $operand, 0);
        $records = $records->where('records.owner_id', $user_id);

        if ($isp == 1) {
            $records = $records->where('records.selling', $isp);
        }
                    
        if ($created) {
            $records = $records->where('transactions.type', '=', 'sell')->orWhere('transactions.type', '=', 'bidding');
        }
        $records = $records->get('records.*');


        $data = $this->setNftData($records, $category, $sortBy, $order, $isp);

        return $data;
    }

    public function getCreatedSelling($user_id){
        $records = Record::join('transactions', 'transactions.record_id', '=', 'records.id');
        $records = $records->where('transactions.type', 'sell');
        $records = $records->where('records.owner_id', $user_id);
        $records = $records->where('records.copies_left', 0);
        $records = $records->get('records.*');
        return $records;
    }

    public function getCreatedBidding($user_id){
        $records = Record::join('transactions', 'transactions.record_id', '=', 'records.id');
        $records = $records->where('transactions.type', 'bidding');
        $records = $records->where('records.owner_id', $user_id);
        $records = $records->where('records.copies_left', 0);
        $records = $records->get('records.*');
        return $records;
    }

    public function getLikedNft($liked){
        $records_ids = [];
        foreach ($liked as $value) {
            $records_ids[] = $value->record_id;
        }
        $records = Record::find($records_ids);
        return $records;
    }

    public function filterQuery($user_id, $isp=0, $created=0, $liked=0, $category=null, $sortBy=null, $order=null, $operand='>'){
        if ($created) {
            $selling = $this->getCreatedSelling($user_id);
            $bidding = $this->getCreatedBidding($user_id);
            $records = $selling->concat($bidding);
        }
        elseif ($liked) {
            $liked = Like::where('user_id', $user_id)->get();
            $records = $this->getLikedNft($liked);
        }
        else{
            $records = Record::join('collectibles', 'records.nft_id', '=', 'collectibles.id');
            $records = $records->where('records.copies_left', $operand, 0);
            $records = $records->where('records.owner_id', $user_id);

            if ($isp == 1) {
                $records = $records->where('records.selling', $isp);
            }
            $records = $records->get('records.*');
        }

        $data = $this->setNftData($records, $category, $sortBy, $order, $isp);

        return $data;
    }

    public function setNftData($records, $category, $sortBy, $order){
        $data = [];
        foreach ($records as $key => $record) {
            $collectible = Collectible::where('id',$record->nft_id);
            if ($category) {
                if ($category != 'all') {
                    $collectible = $collectible->where('category_id', intval($category));
                }
            }
            $collectible = $collectible->first();

            if (!empty($collectible)) {
                $ext = pathinfo($collectible->nft, PATHINFO_EXTENSION);
                $type = $this->checkFileType($ext);
                $price = $collectible->price != null ? $collectible->price : 'Not for sale';
                $currency = $price == 'Not for sale' ? '' : $collectible->currency;
                $new_price = $price .' '. $currency;
                $copies = $record->copies_left. ' of '.$record->copies;

                $legend = Legend::find($collectible->legend_id);

                $user = User::find($record->owner_id);

                $data[] = [
                    'id' => $collectible->id,
                    'record_id' => $record->id,
                    'user_slug' => $user->short_url != null ? $user->short_url : $user->wallet,
                    'owner_id' => $record->owner_id,
                    'legend' => $legend->legend,
                    'icon' => $legend->icon,
                    'nft' => $collectible->nft,
                    'price' => $new_price,
                    'name' => $collectible->name,
                    'slug' => $collectible->slug,
                    'type' => $type,
                    'copies' => $copies,
                    'isp' => $collectible->isp,
                    'is_selling' => $record->selling,
                ];
            }
        }
        return $data;
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
                    'id' => $collectible->id,
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
                'id' => $collectible->id,
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
            'collection_url' => $collection->short_url,
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
            'collection_url' => $collection['collection_url'],
            'available' => $this->getAvailable($collectible->copies),
            'quantity' => $collectible->copies,
            'isp' => $collectible->isp,
            'owners' => $owners,

        ];
        return $data;
    }

    public function fetchThisCollectible($user_slug, $slug, $collectible){

        $user = User::where('short_url', $user_slug)->orWhere('wallet', $user_slug)->first();
        $record = Record::where('nft_id', $collectible->id)->where('owner_id', $user->id)->first();

        $legend = Legend::find($collectible->legend_id);
        $category = Category::find($collectible->category_id);
        $ext = pathinfo($collectible->nft, PATHINFO_EXTENSION);
        $type = $this->checkFileType($ext);


        $transaction = Transaction::where('type', 'sell')->orWhere('type', 'bidding')->where('nft_id', $collectible->id)->first();
        $creator = User::find($transaction->user_id);
        $creator = $this->getUserInfoShowPage($creator);

        $currentOwner = $this->getUserInfoShowPage($user);

        $allOwners = Record::where('nft_id', $collectible->id)->get();
        $owners = [];
        foreach ($allOwners as $owner) {
            $user = User::find($owner->owner_id);
            $owners[] = $this->getUserInfoShowPage($user);
        }

        $collection = $this->getCollection($collectible->collection_id);


        $data = [
            'id' => $collectible->id,
            'link_user_slug' => $user_slug,
            'link_nft_slug' => $slug,
            'record_id' => $record->id,
            'is_selling' => $record->selling,
            'nft_slug' => $collectible->slug,
            'nft' => $collectible->nft,
            'type' => $type,
            'legend' => $legend->legend,
            'icon' => $legend->icon,
            'name' => $collectible->name,
            'price' => $this->getProductPrice($collectible->isp, $collectible->price, $collectible->currency),
            'raw_price' => $collectible->price,
            'description' => $collectible->description,
            'creator' => $creator,
            'current_owner' => $currentOwner,
            'collection' => $collection['collection_name'],
            'collection_image' => $collection['collection_image'],
            'collection_url' => $collection['collection_url'],
            'available' => $this->getAvailable($record->copies, $record->copies_left),
            'copies_left' => $record->copies_left,
            'copies' => $record->copies,
            'isp' => $collectible->isp,
            'owners' => $owners,

        ];

        return $data;


    }

    public function getAvailable($copies, $copies_left){
        $available = $copies_left.' of '.$copies;
        return $available;
    }


    public function getUserInfoShowPage($user){

        $asset_url = 'storage/user/photo/'.$user->display_photo;
        if ($user->display_photo == 'default.png') {
            $asset_url = 'user/photo/'.$user->display_photo;
        }

        $user_name = $user->name != null ? $user->name : $user->wallet;

        $data = [
            'user_id' => $user->id,
            'asset_url' => $asset_url,
            'user_name' => $user_name,
            'user_profile' => $user->short_url != null ? $user->short_url : $user->wallet,
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


    public function fetchUsers($start_date, $end_date, $groupby){
        $q = Transaction::where('type', 'buy')
                ->whereRaw("created_at BETWEEN (?) AND (?)", [$start_date, $end_date])
                ->groupBy($groupby)
                ->orderByRaw('SUM(price) DESC')
                ->select(DB::raw("SUM(price) as totalPrice"), $groupby)
                ->get();

        $q2 = Transaction::where('type', 'bid')
                ->where('sold',1)
                ->whereRaw("created_at BETWEEN (?) AND (?)", [$start_date, $end_date])
                ->groupBy($groupby)
                ->orderByRaw('SUM(price) DESC')
                ->select(DB::raw("SUM(price) as totalPrice"), $groupby)
                ->get();


        $total = $q->concat($q2); // push 2nd collection to 1st collection

        // SET final Price
        $final = $total->groupBy($groupby)->map(function ($row) use ($groupby) {
            $data = [
                $groupby => $row[0]->$groupby,
                'finalPrice' => $row->sum('totalPrice')
            ];
            return $data;
        });
        //###############


        // Collection to array
        $sortedFinal = $final->toArray();
        $key_id = [];
        $key_value = [];

        foreach ($sortedFinal as $key => $row) {
            $key_id[$key]  = $row[$groupby];
            $key_value[$key] = $row['finalPrice'];
        }

        // Sort by final price
        array_multisort($key_value, SORT_DESC, $key_id, SORT_DESC, $sortedFinal);

        $mergeDuplicate = [];
        // MERGE ALL DUPLICATE DATA
        if ($groupby == 'record_id') {
            foreach ($sortedFinal as $key => $value) {
                $record = Record::find($value['record_id']);
                if(count($mergeDuplicate) > 0){
                    foreach ($mergeDuplicate as $key_exist => $exist) {
                        if ($exist['user_id'] == $record->owner_id) {
                            $mergeDuplicate[$key_exist]['finalPrice'] += $value['finalPrice'];
                            unset($sortedFinal[$key]); // remove item at index 0
                            if (isset($sortedFinal[$key_exist])) {
                                $sortedFinal[$key_exist]['finalPrice'] += $value['finalPrice'];
                            }
                            
                        }
                        else{
                            $mergeDuplicate[] = [
                                'user_id' => $record->owner_id,
                                'finalPrice' => $value['finalPrice']
                            ];
                        }
                    }
                }else{
                    $mergeDuplicate[] = [
                        'user_id' => $record->owner_id,
                        'finalPrice' => $value['finalPrice']
                    ];
                }
            }
            
        }

        $sortedFinal = array_values($sortedFinal); // 'reindex' array
        
        // #####################################################

        $return_data = [
            'groupby' => $groupby,
            'finalList' => $sortedFinal
        ];

        return $return_data;
    }


    public function getUserList($day, $type){
        $data = [];
        $prev_day = $day != 'all' ? $day : 365;
        $start_date = date("Y-m-d", strtotime('-'.$prev_day.' days'))." 00:00:00";
        $end_date = date('Y-m-d')." 23:59:59";
        $groupby = $type == 'sell' ? 'record_id' : 'user_id';

        $topList = $this->fetchUsers($start_date, $end_date, $groupby);


        foreach ($topList['finalList'] as $item) {
            if ($topList['groupby'] == 'record_id') {
                $record = Record::find($item['record_id']);
                $user = User::find($record->owner_id);
            }else{
                $user = User::find($item['user_id']);
            }
            $asset_url = 'storage/user/photo/'.$user->display_photo;
            if ($user->display_photo == 'default.png') {
                $asset_url = 'user/photo/'.$user->display_photo;
            }
            $profile_link = $user->short_url != null ? $user->short_url : $user->wallet;
            $data[] = [
                'totalPrice' => $item['finalPrice'],
                'display_name' => $user->name != null ? $user->name : $user->wallet,
                'asset_url' => $asset_url,
                'profile_url' => \URL::to('/').'/profile/'.$profile_link,
            ];
        }

        return $data;
    }
}
