$(document).ready(function() {
    // Filters Collapse
    $(document).on('click', '.filters-toggle', function() {
        if($(this).hasClass('filters-toggle-active')) {
            $(this).removeClass('filters-toggle-active');
            $('.filters-menu').hide();
        } else {
            $(this).addClass('filters-toggle-active');
            $('.filters-menu').show();
        }

        openPane($('.image-active'), {updateGallery: false, ignoreClose: true, scroll: false});
    });

    // Menu Collapse
    $(document).on('click', '.menu-button', function() {
        var menuId = $(this).data('db-id');

        // Remove all active button states
        $('.menu-button:not(#db-'+menuId+')').removeClass('menu-button-collapsed icon-active');

        // Remove all active drop-down states
        $('.menu').removeClass('menu-collapsed');

        // If the drop-down has already already opened
        if($('#db-'+menuId).hasClass('menu-button-collapsed')) {
            // Close the drop-down
            $('#db-'+menuId).removeClass('menu-button-collapsed icon-active');
            return;
        }

        // Add the drop-down active class
        $('#db-'+menuId).toggleClass('menu-button-collapsed icon-active');

        // Show the drop-down
        $('#dd-'+menuId).toggleClass('menu-collapsed');
    });

    $(document).on('click', '.filter-element', function() {
        // Remove all other filter dropdowns active states
        $('.filter-element:not(#'+$(this).attr('id')+')').removeClass('filter-element-active');

        // Add the dropdown active class
        $(this).toggleClass('filter-element-active');
    });

    $(document).on('click', '.notification-close-error, .notification-close-warning, .notification-close-success, .notification-close-info', function() {
        $(this).parent().fadeOut("slow");
        return false;
    });

    // Focus the search box
    if($('#search-input').data('autofocus') == 1) {
        if(isTouchDevice() == false) {
            $('#search-input').focus();
        }
    }

    // Clear search box
    $(document).on('click', '#clear-button', function() {
        $('#search-input').val('');
        $('#search-input').focus();
    });

    // Search button submit
    $(document).on('click', '#search-button', function() {
        var searchInput = $('#search-input');

        // If the search input is not empty
        if(searchInput.val().length > 0) {
            closeSearch();
            loadPage(searchInput.data('search-url')+searchInput.data('search-path')+'?q='+encodeURIComponent(searchInput.val()));
        }
    });

    // Home page active search option
    $(document).on('click', '.home-search-menu', function() {
        // Remove active classes if any
        $('.home-search-menu').removeClass('home-search-menu-active');

        // Add active class to selected element
        $(this).addClass('home-search-menu-active');

        // Update the search input with the new path
        $('#search-input').data('search-path', $(this).data('new-path'));
    });

    // Popup, Modals, Menus hide action
    $(document).on('mouseup', function(e) {
        // All the divs that needs to be excepted when being clicked (including the divs themselves)
        var container = $('.menu-button, .menu-content, .filter-element, .search-list, #search-input, #search-button');

        // If the element clicked isn't the container nor a descendant then hide elements
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            // Close menu
            if($('.menu-button').hasClass('menu-button-collapsed')) {
                $('.menu-button').click();
            }
            // Close Filters dropdowns
            if($('.filter-element').hasClass('filter-element-active')) {
                $('.filter-element').removeClass('filter-element-active');
            }

            // Close search list
            closeSearch();
            $('.content-home').removeClass('content-home-focus');
            $('.header-home').removeClass('header-home-focus');
        }
    });

    document.addEventListener('scroll', function (event) {
        if($(event.target).hasClass('row-dragscroll')) {
            if($(event.target).scrollLeft() > 10) {
                $(event.target).addClass('filters-fade-left');
            } else {
                $(event.target).removeClass('filters-fade-left');
            }

            if($(event.target).scrollRight() > 10) {
                $(event.target).addClass('filters-fade-right');
            } else {
                $(event.target).removeClass('filters-fade-right');
            }
        }
    }, true);

    // Update the panel's position when browser is resized
    $(window).on('resize', function() {
        setTimeout(function() {
            // If the div exists
            if($('.image-active').length) {
                openPane($('.image-active'), {updateGallery: false, ignoreClose: true, scroll: true});
            }
        }, 150);
    });

    $(document).on('click', '.pane-next', function() {
        $('.image-active').next('div').click();
    });

    $(document).on('click', '.pane-prev', function() {
        $('.image-active').prev('div').click();
    });

    $(document).on('click', '.pane-close', function() {
        closePane();
    });

    $(document).on('click', '.image-frame', function(e) {
        e.stopPropagation();
        e.preventDefault();
        openPane(this, {updateGallery: true, ignoreClose: false, scroll: true});
    });

    $(document).on('click', '.read-more', function() {
        $(this).hide();
        $(this).prev().removeAttr('class');
    });

    $(document).on('focus keyup', '#search-input', function(e) {

        var query = $('#search-input').val();

        // Clear any previous search queues
        if(typeof(searchTimeout) !== "undefined") {
            clearTimeout(searchTimeout);
        }

        var key = (e.keyCode || e.which);

        // If the key is an identifiable one
        if(key > 0) {
            $('.content-home').addClass('content-home-focus');
            $('.header-home').addClass('header-home-focus');
        }

        // On enter do the search
        if(key == 13) {
            $('#search-button').click();
            return false;
        }

        // If search suggestions are enabled
        if($(this).data('suggestions') > 0) {
            searchList(key);

            // If arrow keys are pressed
            if(key == 37 || key == 38 || key == 39 || key == 40 || key == 0) {
                if(query.length > 0) {
                    openSearch();
                }
                return;
            } else {
                closeSearch();
            }

            // Check if the new request is unique
            if(typeof(queryHistory) !== "undefined") {
                if(queryHistory == query) {
                    return false;
                }
            }

            // If the user typed in the search box
            if(query.length > 0) {
                searchTimeout = setTimeout(function() {
                    queryHistory = query;
                    search();
                    openSearch();
                }, 250);
            } else {
                closeSearch();
            }
        }
    });

    $(document).on('keyup', function(e) {
        var key = (e.keyCode || e.which);

        // If the search input is not focused
        if(e.target.id != 'search-input') {
            if(key == 37) {
                $('.image-active').prev('div').click();
            } else if(key == 39) {
                $('.image-active').next('div').click();
            }
        }
    });

    reload();
});

/**
 * Detect if the browser device has touch capability
 */
function isTouchDevice() {
    return 'ontouchstart' in document.documentElement;
}

$(document).on("click", "a:not([data-nd])", function() {
    var linkUrl = $(this).attr('href');
    loadPage(linkUrl, 0, null);
    return false;
});

$(window).bind('popstate', function() {
    var linkUrl = location.href;
    loadPage(linkUrl, 0, null);
});

/**
 * Send a GET or POST request dynamically
 *
 * @param   argUrl      Contains the page URL
 * @param   argParams   String or serialized params to be passed to the request
 * @param   argType     Decides the type of the request: 1 for POST; 0 for GET;
 * @param   options     Various misc options
 * @return  string
 */
function loadPage(argUrl, argType, argParams, options = {loadingBar: true}) {
    if(options.loadingBar) {
        loadingBar(1);
    }

    if(argType == 1) {
        argType = "POST";
    } else {
        argType = "GET";

        // Store the url to the last page accessed
        if(argUrl != window.location) {
            window.history.pushState({path: argUrl}, '', argUrl);
        }
    }

    // Request the page
    $.ajax({
        url: argUrl,
        type: argType,
        data: argParams,
        success: function(data) {
            // Parse the output
            try {
                var result = jQuery.parseJSON(data);

                $.each(result, function(item, value) {
                    if(item == "title") {
                        document.title = value;
                    } else if(['header', 'content', 'footer'].indexOf(item) > -1) {
                        $('#'+item).replaceWith(value);
                    } else {
                        $('#'+item).html(value);
                    }
                });
            } catch(e) {

            }

            // Scroll the document at the top of the page
            $(document).scrollTop(0);

            // Reload functions
            reload();

            if(options.loadingBar) {
                loadingBar(0);
            }
        }
    })
}

/**
 * The loading bar animation
 *
 * @param   type    The type of animation, 1 for start, 0 for stop
 */
function loadingBar(type) {
    if(type) {
        $("#loading-bar").show();
        $("#loading-bar").width((50 + Math.random() * 30) + "%");
    } else {
        $("#loading-bar").width("100%").delay(50).fadeOut(400, function() {
            $(this).width("0");
        });
    }
}

/**
 * This function gets called every time a dynamic request is made
 */
function reload() {
    loadFlexImages();
    dragscroll.reset();
}

/**
 * Load the justified gallery
 */
function loadFlexImages() {
    $("#images-results").flexImages({
        rowHeight: 175
    });
}

/**
 * Get search suggestions for a given query
 */
function search() {
    var searchInput = $('#search-input');
    loadPage(searchInput.data('search-url')+searchInput.data('suggestions-path'), 1, {q: searchInput.val(), searchType: searchInput.data('search-path'), token_id: searchInput.data('token-id')}, {loadingBar: false});
}

/**
 * Opens the drop-down search suggestions
 */
function openSearch() {
    $('.search-list').show();
}

/**
 * Closes the Search Results list
 */
function closeSearch() {
    $('.search-list').hide();
}

/**
 * Select an item on up/down keys from the search results list
 *
 * @param   key     The key the user has pressed
 */
function searchList(key) {
    var listItems = $('.search-list-item');
    var selected = listItems.filter('.list-item-selected');
    var current;

    if(key != 40 && key != 38) {
        return;
    }

    listItems.removeClass('list-item-selected');

    if(key == 40) {
        // If there's no selected item, or the selected item is the last element
        if(!selected.length || selected.is(':last-child')) {
            // Select the first element
            current = listItems.eq(0);
        } else {
            current = selected.next();
        }
    } else if(key == 38) {
        // If there's no selected item, or the selected item is the last element
        if(!selected.length || selected.is(':first-child')) {
            // Select the first element
            current = listItems.last();
        } else {
            current = selected.prev();
        }
    }

    // Add the selected class to the selected item
    current.addClass('list-item-selected');

    // Update the search input with the new value
    $('#search-input').val($('.list-item-selected').text().trim());
}

/**
 * Open the Preview Pane
 *
 * @param   target  The target element
 * @param   options Various misc options
 */
function openPane(target, options = {updateGallery: false, ignoreClose: false, scroll: false}) {
    // Close the pane if you click on the same target twice
    if(options.ignoreClose == false) {
        if($(target).hasClass('image-active')) {
            closePane();
            return false;
        }
    }

    // Remove all active rows
    $('.image-frame').removeClass('image-active-row');
    $('.image-frame').removeClass('image-active');

    if(options.updateGallery == true) {
        // Hide the preview image
        $('#pane-image').hide();
    } else {
        // If the high quality image is already loaded
        // Prevents showing a broken image when screen is resized while the image was still loading
        if(highQualityImage) {
            updatePaneImage();
        } else {
            updatePaneImage(1);
        }
    }

    // Add the active state to the image thumbnail
    $(target).addClass('image-active');

    // Update the prev/next buttons
    var previewNext = $('.image-active').next('div');
    var previewPrev = $('.image-active').prev('div');
    if(!previewNext.length) {
        $('.pane-next').addClass('button-disabled');
    } else {
        $('.pane-next').removeClass('button-disabled');
    }
    if(!previewPrev.length) {
        $('.pane-prev').addClass('button-disabled');
    } else {
        $('.pane-prev').removeClass('button-disabled');
    }

    // Item's current position in doc
    var curTopPos = $(target).offset().top;
    var curLeftPos = $(target).offset().left;

    // Show the preview pane
    $('.preview-pane').css({'top': (curTopPos+$(target).outerHeight()+10)+'px'})
    $('.preview-pane').show();

    if(options.updateGallery == true) {
        highQualityImage = false;
        // Load thumbnail image
        $('#pane-thumb').attr('src', $('.image-active img').attr('src')).show();
        // Load full-sized image
        $('#pane-image').attr('src', $(target).data('image-url'));
        // Description
        $('#pane-image-size').html($(target).data('image-size'));
        // Description URLs
        $('#pane-url-name').html($(target).data('image-name'));
        $('#pane-url-name').attr('href', $(target).data('image-host-url'));

        $('#pane-url-url').html($(target).data('image-display-url'));
        $('#pane-url-url').attr('href', $(target).data('image-host-url'));
        // Description Buttons
        $('#preview-button-source').attr('href', $(target).data('image-host-url'));
        $('#preview-button-image').attr('href', $(target).data('image-url'));
        $('#pane-url-image').attr('href', $(target).data('image-url'));
    }

    // Hide the container (needed to recalculate the available viewport space
    $('#pane-url-image').hide();

    var imgRatio = imageRatio($(target).data('w'), $(target).data('h'), $('.pane-image').width(), $('.pane-image').height());

    // Update the image & url with the new sizes
    $('#pane-image, #pane-thumb').attr('width', imgRatio.width).attr('height', imgRatio.height);
    $('#pane-url-image').css({'width': imgRatio.width+'px', 'height': imgRatio.height+'px', 'display': 'block'});

    // Set the preview pane arrow
    $('.preview-pane-arrow').css({'left': (curLeftPos+($(target).outerWidth(true)/2))-15+'px'});

    // Compare to the rest of the targets
    $('.image-frame').each(function() {
        if($(this).offset().top == curTopPos) {
            $(this).addClass('image-active-row');
        }
    });

    // Scroll the preview pane into view
    if(options.scroll == true) {
        $('html, body').stop(true).animate({
            scrollTop: $(target).offset().top-($('#header').height()+10)
        }, 200);
    }
}

/**
 * Update the Preview Pane Image
 *
 * @param   type    Use the thumbnail image
 */
function updatePaneImage(type) {
    highQualityImage = true;
    $('#pane-thumb').hide();
    $('#pane-image').show();

    if(type) {
        $('#pane-image').hide();
        $('#pane-thumb').show();
    }
}

/**
 * Close the Preview Pane
 */
function closePane() {
    $('.preview-pane').hide();
    $('.image-frame').removeClass('image-active-row');
    $('.image-frame').removeClass('image-active');
}

/**
 * Calculate the aspect ratio based on available space.
 *
 * @param   imgWidth    Image width
 * @param   imgHeight   Image height
 * @param   maxWidth    Maximum available width
 * @param   maxHeight   maximum available height
 * @return  object
 */
function imageRatio(imgWidth, imgHeight, maxWidth, maxHeight) {
    var ratio = Math.min(maxWidth/imgWidth, maxHeight/imgHeight);

    return { width: imgWidth*ratio, height: imgHeight*ratio };
}

/**
 * Get and format the user current time for Instant Answers
 *
 * @param   date_format The date format (with sprintf syntax)
 * @param   months      List of translated months
 * @param   type        The request type, 0 for time, 1 for date
 */
function iaUserDateTime(date_format, months, type) {
    if(typeof userTimeRunning !== "undefined") {
        clearTimeout(userTimeRunning);
    }

    var date = new Date();
    // var seconds = ('0' + date.getSeconds()).slice(-2);
    var minutes = ('0' + date.getMinutes()).slice(-2);
    var hours   = ('0' + date.getHours()).slice(-2);
    var days    = ('0' + date.getDate()).slice(-2);
    var month   = date.getMonth();
    var year    = date.getFullYear();
    date_format = date_format.replace('%1$s', year).replace('%2$s', months[month]).replace('%3$s', days);

    if(type) {
        $('.web-ia-user-date .web-ia-content').html(date_format);
    } else {
        $('.web-ia-user-time .web-ia-content').html(hours+':'+minutes);
        $('.web-ia-user-time .web-ia-footer').html(date_format);
    }

    userTimeRunning = setTimeout(iaUserDateTime, 1000, date_format, months);
}

/**
 * Stopwatch function for Instant Answers
 */
function iaStopwatch() {
    var time = 0;
    var offset;

    if(typeof stopwatchRunning !== "undefined") {
        clearInterval(stopwatchRunning);
    }

    function update() {
        if(this.running) {
            time += delta();
        }
        $('.web-ia-stopwatch .web-ia-content').html(timeFormatter(time));
    }

    function delta() {
        var now = Date.now();
        var timePassed = now-offset;

        offset = now;

        return timePassed;
    }

    function timeFormatter(time) {
        time = new Date(time);

        var milliseconds = ('0' + time.getMilliseconds()).slice(-3).substr(0, 2);
        var seconds = ('0' + time.getSeconds()).slice(-2);
        var minutes = ('0' + time.getMinutes()).slice(-2);

        return minutes+':'+seconds+'.'+milliseconds;
    }

    this.start = function() {
        $('#sw-start').hide();
        $('#sw-stop').show();
        stopwatchRunning = setInterval(update.bind(this), 10);
        offset = Date.now();
        this.running = true;
    };

    this.stop = function() {
        $('#sw-stop').hide();
        $('#sw-start').show();
        clearInterval(stopwatchRunning);
        this.running = false;
    };

    this.reset = function() {
        time = 0;
        update();
    };

    this.running = false;
}

/**
 * Get the user's screen resolution
 */
function iaUserScreenResolution() {
    $('.web-ia-user-screen-resolution-width').html(window.screen.width);
    $('.web-ia-user-screen-resolution-height').html(window.screen.height);
}

$.fn.extend({
    scrollRight: function (val) {
        if(val === undefined) {
            return this[0].scrollWidth - (this[0].scrollLeft + this[0].clientWidth);
        }
        return this.scrollLeft(this[0].scrollWidth - this[0].clientWidth - val);
    }
});