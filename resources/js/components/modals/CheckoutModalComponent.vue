<template>
  <div id="checkoutModal" class="custom-modal d-none">
    <div class="modal-content checkout-content">
      <div class="modal-head">
        <h3>Checkout</h3>
        <span class="close-checkout-modal">&times;</span>
      </div>
      <div class="modal-body">
        <label class="item-description"
          >You are about to purchase
          <span class="item-name">{{ singleNft.name }}</span> from billion.
          Check information then proceed to payment</label
        >

        <div class="form-section">
          <form
            autocomplete="off"
            id="purchaseForm"
            @submit.prevent="enoughBalance ? purchase() : ''"
          >
            <div class="form-divide">
              <input
                v-model.number="quantity"
                class="modal-input"
                type="number"
                id="checkout-quantity"
                name="quantity"
                placeholder="Enter quantity"
                min="1"
                :max="singleNft.ownedCopies"
              />
              <label class="desc-url">Enter quantity.</label>
            </div>
            <div class="form-divide">
              <input
                class="modal-input"
                type="text"
                id="checkout-price"
                name="price"
                readonly
                :value="singleNft.price"
              />
              <span class="link-url-end"
                ><span id="checkout-currency">{{
                  singleNft.currencyName
                }}</span>
                <i class="fa fa-angle-down"></i>
                <i class="fa fa-lock" aria-hidden="true"></i
              ></span>
            </div>
            <div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >{{ Number(balance).toFixed(3) }}
                  {{ singleNft.currencyName }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ Number(service_fee).toFixed(3) }}
                  {{ singleNft.currencyName }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  >{{ total_payment }} {{ singleNft.currencyName }}</label
                >
              </div>
            </div>
            <button
              class="form-submit"
              @click.prevent="approve"
              v-if="!approved && enoughBalance"
            >
              Approve
            </button>
            <button
              class="form-submit"
              type="submit"
              :disabled="!enoughBalance"
            >
              Proceed to payment
            </button>

            <label class="text-details" v-if="!enoughBalance"
              >Low Balance</label
            >
            <label class="text-details" v-if="allowance < total_payment"
              >Low Allowance</label
            >
            <!--button class="cancel-btn" type="button">Cancel</button-->
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import $ from "jquery";
import {
  checkTokensApproved,
  approveTokens,
  checkTokensBalance,
  buy,
  waitForTransaction,
  toAddress,
  getBNBBalance,
} from "./../../etherFunc";
import { removeSale } from "../../data";
export default {
  props: ["singleNft", "page", "current_user"],
  data() {
    return {
      quantity: 1,
      balance: 0,
      allowance: 0,
      enoughBalance: false,
      service_fee: 0,
      royalty_fee: 0,
      total_payment: 0,
      payment: 0,
      price: 0,
      currency: "",
      nft_id: 0,
      record_id: 0,
      approved: false,
    };
  },
  async mounted() {
    this.checkEligibility();
  },
  watch: {
    singleNft: async function () {
      this.checkEligibility();
    },
    quantity: async function () {
      this.checkEligibility();
    },
  },
  methods: {
    async checkEligibility() {
      this.singleNft.currencyName == "HPS"
        ? (this.currency = "0xE19DD2fa7d332E593aaf2BBe4386844469e51937")
        : this.singleNft.currency == "BHC"
        ? (this.currency = "0x8Fc7fb3B85C3ADac8a8cBd51BB8EA8Bd6b1Fb876")
        : (this.currency = toAddress(""));
      this.price = this.singleNft.price;
      this.nft_id = this.singleNft.id;
      this.record_id = this.singleNft.record_id;
      this.updateValues();
      if (this.currency != toAddress("")) {
        var allowance = await checkTokensApproved(
          this.currency,
          this.current_user
        );
        this.balance = await checkTokensBalance(
          this.currency,
          this.current_user
        );
        this.allowance = allowance;
      } else {
        this.balance = await getBNBBalance(this.current_user);
        var allowance = this.balance;
        this.allowance = allowance;
      }
      this.allowance = allowance;
      console.log(allowance);
      if (this.total_payment <= allowance) {
        this.approved = true;
      } else {
        this.approved = false;
      }
      if (this.balance < this.total_payment) {
        this.enoughBalance = false;
      } else {
        this.enoughBalance = true;
      }
    },
    updateValues() {
      this.payment = +(this.price * this.quantity);
      this.service_fee = +(this.payment * 0.025);
      this.royalty_fee = (this.payment * this.singleNft.royalties) / 100;
      this.total_payment = +(this.payment + this.service_fee);
    },
    async approve() {
      try {
        var hash = await approveTokens(
          this.currency,
          `${Number(this.total_payment)}`
        );
        waitForTransaction(hash).then((data) => {
          if (data.status) {
            this.approved = true;
          }
        });
      } catch (error) {
        if (error.code == 4001) {
          Toast.fire({
            icon: "error",
            title: "User rejected transaction!",
          });
        }
      }
    },
    purchase() {
      var collectible = this.singleNft;

      let success;

      let message_buyer =
        "You have successfully purchased " +
        collectible.name +
        " for " +
        `${this.price}` +
        this.currency;
      let message_seller =
        "The " +
        collectible.name +
        " has been bought for " +
        `${this.price}` +
        this.currency;

      const _this = this;

      buy(
        collectible.contract,
        collectible.type == 721 ? true : false,
        collectible.id,
        collectible.signed_to,
        _this.quantity,
        _this.currency,
        `${_this.price}`,
        collectible.salt,
        collectible.owner_id,
        collectible.signature
      )
        .then(async function (hash) {
          var data = await waitForTransaction(hash);
          if (data.status) {
            await removeSale(
              collectible.contract,
              collectible.id,
              collectible.signed_to,
              _this.currency,
              `${_this.price}`,
              collectible.salt,
              collectible.db_id
            );

            $(".toast-message").text("Purchased");
            $("#purchaseForm").trigger("reset");
            setTimeout(function () {
              launch_toast();
            }, 500);
            modalClose($("#checkoutModal"), $(".checkout-content"));
            _this.service_fee = 0;
            _this.total_payment = 0;
            _this.payment = 0;
            _this.bid_input = "";
            _this.quantity = 1;

            if (_this.page == "marketplace" || _this.page == "profile") {
              _this.$parent.$parent.getCollectible();
            }
            if (_this.page == "marketplace") {
              _this.$parent.$parent.$parent.updateTopUser();
            }
            if (_this.page == "showcollectible") {
              _this.$parent.updateData();
              if (success) {
                data = {};
                data.message_seller = message_seller;
                data.message_buyer = message_buyer;
                data.buyer_id = toAddress(window.ethereum.selectedAddress);
                data.buy_amount = _this.price;
                data.seller_id = collectible.owner_id;
                data.bid = false;
                await axios.post("addNotification", data, {}).then((res) => {
                  console.log(res.data);
                });
              }
            }
          }
              })
        .catch((error) => {
          if (error.code == 4001) {
            Toast.fire({
              icon: "error",
              title: "User rejected transaction!",
            });
          }
        });
    },
  },
};
</script>