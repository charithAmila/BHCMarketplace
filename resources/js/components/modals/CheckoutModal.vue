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
          <span class="item-name">THUG LIFE CRYPTO</span> from billion. Check
          information then proceed to payment</label
        >

        <div class="form-section">
          <form autocomplete="off">
            <div class="form-divide">
              <input
                class="modal-input"
                type="number"
                id="checkout-quantity"
                name="quantity"
                placeholder="Enter quantity"
                min="1"
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
              />
              <span class="link-url-end"
                ><span id="checkout-currency">BHC</span>
                <i class="fa fa-angle-down"></i>
                <i class="fa fa-lock" aria-hidden="true"></i
              ></span>
            </div>
            <div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value">0 BHC</label>
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  ><span id="service_fee">0.005</span> BHC</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  ><span id="total_checkout">0.205</span> BHC</label
                >
              </div>
            </div>
            <button class="form-submit" type="button">
              Proceed to payment
            </button>
            <button class="cancel-btn" type="button">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>