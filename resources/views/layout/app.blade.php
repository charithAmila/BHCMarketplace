<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>


    {{-- Fonts --}}
    <script src="https://kit.fontawesome.com/f8f2a9650a.js" crossorigin="anonymous"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    {{-- Styles --}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        @include('includes.header')
        @yield('extra_css')
        <div id="main-app">
            @yield('content')
        </div>

    </div>
    @yield('extra_scripts')
    <script>
    $(document).ready(function() {

        $(document).ready(function() {
            $('.filter').click(function() {
                $('.filter').removeClass('active');
                $(this).addClass('active');
            });
        });

        $('.search-mobile').click(function() {
            $('#main-app').hide();
            $('.default-nav').hide();
            $('.onclick-search').fadeIn();
        });
        $('.back-btn').click(function() {
            $('.onclick-search').hide();
            $('#main-app').fadeIn();
            $('.default-nav').fadeIn();
        });

        $('.hamburger-menu').click(function() {
            $('#main-app').hide();
            $('.default-nav').hide();
            $('.onclick-menu').fadeIn();
        });

        $('.menu-close').click(function() {
            $('.onclick-menu').hide();
            $('#main-app').fadeIn();
            $('.default-nav').fadeIn();
        });


        // Close when clicking outside



        $('.notif-mobile').click(function() {
            $('.notification').toggleClass('d-none');
        });

        $('.notif-btn').click(function() {
            toggleNotification();
        });


        $(document).on('click', function(event) {
            if ($(event.target).closest('.notif-btn').length === 0 && $(event.target).closest(
                    '.notification').length === 0 && $('.notification').hasClass('d-md-block')) {
                toggleNotification();
            }
            event.stopPropagation();
        });



        $('.profile-btn').hover(function() {
            identifyState();
        });

        $('.profile-menu').mouseleave(function() {
            $('.profile-btn').removeClass('hovered');
            identifyState();
        });



        // PREFERENCES MODAL
        $('.preferencesBtn').click(function() {
            $('#preferencesModal').addClass('d-block');
            $(".preferences-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
        });

        $('#edit-profile-btn').click(function() {
            $('#preferencesModal').addClass('d-block');
            $(".preferences-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
        });

        $('.close-custom-modal').click(function() {
            $(".preferences-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
            setTimeout(function() {
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
        } else {
            if (!$('.profile-btn').hasClass('hovered')) {
                container.removeClass("fade-in-top").addClass("fade-out-top");
                $(".profile-btn").removeClass("hovered");
                setTimeout(function() {
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
        } else {
            container.addClass("fade-out-top").removeClass("fade-in-top");
            setTimeout(function() {
                $('.notification').toggleClass('d-md-block');
            }, 400);
        }
    }
    </script>

    <script>
    $(document).ready(function() {

        $(document).on("change", "#user-photo", function() {
            var accepted = ['gif', 'png', 'jpg'];
            var ext = $("#user-photo").val().split('.').pop().toLowerCase();
            if ($.inArray(ext, accepted) !== -1) {
                readUploadedPhoto(this, $('.user-logo'));
            }
        });

        $('#preferences-submit').click(function() {
            var count_errors = 0;
            $('.preferences-check').each(function() {
                if ($(this).val() == '') {
                    count_errors += 1;
                    $(this).addClass('emptyVal');
                    $(this).siblings('.preferences-error').text(
                        'This field is not allowed to be empty');
                }
            });

            if (count_errors == 0) {
                $('.preferences-check').removeClass('emptyVal');
                $('.preferences-error').text('');
                $('#update-preferences').submit();
            }
        });

        $(document).on('propertychange input', '.preferences-check', function() {
            $(this).removeClass('emptyVal');
            $(this).siblings('.preferences-error').text('');
        });

        $('#update-preferences').on('submit', function(event) {
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formdata = new FormData();

            if ($('#user-photo').get(0).files.length > 0) {
                formdata.append('display_photo', $('#user-photo').prop('files')[0]);
            }

            formdata.append('name', $('#name-profile').val());
            formdata.append('description', $('#description-profile').val());
            formdata.append('short_url', $('#short_url-profile').val());
            formdata.append('uploaded', 'dp');


            $.ajax({
                url: "/users/update",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function(response) {


                    $(".preferences-content").removeClass("fade-in-bottom").addClass(
                        "fade-out-bottom");
                    setTimeout(function() {
                        $('#preferencesModal').removeClass('d-block');
                    }, 400);

                    $('.toast-message').text(response.message);
                    $('#update-preferences').trigger("reset");
                    setTimeout(function() {
                        launch_toast();
                    }, 500);

                    var source = "{!! asset('images/avatar2.png') !!}"
                    $('#user-photo').val(null);
                    $('.user-logo').attr('src', source);


                    var updated_dp = "{!! asset('storage/user/photo') !!}/" + response
                        .photo;
                    $('#current-display-photo').attr('src', updated_dp);
                    $('.navbar-img-profile').attr('src', updated_dp);
                    $('#profile-name').text(response.name);
                    $('#profile-bio').text(response.description);

                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        var errors = JSON.parse(xhr.responseText);
                        $.each(errors.errors, function(key, value) {
                            var html = '';
                            $.each(value, function(key2, value2) {
                                html += value2 + '</br>';
                            });
                            $('#' + key + '-preferences').html(html);
                        });
                    }
                },
            });
        });

    });


    function readUploadedPhoto(input, container) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                container.attr('src', e.target.result);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }

    function launch_toast() {
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 5000);
    }
    </script>


</body>

</html>