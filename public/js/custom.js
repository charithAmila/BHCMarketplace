// $(document).on('click', '.buy-now', function(){
//     var slug = $(this).attr('id');
//     setTimeout(function() {
//       modalOpen($('#checkoutModal'), $(".checkout-content"));
//     }, 200);

// });

$(document).on('click', '.close-checkout-modal', function () {
    $('#checkout-quantity').val(1);
    modalClose($('#checkoutModal'), $(".checkout-content"));
});

$(document).on('click', '.close-putOnSale-modal', function () {
    //$('#checkout-quantity').val(1);
    modalClose($('#putOnSaleModal'), $(".checkout-content"));
});

$(document).on('click', '.close-bid-list-modal', function () {
    modalClose($('#bidListModal'), $(".bidList-content"));
});

$(document).on('click', '.close-transfer-modal', function () {
    modalClose($('#transferModal'), $(".transfer-content"));
});

$(document).on('click', '.close-changePrice-modal', function () {
    modalClose($('#changePriceModal'), $(".changePrice-content"));
});

$(document).on('click', '.close-burn-modal', function () {
    modalClose($('#burnModal'), $(".burn-content"));
});



$(document).on('click', '.report-page', function () {
    $('#reportModal').attr('data-report-type', 'user');
    var output = getPath();
    $('#reportModal').attr('data-report-slug', output);

});

$(document).on('click', '.report', function () {
    $('#reportModal').attr('data-report-type', 'nft');
});






// $(document).on('click', '.place-bid', function(){
//     var slug = $(this).attr('id');
//     setTimeout(function() {
//       modalOpen($('#bidModal'), $(".bid-content"));
//     }, 200);

// });

$(document).on('click', '.close-bid-modal', function () {
    $('#bid-service_fee').text('0');
    $('#bid-total').text('0');
    $('#bid-input').val('');
    modalClose($('#bidModal'), $(".bid-content"));
});


$(document).on('click', '.currency-btn', function () {
    toggleDropdown($(".currency-drop"), "d-none");
});

$(document).on('click', '.currency-item', function () {
    $('.currency-check').addClass('opacity-0');
    $(this).siblings().removeClass('opacity-0');
    $('.currency-btn').html('<span id="selectedCurrency">' + $(this).attr('id') + '</span>' + ' <i class="fa fa-angle-down"></i>');
    $('.changeDD').text($(this).attr('id'));
    toggleDropdown($(".currency-drop"), "d-none");
});


$(document).on('click', function (event) {
    if ($(event.target).closest('.item-menu').length === 0 && $(event.target).closest('.item-menu-drop').length === 0) {
        $(".item-menu-drop").addClass("d-none");
        $(".item-menu-drop").removeClass("fade-in-top");
    }

    if ($(event.target).closest('.action-menu').length === 0 && $(event.target).closest('.profile-action-menu-drop').length === 0) {
        $(".profile-action-menu-drop").addClass("d-none");
        $(".profile-action-menu-drop").removeClass("fade-in-top");
    }
    event.stopPropagation();
});


$(document).on('click', '.item-menu', function () {
    $(".item-menu-drop").addClass("d-none");
    var container = $(this).parent().siblings(".item-menu-drop");
    if (!container.hasClass('fade-in-top')) {
        container.removeClass("d-none");
        $(".item-menu-drop").removeClass('fade-in-top').removeClass('fade-out-top');
        container.addClass("fade-in-top").removeClass("fade-out-top");
    }
    else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function () {
            $(".item-menu-drop").addClass("d-none");
        }, 200);
    }
});


$(document).on('click', '.action-menu', function () {
    $(".profile-action-menu-drop").addClass("d-none");
    var container = $(this).parent().siblings(".profile-action-menu-drop");
    if (!container.hasClass('fade-in-top')) {
        container.removeClass("d-none");
        $(".profile-action-menu-drop").removeClass('fade-in-top').removeClass('fade-out-top');
        container.addClass("fade-in-top").removeClass("fade-out-top");
    }
    else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function () {
            $(".profile-action-menu-drop").addClass("d-none");
        }, 200);
    }
});



function getPath() {
    var url = new URL(location.href);
    var pathname = new URL(url).pathname;
    var output = pathname.split('/').pop();
    return output;
}


function toggleDropdown(container, stringClass) {
    if (!container.hasClass('fade-in-top')) {
        container.toggleClass(stringClass);
        container.addClass("fade-in-top").removeClass("fade-out-top");
    }
    else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function () {
            container.toggleClass(stringClass);
        }, 400);
    }
}

function launch_toast() {
    var x = document.getElementById("toast")
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 5000);
}

function populateForm(slug) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/nft/fetch/" + slug,
        type: "GET",
        success: function (response) {
            var collectible = response.collectible;
            var price = parseFloat(collectible['price']).toFixed(2);
            var service_fee = (price * 0.025).toFixed(2);
            var total = +price + +service_fee;
            $('.item-name').text(collectible['name']);
            $('#checkout-price').val(collectible['price']);
            $('#checkout-currency').text(collectible['currency']);
            $('#service_fee').text(service_fee);
            $('#total_checkout').text(total);
            $('#checkout-quantity').attr('max', collectible['copies']);
        },
        error: function (xhr) {
            console.log('Something went wrong!');
        },
    });
}

function modalOpen(modal, content) {
    modal.addClass('d-block');
    content.removeClass("fade-out-bottom").addClass("fade-in-bottom");
}

function modalClose(modal, content) {
    content.removeClass("fade-in-bottom").addClass("fade-out-bottom");
    setTimeout(function () {
        modal.removeClass('d-block');
    }, 400);
}


function jsUcFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/*function showPreferencesModal(){
    $('#preferencesModal').addClass('d-block');
    $(".preferences-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/user/fetch/",
        type: "GET",
        success: function(response){
            var user = response.user;
            $('.user-logo').attr('src', $("#current-display-photo").attr('src'));


            $('#name-profile').val(user['name']);
            $('#description-profile').val(user['description']);
            $('#short_url-profile').val(user['short_url']);

        },
        error: function (xhr) {
            console.log('Something went wrong!');
        },
    });
}*/