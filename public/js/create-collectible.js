$(document).ready(function() {
    $(".close-collectible-modal").click(function() {
        $("#create-collectible-modal").removeClass("d-block");
    });
    // Collection MODAL
    $(".generate_collection").click(function() {
        $("#collectionModal").addClass("d-block");
        $(".collection-content")
            .removeClass("fade-out-bottom")
            .addClass("fade-in-bottom");
    });

    $(".close-custom-modal").click(function() {
        $(".collection-content")
            .removeClass("fade-in-bottom")
            .addClass("fade-out-bottom");
        setTimeout(function() {
            $("#collectionModal").removeClass("d-block");
        }, 400);
    });
    // ######################

    // Steps MODAL
    // $('.submitBtn').click(function(){
    //     $('#create-stepsModal').addClass('d-block');
    //     $(".create-steps-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
    // });

    $(".close-steps-modal").click(function() {
        $(".create-steps-content")
            .removeClass("fade-in-bottom")
            .addClass("fade-out-bottom");
        setTimeout(function() {
            $("#create-stepsModal").removeClass("d-block");
        }, 400);
    });

    // ######################

    $(document).on("change", "#putSale", function(evt) {
        $(".instant-sale").toggleClass("d-none");
        $("#instantSale").prop("checked", false);
        $(".price-tag").addClass("d-none");
    });

    $(document).on("change", "#instantSale", function(evt) {
        if ($(this).prop("checked") == false) {
            $(".price-tag").addClass("d-none");
            $(".sale-price").removeClass("to-check");
        } else {
            $(".sale-price").addClass("to-check");
            $(".price-tag").removeClass("d-none");
        }
        $(".sale-price").removeClass("emptyVal");
    });

    $(document).on("change", "#accessToggle", function(evt) {
        $(".digital-link").toggleClass("d-none");
        $("#d-link").toggleClass("to-check");
        $("#d-link").removeClass("emptyVal");
    });

    $(document).on("input", ".provided-key", function(e) {
        if ($(this).val() != "" && $(this).hasClass("input-head")) {
            $(this).removeClass("input-head");
            var html = "";
            html +=
                '<div class="row">\
						<div class="col-6 col-md-6">\
							<input class="inp bold-placeholder provided-key input-head" type="text" name="key[]" placeholder="Provided Key"/>\
						</div>\
						<div class="col-6 col-md-6">\
							<input class="inp bold-placeholder provided-value" type="text" name="value[]" placeholder="Provided Value"/>\
						</div>\
					</div>';
            $("#properties-field").append(html);
        }
        if ($(this).val() == "" && !$(this).hasClass("input-head")) {
            $(this)
                .parent()
                .parent()
                .remove();
        }
    });

    /*

    $(document).on("change", "#selectedFile", function() {
    	$('#fileInvalid').addClass('d-none');
    	$('#sizeInvalid').addClass('d-none');
    	var correctSize = checkFileSize();
    	var ext = $("#selectedFile").val().split('.').pop().toLowerCase();
    	var accepted = ['gif', 'png', 'jpg','mp4', 'webp', 'mp3'];
    	var images = ['gif', 'png', 'jpg'];
    	var videos = ['mp4', 'webp', 'mp3'];

    	if (correctSize) {
	    	if ($.inArray(ext, accepted) !== -1) {
	    		$('.file-input').addClass('d-none');
		    	if ($.inArray(ext, images) !== -1) {
		    		$('.file-output').removeClass('d-none');
		    		$('.image-container').removeClass('d-none');
		    		readURL(this);
		    	}
		    	if ($.inArray(ext, videos) !== -1) {
		    		$('.video-container').removeClass('d-none');
		    		$('.file-output-video').removeClass('d-none');
					var $source = $('.category-video-tag');
					$source[0].src = URL.createObjectURL(this.files[0]);
					$source.parent()[0].load();
					var preview = $('.category-video-prev');
					preview[0].src = URL.createObjectURL(this.files[0]);
					preview.parent()[0].load();
		    	}
		    	$('.preview-desc').toggleClass('d-none');
	    	}
	    	else{
	    		$('#fileInvalid').removeClass('d-none');
	    	}
    	}
    });
	*/

    $(document).on("change", "#collection-file", function() {
        var accepted = ["gif", "png", "jpg"];
        var ext = $("#collection-file")
            .val()
            .split(".")
            .pop()
            .toLowerCase();
        if ($.inArray(ext, accepted) !== -1) {
            readCollectionLogo(this);
        }
    });

    $(".close-btn").click(function() {
        $(".file-input").removeClass("d-none");
        $(".file-output").addClass("d-none");
        $("#selectedFile").val(null);
        $(".category-img-tag").attr("src", "");
        $(".image-container").addClass("d-none");
        $(".preview-desc").toggleClass("d-none");
    });

    $(".close-btn-video").click(function() {
        $(".file-input").removeClass("d-none");
        $(".file-output-video").addClass("d-none");
        $("#selectedFile").val(null);
        $(".category-video-tag").attr("src", "");
        $(".video-container").addClass("d-none");
        $(".preview-desc").toggleClass("d-none");
    });

    $(".fileBtn").click(function() {
        $(this).addClass("button-click");
        setTimeout(function() {
            $(".fileBtn").removeClass("button-click");
        }, 80);
    });

    $(document).on('click', '.sale-price-btn', function(){
        toggleCurrencyDrop();
    })
    $(".sale-price-btn").click(function() {
        toggleCurrencyDrop();
    });

    $(document).on('click', '.currency-item', function(){
        $(".currency-check").addClass("opacity-0");
        $(this)
            .siblings()
            .removeClass("opacity-0");
        $(".sale-price-btn").attr("id", $(this).attr("id"));
        $(".sale-price-btn").html(
            $(this).attr("id") + ' <i class="fa fa-angle-down"></i>'
        );
        $(".changeDD").text($(this).attr("id"));
        toggleCurrencyDrop();
    });

    $("#createCollectible").click(function() {
        var count_errors = 0;
        $(".to-check").each(function() {
            if ($(this).val() == "") {
                count_errors += 1;
                $(this).addClass("emptyVal");
                $(this)
                    .parent()
                    .siblings(".this-error")
                    .text("This field is not allowed to be empty");
            }
        });
        if (count_errors > 0) {
            $(".error-msg").removeClass("d-none");
        } else {
            $(".to-check").removeClass("emptyVal");
            $(".this-error").text("");
            $("#create-collectible").submit();
        }
    });

    $("#collection-submit").click(function() {
        var count_errors = 0;
        $(".collection-check").each(function() {
            if ($(this).val() == "") {
                count_errors += 1;
                $(this).addClass("emptyVal");
                $(this)
                    .siblings(".collection-error")
                    .text("This field is not allowed to be empty");
            }
        });

        if (count_errors == 0) {
            $(".collection-check").removeClass("emptyVal");
            $(".collection-error").text("");
            // $('#create-collection').submit();
        }
    });

    $(document).on("propertychange input", ".to-check", function() {
        $(this).removeClass("emptyVal");
        $(this)
            .parent()
            .siblings(".this-error")
            .text("");
    });

    $(document).on("propertychange input", ".collection-check", function() {
        $(this).removeClass("emptyVal");
        $(this)
            .parent()
            .siblings(".collection-check")
            .text("");
        $(this)
            .siblings(".custom-error")
            .text("");
        $(this)
            .siblings(".collection-error")
            .text("");
    });

    $("#create-collection").on("submit", function(event) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        var formdata = new FormData();

        formdata.append("image", $("#collection-file").prop("files")[0]);
        formdata.append(
            "display_name",
            $(".collection-input[name='display_name']").val()
        );
        formdata.append("symbol", $(".collection-input[name='symbol']").val());
        formdata.append(
            "description",
            $(".collection-input[name='description']").val()
        );
        formdata.append(
            "short_url",
            $(".collection-input[name='short_url']").val()
        );

        $.ajax({
            url: "/collection",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function(response) {
                $(".collection-content")
                    .removeClass("fade-in-bottom")
                    .addClass("fade-out-bottom");
                setTimeout(function() {
                    $("#collectionModal").removeClass("d-block");
                }, 400);

                $(".toast-message").text(response.message);
                $("#create-collection").trigger("reset");
                setTimeout(function() {
                    launch_toast();
                }, 500);

                var collection = response.collection;
                var id = collection["id"];
                var image = collection["image"];
                var display_name = collection["display_name"];
                var symbol = collection["symbol"];

                var source =
                    "{!! asset('storage/collections/" + image + "') !!}";

                var html = "";
                html +=
                    '<a id="' +
                    id +
                    '" href="javascript:void(0)" class="g_select active-btn">\
							<div class="outside">\
							  <div class="inside">\
							  	<div class="inner-outside">\
							  		<div class="inner">\
							  			<img src="' +
                    source +
                    '">\
							  			<h6>' +
                    display_name +
                    " " +
                    symbol +
                    "</h6>\
							  		</div>\
							  	</div>\
							  </div>\
							</div>\
						</a>";

                setTimeout(function() {
                    $(".g_select")
                        .removeClass("active-btn")
                        .addClass("inactive-btn");
                    $("#collection-group").append(html);
                }, 600);
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = JSON.parse(xhr.responseText);
                    $.each(errors.errors, function(key, value) {
                        var html = "";
                        $.each(value, function(key2, value2) {
                            html += value2 + "</br>";
                        });
                        $("#" + key + "-collection").html(html);
                    });
                }
            }
        });
    });

    $("#create-collectible").on("submit", function(event) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        var formdata = new FormData();

        var aop = $("[name='aop']").is(":checked") == true ? 1 : 0;
        var pos = $("[name='pos']").is(":checked") == true ? 1 : 0;
        var isp = $("[name='isp']").is(":checked") == true ? 1 : 0;

        formdata.append("nft", $("#selectedFile").prop("files")[0]);
        formdata.append("aop", aop);
        formdata.append("aop_link", $("[name='aop_link']").val());
        formdata.append("collection_id", $(".active-btn").attr("id"));
        formdata.append("name", $("[name='collectible_name']").val());
        formdata.append("description", $("[name='description']").val());
        formdata.append("royalties", $("[name='royalties']").val());

        var copies =
            $("[name='copies']").val() != undefined ?
            $("[name='copies']").val() :
            1;
        formdata.append("copies", copies);
        formdata.append("pos", pos);
        formdata.append("isp", isp);
        formdata.append("price", $("[name='price']").val());
        formdata.append("currency", $(".sale-price-btn").attr("id"));
        formdata.append("category_id", $("#category-drop").val());
        formdata.append("legend_id", $("#legend-drop").val());

        var keys = $(".provided-key");
        var values = $(".provided-value");

        var arr = [];
        var this_row = {};
        for (var i = 0; i < keys.length; i++) {
            var key = $(keys[i]).val();
            var value = $(values[i]).val();
            this_row[key] = value;
        }
        arr.push(this_row);
        arr = JSON.stringify(arr);
        formdata.append("properties", arr);

        formdata.append(
            "type",
            $("#create-collectible").data("collectible-type")
        );

        $.ajax({
            url: "/create/collectible",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function(response) {
                $("html, body").animate({
                        scrollTop: $("#main-app").offset().top
                    },
                    200
                );

                $(".toast-message").text(response.message);
                launch_toast();

                $(".digital-link").addClass("d-none");
                $("#d-link")
                    .removeClass("to-check")
                    .removeClass("emptyVal");

                $(".price-tag").addClass("d-none");
                $(".sale-price")
                    .removeClass("to-check")
                    .removeClass("emptyVal");

                $(".file-input").removeClass("d-none");
                $(".file-output").addClass("d-none");
                $(".file-output-video").addClass("d-none");
                $("#selectedFile").val(null);
                $(".category-img-tag").attr("src", "");
                $(".category-video-tag").attr("src", "");
                $(".video-container").addClass("d-none");
                $(".preview-desc").toggleClass("d-none");
                $(".error-msg").addClass("d-none");
                $("#create-collectible").trigger("reset");

                var html = "";
                html +=
                    '<div class="row">\
						<div class="col-6 col-md-6">\
							<input class="inp bold-placeholder provided-key input-head" type="text" name="key[]" placeholder="Provided Key"/>\
						</div>\
						<div class="col-6 col-md-6">\
							<input class="inp bold-placeholder provided-value" type="text" name="value[]" placeholder="Provided Value"/>\
						</div>\
					</div>';
                $("#properties-field").html(html);
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = JSON.parse(xhr.responseText);
                    $.each(errors.errors, function(key, value) {
                        var html = "";
                        $.each(value, function(key2, value2) {
                            html += value2 + "</br>";
                        });
                        $("#" + key + "-validation").html(html);
                    });
                }
            }
        });
    });

    $(document).on("click", ".g_select", function() {
        $(".g_select").addClass("inactive-btn");
        $(".g_select").removeClass("active-btn");
        $(this).addClass("active-btn");
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(".category-img-tag").attr("src", e.target.result);
        };
    }
    reader.readAsDataURL(input.files[0]);
}

function readCollectionLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#collection-logo-form").attr("src", e.target.result);
        };
    }
    reader.readAsDataURL(input.files[0]);
}

function checkFileSize() {
    const fi = document.getElementById("selectedFile");
    // Check if any file is selected.
    if (fi.files.length > 0) {
        for (var i = 0; i <= fi.files.length - 1; i++) {
            const fsize = fi.files.item(i).size;
            const file = Math.round(fsize / 1024);
            if (file >= 51200) {
                $("#sizeInvalid").removeClass("d-none");
                return false;
            }
            return true;
        }
    }
}

function toggleCurrencyDrop() {
    var container = $(".sale-price-drop");
    if (!container.hasClass("fade-in-top")) {
        container.toggleClass("d-none");
        container.addClass("fade-in-top").removeClass("fade-out-top");
    } else {
        container.addClass("fade-out-top").removeClass("fade-in-top");
        setTimeout(function() {
            container.toggleClass("d-none");
        }, 400);
    }
}

function launch_toast() {
    var x = document.getElementById("toast");
    x.className = "show";
    setTimeout(function() {
        x.className = x.className.replace("show", "");
    }, 5000);
}