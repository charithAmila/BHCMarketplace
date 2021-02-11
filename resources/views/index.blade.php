@extends('layout.app')

@section('content')

<div class="main-content">
    <div class="row main-row">
        <div class="col-md-2 col-lg-2 top-sellers">
            <div class="seller-content">
                <label class="fontBold">Top</label>
                <select id="user-type" class="">
                    <option value="sell">Sellers</option>
                    <option value="buy">Buyers</option>
                </select>
                <strong>in</strong>
                <select id="filter-time" class="">
                    <option value="all">All time</option>
                    <option value="0">1 day</option>
                    <option value="6">7 days</option>
                    <option value="29">30 days</option>
                </select>
            </div>
            <div class="filterList d-none d-md-block">

                <div id="preloader-top-user" class="d-none">

                    @for($x = 1; $x <= 8; $x++) <div class="row placeholder-div">
                        <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                            <div class="userCircle"></div>
                        </div>
                        <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                            <div class="userName"></div>
                            <div class="userPrice"></div>
                        </div>
                </div>
                @endfor
            </div>

            <div id="actual-top-user">
                @foreach($seller as $item)
                <div class="row topUserList">
                    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                        <img class="filterImg" src="{{ asset('/'.$item->asset_url) }}">
                    </div>
                    <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                        <h6>{{ $item->display_name }}</h6>
                        <label>{{ $item->totalPrice }} BHC</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="filterListMobile d-sm-block d-md-none">
            @foreach($seller as $item)
            <div class="filterItemMobile">
                <img class="filterImg" src="{{ asset('/'.$item->asset_url) }}">
                <label class="profName">
                    {{ \Illuminate\Support\Str::limit( $item->display_name, 8, $end='...') }}
                </label>
                <label class="prof">
                    {{ $item->totalPrice }} BHC
                </label>
            </div>
            @endforeach


        </div>
    </div>
    <div class="col-md-10  col-lg-10  item-content">
        <div class="item-filter">
            <div>
                <button data-filter-id="all" class="filter-btn active">All</button>
                <button data-filter-id="1" class="filter-btn">Arts</button>
                <button data-filter-id="2" class="filter-btn">Memes</button>

            </div>
            <div class="sortDiv">
                <a class="sorter sortDesk" href="javascript:void(0)">
                    <img class="imgFilter" src="{{ asset('images/filter.png')}}" width="25"> Sort & Filter
                </a>

                <a class="sorter d-sm-block d-md-none" href="javascript:void(0)">
                    <img class="imgFilter" src="{{ asset('images/filter.png')}}" width="25">
                </a>

                <div class="sortfilter d-none">
                    <label class="sortLabel">Sort by</label>
                    <ul>
                        <li>
                            <a href="javascript:void(0)" class="sortItem" data-sort-by="updated_at" data-order="desc">
                                <div class="row">
                                    <div class="col-md-9">Recently added</div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sortItem" data-sort-by="price" data-order="asc">
                                <div class="row">
                                    <div class="col-md-9">Cheapest</div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="sortItem" data-sort-by="price" data-order="desc">
                                <div class="row">
                                    <div class="col-md-9">Highest price</div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <label class="sortLabel">Options</label>
                    <ul>
                        <li>
                            <a href="javascript:void(0)">
                                <div class="row">
                                    <div class="col-md-9">
                                        Verified only
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                                checked>
                                            <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="items">
            <div id="preloader" class="row d-none">
                @for($x = 1; $x <= 24; $x++) <div class="col-md-3 col-lg-3 custom-column-xl main-dashboard">
                    <div class="outside-nft border-on-profile">
                        <div class="inside-nft">
                            <div class="inner-outside-nft">
                                <div class="inner-nft">
                                    <div class="item-main">

                                        <div class="item-head">
                                            <div class="preloader-img"></div>
                                        </div>
                                    </div>

                                    <div class="item-img">
                                    </div>

                                    <div class="display-flex -mt-15">
                                        <div class="preloader-content"></div>
                                    </div>
                                    <div class="text-center currency-label">
                                        <div class="preloader-content"></div>
                                    </div>
                                    <div class="preloader-half">
                                        <div class="preloader-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endfor
        </div>

        <div id="nft-container" class="row">
            @foreach($collectibles as $collectible)

            <div class="col-md-3 col-lg-3 custom-column-xl main-dashboard">
                <div class="outside-nft">
                    <div class="inside-nft">
                        <div class="inner-outside-nft">
                            <div class="inner-nft">
                                <div class="item-main">

                                    <div class="item-head">

                                        <div class="legend">
                                            <div class="{{ $collectible->legend }}">
                                                <i class="{{ $collectible->icon }}"></i>
                                                {{ ucwords($collectible->legend) }}
                                            </div>
                                        </div>

                                        <div class="item-menu">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </div>
                                    </div>

                                    <div class="item-menu-drop d-none">
                                        @if($collectible->isp == 1)
                                        <a id="{{ $collectible->slug }}" class="buy-now" href="javascript:void(0)">Buy
                                            now</a>
                                        @endif
                                        <a id="{{ $collectible->slug }}" class="place-bid"
                                            href="javascript:void(0)">Place a bid</a>
                                        <a id="{{ $collectible->slug }}" class="report"
                                            href="javascript:void(0)">Report</a>
                                    </div>
                                </div>


                                <div class="item-img">
                                    @if($collectible->type == 'image')
                                    <img src="{{ asset('storage/collectibles/'.$collectible->nft) }}">
                                    @else
                                    <video autoplay loop muted>
                                        <source src="{{ asset('storage/collectibles/'.$collectible->nft) }}"
                                            type="video/mp4">
                                    </video>
                                    @endif
                                </div>

                                <div class="display-flex">
                                    <div class="text-center currency-amount">
                                        {{ $collectible->price }}
                                    </div>
                                    <span class="copies">{{ $collectible->copies }}</span>
                                </div>
                                <div class="text-center currency-label">{{ $collectible->name }}</div>
                                <a href="{{ route('show.collectible', ['slug' => $collectible->slug ]) }}"
                                    class="btn viewBtn">VIEW</a>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>


    @include('includes.action-modal')

</div>
</div>
</div>

@section('extra_scripts')
<script>
$(document).ready(function() {
    $(".sorter").click(function() {
        toggleDropdown($(".sortfilter"), "d-none");
    });

    $(".filter-btn").click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    $('.report').click(function() {
        modalOpen($('#reportModal'), $(".report-content"));
    });
    $('.close-report-modal').click(function() {
        modalClose($('#reportModal'), $(".report-content"));
    });

    $(document).click(function(event) {

        if ($(event.target).closest('.sorter').length === 0 && $(event.target).closest('.sortfilter')
            .length === 0 && $('.sortfilter').hasClass('fade-in-top')) {
            toggleDropdown($(".sortfilter"), "d-none");
        }
        event.stopPropagation();
    });


    $(".filter-btn").click(function() {
        $(".checkLabel").hide();
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        var category = $(this).data('filter-id');
        var sortBy = '';
        var order = '';
        filterNft(category, sortBy, order);
    });

    $(".sortItem").click(function() {
        $(".checkLabel").hide();
        $(this).find(".checkLabel").show();
        var category = $(".filter-btn.active").data('filter-id');
        var sortBy = $(this).data('sort-by');
        var order = $(this).data('order');
        filterNft(category, sortBy, order);
    });

    $('#user-type').change(function() {
        var day = $('#filter-time').val();
        var type = $(this).val();
        filterUser(day, type);
    });
    $('#filter-time').change(function() {
        var day = $(this).val();
        var type = $('#user-type').val();
        filterUser(day, type);

    });



});

function filterUser(day, type) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/nft/user/filter",
        type: "GET",
        data: {
            day: day,
            type: type,
        },
        beforeSend: function() {
            $("#preloader-top-user").removeClass('d-none');
            $('#actual-top-user').addClass('d-none');
        },
        success: function(response) {
            $("#preloader-top-user").addClass('d-none');
            $('#actual-top-user').removeClass('d-none');
            var html = '';

            $.each(response, function(index, user) {
                var totalPrice = user['totalPrice'];
                var display_name = user['display_name'];
                var asset_url = user['asset_url'];
                var source = "{!! asset('/" + asset_url + "') !!}";

                html += '<div class="row topUserList">\
					<div class="col-3 col-sm-3 col-md-3 col-lg-3">\
						<img class="filterImg" src="' + source + '">\
					</div>\
					<div class="col-9 col-sm-9 col-md-9 col-lg-9">\
						<h6>' + display_name + '</h6>\
						<label>' + totalPrice + ' BHC</label>\
					</div>\
				</div>';

            });
            $('.filterList').html(html);
        },
        error: function(xhr) {
            console.log('Something went wrong!');
        },
    });

}

function filterNft(category, sortBy, order) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/nft/filter/" + category,
        type: "GET",
        data: {
            sortBy: sortBy,
            order: order,
        },
        beforeSend: function() {
            $("#preloader").removeClass('d-none');
            $('#nft-container').addClass('d-none');
        },
        success: function(response) {
            $("#preloader").addClass('d-none');
            $('#nft-container').removeClass('d-none');
            var html = '';
            var collectibles = response.collectibles;

            $.each(collectibles, function(index, nft) {
                var legend = nft['legend'];
                var legend_upper = jsUcFirst(nft['legend']);
                var icon = nft['icon'];
                var collectible = nft['nft'];
                var price = nft['price'];
                var copies = nft['copies'];
                var name = nft['name'];
                var slug = nft['slug'];

                var source = "{!! asset('storage/collectibles/" + collectible + "') !!}";
                var routes = "{!! asset('nft/" + slug + "') !!}";

                var dropdown = '';
                if (nft['isp'] == 1) {
                    dropdown += '<a id="' + slug +
                        '" class="buy-now" href="javascript:void(0)">Buy now</a>';
                }
                dropdown += '<a id="' + slug + '" class="place-bid" href="javascript:void(0)">Place a bid</a>\
			    			<a id="' + slug + '" class="report" href="javascript:void(0)">Report</a>';


                var asset = '';
                if (nft['type'] == 'image') {
                    asset = '<img src="' + source + '">';
                } else {
                    asset = '<video autoplay loop muted>\
							      <source src="' + source + '" type="video/mp4">\
							</video>';
                }


                html += '<div class="col-md-3 col-lg-3 custom-column-xl main-dashboard">\
							<div class="outside-nft border-on-profile">\
							  <div class="inside-nft">\
							  	<div class="inner-outside-nft">\
							  		<div class="inner-nft">\
							  			<div class="item-main">\
											<div class="item-head">\
												<div class="legend">\
			        								<div class="' + legend + '">\
			        									<i class="' + icon + '"></i> ' + legend_upper + '\
			        								</div>\
			        							</div>\
			        							<div class="item-menu">\
			        								<i class="fas fa-ellipsis-h"></i>\
			        							</div>\
			        						</div>\
			        						<div class="item-menu-drop d-none">\
			        							' + dropdown + '\
			    							</div>\
										</div>\
										<div class="item-img">\
											' + asset + '\
										</div>\
										<div class="display-flex">\
											<div class="text-center currency-amount">\
												' + price + '\
											</div>\
											<span class="copies">' + copies + '</span>\
										</div>\
										<div class="text-center currency-label">' + name + '</div>\
										<a href="' + routes + '" class="btn viewBtn">VIEW</a>\
							  		</div>\
							  	</div>\
							  </div>\
							</div>\
						</div>';
            });
            $('#nft-container').html(html);
        },
        error: function(xhr) {
            console.log('Something went wrong!');
        },
    });
}
</script>
@endsection


@endsection