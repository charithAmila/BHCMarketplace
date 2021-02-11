@extends('layout.app')

@section('content')

<div class="faq-container">
	<section class="heading">
		<img class="head-img" src="{{ asset('images/logo.png') }}">
		<label class="head-text">billionhappiness.com FAQ</label>
	</section>
	<section class="marketplace">
		<label class="section-head">Marketplace</label>
		<div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						NFT? ERC-721 tokens?
					</label>
					<label class="answer d-none">
						NFT stands for non-fungible tokens like ERC-721 (a smart contract standard) tokens which are hosted on Ethereum’s own blockchain. NFTs are unique digital items such as collectibles or artworks or game items. As an artist, by tokenizing your work you both ensure that it is unique and brand it as your work. The actual ownership is blockchain-managed.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What does “minting” mean?
					</label>
					<label class="answer d-none">
						The process of tokenizing your work and creating an NFT (see above).
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						Can I create an NFT on BHCble.com without putting it on sale?
					</label>
					<label class="answer d-none">
						Yes, you can and it is up to you if you decide to sell it later or not.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						Can I change the price of an already created collectible?
					</label>
					<label class="answer d-none">
						Absolutely, you can lower the price free of transaction costs at any time. You just need to sign the signature request via your wallet.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						Can I gift or send a collectible to someone?
					</label>
					<label class="answer d-none">
						Yes, just go on the detail page of a collectible you own, open the “...” menu and select “transfer token”
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What does “burn a token” mean?
					</label>
					<label class="answer d-none">
						The ERC-721 standard does not only allow the creation of NFTs, but also includes a possibility to destroy them - i.e. burning the token.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What is “unlockable content”?
					</label>
					<label class="answer d-none">
						As a content creator, you can add unlockable content to your collectibles, that only becomes visible after a transfer of ownership (i.e. selling or gifting your NFT). Artists use this feature to include high res files, making ofs. videos, secret messages etc.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						How does the royalty system work?
					</label>
					<label class="answer d-none">
						Whenever you create a collectible you can set a certain percentage as royalty for secondary sales. Example: You create a digital painting and sell it for 0.2 ETH, the royalty is 10 percent. Your buyer then sells your painting at a higher price point for 0.5 ETH. Here, the royalty system kicks in. As the original content creator you receive 10% of that sale, being 0.05 ETH.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						Can I report an artwork or collectible?
					</label>
					<label class="answer d-none">
						Yes, go on the detail page of the token you want to report, click on the “...” button and select “Report”.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What is verification?
					</label>
					<label class="answer d-none">
						Verified badges are granted to creators and collectors that show enough proof of authenticity and active dedication to the marketplace. We are looking at multiple factors such as active social media presence and following, dialogue with community members,number of minted and sold items.
					</label>
				</div>
			</div>
		</div>
	</section>

	<section class="marketplace">
		<label class="section-head">Governance</label>
		<div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What is BHCble governance system? How does it work?
					</label>
					<label class="answer d-none">
						The ultimate goal of BHCble is to evolve into a fully Decentralized Autonomous Organization (DAO), where all governance and decision rights belong to the platform users. By providing creators and collectors with the opportunity to propose and vote on platform upgrades, we make sure that the platform becomes a public good, responsive directly to its community members. On BHCble, you have a voice, and your voice is heard.
					</label>
				</div>
			</div>
			
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						I have an idea. Where should I start?
					</label>
					<label class="answer d-none">
						Got an idea? Awesome –– now, go advocate! Initiate a discussion in BHCble Discord, in BHCble Telegram and Twitter. Convince other participants to support you, get them to express their voice & upvote. Hear feedback. Make sure that your proposal will make the platform better for everyone.
					</label>
				</div>
			</div>
		</div>
	</section>

	<section class="marketplace">
		<label class="section-head">BHC Token</label>
		<div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What is $BHC, and why is it being distributed?
					</label>
					<label class="answer d-none">
						BHC is the native governance token of the NFT marketplace BHCble, designed to reward active platform users with a voice on the platform's future. As we are growing in numbers and expanding our presence on the market, we decided to take a shift towards a becoming a fully Decentralized Autonomous Organization. BHC has been created to give BHCble community the power to influence decisions and incentivize active participation.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						How is $BHC distributed?
					</label>
					<label class="answer d-none">
						The majority (60%) of BHC's total supply is reserved for sellers and buyers on BHCble marketplace. 75,000 tokens are distributed every Monday by 12pm San Francisco time to creators and collectors who made a sale or a purchase on BHCble during the past week. Both buyers and sellers equally receive 50% of the weekly distributed amount. 

						To kick-start the token launch, we are also conducting a **BHC airdrop to all NFT holders** out there. ****10% of the total BHC supply is distributed among everyone who has purchased NFTs, regardless of the platform they used.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						Who is eligible to receive $BHC in the airdrop?
					</label>
					<label class="answer d-none">
						In order to participate, you have to be either 1) an active BHCble users or 2) to own NFTs with a price — meaning that you should have bought them on any platform that records data on-chain (on a marketplace, in a game pre-sale, etc).
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What do I need to do to receive $BHC as BHCble user? 
					</label>
					<label class="answer d-none">
						You can't purchase BHC on BHCble — it can only be earned by active participation on the platform. We call it Marketplace Liquidity Mining. Buy and sell items, and every Sunday you will receive your portion of 75,000 BHC distributed weekly, according to the volume of your sales & purchases for the past week.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						I own NFTs. Can I receive BHC in the airdrop?
					</label>
					<label class="answer d-none">
						Short answer: yes. 10% of the total BHC supply is distributed among NFT holders.  In order to participate, you have to own NFTs with a price — meaning that you should have bought them on any platform that records data on-chain (on a marketplace, in a game pre-sale, etc). 

						Airdrop is conducted in 3 stages: 

						1) 15/07/2020 –– existing BHCble users

						Active users received 2% of the total BHC supply according to the Liquidity Mining principle: based on the previous volume on BHCble marketplace. 

						2) by Monday 20/07/2020 — documented NFT holders/buyers 

						In this stage, 4% is distributed amoung Ethereum addresses of all NFTs with documented sales on Dune Analytics. 

						3) To be announced — remaining NFT owners

						As we believe that Dune Analytics might not hold all the data, we introduced the third stage with another 4% reserved for corrections. If you haven't found yourself on the list, but you know you have significant NFT holdings — please reach out to us via! The distribution dates will be announced later.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						I own NFTs, but I haven't received BHC in the airdrop.
					</label>
					<label class="answer d-none">
						If you haven't received your BHC in the first waves of the airdrop, but you know you have significant NFT holdings that you purchased on a platform that records data on-chain (check eligibility criteria above!), please fill out this form: 

						If you comply with the requirements, you will receive $BHC in the third wave of the aidrop. The exact dates are to be announced later.
					</label>
				</div>
			</div>
			<div class="display-flex">
				<a class="arrow-link" href="javascript:void(0)">
					<i class="fas fa-caret-right arrow-drop"></i>
				</a>
				<div class="change-bg">
					<label class="question">
						What rights do I have as a BHC holder?
					</label>
					<label class="answer d-none">
						The key part of token holder rights is to have influence over the platform development. If community wants it — everything is possible. As BHC holder, you are entitled to: 

						1) Submit on proposals about:

						- BHCble’s trading fees
						- new BHCble features to be developed or implemented by our team
						- the use of BHC to further decentralize the governance and development of BHCble

						2) Vote on proposals 

						3) Moderate creators on BHCble 

						4) Curate featured artworks
					</label>
				</div>
			</div>
		</div>
	</section>
</div>



@include('includes.footer')

@section('extra_scripts')
	<script>
		$(document).ready(function(){
			$('.arrow-link').click(function(){
				if ($(this).siblings().children('.answer').hasClass('d-none')) {
					$(this).siblings().children('.answer').removeClass('d-none');
					$('.display-flex').removeClass('lightBlue-bg');
					$(this).parent().addClass('lightBlue-bg');
					$(this).children('.arrow-drop').removeClass('rotate-out').addClass('rotate-in');
				}else{
					$(this).siblings().children('.answer').addClass('d-none');
					$(this).parent().removeClass('lightBlue-bg');
					$(this).children('.arrow-drop').removeClass('rotate-in').addClass('rotate-out');
				}
			});
		});
	</script>
@endsection
@endsection