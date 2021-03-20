<template>
  <div>
    <div class="all-collection">
      <div class="filter-collection">
        <a
          id="on-sale"
          class="filter active"
          href="javascript:void(0)"
          @click="filterProfileNft('on-sale')"
          >On sale</a
        >
        <a
          id="collectibles"
          class="filter"
          href="javascript:void(0)"
          @click="filterProfileNft('collectibles')"
          >Collectibles</a
        >
        <a
          id="created"
          class="filter"
          href="javascript:void(0)"
          @click="filterProfileNft('created')"
          >Created</a
        >
        <a
          id="liked"
          class="filter"
          href="javascript:void(0)"
          @click="filterProfileNft('liked')"
          >Liked</a
        >
        <a
          id="fetchFollowing"
          class="following"
          href="javascript:void(0)"
          @click="getFollowing()"
          >Following</a
        >
        <a class="follower" href="javascript:void(0)" @click="getFollower()"
          >Followers</a
        >
      </div>
      <div class="collection-tab">
        <div id="preloader" class="row d-none">
          <div
            v-for="index in 12"
            :key="index"
            class="col-md-3 col-lg-3 custom-column-xl main-dashboard"
          >
            <div class="outside-nft border-on-profile">
              <div class="inside-nft">
                <div class="inner-outside-nft">
                  <div class="inner-nft">
                    <div class="item-main">
                      <div class="item-head">
                        <div class="preloader-img"></div>
                      </div>
                    </div>

                    <div class="item-img"></div>

                    <div class="display-flex -mt-15">
                      <div class="preloader-content"></div>
                    </div>
                    <div class="text-center currency-label">
                      <div class="preloader-content"></div>
                    </div>
                    <div class="preloader-half">
                      <div class="preloader-content"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <collectible-component
          :div_id="div_id"
          :collectible_asset="collectible_asset"
          :show_collectible="show_collectible"
          :current_user="current_user"
          :base_url="base_url"
          :collectibles="collectibles"
          :page="'profile'"
          :filter="filter"
          :user_id="user_id"
        ></collectible-component>

        <following-modal-component
          :following="following"
          :asset_url="asset_url"
        ></following-modal-component>

        <follower-modal-component
          :followers="followers"
          :asset_url="asset_url"
        ></follower-modal-component>

        <div v-if="collectibles.length == 0 && !loading" class="no-item-found w-30">
          <span class="sad-face">:(</span>
          <span class="collection-title">No items found</span>
          <span class="collection-desc"
            >Come back soon! Or try to browse something for you on our
            marketplace</span
          >
          <a class="browse" :href="marketplace_url">Browse marketplace</a>
        </div>
		<div v-if="loading" class="no-item-found w-30">
          <span class="collection-title">Items are loading....</span>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import $ from "jquery";
import Collectible from "./CollectibleComponent.vue";
import Following from "./modals/FollowingModalComponent.vue";
import Follower from "./modals/FollowerModalComponent.vue";
import { getTokensData } from "./../data.js";
export default {
  components: {
    Collectible,
    Following,
    Follower,
  },
  props: [
    "collectible_asset",
    "show_collectible",
    "current_user",
    "base_url",
    "asset_url",
    "marketplace_url",
    "user_slug",
    "user_id",
  ],
  data() {
    return {
      div_id: "collectible-field",
      rowClass: true,
      collectibles: [],
      filter: "on-sale",
      following: [],
      followers: [],
	  loading:false
    };
  },
  methods: {
    async getCollectible() {
      const _this = this;
	  _this.loading=true;
	  _this.collectibles=[];
      var data = await getTokensData(_this.user_id, _this.base_url);
      _this.collectibles = data[_this.filter];
	  _this.loading=false;
    },
    async filterProfileNft(selectedFilter) {
      const _this = this;
      _this.filter = selectedFilter;
      await _this.getCollectible();
    },
    refreshAfterUpdate() {
      this.getCollectible();
    },
    getFollowing() {
      axios.get("/user-follow/" + this.user_slug + "/following").then((res) => {
        this.following = res.data.user_follow;
      });
      modalOpen($("#following-modal"), $(".following-content"));
    },
    getFollower() {
      axios.get("/user-follow/" + this.user_slug + "/follower").then((res) => {
        this.followers = res.data.user_follow;
      });
      modalOpen($("#follower-modal"), $(".follower-content"));
    },
  },
  async mounted() {
    await this.getCollectible();
  },
};
</script>