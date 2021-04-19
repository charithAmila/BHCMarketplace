<template>
  <div class="first-section">
    <img :src="asset_url + 'images/logo.png'" />
    <h1>Meet BHC - BHC Governance Token</h1>
    <span class="desc"
      >We think that the best way to align platform development with customers'
      interests is to empower the ones who actively interact with protocol:
      creators and collectors</span
    >
    <!--	<button class="learn-more">Learn more about BHC Token</button>
		<div class="claim-section">
			<div>
				<label>Your Balance</label>
				<span class="currency">0 BHC</span>
			</div>
			<div>
				<label>Available for claim</label>
				<span class="currency">0 BHC</span>
			</div>
			<div>
				<button class="claim">Nothing to claim</button>
				<span class="question-mark"><i class="fa fa-question" aria-hidden="true"></i></span>
			</div>
		</div> -->
  </div>
</template>

<script>
export default {
  props: ["asset_url"],
};
</script>