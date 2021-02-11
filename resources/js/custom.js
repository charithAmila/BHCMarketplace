$(document).on("click", ".buy-now", function() {
    var slug = $(this).attr("id");
    populateForm(slug);
    setTimeout(function() {
        modalOpen($("#checkoutModal"), $(".checkout-content"));
    }, 200);
});
$(document).on("propertychange input", "#checkout-quantity", function() {
    var quantity = $(this).val();
    var sum = $("#checkout-price").val() * quantity;
    var service_fee = (sum * 0.025).toFixed(2);
    var total = (+sum + +service_fee).toFixed(2);
    $("#service_fee").text(service_fee);
    $("#total_checkout").text(total);
});
$(".close-checkout-modal").click(function() {
    $("#checkout-quantity").val(1);
    modalClose($("#checkoutModal"), $(".checkout-content"));
});

$(document).on("click", ".place-bid", function() {
    var slug = $(this).attr("id");
    populateForm(slug);
    setTimeout(function() {
        modalOpen($("#bidModal"), $(".bid-content"));
    }, 200);
});
$(document).on("propertychange input", "#bid-input", function() {
    var bid = $(this).val();
    var service_fee = (bid * 0.025).toFixed(2);
    var total = (+bid + +service_fee).toFixed(2);
    $("#bid-service_fee").text(service_fee);
    $("#bid-total").text(total);
});
$(".close-bid-modal").click(function() {
    $("#bid-service_fee").text("0");
    $("#bid-total").text("0");
    $("#bid-input").val("");
    modalClose($("#bidModal"), $(".bid-content"));
});

$(".currency-btn").click(function() {
    toggleDropdown($(".currency-drop"), "d-none");
});

$(".currency-item").click(function() {
    $(".currency-check").addClass("opacity-0");
    $(this)
        .siblings()
        .removeClass("opacity-0");
    $(".currency-btn").html(
        $(this).attr("id") + ' <i class="fa fa-angle-down"></i>'
    );
    $(".changeDD").text($(this).attr("id"));
    toggleDropdown($(".currency-drop"), "d-none");
});

$(document).on("click", function(event) {
    if (
        $(event.target).closest(".item-menu").length === 0 &&
        $(event.target).closest(".item-menu-drop").length === 0
    ) {
        $(".item-menu-drop").addClass("d-none");
        $(".item-menu-drop").removeClass("fade-in-top");
    }
    event.stopPropagation();
});

$(document).on("click", ".item-menu", function() {
    $(".item-menu-drop").addClass("d-none");
    var container = $(this)
        .parent()
        .siblings(".item-menu-drop");
    if (!container.hasClass("fade-in-top")) {
        container.removeClass("d-none");
        $(".item-menu-drop")
            .removeClass("fade-in-top")
            .removeClass("fade-out-top");
        container.addClass("fade-in-top").removeClass("fade-out-top");
    } else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function() {
            $(".item-menu-drop").addClass("d-none");
        }, 200);
    }
});

function toggleDropdown(container, stringClass) {
    if (!container.hasClass("fade-in-top")) {
        container.toggleClass(stringClass);
        container.addClass("fade-in-top").removeClass("fade-out-top");
    } else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function() {
            container.toggleClass(stringClass);
        }, 400);
    }
}

function populateForm(slug) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $.ajax({
        url: "/nft/fetch/" + slug,
        type: "GET",
        success: function(response) {
            var collectible = response.collectible;
            var price = parseFloat(collectible["price"]).toFixed(2);
            var service_fee = (price * 0.025).toFixed(2);
            var total = +price + +service_fee;
            $(".item-name").text(collectible["name"]);
            $("#checkout-price").val(collectible["price"]);
            $("#checkout-currency").text(collectible["currency"]);
            $("#service_fee").text(service_fee);
            $("#total_checkout").text(total);
            $("#checkout-quantity").attr("max", collectible["copies"]);
        },
        error: function(xhr) {
            console.log("Something went wrong!");
        }
    });
}

function modalOpen(modal, content) {
    modal.addClass("d-block");
    content.removeClass("fade-out-bottom").addClass("fade-in-bottom");
}

function modalClose(modal, content) {
    content.removeClass("fade-in-bottom").addClass("fade-out-bottom");
    setTimeout(function() {
        modal.removeClass("d-block");
    }, 400);
}

function jsUcFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function showPreferencesModal() {
    $("#preferencesModal").addClass("d-block");
    $(".preferences-content")
        .removeClass("fade-out-bottom")
        .addClass("fade-in-bottom");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $.ajax({
        url: "/user/fetch/",
        type: "GET",
        success: function(response) {
            var user = response.user;
            $(".user-logo").attr(
                "src",
                $("#current-display-photo").attr("src")
            );

            $("#name-profile").val(user["name"]);
            $("#description-profile").val(user["description"]);
            $("#short_url-profile").val(user["short_url"]);
        },
        error: function(xhr) {
            console.log("Something went wrong!");
        }
    });
}

$(document).ready(function() {
    $(".wallet-link").click(function() {
        modalOpen($("#loginModal"), $(".login-content"));
    });
    $(".close-login-modal").click(function() {
        modalClose($("#loginModal"), $(".login-content"));
    });
});