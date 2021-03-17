$(document).ready(function () {

    $(document).ready(function () {
        $('.filter').click(function () {
            $('.filter').removeClass('active');
            $(this).addClass('active');
        });
    });

    $('.search-mobile').click(function () {
        $('.default-nav').hide();
        $('.onclick-search').fadeIn();
    });
    $('.back-btn').click(function () {
        $('.onclick-search').hide();
        $('.default-nav').fadeIn();
    });

    $('.hamburger-menu').click(function () {
        $('.main-content').hide();
        $('.faq-container').hide();
        $('.about-bhc').hide();
        $('.user-profile-page').hide();
        $('.default-nav').hide();
        $('.onclick-menu').fadeIn();
    });

    $('.menu-close').click(function () {
        $('.onclick-menu').hide();
        $('.faq-container').fadeIn();
        $('.about-bhc').fadeIn();
        $('.user-profile-page').fadeIn();
        $('.main-content').fadeIn();
        $('.default-nav').fadeIn();
    });

    $('.report-collection').click(function () {
        modalOpen($('#reportModal'), $(".report-content"));
        $('#reportModal').attr("data-report-type", "collection");
        var output = getPath();
        $('#reportModal').attr('data-report-slug', output);
    });


    // Close when clicking outside



    $('.notif-mobile').click(function () {
        $('.notification').toggleClass('d-none');
    });

    $('.notif-btn').click(function () {
        toggleNotification();
    });


    $(document).on('click', function (event) {
        if ($(event.target).closest('.notif-btn').length === 0 && $(event.target).closest('.notification').length === 0 && $('.notification').hasClass('d-md-block')) {
            toggleNotification();
        }
        event.stopPropagation();
    });



    $('.profile-btn').hover(function () {
        identifyState();
    });

    $('.profile-menu').mouseleave(function () {
        $('.profile-btn').removeClass('hovered');
        identifyState();
    });



    // PREFERENCES MODAL
    $('.preferencesBtn').click(function () {
        $('#preferencesModal').addClass('d-block');
        $(".preferences-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
    });

    $('#edit-profile-btn').click(function () {
        $('#preferencesModal').addClass('d-block');
        $(".preferences-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
    });

    $('.close-custom-modal').click(function () {
        $(".preferences-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
        setTimeout(function () {
            $('#preferencesModal').removeClass('d-block');
        }, 400);
    });
    // ######################





});

function identifyState() {
    var container = $('.profile-menu');
    if (!$(".profile-menu").hasClass('fade-in-top')) {
        container.toggleClass('d-md-block');
        container.addClass("fade-in-top").removeClass("fade-out-top");
        $(".profile-btn").addClass("hovered");
    }
    else {
        if (!$('.profile-btn').hasClass('hovered')) {
            container.removeClass("fade-in-top").addClass("fade-out-top");
            $(".profile-btn").removeClass("hovered");
            setTimeout(function () {
                container.toggleClass('d-md-block');
            }, 200);
        }

    }
}

function toggleNotification() {
    var container = $(".notification");
    if (!container.hasClass('fade-in-top')) {
        $('.notification').toggleClass('d-md-block');
        container.addClass("fade-in-top").removeClass("fade-out-top");
    }
    else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function () {
            $('.notification').toggleClass('d-md-block');
        }, 400);
    }
}