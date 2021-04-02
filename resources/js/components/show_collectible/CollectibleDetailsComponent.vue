<template>
  <div>
    <div class="details-tab">
      <ul class="nav nav-tabs">
        <li>
          <a href="#home" @click="detailsActive()" class="tabLink">Details</a>
        </li>
        <li>
          <a href="#holder" @click="holderActive()" class="tabLink">Holder</a>
        </li>

        <li class="active">
          <a href="#past_transactions" @click="bidsActive()" class="tabLink"
            >Bids</a
          >
        </li>
      </ul>
      <div class="tabGroup">
        <div
          id="home"
          class="tab-pane"
          v-bind:class="{ 'tab-active': home_active }"
        >
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
                <a :href="asset_url + 'collection/'">
                  <img class="br-50" :src="st.depositphotos.com/1779253/5140/v/600/depositphotos_51405259-stock-illustration-male-avatar-profile-picture-use.jpg" width="50" />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position">Collection</label>
              <label class="positionHolder"
                ><a :href="asset_url + 'collection/' + Address">{{
                
                }}</a></label
              >
            </div>
          </div>
        </div>

        <div
          id="holder"
          class="tab-pane"
          v-bind:class="{ 'tab-active': holder_active }"
        >
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

        <div
          id="past_transactions"
          class="tab-pane"
          v-bind:class="{ 'tab-active': bid_active }"
        >
          <button
            type="submit"
            class="btn btn-success"
            v-if="!biddingStatus && owner"
            @click="startBid()"
          >
            Open Token for Bidding
          </button>
          <button
            type="submit"
            class="btn btn-danger"
            v-if="biddingStatus && owner"
            @click="endBid()"
          >
            Remove from Bidding
          </button>
          <button
            type="submit"
            class="btn btn-success"
            v-if="biddingStatus && owner"
            @click="acceptBidding()"
          >
            Accept Highest Bid
          </button>
          <div class="col-3 col-md-2">
            <div class="inlineDiv">
                <a :href="user_profile + '/' + transac.bidding_address">
                  <img class="br-50" :src="asset_url" width="50" />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
          </div>
          <div class="col-9 col-md-10">
              <label class="position"
                >{{ transac.bidding_amount }} {{ transac.bidding_token }}
                <span class="positionHolder">{{ transac.bidding_amount }}</span>
                on {{ transac.created_at.slice(0, 10) }} by
                <a :href="user_profile + '/' + transac.bidding_address"
                  ><span class="positionHolder">{{
                    transac.bidding_address
                  }}</span></a
                >
              </label>
            </div>
          <div
            v-for="(transac, index) in allBids"
            :key="index"
            class="row dtab"
            v-show="biddingStatus"
          >
            <div class="col-3 col-md-2">
              <div class="inlineDiv">
                <a :href="user_profile + '/' + transac.bidding_address">
                  <img class="br-50" :src="asset_url" width="50" />
                </a>
                <i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
              </div>
            </div>
            <div class="col-9 col-md-10">
              <label class="position"
                >{{ transac.bidding_amount }} {{ transac.bidding_token }}
                <span class="positionHolder">{{ transac.bidding_amount }}</span>
                on {{ transac.created_at.slice(0, 10) }} by
                <a :href="user_profile + '/' + transac.bidding_address"
                  ><span class="positionHolder">{{
                    transac.bidding_address
                  }}</span></a
                >
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  getHighestBid,
  getBiddingStatus,
  getAllBids,
  startBidding,
  endBidding,
  getConnectedAddress,
  acceptBid,
} from ".././../bidFunc";
import { toAddress, checkConnection} from ".././../etherFunc";
export default {
  props: [
    "creator",
    "current_owner",
    "owners",
    "transactions",
    "user_profile",
    "collectible",
    "asset_url",
    "collection",
    "collection_image",
    "collection_url",
  ],
  data() {
    return {
      //asset_url :  "https://cdn.pixabay.com/photo/2017/06/13/12/53/profile-2398782_1280.png",
      allBids: {},
      bid_active: false,
      home_active: true,
      holder_active: false,
      biddingStatus: false,
      owner: false,
      highestBid: {},
      address: {},
    };
  },
  async mounted() {
    //var highestBid = await getHighestBid(this.current_owner.wallet, this.collectible.contract,this.collectible.id);
    this.address = await getConnectedAddress();
    var allBids = await getAllBids(
      this.current_owner.wallet,
      this.collectible.contract,
      this.collectible.id
    );
    var output = await getBiddingStatus(
      this.current_owner.wallet,
      this.collectible.contract,
      this.collectible.id
    );
    this.highestBid = await getHighestBid(
      this.current_owner.wallet,
      this.collectible.contract,
      this.collectible.id
    );
    this.biddingStatus = output;
    this.allBids = allBids;
    if (this.address == this.current_owner.wallet) {
      this.owner = true;
    }
  },
  methods: {
    detailsActive() {
      this.home_active = true;
      this.holder_active = false;
      this.bid_active = false;
    },
    holderActive() {
      this.home_active = false;
      this.holder_active = true;
      this.bid_active = false;
    },
    bidsActive() {
      this.home_active = false;
      this.holder_active = false;
      this.bid_active = true;
    },

    startBid() {
      var res = startBidding(
        this.current_owner.wallet,
        this.collectible.contract,
        this.collectible.id
      );
      console.log(res);
    },
    endBid() {
      var res = endBidding(
        this.current_owner.wallet,
        this.collectible.contract,
        this.collectible.id
      );
      console.log(res);
    },

    async acceptBidding() {
      console.log(this.collectible.contract);
      var res = await acceptBid(
        this.collectible.contract,
        this.collectible.type == 721 ? true : false,
        this.collectible.id,
        toAddress(this.highestBid.maxBidder),
        1,
       "0xE19DD2fa7d332E593aaf2BBe4386844469e51937",
        `${this.highestBid.maxAmount}`,
        "Place a Bid",
        this.owner == true ? toAddress(this.address) : "",
        this.highestBid.maxBidSig
      );
      console.log(res);
    },
  },
};
</script>