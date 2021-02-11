<template>
  <nav class="navbar navbar-light custom-navbar">
    <div class="left-nav default-nav onclick-menu">
      <a class="navbar-brand" href="marketplace">
        <img
          src="images/logo.png"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />
      </a>
      <div class="has-search d-none d-md-block">
        <span class="fa fa-search form-control-feedback"></span>
        <input
          type="text"
          class="form-control search-box"
          placeholder="Search by collector, owner, NFT"
        />
      </div>
    </div>

    <div class="search-nav d-sm-block d-md-none onclick-search">
      <a class="back-btn" href="javascript:void()">
        <i class="fa fa-arrow-left arrow-back"></i>
      </a>
      <div class="has-search-mobile">
        <span class="fa fa-search form-control-feedback"></span>
        <input
          type="text"
          class="form-control search-box"
          placeholder="Search by collector, owner, NFT"
        />
      </div>
    </div>

    <div class="right-nav d-none d-md-block default-nav">
      <a class="nav-item right-border active" href="marketplace"
        ><span>Marketplace</span></a
      >
      @if (Auth::check())
      <a class="nav-item right-border active" href="">My NFT's</a>
      @endif
      <a class="nav-item right-border active" href="">FAQ</a>
      <a class="nav-item right-border active" href="}">HPS</a>
      <a href="" class="btn nav-create">Create</a>

      <a class="nav-item notif-btn" href="javascript:void(0)">
        <img
          src="images/logo2.png"
          width="35"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />
      </a>
      <a class="nav-item profile-btn" href="">
        <!-- <img
          class="navbar-img-profile"
          src="/user/photo/default.png"
          width="30"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />

      <img
          class="navbar-img-profile"
          src="/storage/user/photo/default.png"
          width="30"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />-->
      </a>

      <a href="" class="btn nav-connect">Connect</a>
    </div>

    <div class="right-nav d-sm-block d-md-none default-nav">
      <a class="nav-item search-mobile" href="javascript:void(0)"
        ><i class="fa fa-search"></i
      ></a>
      <a class="nav-item notif-btn notif-mobile" href="javascript:void(0)">
        <img
          src="images/logo2.png"
          width="25"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />
      </a>
      <a class="nav-item hamburger-menu" href="javascript:void(0)"
        ><i class="fa fa-bars"></i
      ></a>

      <a class="nav-item profile-btn" href="">
        <!--   <img
          class="navbar-img-profile"
          src="/user/photo/default.png"
          width="30"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />

      <img
          class="navbar-img-profile"
          src="/user/photo/default.png"
          width="30"
          class="d-inline-block align-top"
          alt=""
          loading="lazy"
        />-->
      </a>
    </div>

    <div class="d-sm-block d-md-none onclick-menu">
      <a class="nav-item menu-close" href="javascript:void(0)">
        <i class="fa fa-close"></i>
      </a>
    </div>

    <div class="notification d-none">
      <label class="notif-title">Notifications</label>
      <div class="notif">
        <img src="images/logo2.png" />
        <label class="notif-content">No new notifications</label>
      </div>
    </div>

    <div class="profile-menu d-none">
      <div class="name-section">
        <h6>Name here</h6>
        <a class="preferencesBtn" href="javascript:void(0)">Set display name</a>
      </div>
      <div class="menu-options">
        <a href="">My account</a>
        <a id="edit-profile-btn" href="javascript:void(0)">Edit profile</a>
        <a href="javascript:void(0)">Manage funds</a>
        <!-- <a href="javascript:void(0)">
        <div class="row">
          <div class="col-sm-10 col-md-10">GIF/Video enabled</div>
          <div class="col-sm-2 col-md-2"></div>
        </div>
      </a> -->
        <a href="">Disconnect</a>
      </div>
    </div>
  </nav>
</template>