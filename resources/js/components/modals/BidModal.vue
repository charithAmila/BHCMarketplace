<template>
  <div id="bidModal" class="custom-modal d-none">
    <div class="modal-content bid-content">
      <div class="modal-head">
        <h3>Place a bid</h3>
        <span class="close-bid-modal">&times;</span>
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
              <label class="input-label">Your bid</label>
              <input
                class="modal-input dropdown-bid"
                type="number"
                id="bid-input"
                name="bid"
                placeholder="Enter bid"
              />
              <span class="link-url-end currency-btn"
                >BHC <i class="fa fa-angle-down"></i
              ></span>

              <div class="currency-drop d-none">
                <div class="drop-group">
                  <a href="javascript:void(0)" id="BHC" class="currency-item"
                    >BHC</a
                  >
                  <i class="fa fa-check currency-check"></i>
                </div>
                <div class="drop-group">
                  <a href="javascript:void(0)" id="BNB" class="currency-item"
                    >BNB</a
                  >
                  <i class="fa fa-check currency-check opacity-0"></i>
                </div>
              </div>
            </div>
            <div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >0 <span class="changeDD">BHC</span></label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  ><span id="bid-service_fee">0</span>
                  <span class="changeDD">BHC</span></label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  ><span id="bid-total">0</span>
                  <span class="changeDD">BHC</span></label
                >
              </div>
            </div>
            <button class="form-submit" type="button">Place a bid</button>
            <button class="cancel-btn" type="button">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>