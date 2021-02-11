@extends('layout.app')

@section('content')

<div class="about-bhc">
	<div class="first-section">
		<img src="{{ asset('images/logo.png') }}">
		<h1>Meet BHC - BHC Governance Token</h1>
		<span class="desc">We think that the best way to align platform development with customers' interests is to empower the ones who actively interact with protocol: creators and collectors</span>
		<button class="learn-more">Learn more about BHC Token</button>
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
		</div>
	</div>

	<div class="second-section">
		<h1>Who is eligible to participate in the airdrop?</h1>
		<div class="row">
			<div class="col-2 col-md-2">
				<span class="item">1</span>
			</div>
			<div class="col-10 col-md-10">
				<span class="sub-title">Existing Billion users</span>
				<span class="date">15/07/2020</span>
				<p class="text-content">
					Active users will receive 2% of the total RARI supply according to the Liquidity Mining principle: based on the previous volume on Rarible marketplace. Both buyers and sellers will receive 50%.
				</p>
			</div>
		</div>

		<div class="row">
			<div class="col-2 col-md-2">
				<span class="item">2</span>
			</div>
			<div class="col-10 col-md-10">
				<span class="sub-title">Documented NFT holders/buyers</span>
				<span class="date">by Monday 20/07/2020</span>
				<p class="text-content">
					In this stage, 4% will be distributed amoung Ethereum addresses of all NFTs with documented sales on Dune Analytics.
				</p>
			</div>
		</div>

		<div class="row">
			<div class="col-2 col-md-2">
				<span class="item">3</span>
			</div>
			<div class="col-10 col-md-10">
				<span class="sub-title">Remaining NFT owners</span>
				<span class="date">to be announced</span>
				<p class="text-content">
					As we believe that Dune Analytics might not hold all the data, we introduced the third stage for corrections. If you haven’t found yourself on the list, but you know you have significant NFT holdings — please reach out to us! The second airdrop wave will be announced later.
				</p>
			</div>
		</div>
	</div>

	<div class="third-section">
		<h1>How you can get BHC</h1>
		<label class="desc">BHC is not an investment and should be earned by active participation on the platform. 75,000 tokens are issued every week, with 50% reserved for buyers, and 50% for sellers</label>
		<div class="icon-group">
			<div>
				<i class="fa fa-shopping-cart"></i>
				<label>Create and sell</label>
			</div>
			<div>
				<i class="fa fa-shopping-cart"></i>
				<label>Collect NFTs</label>
			</div>
			<div>
				<i class="fas fa-parachute-box"></i>
				<label>Get airdrop as BHC user</label>
			</div>
			<div>
				<i class="fas fa-parachute-box"></i>
				<label>Get airdrop as NFT holder</label>
			</div>
		</div>
	</div>

	<div class="fourth-section">
		<h1>How to use BHC</h1>
		<div class="icon-group">
			<div>
				<i class="fa fa-thumbs-up"></i>
				<label>Hustle in BHC DAO</label>
			</div>
			<div>
				<i class="fa fa-thumbs-up"></i>
				<label>Vote for platform upgrades</label>
			</div>
			<div>
				<i class="fa fa-diamond"></i>
				<label>Choose featured artworks</label>
			</div>
			<div>
				<i class="fas fa-eye"></i>
				<label>Participate in moderation</label>
			</div>
		</div>
		<button class="learn-more">Learn more about BHC token</button>
	</div>
	
</div>


@include('includes.footer')
@endsection