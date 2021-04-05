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
                  >{{ balance }} {{ singleNft.currencyName }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ service_fee }} {{ singleNft.currencyName }}</label
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
              v-if="!approved"
            >
              Approve
            </button>
            <button
              class="form-submit"
              type="submit"
              :disabled="Number(balance) < Number(total_payment)"
            >
              Proceed to payment
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
import {
  checkTokensApproved,
  approveTokens,
  checkTokensBalance,
  buy,
  waitForTransaction,
  toAddress,
  getBNBBalance,
} from "./../../etherFunc";
export default {
  props: ["singleNft", "page", "current_user"],
  data() {
    return {
      quantity: 1,
      balance: 0,
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
      this.balance = await checkTokensBalance(this.currency, this.current_user);
      this.balance = this.balance.toFixed(3);
    } else {
      this.balance = await getBNBBalance(this.current_user);
      var allowance = this.balance;
    }
    console.log(allowance);
    if (this.total_payment <= allowance) {
      this.approved = true;
    } else {
      this.approved = false;
    }
  },
  watch: {
    singleNft: async function () {
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
        this.balance = this.balance.toFixed(3);
      } else {
        this.balance = await getBNBBalance(this.current_user);
        var allowance = this.balance;
      }
      console.log(allowance);
      if (this.total_payment <= allowance) {
        this.approved = true;
      } else {
        this.approved = false;
      }
    },
    quantity: async function () {
      if (this.currency != toAddress("")) {
        var allowance = await checkTokensApproved(
          this.currency,
          this.current_user
        );
        this.balance = await checkTokensBalance(
          this.currency,
          this.current_user
        );
        this.balance = this.balance.toFixed(3);
      } else {
        this.balance = await getBNBBalance(this.current_user);
        var allowance = this.balance;
      }
      console.log(allowance);
      if (this.total_payment <= allowance) {
        this.approved = true;
      } else {
        this.approved = false;
      }
      this.updateValues();
    },
  },
  methods: {
    updateValues() {
      this.payment = +(this.price * this.quantity);
      this.service_fee = +(this.payment * 0.025);
      this.royalty_fee = (this.payment * this.singleNft.royalties) / 100;
      this.total_payment = +(this.payment + this.service_fee);
    },
    async approve() {
      var hash = await approveTokens(
        this.currency,
        `${Number(this.total_payment)}`
      );
      waitForTransaction(hash).then((stat) => {
        if (stat) {
          this.approved = true;
        }
      });
    },
    purchase() {
      var collectible = this.singleNft;
      let success;
      let message = "You have successfully purchased "+collectible.name+" for "+`${this.price}`+this.currency;
      buy(
        collectible.contract,
        collectible.type == 721 ? true : false,
        collectible.id,
        collectible.signed_to,
        this.quantity,
        this.currency,
        `${this.price}`,
        collectible.salt,
        collectible.owner_id,
        collectible.signature
      )
        .then(async function (hash) {
          var status = await waitForTransaction(hash);
          if (status) {
            success =true;
            $(".toast-message").text("Purchased");
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
              if(success){
          data={};
          data.message = message;
          data.user_id = toAddress(window.ethereum.selectedAddress);
          await axios.post('addNotification',data,{
          }).then((res) => {
            console.log(res.data);
          });
        }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>