<template>
  <div>
    <div class="row row-0 show-collectible">
      <div
        class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 custom-lg-4 withBgGray second-mobile"
      >
        <div class="title-container">
          <div class="d-flex margin-0 space-between">
            <div class="collectibleTitle col-title">
              <h3 class="inlineDiv inline-title">{{ set_collectible.name }}</h3>
            </div>
            <div class="collectibleTitle col-option">
              <h3 class="inlineDiv inline-btn">
                <a
                  v-if="current_user != current_owner.wallet"
                  id="options-btn"
                  class="show-drop"
                  href="javascript:void(0)"
                  @click="toggleDropdown('.show-opt-menu')"
                >
                  <i class="fas fa-ellipsis-h titleIcon"></i
                ></a>
                <a
                  id="share-btn"
                  class="show-drop"
                  href="javascript:void(0)"
                  @click="toggleDropdown('.show-share-drop')"
                >
                  <i class="fa fa-share-square-o titleIcon"></i
                ></a>
              </h3>

              <div
                class="show-opt-menu d-none"
                v-if="current_user != current_owner.wallet"
              >
                <a
                  :class="set_collectible.is_selling == 1 ? '' : 'd-none'"
                  class="buy-now"
                  href="javascript:void(0)"
                  @click="fetchSingleNft('checkout')"
                  >Buy now</a
                >
                <a
                  v-if="singleNft.biddingStatus"
                  class="place-bid"
                  href="javascript:void(0)"
                  @click="fetchSingleNft('bid')"
                  >Place a bid</a
                >
                <a
                  class="report"
                  href="javascript:void(0)"
                  @click="fetchSingleNft('report')"
                  >Report</a
                >
              </div>

              <div class="show-share-drop d-none">
                <label class="share-title">Share link to this page</label>
                <div class="row">
                  <div class="col-4 col-md-4">
                    <a
                      href="javascript:void(0)"
                      data-sharer="twitter"
                      :data-title="
                        'Look what I found! ' +
                        collectible.name +
                        ' collectible'
                      "
                      :data-url="
                        asset_url +
                        'nft/' +
                        current_owner.user_profile +
                        '/' +
                        collectible.nft_slug
                      "
                    >
                      <i class="fa fa-twitter s-btn"></i>
                      <label>Twitter</label>
                    </a>
                  </div>
                  <div class="col-4 col-md-4">
                    <a
                      href="javascript:void(0)"
                      data-sharer="facebook"
                      :data-title="
                        'Look what I found! ' +
                        collectible.name +
                        ' collectible'
                      "
                      :data-url="
                        asset_url +
                        'nft/' +
                        current_owner.user_profile +
                        '/' +
                        collectible.nft_slug
                      "
                    >
                      <i class="fa fa-facebook s-btn"></i>
                      <label class="fb-label">Facebook</label>
                    </a>
                  </div>
                  <div class="col-4 col-md-4">
                    <a
                      href="javascript:void(0)"
                      data-sharer="telegram"
                      :data-title="
                        'Look what I found! ' +
                        collectible.name +
                        ' collectible'
                      "
                      :data-url="
                        asset_url +
                        'nft/' +
                        current_owner.user_profile +
                        '/' +
                        collectible.nft_slug
                      "
                    >
                      <i class="fab fa-telegram-plane s-btn"></i>
                      <label>Telegram</label>
                    </a>
                  </div>
                  <div class="col-4 col-md-4">
                    <a
                      href="javascript:void(0)"
                      data-sharer="email"
                      :data-title="
                        'Look what I found! ' +
                        collectible.creator +
                        ' collectible'
                      "
                      :data-url="
                        asset_url +
                        'nft/' +
                        current_owner.user_profile +
                        '/' +
                        collectible.nft_slug
                      "
                      data-subject="Hey! Check out that URL"
                    >
                      <i class="fa fa-envelope s-btn"></i>
                      <label>Email</label>
                    </a>
                  </div>
                  <div class="col-4 col-md-4">
                    <input
                      class="linkToCopy"
                      :value="
                        asset_url +
                        'nft/' +
                        current_owner.user_profile +
                        '/' +
                        collectible.nft_slug
                      "
                    />
                    <a href="javascript:void(0)" @click="copyUrl">
                      <i class="fa fa-copy s-btn"></i>
                      <label class="c-link">Copy link</label>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="priceTag">
          <label class="showCurrency" v-if="set_collectible.is_selling == 1"
            >{{ set_collectible.price }}
            {{ set_collectible.currencyName }}</label
          >
          <label class="showCurrency" v-else>Not for sale</label>
          <!-- <label class="showCurrencyPlain">$135.20</label> -->
        </div>
        <h5 class="itemType">
          <div class="legend">
            <div :class="set_collectible.legend">
              <i :class="set_collectible.icon"></i>
              {{ capitalizeFirstLetter(set_collectible.legend) }}
            </div>
          </div>
        </h5>
        <p class="itemDesc">{{ set_collectible.description }}</p>

        <collectible-details-component
          v-if="loaded"
          :creator="creator"
          :current_owner="current_owner"
          :owners="owners"
          :transactions="set_transactions"
          :user_profile="user_profile"
          :asset_url="asset_url"
          :collection="set_collectible.collection"
          :collection_image="set_collectible.collection_image"
          :collection_url="set_collectible.collection_url"
          :collectible="collectible"
        ></collectible-details-component>

        <div class="row m-20 text-center end-content">
          <div class="col-4 col-md-4">
            <label class="position">Available</label>
            <label class="positionHolder"
              >{{ set_collectible.ownedCopies }} out of
              {{ set_collectible.count }}</label
            >
          </div>
          <!--div
            v-if="
              current_user != current_owner.wallet && collectible.is_selling
            "
            class="col-4 col-md-4"
          >
            <label class="position">Quantity</label>
            <span
              class="quantity-btn positionHolder"
              @click="toggleDropdown('.quantity-drop')"
              >1 <i class="fa fa-angle-down"></i
            ></span>

            <div
              class="quantity-drop d-none"
              @click="toggleDropdown('.quantity-drop')"
            >
              <div
                v-for="index in set_collectible.count"
                :key="index"
                class="drop-group"
              >
                <a
                  href="javascript:void(0)"
                  :id="index"
                  class="quantity-item"
                  >{{ index }}</a
                >
              </div>
            </div>
          </div>
          <div
            v-if="
              current_user != current_owner.wallet && collectible.is_selling
            "
            class="col-4 col-md-4"
          >
            <label class="position">Pay with</label>
            <span
              class="checkout-currency positionHolder"
              @click="toggleDropdown('.checkout-drop')"
              >BHC <i class="fa fa-angle-down"></i
            ></span>

            <div
              class="checkout-drop d-none"
              @click="toggleDropdown('.checkout-drop')"
            >
              <div class="drop-group">
                <a
                  href="javascript:void(0)"
                  id="BHC"
                  class="side-drop currency-item"
                  >BHC</a
                >
                <i class="fa fa-check currency-check"></i>
              </div>
              <div class="drop-group">
                <a
                  href="javascript:void(0)"
                  id="BNB"
                  class="side-drop currency-item"
                  >BNB</a
                >
                <i class="fa fa-check currency-check opacity-0"></i>
              </div>
            </div>
          </div-->
        </div>

        <div
          v-if="
            collectible.is_selling == 1 && current_user != current_owner.wallet
          "
          class="buy-container"
        >
          <button
            id="buyBtn"
            class="buyBtn d-none d-md-block"
            @click="fetchSingleNft('checkout')"
          >
            Buy 1 for {{ set_collectible.price }}
            {{ set_collectible.currencyName }}
          </button>

          <p class="text-gray text-center d-none d-md-block">
            Service fee 2.5% =
            <span class="text-dark-gray"
              >{{ service_fee }} {{ set_collectible.currencyName }}</span
            >
          </p>
          <p class="text-gray text-center d-none d-md-block">
            Total to be paid for 1 collectible =
            <span class="text-dark-gray"
              >{{ singleNft.price + service_fee }}
              {{ set_collectible.currencyName }}</span
            >
          </p>
          <p class="text-gray text-center d-none d-md-block">
            Original Creator will get {{ singleNft.royalties }}% of the total
            sale
            <!--span class="text-dark-gray">{{ service_fee }} {{ set_collectible.currencyName }}</span-->
          </p>

          <div class="show-footer-btn d-block d-md-none">
            <button class="buyBtn" @click="fetchSingleNft('checkout')">
              Buy 1 for {{ set_collectible.price }}
              {{ set_collectible.currencyName }}
            </button>

            <p class="text-gray text-center">
              Service fee 2.5% =
              <span class="text-dark-gray"
                >{{ service_fee }} {{ set_collectible.currencyName }}</span
              >
            </p>
            <p class="text-gray text-center d-none d-md-block">
              Total to be paid for 1 collectible =
              <span class="text-dark-gray"
                >{{ singleNft.price + service_fee }}
                {{ set_collectible.currencyName }}</span
              >
            </p>
            <p class="text-gray text-center d-none d-md-block">
              Original Creator will get {{ singleNft.royalties }}% of the total
              sale
              <!--span class="text-dark-gray">{{ service_fee }} {{ set_collectible.currencyName }}</span-->
            </p>
          </div>
        </div>

        <div
          v-if="
            collectible.is_selling == 0 && current_user != current_owner.wallet
          "
          class="bid-container"
        >
          <button id="bidBtn" class="buyBtn d-none d-md-block">
            Place a bid
          </button>
        </div>
      </div>

      <div
        class="col-sm-12 col-md-8 col-lg-8 col-xl-8 custom-lg-8 showContent my-auto first-mobile"
      >
        <div class="inner-img">
          <div class="mobile-imgHead d-block d-md-none">
            <a
              v-if="current_user != current_owner.wallet"
              :data-nft-slug="set_collectible.nft_slug"
              :data-record-id="set_collectible.record_id"
              :class="is_liked == true ? 'nft-liked' : ''"
              class="like-btn m-imgHead-link"
              href="javascript:void(0)"
              @click="addLike()"
            >
              <i class="fa fa-heart nft-option"></i>
            </a>

            <a class="nft-expand m-imgHead-link" href="javascript:void(0)">
              <i class="fa fa-arrows-alt expand nft-option"></i>
            </a>
          </div>
          <div class="collectible-container">
            <img
              v-if="set_collectible.fileType == 'image'"
              class="showImg"
              :src="set_collectible.file"
              alt=""
            />
            <video
              v-if="
                set_collectible.fileType == 'video' ||
                set_collectible.fileType == 'audio'
              "
              class="showImg"
              autoplay
              loop
              controls
              muted
            >
              <source :src="set_collectible.file" type="video/mp4" />
            </video>

            <div class="show-nft-option imgHead d-none d-md-block">
              <a
                v-if="current_user != current_owner.wallet"
                :data-nft-slug="set_collectible.nft_slug"
                :data-record-id="set_collectible.record_id"
                :class="is_liked == true ? 'nft-liked' : ''"
                class="like-btn imgHead-link"
                href="javascript:void(0)"
                @click="addLike()"
              >
                <i class="fa fa-heart nft-option"></i>
              </a>

              <a
                class="nft-expand imgHead-link"
                id="nft-expand"
                href="javascript:void(0)"
              >
                <i class="fa fa-arrows-alt expand nft-option"></i>
              </a>
            </div>
          </div>
        </div>

        <img
          class="showImgBg d-none d-md-block"
          :src="asset_url + 'images/right.png'"
          alt=""
        />
      </div>
    </div>

    <checkout-modal-component
      v-if="current_user != null"
      :singleNft="singleNft"
      :page="'showcollectible'"
      :current_user="current_user"
    ></checkout-modal-component>
    <bid-modal-component
      :singleNft="singleNft"
      :page="'showcollectible'"
    ></bid-modal-component>
  </div>
</template>

<script>
import $ from "jquery";
import CollectibleDetails from "./show_collectible/CollectibleDetailsComponent.vue";
import BidModal from "./modals/BidModalComponent.vue";
import CheckoutModal from "./modals/CheckoutModalComponent.vue";
import { getUserDetails } from "./../data";

import { checkConnection, toAddress } from "./../etherFunc";

import { LikeController } from "../mediaFunc";

export default {
  components: {
    CollectibleDetails,
    BidModal,
    CheckoutModal,
  },
  props: [
    "collectible",
    "transactions",
    "onWishList",
    "asset_url",
    "auth_check",
    "user_profile",
    "base_url",
  ],
  data() {
    return {
      creator: [],
      current_owner: [],
      owners: [],
      singleNft: {},
      set_collectible: [],
      set_transactions: [],
      current_url: "",
      loaded: false,
      current_user: null,
      service_fee: 0,
      royaltyFee: 0,
      is_liked: false,
    };
  },
  watch: {
    singleNft: function () {
      this.price = +parseFloat(this.singleNft.total).toFixed(2);
      this.nft_id = this.singleNft.id;
      this.record_id = this.singleNft.record_id;
    },
  },
  methods: {
    checkConnection() {
      const _this = this;
      var interval = setInterval(function () {
        var acc = checkConnection();
        if (acc != toAddress("")) {
          _this.current_user = acc;
          clearInterval(interval);
        }
      }, 300);
    },
    async getOwnersDetails() {
      const _this = this;
      for (var i = 0; i < _this.collectible.owners.length; i++) {
        var details = await getUserDetails(_this.collectible.owners[i].owner);
        details.ownedCopies = Number(_this.collectible.owners[i].ownedCopies);
        _this.owners.push(details);
      }
    },
    toggleDropdown(ct) {
      console.log(ct);
      var container;
      container = $(ct);
      if (!container.hasClass("fade-in-top")) {
        container.toggleClass("d-none");
        container.addClass("fade-in-top").removeClass("fade-out-top");
      } else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function () {
          container.toggleClass("d-none");
        }, 400);
      }
    },
    capitalizeFirstLetter(string) {
      return String(string).charAt(0).toUpperCase() + String(string).slice(1);
    },
    fetchSingleNft(clicked) {
      if (this.current_user == 0) {
        window.location.href = this.base_url + "/connect";
      } else {
        modalOpen($("#" + clicked + "Modal"), $("." + clicked + "-content"));
      }
      this.singleNft = this.collectible;
      this.singleNft.total = this.singleNft.price;
      this.singleNft.currency = this.singleNft.currency;
      this.singleNft.max = this.singleNft.available;
    },
    updateData() {
      axios
        .get(
          "/update/show-page/" +
            this.collectible.link_user_slug +
            "/" +
            this.collectible.link_nft_slug
        )
        .then((res) => {
          this.set_collectible = res.data.collectible;
          this.owners = res.data.collectible.owners;
          this.set_transactions = res.data.transactions;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    copyUrl() {
      $("input.linkToCopy").select();
      document.execCommand("copy");
    },
    async addLike() {
      if (this.is_liked) {
        this.unlike();
      } else {
        var contract = this.collectible.contract;
        var id = this.collectible.id;
        var output = await LikeController(contract, id);
        if (output.success) {
          this.is_liked = true;
        }
      }
    },
    unlike() {
      var data = {};
      var contract = this.collectible.contract;
      var id = this.collectible.id;
      var _this = this;
      data.contract = contract;
      data.token_id = id;
      data.address = checkConnection().toLowerCase();
      axios
        .post("/unlike", data, {})
        .then(function (response) {
          if (response.data.success) {
            _this.is_liked = false;
          }
        })
        .catch(function (error) {});
    },
    checkLike() {
      var contract = this.collectible.contract;
      var id = this.collectible.id;
      var _this = this;
      axios.get("/like").then((res) => {
        var valObj = res.data.likes.filter(function (elem) {
          if (
            elem.token_id == id &&
            elem.contract == contract &&
            elem.address == checkConnection() &&
            elem.liked == true
          )
            return elem.token_id;
        });
        if (valObj.length > 0) {
          _this.is_liked = true;
        }
      });
    },
  },
  async mounted() {
    this.loaded = false;
    this.set_collectible = this.collectible;
    this.singleNft = this.collectible;
    this.set_transactions = this.transactions;
    this.creator = await getUserDetails(this.collectible.creator);
    this.current_owner = await getUserDetails(this.collectible.owner_id);

    await this.getOwnersDetails();
    this.loaded = true;

    this.checkConnection();
    this.service_fee = (this.collectible.price * 2.5) / 100;
    this.royaltyFee =
      (this.collectible.price * this.collectible.royalties) / 100;

    this.checkLike();
  },
};
</script>