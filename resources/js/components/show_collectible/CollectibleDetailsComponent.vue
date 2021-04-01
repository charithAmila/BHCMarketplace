<template>
  <div>
    <div class="details-tab">
      <ul class="nav nav-tabs">
        <li><a href="#home" @click="detailsActive()" class="tabLink">Details</a></li>
        <li><a href="#holder" @click="holderActive()" class="tabLink">Holder</a></li>
        
        <li class="active">
          <a href="#past_transactions" @click="bidsActive()" class="tabLink">Bids</a>
        </li>
      </ul>
      <div class="tabGroup">
        <div id="home" class="tab-pane" v-bind:class="{'tab-active' : home_active}">
          <div class="row dtab">
            <div class="col-3 col-md-2">
              <div class="inlineDiv">
                <a :href="user_profile + '/' + creator.wallet">
                  <img class="br-50" :src="creator.display_photo" width="50" />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position">Designer</label>
              <label class="positionHolder"
                ><a :href="user_profile + '/' + creator.wallet">{{
                  creator.name
                }}</a></label
              >
            </div>
          </div>

          <div class="row dtab">
            <div class="col-3 col-md-2">
              <div class="inlineDiv">
                <a :href="user_profile + '/' + current_owner.wallet">
                  <img
                    class="br-50"
                    :src="current_owner.display_photo"
                    width="50"
                  />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position">Holder</label>
              <label class="positionHolder"
                ><a :href="user_profile + '/' + current_owner.wallet">{{
                  current_owner.name
                }}</a></label
              >
            </div>
          </div>

          <div class="row dtab">
            <div class="col-3 col-md-2">
              <div class="inlineDiv">
                <a :href="asset_url + 'collection/' + collection.address">
                  <img class="br-50" :src="collection.icon" width="50" />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position">Collection</label>
              <label class="positionHolder"
                ><a :href="asset_url + 'collection/' + collection.address">{{
                  collection.name
                }}</a></label
              >
            </div>
          </div>
        </div>

        <div id="holder" class="tab-pane" v-bind:class="{'tab-active' : holder_active}">
          <div v-for="(owner, index) in owners" :key="index" class="row dtab">
            <div class="col-3 col-md-2">
              <div class="inlineDiv">
                <a :href="user_profile + '/' + owner.user_profile">
                  <img
                    class="br-50"
                    :src="asset_url + owner.asset_url"
                    width="50"
                  />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position">Holder</label>
              <label class="positionHolder"
                ><a :href="user_profile + '/' + owner.user_profile">{{
                  owner.user_name
                }}</a></label
              >
            </div>
          </div>
        </div>

        <div id="past_transactions" class="tab-pane" v-bind:class="{'tab-active' : bid_active}" >
          <button type="submit" class="btn btn-success" v-if="!biddingStatus && owner" @click="startBid()"> Open Token for Bidding
          </button>
           <button type="submit" class="btn btn-danger" v-if="biddingStatus && owner" @click="endBid()">Remove from Bidding
          </button>
            <button type="submit" class="btn btn-success" v-if="biddingStatus && owner" @click="acceptBid()">Accept Highest Bid
          </button>
          <div v-for="(transac, index) in allBids" :key="index" class="row dtab"  v-show="biddingStatus"
          >
            <div class="col-3 col-md-2">
              <div class="inlineDiv">

                <a :href="user_profile + '/' + transac.bidding_address">
                  <img
                    class="br-50"
                    :src=asset_url
                    width="50"
                  />

                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position"
                >{{ transac.bidding_amount }} {{transac.bidding_token}}
                <span class="positionHolder">{{ bidding_amount }}</span>
              on {{ transac.created_at.slice(0,10) }} by
                <a :href="user_profile + '/' + transac.bidding_address"
                  ><span class="positionHolder">{{
                    transac.bidding_address
                  }}</span></a
                >
              </label>

              <!--label v-if="transac.action == 'Offered'" class="position"
                >{{ transac.action }}
                <span class="positionHolder">{{ transac.price }}</span> for
                {{ transac.quantity }} edition {{ transac.sub_context
                }}{{ transac.time_ago }} by
                <a :href="user_profile + '/' + transac.user_profile"
                  ><span class="positionHolder">{{
                    transac.user_name
                  }}</span></a
                >
              </label-->

              <!--label v-if="transac.action == 'Put on sale for'" class="position"
                >{{ transac.action }}
                <span class="positionHolder">{{ transac.price }}</span>
                {{ transac.time_ago }} by
                <a :href="user_profile + '/' + transac.user_profile"
                  ><span class="positionHolder">{{
                    transac.user_name
                  }}</span></a
                >
              </label>
            </div-->
          </div>
        </div>
      </div>
    </div>
  </div></div>
</template>

<script>

import { getHighestBid, getBiddingStatus,getAllBids,startBidding,endBidding, getConnectedAddress} from ".././../bidFunc";


export default {
  props: [
    "creator",
    "current_owner",
    "owners",
    "transactions",
    "user_profile",
    "collectible",
   // "asset_url",
    "collection",
    "collection_image",
    "collection_url",
  ],
  data() {
    return {


     asset_url :  "https://cdn.pixabay.com/photo/2017/06/13/12/53/profile-2398782_1280.png",
     allBids:{},
     bid_active: false,
     home_active: true,
     holder_active:false,
     biddingStatus:false,
     owner:false,
     highestBid:{}

    };
  },
  async mounted() {
    //var highestBid = await getHighestBid(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
    var address = await getConnectedAddress();
    var allBids = await getAllBids(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
    var output = await getBiddingStatus(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
  this.highestBid = await getHighestBid(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
   this.biddingStatus = output;
    this.allBids = allBids;
    if(address == this.current_owner.wallet){
      this.owner = true;
    }
  },
  methods: {
    detailsActive(){
      this.home_active = true;
      this.holder_active = false;
      this.bid_active = false
    },
holderActive(){
  this.home_active = false;
  this.holder_active = true;
  this.bid_active = false
},
bidsActive(){
  this.home_active =false;
  this.holder_active =false;
  this.bid_active = true;
},

startBid(){
var res  = startBidding(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
console.log(res);
}
,
endBid(){
var res = endBidding(this.current_owner.wallet,this.collectible.contract, this.collectible.id);
console.log(res);
},
acceptBid(){
var res = acceptBidding()
}

  }

};
</script>