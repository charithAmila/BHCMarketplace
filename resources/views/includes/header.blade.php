<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="left-nav default-nav onclick-menu">
        <a class="navbar-brand" href="{{ route('marketplace') }}">
            <img src="{{ asset('images/logo.png')}}" class="d-inline-block align-top" alt="" loading="lazy">
        </a>
        <div class="has-search d-none d-md-block">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control search-box" placeholder="Search by collector, owner, NFT">
        </div>
    </div>

    <div class="search-nav d-sm-block d-md-none onclick-search">
        <a class="back-btn" href="javascript:void()">
            <i class="fa fa-arrow-left arrow-back"></i>
        </a>
        <div class="has-search-mobile">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control search-box" placeholder="Search by collector, owner, NFT">
        </div>
    </div>

    <div class="right-nav d-none d-md-block default-nav">
        <a class="nav-item right-border {{ Request::segment(1) == 'nft' ? 'active' : ''}}"
            href="{{ route('marketplace') }}"><span>Marketplace</span></a>
        @if (Auth::check())
        <a class="nav-item right-border {{ Request::segment(1) == 'profile' ? 'active' : ''}}"
            href="{{ route('user.profile', Auth::user()->short_url != null ? Auth::user()->short_url : Auth::user()->wallet) }}">My
            NFT's</a>
        @endif
        <a class="nav-item right-border {{ Request::segment(1) == 'faq' ? 'active' : ''}}"
            href="{{ route('faq') }}">FAQ</a>
        <a class="nav-item right-border {{ Request::segment(1) == 'bhc' ? 'active' : ''}}"
            href="{{ route('about') }}">BHC</a>
        <a href="{{ route('create.collectible.choices') }}" class="btn nav-create">Create</a>

        @if (Auth::check())
        <a class="nav-item notif-btn" href="javascript:void(0)">
            <img src="{{ asset('images/logo2.png') }}" width="35" class="d-inline-block align-top" alt=""
                loading="lazy">
        </a>
        <a class="nav-item profile-btn"
            href="{{ route('user.profile', Auth::user()->short_url != null ? Auth::user()->short_url : Auth::user()->wallet) }}">
            @if(Auth::user()->display_photo == 'default.png')
            <img class="navbar-img-profile" src="{{ asset('/user/photo/'.Auth::user()->display_photo)}}" width="30"
                class="d-inline-block align-top" alt="" loading="lazy">
            @else
            <img class="navbar-img-profile" src="{{ asset('/storage/user/photo/'.Auth::user()->display_photo)}}"
                width="30" class="d-inline-block align-top" alt="" loading="lazy">
            @endif
        </a>
        @else
        <a href="{{ route('connect.wallet') }}" class="btn nav-connect">Connect</a>
        @endif
    </div>

    <div class="right-nav d-sm-block d-md-none default-nav">
        <a class="nav-item search-mobile" href="javascript:void(0)"><i class="fa fa-search"></i></a>
        <a class="nav-item notif-btn notif-mobile" href="javascript:void(0)">
            <img src="{{ asset('images/logo2.png')}}" width="25" class="d-inline-block align-top" alt="" loading="lazy">
        </a>
        <a class="nav-item hamburger-menu" href="javascript:void(0)"><i class="fa fa-bars"></i></a>
        @if (Auth::check())
        <a class="nav-item profile-btn"
            href="{{ route('user.profile', Auth::user()->short_url != null ? Auth::user()->short_url : Auth::user()->wallet) }}">
            @if(Auth::user()->display_photo == 'default.png')
            <img class="navbar-img-profile" src="{{ asset('/user/photo/'.Auth::user()->display_photo)}}" width="30"
                class="d-inline-block align-top" alt="" loading="lazy">
            @else
            <img class="navbar-img-profile" src="{{ asset('/storage/user/photo/'.Auth::user()->display_photo)}}"
                width="30" class="d-inline-block align-top" alt="" loading="lazy">
            @endif
        </a>
        @endif

    </div>

    @if (Auth::check())
    <div class="d-sm-block d-md-none onclick-menu">
        <a class="nav-item menu-close" href="javascript:void(0)">
            <i class="fa fa-close"></i>
        </a>
    </div>

    <div class="notification d-none">
        <label class="notif-title">Notifications</label>
        <div class="notif">
            <img src="{{ asset('images/logo2.png') }}">
            <label class="notif-content">No new notifications</label>
        </div>
    </div>

    <div class="profile-menu d-none">
        <div class="name-section">
            <h6>{{ \Str::random(40) }}</h6>
            <a class="preferencesBtn" href="javascript:void(0)">Set display name</a>
        </div>
        <div class="menu-options">
            <a
                href="{{ route('user.profile', Auth::user()->short_url != null ? Auth::user()->short_url : Auth::user()->wallet) }}">My
                account</a>
            <a id="edit-profile-btn" href="javascript:void(0)">Edit profile</a>
            <a href="javascript:void(0)">Manage funds</a>
            <!-- <a href="javascript:void(0)">
        <div class="row">
          <div class="col-sm-10 col-md-10">GIF/Video enabled</div>
          <div class="col-sm-2 col-md-2"></div>
        </div>
      </a> -->
            <a href="{{ route('disconnect') }}">Disconnect</a>
        </div>
    </div>
    @endif
</nav>



<div class="search-section onclick-search">
    <div class="search-50">
        <i class="fa fa-search"></i>
        <h5 class="font-bold">Search for ...</h5>
        <label class="text-gray">Start enter query to search through collections</label>
    </div>
    <div class="search-50-foot">
        @include('includes.footer')
    </div>
</div>

<div class="mobile-menu onclick-menu">
    <div class="menu-links">
        <a href="{{ route('marketplace') }}">Marketplace</a>
        <a href="javascript:void(0)">My NFT's</a>
        <a href="javascript:void(0)">Questions</a>
        <a href="javascript:void(0)">HPS</a>
    </div>
    <div class="menu-footer">
        <div class="footer-social">
            <a href="javascript:void(0)">
                <img src="{{ asset('images/twitter.png') }}">
            </a>
            <a href="javascript:void(0)">
                <img src="{{ asset('images/telegram.png') }}">
            </a>
            <a href="javascript:void(0)">
                <img src="{{ asset('images/message.png') }}">
            </a>
        </div>
        <div class="footer-btn">
            <a href="{{ url('/create') }}" class="btnPrimary">Create</a>
            <a href="{{ url('/connect') }}" class="btnSecondary">Connect</a>
        </div>
    </div>
</div>



<div id="preferencesModal" class="custom-modal">

    <div class="modal-content preferences-content">
        <div class="modal-head">
            <h3>Preferences</h3>
            <span class="close-custom-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-img col-5 col-md-5">
                    <img class="user-logo" src="{{ asset('user/photo/default.png')}}" alt="profile">
                </div>
                <div class="col-label col-7 col-md-7">
                    <label>Allowed png, gif, jpg. 400x400 Recommended</label>
                    <input type="file" id="user-photo" name='display_photo' class="user-check"
                        accept="image/x-png,image/gif,image/jpeg" />
                    <input class="user-photo-btn btn-setPicture" type="button" value="Set picture"
                        onclick="document.getElementById('user-photo').click();" />
                    <p class="preferences-error text-danger"></p>
                    <span id="display_photo-preferences" class="custom-error text-danger"></span>

                </div>
            </div>

            <div class="form-section">
                <form id="update-preferences" method="POST">
                    @csrf
                    <div class="form-divide">
                        <label class="input-label">Display name</label>
                        <input id="name-profile" class="modal-input preferences-check" type="text" name="name"
                            placeholder="E.g. John Doe">
                        <p class="preferences-error text-danger"></p>
                        <span id="name-preferences" class="custom-error text-danger"></span>
                    </div>
                    <div class="form-divide">
                        <label class="input-label">Description</label>
                        <input id="description-profile" class="modal-input" type="text" name="description"
                            placeholder="Spread some words about yourself">
                        <p class="preferences-error text-danger"></p>
                        <span id="description-preferences" class="custom-error text-danger"></span>
                        <label class="desc-url">URLs are allowed</label>
                    </div>
                    <div class="form-divide">
                        <label class="input-label">Short url</label>
                        <span class="link-url">billion.com/</span>
                        <input id="short_url-profile" class="modal-input preferences-check with-link" type="text"
                            name="short_url" placeholder="Enter short url">
                        <p class="preferences-error text-danger"></p>
                        <span id="short_url-preferences" class="custom-error text-danger"></span>
                    </div>
                    <button id="preferences-submit" class="form-submit" type="button">Update preferences</button>
                </form>
            </div>
        </div>

    </div>

</div>


<div id="toast">
    <img src="{{ asset('images/logo.png') }}">
    <div id="desc" class="toast-message">A notification message..</div>
</div>