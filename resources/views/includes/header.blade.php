<navigation-component></navigation-component>
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