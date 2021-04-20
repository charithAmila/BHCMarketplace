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
                <a
                    class="follower"
                    href="javascript:void(0)"
                    @click="getFollower()"
                    >Followers</a
                >
            </div>
            <div class="collection-tab">
                <collectible-component
                    :div_id="div_id"
                    :collectible_asset="collectible_asset"
                    :show_collectible="show_collectible"
                    :current_user="current_user"
                    :base_url="base_url"
                    :collectibles="showing"
                    :page="'profile'"
                    :filter="filter"
                    :user_id="user_id"
                ></collectible-component>

                <following-modal-component
                    :following="followings"
                    :asset_url="asset_url"
                ></following-modal-component>

                <follower-modal-component
                    :followers="followers"
                    :asset_url="asset_url"
                ></follower-modal-component>

                <div id="preloader" class="row grid-conatainer" v-if="loading">
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
                                                <div
                                                    class="preloader-img"
                                                ></div>
                                            </div>
                                        </div>

                                        <div class="item-img"></div>

                                        <div class="display-flex -mt-15">
                                            <div
                                                class="preloader-content"
                                            ></div>
                                        </div>
                                        <div class="text-center currency-label">
                                            <div
                                                class="preloader-content"
                                            ></div>
                                        </div>
                                        <div class="preloader-half">
                                            <div
                                                class="preloader-content"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="showing.length == 0 && !loading"
                    class="no-item-found w-30"
                >
                    <span class="sad-face">:(</span>
                    <span class="collection-title">No items found</span>
                    <span class="collection-desc"
                        >Come back soon! Or try to browse something for you on
                        our marketplace</span
                    >
                    <a class="browse" :href="marketplace_url"
                        >Browse marketplace</a
                    >
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
import {
    getTokensData,
    getOnSaleTokens,
    getLikedTokens,
    getCreatedTokens
} from "./../data.js";
import { getUserDetails } from "../data";
import { log } from "util";
export default {
    components: {
        Collectible,
        Following,
        Follower
    },
    props: [
        "collectible_asset",
        "show_collectible",
        "current_user",
        "base_url",
        "asset_url",
        "marketplace_url",
        "user_slug",
        "user_id"
    ],
    data() {
        return {
            div_id: "collectible-field",
            rowClass: true,
            collectibles: window.myTokens,
            showing: [],
            filter: "on-sale",
            following: [],
            baseLoaded: false,
            followers: [],
            followings: [],
            output_array: [],
            output_array_followings: [],
            loading: true
        };
    },
    methods: {
        async getCollectible() {
            const _this = this;
            getTokensData(_this.user_id, _this.base_url);
        },
        async filterProfileNft(selectedFilter) {
            const _this = this;
            _this.loading = true;
            _this.filter = selectedFilter;

            if (selectedFilter == "on-sale") {
                getOnSaleTokens(_this.user_id, _this.base_url);
            }
            window.myTokens[selectedFilter] != null
                ? (_this.showing = window.myTokens[selectedFilter])
                : (_this.showing = []);

            var interval = setInterval(function() {
                if (window.myTokens[_this.filter] == null) {
                    _this.loading = false;
                    _this.showing = [];
                    clearInterval(interval);
                } else if (window.myTokens[_this.filter].length > 0) {
                    _this.loading = false;
                    clearInterval(interval);
                }
            }, 1);

            /*const _this = this;
            _this.filter = selectedFilter;
            _this.showing = window.myTokens[selectedFilter];
            if (selectedFilter == "on-sale") {
                var onSale = await getOnSaleTokens(
                    _this.user_id,
                    _this.base_url
                );
                window.myTokens[selectedFilter] = onSale;
            } else if (
                selectedFilter == "liked" &&
                window.myTokens[selectedFilter].length == 0
            ) {
                var liked = await getLikedTokens(_this.user_id, _this.base_url);
                window.myTokens[selectedFilter] = liked;
            } else if (
                selectedFilter == "created" &&
                window.myTokens[selectedFilter].length == 0
            ) {
                var created = await getCreatedTokens(
                    _this.user_id,
                    _this.base_url
                );
                window.myTokens[selectedFilter] = created;
            }

            _this.showing = window.myTokens[selectedFilter];*/
        },
        refreshAfterUpdate() {
            this.getCollectible();
        },
        getFollowing() {
            modalOpen($("#following-modal"), $(".following-content"));
        },
        getFollower() {
            modalOpen($("#follower-modal"), $(".follower-content"));
        },
        async getFollowers() {
            var user_id = this.user_id;
            var _this = this;
            var res = await axios.get("/followers");
            _this.output_array = res.data.followers.filter(function(elem) {
                if (elem.user_id == user_id) {
                    return elem.user_id;
                }
            });
        },
        async getFollowings() {
            var user_id = this.user_id;
            const new_user_id = user_id.toLowerCase();
            var _this = this;
            var res = await axios.get("/followers");
            _this.output_array_followings = res.data.followers.filter(function(
                elem
            ) {
                if (elem.follower_id.toLowerCase() == new_user_id) {
                    return elem.follower_id;
                }
            });
        },
        async asignFollowers() {
            var _this = this;
            for (var i = 0; i < this.output_array.length; i++) {
                var resu = await getUserDetails(
                    this.output_array[i].follower_id
                );
                _this.followers.push(resu);
            }

            for (var j = 0; j < this.output_array_followings.length; j++) {
                var resul = await getUserDetails(
                    this.output_array_followings[j].user_id
                );
                _this.followings.push(resul);
            }
        }
    },
    async mounted() {
        this.getCollectible();
        this.filterProfileNft("on-sale");

        await this.getFollowers();
        await this.getFollowings();
        await this.asignFollowers();
    },
    watch: {
        filter() {
            //this.loading = true;
        }
    }
};
</script>
