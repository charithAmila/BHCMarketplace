<template>
  <div id="putOnSaleModal" class="custom-modal d-none">
    <div class="modal-content putOnSale-content">
      <div class="modal-head">
        <h3>Put on Sale</h3>
        <span class="close-putOnSale-modal">&times;</span>
      </div>
      <div class="modal-body">
        <label class="item-description"
          >You are about to put the
          <span class="item-name">{{ collectible.name }}</span> from
          {{ collectible.collection.name }} on sale. Check information then
          submit</label
        >

        <div class="form-section">
          <form autocomplete="off" id="purchaseForm" @submit.prevent="sign">
            <div class="form-divide">
              <!--input
                v-model.number="quantity"
                class="modal-input"
                type="number"
                id="checkout-quantity"
                name="quantity"
                placeholder="Enter quantity"
                min="1"
                :max="singleNft.max"
              /-->
              <label class="desc-url">You Have.</label>
              <label class="desc-url"
                >{{ collectible.ownedCopies }} out of
                {{ collectible.copies }}</label
              >
            </div>
            <div class="form-divide">
              <input
                class="modal-input"
                type="text"
                id="checkout-price"
                name="price"
                :v-model="price"
                :value="collectible.price"
              />
              <span class="link-url-end"
                ><!--span id="checkout-currency">{{ currency }}</span>
                <i class="fa fa-angle-down"></i>
                <i class="fa fa-lock" aria-hidden="true"></i
              -->
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
                      @click="currency = 1"
                      >BHC</a
                    >
                    <i class="fa fa-check currency-check"></i>
                  </div>
                  <div class="drop-group">
                    <a
                      href="javascript:void(0)"
                      id="BNB"
                      class="side-drop currency-item"
                      @click="currency = 2"
                      >BNB</a
                    >
                    <i class="fa fa-check currency-check opacity-0"></i>
                  </div>
                </div>
              </span>
            </div>
            <!--div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >{{ balance }} {{ singleNft.currency }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ service_fee }} {{ singleNft.currency }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  >{{ total_payment }} {{ singleNft.currency }}</label
                >
              </div>
            </div-->
            <button class="form-submit" type="submit">
              {{ progress || "Sign Order" }}
            </button>
            <!--button class="cancel-btn" type="button">Cancel</button-->
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import $ from "jquery";
import { generateOrderIdMessage, signMessage } from "./../../etherFunc";
import { hpsAddress, bhcAddress } from "./../../addresses/constants";
import { addSale } from "./../../data";
export default {
  props: ["singleNft", "page", "collectible"],
  data() {
    return {
      quantity: 1,
      balance: 0,
      service_fee: 0,
      total_payment: 0,
      payment: 0,
      price: 0, //thist.collectible.price,
      currency: 1,
      nft_id: 0,
      record_id: 0,
      s: "",
      signed: false,
      salt: "",
      progress: "Sign Order",
      processing: false,
      orderId: "",
    };
  },
  watch: {
    singleNft: function () {
      this.price = +parseFloat(this.singleNft.total).toFixed(2);
      this.nft_id = this.singleNft.id;
      this.record_id = this.singleNft.record_id;
      this.updateValues();
    },
    quantity: function () {
      this.updateValues();
    },
  },
  methods: {
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
    async sign() {
      const _this = this;
      var salt,
        orderId = await generateOrderIdMessage(
          _this.collectible.contract,
          _this.collectible.id,
          _this.collectible.ownedCopies,
          _this.collectible.contract,
          _this.collectible.price,
          "dhgjdfh"
        );
      var sig = await signMessage(orderId);

      _this.s = sig;
      _this.signed = true;
      _this.progress = "Put Order";
      _this.salt = salt;
      _this.orderId = orderId;
    },
    async placeOrder() {
      const _this = this;
      var data = {
        collection: _this.collectible.contract,
        current_owner: _this.collectible.owner_id,
        token_id: _this.collectible.id,
        price: _this.price,
        is_instant: false,
        currency: _this.currency == 1 ? hpsAddress : bhcAddress,
        signature: _this.s,
        orderId: _this.orderId,
      };
      await addSale(data);
    },
    updateValues() {
      this.payment = +(this.price * this.quantity).toFixed(2);
      this.service_fee = +(this.payment * 0.025).toFixed(2);
      this.total_payment = +(this.payment + +this.service_fee).toFixed(2);
    },
    purchase() {
      this.currency = $("#checkout-currency").text();

      axios
        .post("/create/transaction", {
          type: "buy",
          nft_id: this.nft_id,
          price: this.payment,
          currency: this.currency,
          record_id: this.record_id,
          quantity_input: this.quantity,
        })
        .then((res) => {
          $(".toast-message").text(res.data.message);
          $("#purchaseForm").trigger("reset");
          setTimeout(function () {
            launch_toast();
          }, 500);
          modalClose($("#checkoutModal"), $(".checkout-content"));
          this.service_fee = 0;
          this.total_payment = 0;
          this.payment = 0;
          this.bid_input = "";
          this.quantity = 1;

          if (this.page == "marketplace" || this.page == "profile") {
            this.$parent.$parent.getCollectible();
          }
          if (this.page == "marketplace") {
            this.$parent.$parent.$parent.updateTopUser();
          }
          if (this.page == "showcollectible") {
            this.$parent.updateData();
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>