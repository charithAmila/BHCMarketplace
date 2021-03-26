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
          <form autocomplete="off" id="purchaseForm" @submit.prevent="purchase">
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
                ><span id="checkout-currency">{{ singleNft.currency }}</span>
                <i class="fa fa-angle-down"></i>
                <i class="fa fa-lock" aria-hidden="true"></i
              ></span>
            </div>
            <div class="purchase">
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
            </div>
            <button class="form-submit" type="submit">
              Proceed to payment
            </button>
            <button class="cancel-btn" type="button">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import $ from "jquery";

export default {
  props: ["singleNft", "page"],
  data() {
    return {
      quantity: 1,
      balance: 0,
      service_fee: 0,
      total_payment: 0,
      payment: 0,
      price: 0,
      currency: "",
      nft_id: 0,
      record_id: 0,
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