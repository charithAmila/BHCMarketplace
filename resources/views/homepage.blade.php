@extends('layouts.default')

@section('content')

<div class="row row-0 walletSection">
    <!-- <div class="col-sm-12 col-md-3 hide-sm">
		</div> -->
    <div id="second" class="col-xs-12 col-sm-12 col-md-9 withContent my-auto">
        <h1 class="content-title pl-70 landing-title">Create and sell digital arts & collectibles secured with Binance
            Smart Chain</h1>
        <div class="btnGroup">
            <a href="{{ url('/create') }}" class="btnPrimary">Create</a>
            <a href="{{ route('marketplace') }}" class="btnSecondary">Explore</a>
        </div>
    </div>


    <div id="first" class="col-xs-12 col-sm-12 col-md-3 withBg color-change-3x">
        <div class="posBtn z-2">
            <a class="btn content-title" href="{{ url('/connect') }}" id="connectWallet"><i
                    class="fas fa-angle-double-right"></i> Connect Wallet</a>
        </div>
        <a href="{{ route('marketplace') }}">
            <img class="sidebarLogo z-2" src="{{ asset('images/logo.png') }}">
        </a>
        <div class="my-auto ">
            <div class="imgSmall imgPos1">
                <img class="sidebarImg" src="{{ asset('images/2_icon1.png') }}">
            </div>
            <div class="imgLarge imgPos2">
                <img class="sidebarImg" src="{{ asset('images/2_icon2.png') }}">
            </div>
            <div class="imgNormal imgPos3">
                <img class="sidebarImg" src="{{ asset('images/2_icon3.png') }}">
            </div>
            <div class="imgNormal imgPos4">
                <img class="sidebarImg" src="{{ asset('images/2_icon4.png') }}">
            </div>
        </div>
    </div>
</div>


<div class="fixed-social-links d-none d-md-block">
    <a href="javascript:void(0)">
        <img src="{{ asset('images/twitter.png') }}"> Follow us on Twitter
    </a>
    <a href="javascript:void(0)">
        <img src="{{ asset('images/telegram.png') }}"> Join our Telegram community
    </a>
    <a href="javascript:void(0)">
        <img src="{{ asset('images/message.png') }}"> Community Feedback
    </a>
</div>


@endsection