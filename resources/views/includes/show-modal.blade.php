<div id="checkoutModal" class="custom-modal d-none">

	  <div class="modal-content checkout-content">
	    <div class="modal-head">
	      <h3>Checkout</h3>
	      <span class="close-checkout-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<label class="item-description">You are about to purchase <span class="item-name">{{ $collectible->name }}</span> from billion. Check information then proceed to payment</label>

			<div class="form-section">
				<form>
					<div class="form-divide">
						<input class="modal-input quantity-input" type="number" name="display_name" placeholder="Enter quantity" value="1" max="{{ $collectible->quantity }}" min="1">
						<label class="desc-url">Enter quantity. {{ $collectible->quantity }} available</label>
					</div>
					<div class="form-divide">
						<input class="modal-input" type="text" name="display_name" value="{{ $collectible->raw_price }}" readonly>
						<span class="link-url-end"><span class="toggle-currency">BHC</span> <i class="fa fa-angle-down"></i> <i class="fa fa-lock" aria-hidden="true"></i></span>
					</div>
					<div class="purchase">
						<div class="purchase-info">
							<label class="text-details">Your balance</label>
							<label class="text-value">0 <span class="toggle-currency">BHC</span></label>
						</div>
						<div class="purchase-info">
							<label class="text-details">Service fee</label>
							<label class="text-value">0.005 <span class="toggle-currency">BHC</span></label>
						</div>
						<div class="purchase-info">
							<label class="text-details">You will pay</label>
							<label class="text-value">0.205 <span class="toggle-currency">BHC</span></label>
						</div>
					</div>
					<button class="form-submit" type="button">Proceed to payment</button>
					<button class="cancel-btn" type="button">Cancel</button>
				</form>
			</div>
		</div>
	    
	  </div>

</div>



<div id="bidModal" class="custom-modal d-none">

  <div class="modal-content bid-content">
    <div class="modal-head">
      <h3>Place a bid</h3>
      <span class="close-bid-modal">&times;</span>
    </div>
    <div class="modal-body">
    	<label class="item-description">You are about to purchase <span class="item-name">{{ $collectible->name }}</span> from billion. Check information then proceed to payment</label>

		<div class="form-section">
			<form>
				<div class="form-divide">
					<label class="input-label">Your bid</label>
					<input class="modal-input dropdown-bid" type="number" name="display_name" placeholder="Enter bid">
					<span class="link-url-end currency-btn">BHC <i class="fa fa-angle-down"></i></span>

					<div class="currency-drop d-none">
						<div class="drop-group">
							<a href="javascript:void(0)" id="BHC" class="bid-drop currency-item">BHC</a>
							<i class="fa fa-check currency-check"></i>
						</div>
						<div class="drop-group">
							<a href="javascript:void(0)" id="BNB" class="bid-drop currency-item">BNB</a>
							<i class="fa fa-check currency-check opacity-0"></i>
						</div>
					</div>
				</div>

				<div class="purchase">
					<div class="purchase-info">
						<label class="text-details">Your balance</label>
						<label class="text-value">0 <span class="changeDD">BHC</span></label>
					</div>
					<div class="purchase-info">
						<label class="text-details">Service fee</label>
						<label class="text-value">0.005 <span class="changeDD">BHC</span></label>
					</div>
					<div class="purchase-info">
						<label class="text-details">You will pay</label>
						<label class="text-value">0.205 <span class="changeDD">BHC</span></label>
					</div>
				</div>
				<button class="form-submit" type="button">Place a bid</button>
				<button class="cancel-btn" type="button">Cancel</button>
			</form>
		</div>
	</div>
    
  </div>

</div>


<div id="reportModal" class="custom-modal d-none">

  <div class="modal-content report-content">
    <div class="modal-head">
      <h3>Why are you reporting</h3>
      <div>
      	<span class="close-report-modal">&times;</span>
      </div>
    </div>
    <div class="modal-body">
    	<label class="report-description">Describe why you think this item should be removed from marketplace</label>

		<div class="form-section">
			<form>
				<div class="form-divide">
					<label class="input-label">Message</label>
					<input class="modal-input" type="text" name="display_name" placeholder="Tell us some details">
				</div>
				<button class="form-submit" type="button">Report</button>
				<button class="cancel-btn" type="button">Cancel</button>
			</form>
		</div>
	</div>
    
  </div>

</div>


