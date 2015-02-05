/*---------------------------------
		CSS Helpers
	-----------------------------------*/
	if($.browser.msie){ $('body').addClass('msie'); }
	$('input[type=checkbox]').addClass('checkbox');
	$('input[type=radio]').addClass('radio');
	$('input[type=file]').addClass('file');
	$('[disabled=disabled]').addClass('disabled');
	$('table').find('tr:even').addClass('alt');
	$('table').find('tr:first-child').addClass('first');
	$('table').find('tr:last-child').addClass('last');
	$('ul').find('li:first-child').addClass('first');
	$('ul').find('li:last-child').addClass('last');
	$('hr').before('<div class="clear">&nbsp;</div>');
	$('[class*=col_]').not('input, label,dl,table').addClass('column').each(
	    function (i,o){
	      var div = document.createElement('div'); div.className="inner";
	      while (o.firstChild) div.appendChild(o.firstChild);
	      o.appendChild(div);
	    }
	  );
	$('ol li').each(
	    function (i,o){
	      var span = document.createElement('span'); span.className="ol-li";
	      while (o.firstChild) span.appendChild(o.firstChild);
	      o.appendChild(span);
	    }
	  );
	
	if(!($.browser.msie && $.browser.version <= 8)){
		$('.gallery a img').after('<span class="overlay"></span>');
	}
	$('.gallery a').hover(function(){
		$(this).children('span.overlay').stop().fadeIn(300);
	},function(){
		$(this).children('span.overlay').stop().fadeOut(300);
	});
	
	
	/**
Core script to handle the entire layout and base functions
**/
var App = function () {

    // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;

    var sidebarWidth = 225;
    var sidebarCollapsedWidth = 35;

    var responsiveHandlers = [];

    // theme layout color set
    var layoutColorCodes = {
        'blue': '#77a3e4',
        'red': '#e02222',
        'green': '#35aa47',
        'purple': '#852b99',
        'grey': '#555555',
        'light-grey': '#fafafa',
        'yellow': '#ffb848'
    };

    var handleInit = function() {

        if ($('body').css('direction') === 'rtl') {
            isRTL = true;
        }

        isIE8 = !! navigator.userAgent.match(/MSIE 8.0/);
        isIE9 = !! navigator.userAgent.match(/MSIE 9.0/);
        isIE10 = !! navigator.userAgent.match(/MSIE 10/);
        
        if (isIE10) {
            jQuery('html').addClass('ie10'); // detect IE10 version
        }
    }

    var handleDesktopTabletContents = function () {
        // loops all page elements with "responsive" class and applies classes for tablet mode
        // For metornic  1280px or less set as tablet mode to display the content properly
        if ($(window).width() <= 1280 || $('body').hasClass('page-boxed')) {
            $(".responsive").each(function () {
                var forTablet = $(this).attr('data-tablet');
                var forDesktop = $(this).attr('data-desktop');
                if (forTablet) {
                    $(this).removeClass(forDesktop);
                    $(this).addClass(forTablet);
                }
            });
        }

        // loops all page elements with "responsive" class and applied classes for desktop mode
        // For metornic  higher 1280px set as desktop mode to display the content properly
        if ($(window).width() > 1280 && $('body').hasClass('page-boxed') === false) {
            $(".responsive").each(function () {
                var forTablet = $(this).attr('data-tablet');
                var forDesktop = $(this).attr('data-desktop');
                if (forTablet) {
                    $(this).removeClass(forTablet);
                    $(this).addClass(forDesktop);
                }
            });
        }
    }

    var handleSidebarState = function () {
        // remove sidebar toggler if window width smaller than 900(for table and phone mode)
        if ($(window).width() < 980) {
            $('body').removeClass("page-sidebar-closed");
        }
    }

    var runResponsiveHandlers = function () {
        // reinitialize other subscribed elements
        for (var i in responsiveHandlers) {
            var each = responsiveHandlers[i];
            each.call();
        }
    }

    var handleResponsive = function () {
        handleTooltips();
        handleSidebarState();
        handleDesktopTabletContents();
        handleSidebarAndContentHeight();
        handleChoosenSelect();
        handleFixedSidebar();
        runResponsiveHandlers();
    }

    var handleResponsiveOnInit = function () {
        handleSidebarState();
        handleDesktopTabletContents();
        handleSidebarAndContentHeight();
    }

    var handleResponsiveOnResize = function () {
        var resize;
        if (isIE8) {
            var currheight;
            $(window).resize(function() {
                if(currheight == document.documentElement.clientHeight) {
                    return; //quite event since only body resized not window.
                }                
                if (resize) {
                    clearTimeout(resize);
                }   
                resize = setTimeout(function() {
                    handleResponsive();    
                }, 50); // wait 50ms until window resize finishes.                
                currheight = document.documentElement.clientHeight; // store last body client height
            });
        } else {
            $(window).resize(function() {
                if (resize) {
                    clearTimeout(resize);
                }   
                resize = setTimeout(function() {
                    console.log('resize');
                    handleResponsive();    
                }, 50); // wait 50ms until window resize finishes.
            });
        }   
    }

    //* BEGIN:CORE HANDLERS *//
    // this function handles responsive layout on screen size resize or mobile device rotate.
  
    var handleSidebarAndContentHeight = function () {
        var content = $('.page-content');
        var sidebar = $('.page-sidebar');
        var body = $('body');
        var height;

        if (body.hasClass("page-footer-fixed") === true && body.hasClass("page-sidebar-fixed") === false) {
            var available_height = $(window).height() - $('.footer').height();
            if (content.height() <  available_height) {
                content.attr('style', 'min-height:' + available_height + 'px !important');
            }
        } else {
            if (body.hasClass('page-sidebar-fixed')) {
                height = _calculateFixedSidebarViewportHeight();
            } else {
                height = sidebar.height() + 20;
            }
            if (height >= content.height()) {
                content.attr('style', 'min-height:' + height + 'px !important');
            } 
        }          
    }

    var handleSidebarMenu = function () {
        jQuery('.page-sidebar').on('click', 'li > a', function (e) {
                if ($(this).next().hasClass('sub-menu') == false) {
                    if ($('.btn-navbar').hasClass('collapsed') == false) {
                        $('.btn-navbar').click();
                    }
                    return;
                }

                var parent = $(this).parent().parent();

                parent.children('li.open').children('a').children('.arrow').removeClass('open');
                parent.children('li.open').children('.sub-menu').slideUp(200);
                parent.children('li.open').removeClass('open');

                var sub = jQuery(this).next();
                if (sub.is(":visible")) {
                    jQuery('.arrow', jQuery(this)).removeClass("open");
                    jQuery(this).parent().removeClass("open");
                    sub.slideUp(200, function () {
                            handleSidebarAndContentHeight();
                        });
                } else {
                    jQuery('.arrow', jQuery(this)).addClass("open");
                    jQuery(this).parent().addClass("open");
                    sub.slideDown(200, function () {
                            handleSidebarAndContentHeight();
                        });
                }

                e.preventDefault();
            });

        // handle ajax links
        jQuery('.page-sidebar').on('click', ' li > a.ajaxify', function (e) {
                e.preventDefault();
                App.scrollTop();

                var url = $(this).attr("href");
                var menuContainer = jQuery('.page-sidebar ul');
                var pageContent = $('.page-content');
                var pageContentBody = $('.page-content .page-content-body');

                menuContainer.children('li.active').removeClass('active');
                menuContainer.children('arrow.open').removeClass('open');

                $(this).parents('li').each(function () {
                        $(this).addClass('active');
                        $(this).children('a > span.arrow').addClass('open');
                    });
                $(this).parents('li').addClass('active');

                App.blockUI(pageContent, false);

                $.post(url, {}, function (res) {
                        App.unblockUI(pageContent);
                        pageContentBody.html(res);
                        App.fixContentHeight(); // fix content height
                        App.initUniform(); // initialize uniform elements
                    });
            });
    }

    var _calculateFixedSidebarViewportHeight = function () {
        var sidebarHeight = $(window).height() - $('.header').height() + 1;
        if ($('body').hasClass("page-footer-fixed")) {
            sidebarHeight = sidebarHeight - $('.footer').height();
        }

        return sidebarHeight; 
    }

    var handleFixedSidebar = function () {
        var menu = $('.page-sidebar-menu');

        if (menu.parent('.slimScrollDiv').size() === 1) { // destroy existing instance before updating the height
            menu.slimScroll({
                destroy: true
            });
            menu.removeAttr('style');
            $('.page-sidebar').removeAttr('style');            
        }

        if ($('.page-sidebar-fixed').size() === 0) {
            handleSidebarAndContentHeight();
            return;
        }

        if ($(window).width() >= 980) {
            var sidebarHeight = _calculateFixedSidebarViewportHeight();

            menu.slimScroll({
                size: '7px',
                color: '#a1b2bd',
                opacity: .3,
                position: isRTL ? 'left' : ($('.page-sidebar-on-right').size() === 1 ? 'left' : 'right'),
                height: sidebarHeight,
                allowPageScroll: false,
                disableFadeOut: false
            });
            handleSidebarAndContentHeight();
        }
    }

    var handleFixedSidebarHoverable = function () {
        if ($('body').hasClass('page-sidebar-fixed') === false) {
            return;
        }

        $('.page-sidebar').off('mouseenter').on('mouseenter', function () {
            var body = $('body');

            if ((body.hasClass('page-sidebar-closed') === false || body.hasClass('page-sidebar-fixed') === false) || $(this).hasClass('page-sidebar-hovering')) {
                return;
            }

            body.removeClass('page-sidebar-closed').addClass('page-sidebar-hover-on');
            $(this).addClass('page-sidebar-hovering');                
            $(this).animate({
                width: sidebarWidth
            }, 400, '', function () {
                $(this).removeClass('page-sidebar-hovering');
            });
        });

        $('.page-sidebar').off('mouseleave').on('mouseleave', function () {
            var body = $('body');

            if ((body.hasClass('page-sidebar-hover-on') === false || body.hasClass('page-sidebar-fixed') === false) || $(this).hasClass('page-sidebar-hovering')) {
                return;
            }

            $(this).addClass('page-sidebar-hovering');
            $(this).animate({
                width: sidebarCollapsedWidth
            }, 400, '', function () {
                $('body').addClass('page-sidebar-closed').removeClass('page-sidebar-hover-on');
                $(this).removeClass('page-sidebar-hovering');
            });
        });
    }

    var handleSidebarToggler = function () {
        // handle sidebar show/hide
        $('.page-sidebar').on('click', '.sidebar-toggler', function (e) {            
            var body = $('body');
            var sidebar = $('.page-sidebar');

            if ((body.hasClass("page-sidebar-hover-on") && body.hasClass('page-sidebar-fixed')) || sidebar.hasClass('page-sidebar-hovering')) {
                body.removeClass('page-sidebar-hover-on');
                sidebar.css('width', '').hide().show();
                e.stopPropagation();
                runResponsiveHandlers();
                return;
            }

            $(".sidebar-search", sidebar).removeClass("open");

            if (body.hasClass("page-sidebar-closed")) {
                body.removeClass("page-sidebar-closed");
                if (body.hasClass('page-sidebar-fixed')) {
                    sidebar.css('width', '');
                }
            } else {
                body.addClass("page-sidebar-closed");
            }
            runResponsiveHandlers();
        });

        // handle the search bar close
        $('.page-sidebar').on('click', '.sidebar-search .remove', function (e) {
            e.preventDefault();
            $('.sidebar-search').removeClass("open");
        });

        // handle the search query submit on enter press
        $('.page-sidebar').on('keypress', '.sidebar-search input', function (e) {
            if (e.which == 13) {
                window.location.href = "extra_search.html";
                return false; // ---- Add this line
            }
        });

        // handle the search submit
        $('.sidebar-search .submit').on('click', function (e) {
            e.preventDefault();
          
                if ($('body').hasClass("page-sidebar-closed")) {
                    if ($('.sidebar-search').hasClass('open') == false) {
                        if ($('.page-sidebar-fixed').size() === 1) {
                            $('.page-sidebar .sidebar-toggler').click(); //trigger sidebar toggle button
                        }
                        $('.sidebar-search').addClass("open");
                    } else {
                        window.location.href = "extra_search.html";
                    }
                } else {
                    window.location.href = "extra_search.html";
                }
        });
    }

    var handleHorizontalMenu = function () {
        //handle hor menu search form toggler click
        $('.header').on('click', '.hor-menu .hor-menu-search-form-toggler', function (e) {
                if ($(this).hasClass('hide')) {
                    $(this).removeClass('hide');
                    $('.header .hor-menu .search-form').hide();
                } else {
                    $(this).addClass('hide');
                    $('.header .hor-menu .search-form').show();
                }
                e.preventDefault();
            });

        //handle hor menu search button click
        $('.header').on('click', '.hor-menu .search-form .btn', function (e) {
                window.location.href = "extra_search.html";
                e.preventDefault();
            });

        //handle hor menu search form on enter press
        $('.header').on('keypress', '.hor-menu .search-form input', function (e) {
                if (e.which == 13) {
                    window.location.href = "extra_search.html";
                    return false;
                }
            });
    }

    var handleGoTop = function () {
        /* set variables locally for increased performance */
        jQuery('.footer').on('click', '.go-top', function (e) {
                App.scrollTo();
                e.preventDefault();
            });
    }

    var handlePortletTools = function () {
        jQuery('body').on('click', '.portlet .tools a.remove', function (e) {
            e.preventDefault();
                var removable = jQuery(this).parents(".portlet");
                if (removable.next().hasClass('portlet') || removable.prev().hasClass('portlet')) {
                    jQuery(this).parents(".portlet").remove();
                } else {
                    jQuery(this).parents(".portlet").parent().remove();
                }
        });

        jQuery('body').on('click', '.portlet .tools a.reload', function (e) {
            e.preventDefault();
                var el = jQuery(this).parents(".portlet");
                App.blockUI(el);
                window.setTimeout(function () {
                        App.unblockUI(el);
                    }, 1000);
        });

        jQuery('body').on('click', '.portlet .tools .collapse, .portlet .tools .expand', function (e) {
            e.preventDefault();
                var el = jQuery(this).closest(".portlet").children(".portlet-body");
                if (jQuery(this).hasClass("collapse")) {
                    jQuery(this).removeClass("collapse").addClass("expand");
                    el.slideUp(200);
                } else {
                    jQuery(this).removeClass("expand").addClass("collapse");
                    el.slideDown(200);
                }
        });
    }

    var handleUniform = function () {
        if (!jQuery().uniform) {
            return;
        }
        var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle, .star)");
        if (test.size() > 0) {
            test.each(function () {
                    if ($(this).parents(".checker").size() == 0) {
                        $(this).show();
                        $(this).uniform();
                    }
                });
        }
    }

    var handleAccordions = function () {
        $(".accordion").collapse().height('auto');

        var lastClicked;

        //add scrollable class name if you need scrollable panes
        jQuery('body').on('click', '.accordion.scrollable .accordion-toggle', function () {
            lastClicked = jQuery(this);
        }); //move to faq section

        jQuery('body').on('shown', '.accordion.scrollable', function () {
            jQuery('html,body').animate({
                scrollTop: lastClicked.offset().top - 150
            }, 'slow');
        });
    }

    var handleTabs = function () {

        // function to fix left/right tab contents
        var fixTabHeight = function(tab) {
            $(tab).each(function() {
                var content = $($($(this).attr("href")));
                var tab = $(this).parent().parent();
                if (tab.height() > content.height()) {
                    content.css('min-height', tab.height());    
                } 
            });            
        }

        // fix tab content on tab shown
        $('body').on('shown', '.nav.nav-tabs.tabs-left a[data-toggle="tab"], .nav.nav-tabs.tabs-right a[data-toggle="tab"]', function(){
            fixTabHeight($(this)); 
        });

        $('body').on('shown', '.nav.nav-tabs', function(){
            handleSidebarAndContentHeight();
        });

        //fix tab contents for left/right tabs
        fixTabHeight('.nav.nav-tabs.tabs-left > li.active > a[data-toggle="tab"], .nav.nav-tabs.tabs-right > li.active > a[data-toggle="tab"]');

        //activate tab if tab id provided in the URL
        if(location.hash) {
            var tabid = location.hash.substr(1);
            $('a[href="#'+tabid+'"]').click();
        }
    }

    var handleScrollers = function () {
        $('.scroller').each(function () {
                $(this).slimScroll({
                        size: '7px',
                        color: '#a1b2bd',
                        position: isRTL ? 'left' : 'right',
                        height: $(this).attr("data-height"),
                        alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                        railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                        disableFadeOut: true
                    });
            });
    }

    var handleTooltips = function () {
        if (App.isTouchDevice()) { // if touch device, some tooltips can be skipped in order to not conflict with click events
            jQuery('.tooltips:not(.no-tooltip-on-touch-device)').tooltip();
        } else {
            jQuery('.tooltips').tooltip();
        }
    }

    var handleDropdowns = function () {
        $('body').on('click', '.dropdown-menu.hold-on-click', function(e){
            e.stopPropagation();
        })
    }

    var handlePopovers = function () {
        jQuery('.popovers').popover();
    }

    var handleChoosenSelect = function () {
        if (!jQuery().chosen) {
            return;
        }

        $(".chosen").each(function () {
            $(this).chosen({
                allow_single_deselect: $(this).attr("data-with-diselect") === "1" ? true : false
            });
        });
    }

    var handleFancybox = function () {
        if (!jQuery.fancybox) {
            return;
        }

        if (jQuery(".fancybox-button").size() > 0) {
            jQuery(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    }

    var handleTheme = function () {

        var panel = $('.color-panel');

        if ($('body').hasClass('page-boxed') == false) {
            $('.layout-option', panel).val("fluid");
        }
        
        $('.sidebar-option', panel).val("default");
        $('.header-option', panel).val("fixed");
        $('.footer-option', panel).val("default"); 

        //handle theme layout
        var resetLayout = function () {
            $("body").
                removeClass("page-boxed").
                removeClass("page-footer-fixed").
                removeClass("page-sidebar-fixed").
                removeClass("page-header-fixed");

            $('.header > .navbar-inner > .container').removeClass("container").addClass("container-fluid");

            if ($('.page-container').parent(".container").size() === 1) {
                $('.page-container').insertAfter('.header');
            } 

            if ($('.footer > .container').size() === 1) {                        
                $('.footer').html($('.footer > .container').html());                        
            } else if ($('.footer').parent(".container").size() === 1) {                        
                $('.footer').insertAfter('.page-container');
            }

            $('body > .container').remove(); 
        }

        var lastSelectedLayout = '';

        var setLayout = function () {

            var layoutOption = $('.layout-option', panel).val();
            var sidebarOption = $('.sidebar-option', panel).val();
            var headerOption = $('.header-option', panel).val();
            var footerOption = $('.footer-option', panel).val(); 

            if (sidebarOption == "fixed" && headerOption == "default") {
                alert('Default Header with Fixed Sidebar option is not supported. Proceed with Default Header with Default Sidebar.');
                $('.sidebar-option', panel).val("default");
                sidebarOption = 'default';
            }

            resetLayout(); // reset layout to default state

            if (layoutOption === "boxed") {
                $("body").addClass("page-boxed");

                // set header
                $('.header > .navbar-inner > .container-fluid').removeClass("container-fluid").addClass("container");
                var cont = $('.header').after('<div class="container"></div>');

                // set content
                $('.page-container').appendTo('body > .container');

                // set footer
                if (footerOption === 'fixed' || sidebarOption === 'default') {
                    $('.footer').html('<div class="container">'+$('.footer').html()+'</div>');
                } else {
                    $('.footer').appendTo('body > .container');
                }            
            }

            if (lastSelectedLayout != layoutOption) {
                //layout changed, run responsive handler:
                runResponsiveHandlers();
            }
            lastSelectedLayout = layoutOption;

            //header
            if (headerOption === 'fixed') {
                $("body").addClass("page-header-fixed");
                $(".header").removeClass("navbar-static-top").addClass("navbar-fixed-top");
            } else {
                $("body").removeClass("page-header-fixed");
                $(".header").removeClass("navbar-fixed-top").addClass("navbar-static-top");
            }

            //sidebar
            if (sidebarOption === 'fixed') {
                $("body").addClass("page-sidebar-fixed");
            } else {
                $("body").removeClass("page-sidebar-fixed");
            }
            handleFixedSidebar(); // reinitialize fixed sidebar
            handleFixedSidebarHoverable(); // reinitialize fixed sidebar hover effect

            //footer 
            if (footerOption === 'fixed') {
                $("body").addClass("page-footer-fixed");
            } else {
                $("body").removeClass("page-footer-fixed");
            }

            handleSidebarAndContentHeight(); // fix content height
        }

        // handle theme colors
        var setColor = function (color) {
            $('#style_color').attr("href", "assets/css/themes/" + color + ".css");
            $.cookie('style_color', color);                
        }

        $('.icon-color', panel).click(function () {
            $('.color-mode').show();
            $('.icon-color-close').show();
        });

        $('.icon-color-close', panel).click(function () {
            $('.color-mode').hide();
            $('.icon-color-close').hide();
        });

        $('li', panel).click(function () {
            var color = $(this).attr("data-style");
            setColor(color);
            $('.inline li', panel).removeClass("current");
            $(this).addClass("current");
        });

        $('.layout-option, .header-option, .sidebar-option, .footer-option', panel).change(setLayout);
    }

    var handleFixInputPlaceholderForIE = function () {
        //fix html5 placeholder attribute for ie7 & ie8
        if (isIE8 || isIE9) { // ie7&ie8
            // this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
            jQuery('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function () {

                var input = jQuery(this);

                if(input.val()=='' && input.attr("placeholder") != '') {
                    input.addClass("placeholder").val(input.attr('placeholder'));
                }

                input.focus(function () {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                input.blur(function () {                         
                    if (input.val() == '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    }

    //* END:CORE HANDLERS *//

    return {

        //main function to initiate template pages
        init: function () {

            //IMPORTANT!!!: Do not modify the core handlers call order.

            //core handlers
            handleInit();
            handleResponsiveOnResize(); // set and handle responsive    
            handleUniform();        
            handleScrollers(); // handles slim scrolling contents 
            handleResponsiveOnInit(); // handler responsive elements on page load

            //layout handlers
            handleFixedSidebar(); // handles fixed sidebar menu
            handleFixedSidebarHoverable(); // handles fixed sidebar on hover effect 
            handleSidebarMenu(); // handles main menu
            handleHorizontalMenu(); // handles horizontal menu
            handleSidebarToggler(); // handles sidebar hide/show            
            handleFixInputPlaceholderForIE(); // fixes/enables html5 placeholder attribute for IE9, IE8
            handleGoTop(); //handles scroll to top functionality in the footer
            handleTheme(); // handles style customer tool

            //ui component handlers
            handlePortletTools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
            handleDropdowns(); // handle dropdowns
            handleTabs(); // handle tabs
            handleTooltips(); // handle bootstrap tooltips
            handlePopovers(); // handles bootstrap popovers
            handleAccordions(); //handles accordions
            handleChoosenSelect(); // handles bootstrap chosen dropdowns     

            App.addResponsiveHandler(handleChoosenSelect); // reinitiate chosen dropdown on main content resize. disable this line if you don't really use chosen dropdowns.
        },

        fixContentHeight: function () {
            handleSidebarAndContentHeight();
        },

        addResponsiveHandler: function (func) {
            responsiveHandlers.push(func);
        },

        // useful function to make equal height for contacts stand side by side
        setEqualHeight: function (els) {
            var tallestEl = 0;
            els = jQuery(els);
            els.each(function () {
                    var currentHeight = $(this).height();
                    if (currentHeight > tallestEl) {
                        tallestColumn = currentHeight;
                    }
                });
            els.height(tallestEl);
        },

        // wrapper function to scroll to an element
        scrollTo: function (el, offeset) {
            pos = el ? el.offset().top : 0;
            jQuery('html,body').animate({
                    scrollTop: pos + (offeset ? offeset : 0)
                }, 'slow');
        },

        scrollTop: function () {
            App.scrollTo();
        },

        // wrapper function to  block element(indicate loading)
        blockUI: function (el, centerY) {
            var el = jQuery(el); 
            el.block({
                    message: '<img src="./assets/img/ajax-loading.gif" align="">',
                    centerY: centerY != undefined ? centerY : true,
                    css: {
                        top: '10%',
                        border: 'none',
                        padding: '2px',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.05,
                        cursor: 'wait'
                    }
                });
        },

        // wrapper function to  un-block element(finish loading)
        unblockUI: function (el) {
            jQuery(el).unblock({
                    onUnblock: function () {
                        jQuery(el).removeAttr("style");
                    }
                });
        },

        // initializes uniform elements
        initUniform: function (els) {

            if (els) {
                jQuery(els).each(function () {
                        if ($(this).parents(".checker").size() == 0) {
                            $(this).show();
                            $(this).uniform();
                        }
                    });
            } else {
                handleUniform();
            }

        },

        // initializes choosen dropdowns
        initChosenSelect: function (els) {
            $(els).chosen({
                    allow_single_deselect: true
                });
        },

        initFancybox: function () {
            handleFancybox();
        },

        getActualVal: function (el) {
            var el = jQuery(el);
            if (el.val() === el.attr("placeholder")) {
                return "";
            }

            return el.val();
        },

        getURLParameter: function (paramName) {
            var searchString = window.location.search.substring(1),
                i, val, params = searchString.split("&");

            for (i = 0; i < params.length; i++) {
                val = params[i].split("=");
                if (val[0] == paramName) {
                    return unescape(val[1]);
                }
            }
            return null;
        },

        // check for device touch support
        isTouchDevice: function () {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        },

        isIE8: function () {
            return isIE8;
        },

        isRTL: function () {
            return isRTL;
        },

        getLayoutColorCode: function (name) {
            if (layoutColorCodes[name]) {
                return layoutColorCodes[name];
            } else {
                return '';
            }
        }

    };

}();






//floating sidebar
jQuery(window).load(function(){
var IE6browser = (navigator.userAgent.indexOf("MSIE 6")>=0) ? true : false;
if(!IE6browser){
	var $window = jQuery(window);
	var $sidebar = jQuery(".page-sidebar");
	var $footer = jQuery(".footer");
	var $content = jQuery("#page-content");
	var $addHeigth = 0;

	if($sidebar.length>0){
		var sidebarTop = $sidebar.position().top;
		var sidebarHeight = $sidebar.height()+90;
		var contentHeight = $content.height(); 
		var footerTop = $footer.position().top;

		
		if(contentHeight>sidebarHeight){
			$window.scroll(function(event) {
				$sidebar.addClass('fixed');
				scrollTop = $window.scrollTop(),
				topPosition = Math.max(12, (sidebarTop) - scrollTop),
				topPosition = Math.min(topPosition, (footerTop - scrollTop) - sidebarHeight);
				$sidebar.css('top', topPosition+$addHeigth);
			});
		}
	}
}
}); 



/*
  pageTop
*/

	
var scrolltotop={

	//startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
	setting: {startline:300},

	state: {isvisible:false, shouldvisible:false},

	togglecontrol:function(){

		var scrolltop=jQuery(window).scrollTop()

		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
		if (this.state.shouldvisible && !this.state.isvisible){
			jQuery(".go-top").fadeIn();
			this.state.isvisible=true;

		}
		else if (this.state.shouldvisible==false && this.state.isvisible){
			jQuery(".go-top").fadeOut();
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			jQuery(".go-top").hide();
			var mainobj=scrolltotop;

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			})
		})
	}
}
scrolltotop.init();

//yuga.js

(function($) {

	$(function() {
		$.yuga.selflink();
		$.yuga.rollover();
		$.yuga.externalLink();
	});
	//---------------------------------------------------------------------

	$.yuga = {
		Uri: function(path){
			var self = this;
			this.originalPath = path;
			this.absolutePath = (function(){
				var e = document.createElement('span');
				e.innerHTML = '<a href="' + path + '" />';
				return e.firstChild.href;
			})();
			var fields = {'schema' : 2, 'username' : 5, 'password' : 6, 'host' : 7, 'path' : 9, 'query' : 10, 'fragment' : 11};
			var r = /^((\w+):)?(\/\/)?((\w+):?(\w+)?@)?([^\/\?:]+):?(\d+)?(\/?[^\?#]+)?\??([^#]+)?#?(\w*)/.exec(this.absolutePath);
			for (var field in fields) {
				this[field] = r[fields[field]];
			}
			this.querys = {};
			if(this.query){
				$.each(self.query.split('&'), function(){
					var a = this.split('=');
					if (a.length == 2) self.querys[a[0]] = a[1];
				});
			}
		},
		selflink: function (options) {
			var c = $.extend({
				selfLinkAreaSelector:'body',
				selfLinkClass:'current',
				parentsLinkClass:'parentsLink',
				postfix: '_cr',
				changeImgSelf:true,
				changeImgParents:true
			}, options);
			$(c.selfLinkAreaSelector+((c.selfLinkAreaSelector)?' ':'')+'a[href]').each(function(){
				var href = new $.yuga.Uri(this.getAttribute('href'));
				var setImgFlg = false;
				if ((href.absolutePath == location.href) && !href.fragment) {
					//同じ文書にリンク
					$(this).addClass(c.selfLinkClass);
					setImgFlg = c.changeImgSelf;
				} else if (0 <= location.href.search(href.absolutePath)) {
					//親ディレクトリリンク
					$(this).addClass(c.parentsLinkClass);
					setImgFlg = c.changeImgParents;
				}
				if (setImgFlg){
					//img要素が含まれていたら現在用画像（_cr）に設定
				//	$(this).find('img').each(function(){
					//	this.originalSrc = $(this).attr('src');
					//	this.currentSrc = this.originalSrc.replace(new RegExp('('+c.postfix+')?(\.gif|\.jpg|\.png)$'), c.postfix+"$2");
					//	$(this).attr('src',this.currentSrc);
				//	});
				}
			});
		},
		rollover: function(options) {
			var c = $.extend({
				hoverSelector: 'img.btn, .btn img',
				groupSelector: '.btngroup',
				postfix: '_on'
			}, options);
			var rolloverImgs = $(c.hoverSelector).filter(isNotCurrent);
			rolloverImgs.each(function(){
				this.originalSrc = $(this).attr('src');
				this.rolloverSrc = this.originalSrc.replace(new RegExp('('+c.postfix+')?(\.gif|\.jpg|\.png)$'), c.postfix+"$2");
				this.rolloverImg = new Image;
				this.rolloverImg.src = this.rolloverSrc;
			});
			var groupingImgs = $(c.groupSelector).find('img').filter(isRolloverImg);

			rolloverImgs.not(groupingImgs).hover(function(){
				$(this).attr('src',this.rolloverSrc);
			},function(){
				$(this).attr('src',this.originalSrc);
			});
			$(c.groupSelector).hover(function(){
				$(this).find('img').filter(isRolloverImg).each(function(){
					$(this).attr('src',this.rolloverSrc);
				});
			},function(){
				$(this).find('img').filter(isRolloverImg).each(function(){
					$(this).attr('src',this.originalSrc);
				});
			});
			function isNotCurrent(i){
				return Boolean(!this.currentSrc);
			}
			function isRolloverImg(i){
				return Boolean(this.rolloverSrc);
			}

		},
		
		externalLink: function(options) {
			var c = $.extend({
				windowOpen:true,
				externalClass: 'externalLink',
				notWindowURL: '',
				pdfIconSrc: './assets/img/ico_pdf.gif',
				xlsIconSrc: './assets/img/ico_xls.gif',
				docIconSrc: './assets/img/ico_doc.gif',
				zipIconSrc: './assets/img/ico_zip.gif',
				pptIconSrc: './assets/img/ico_ppt.gif',
				addIconSrc: './assets/img/ico_blank.gif'
			}, options);
			var uri = new $.yuga.Uri(location.href);
			var e = $('a[href^="http://"], a[href^="https://"], a.exLink, a[href$=".pdf"]').not('a[href^="http://www.youtube.com/"],a[href^="https://www.youtube.com/"],a[href^="' + uri.schema + '://' + uri.host + '/' + '"]').not(c.notWindowURL);
			if (c.windowOpen) {
				e.click(function(){
					window.open(this.href, '_blank');
					return false;
				});
			}
			if (c.addIconSrc) e.not(':has(img), :has(button), .twitter-share-button, a[href$=".pdf"], .iframe a,a.noicon').append($('<img src="'+c.addIconSrc+'" class="externalIcon" />'));
			e.addClass(c.externalClass);
			$('a[href$=".pdf"]').not(':has(img)').prepend($('<img src="'+c.pdfIconSrc+'" class="pdfIcon" alt="PDF" />'));
			$('a[href$=".xls"]').not(':has(img)').prepend($('<img src="'+c.xlsIconSrc+'" class="xlsIcon" alt="EXEL" />'));
			$('a[href$=".xlsx"]').not(':has(img)').prepend($('<img src="'+c.xlsIconSrc+'" class="xlsIcon" alt="EXEL" />'));
			$('a[href$=".doc"]').not(':has(img)').prepend($('<img src="'+c.docIconSrc+'" class="docIcon" alt="WORD" />'));
			$('a[href$=".docx"]').not(':has(img)').prepend($('<img src="'+c.docIconSrc+'" class="docIcon" alt="WORD" />'));
			$('a[href$=".zip"]').not(':has(img)').prepend($('<img src="'+c.zipIconSrc+'" class="zipIcon" alt="ZIP" />'));
			$('a[href$=".ppt"]').not(':has(img)').prepend($('<img src="'+c.pptIconSrc+'" class="pptIcon" alt="PPT" />'));
			$('a[href$=".pptx"]').not(':has(img)').prepend($('<img src="'+c.pptIconSrc+'" class="pptIcon" alt="PPT" />'));
		}
	};
})(jQuery);




	  
	  
	  /*
 * Treeview 1.4 - jQuery plugin to hide and show branches of a tree
 * 
 * http://bassistance.de/jquery-plugins/jquery-plugin-treeview/
 * http://docs.jquery.com/Plugins/Treeview
 *
 * Copyright (c) 2007 J??ｽｶrn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.treeview.js 4684 2008-02-07 19:08:06Z joern.zaefferer $
 *
 */
(function($){$.extend($.fn,{swapClass:function(c1,c2){var c1Elements=this.filter('.'+c1);this.filter('.'+c2).removeClass(c2).addClass(c1);c1Elements.removeClass(c1).addClass(c2);return this;},replaceClass:function(c1,c2){return this.filter('.'+c1).removeClass(c1).addClass(c2).end();},hoverClass:function(className){className=className||"hover";return this.hover(function(){$(this).addClass(className);},function(){$(this).removeClass(className);});},heightToggle:function(animated,callback){animated?this.animate({height:"toggle"},animated,callback):this.each(function(){jQuery(this)[jQuery(this).is(":hidden")?"show":"hide"]();if(callback)callback.apply(this,arguments);});},heightHide:function(animated,callback){if(animated){this.animate({height:"hide"},animated,callback);}else{this.hide();if(callback)this.each(callback);}},prepareBranches:function(settings){if(!settings.prerendered){this.filter(":last-child:not(ul)").addClass(CLASSES.last);this.filter((settings.collapsed?"":"."+CLASSES.closed)+":not(."+CLASSES.open+")").find(">ul").hide();}return this.filter(":has(>ul)");},applyClasses:function(settings,toggler){this.filter(":has(>ul):not(:has(>a))").find(">span").click(function(event){toggler.apply($(this).next());}).add($("a",this)).hoverClass();if(!settings.prerendered){this.filter(":has(>ul:hidden)").addClass(CLASSES.expandable).replaceClass(CLASSES.last,CLASSES.lastExpandable);this.not(":has(>ul:hidden)").addClass(CLASSES.collapsable).replaceClass(CLASSES.last,CLASSES.lastCollapsable);this.prepend("<div class=\""+CLASSES.hitarea+"\"/>").find("div."+CLASSES.hitarea).each(function(){var classes="";$.each($(this).parent().attr("class").split(" "),function(){classes+=this+"-hitarea ";});$(this).addClass(classes);});}this.find("div."+CLASSES.hitarea).click(toggler);},treeview:function(settings){settings=$.extend({cookieId:"treeview"},settings);if(settings.add){return this.trigger("add",[settings.add]);}if(settings.toggle){var callback=settings.toggle;settings.toggle=function(){return callback.apply($(this).parent()[0],arguments);};}function treeController(tree,control){function handler(filter){return function(){toggler.apply($("div."+CLASSES.hitarea,tree).filter(function(){return filter?$(this).parent("."+filter).length:true;}));return false;};}$("a:eq(0)",control).click(handler(CLASSES.collapsable));$("a:eq(1)",control).click(handler(CLASSES.expandable));$("a:eq(2)",control).click(handler());}function toggler(){$(this).parent().find(">.hitarea").swapClass(CLASSES.collapsableHitarea,CLASSES.expandableHitarea).swapClass(CLASSES.lastCollapsableHitarea,CLASSES.lastExpandableHitarea).end().swapClass(CLASSES.collapsable,CLASSES.expandable).swapClass(CLASSES.lastCollapsable,CLASSES.lastExpandable).find(">ul").heightToggle(settings.animated,settings.toggle);if(settings.unique){$(this).parent().siblings().find(">.hitarea").replaceClass(CLASSES.collapsableHitarea,CLASSES.expandableHitarea).replaceClass(CLASSES.lastCollapsableHitarea,CLASSES.lastExpandableHitarea).end().replaceClass(CLASSES.collapsable,CLASSES.expandable).replaceClass(CLASSES.lastCollapsable,CLASSES.lastExpandable).find(">ul").heightHide(settings.animated,settings.toggle);}}function serialize(){function binary(arg){return arg?1:0;}var data=[];branches.each(function(i,e){data[i]=$(e).is(":has(>ul:visible)")?1:0;});$.cookie(settings.cookieId,data.join(""));}function deserialize(){var stored=$.cookie(settings.cookieId);if(stored){var data=stored.split("");branches.each(function(i,e){$(e).find(">ul")[parseInt(data[i])?"show":"hide"]();});}}this.addClass("treeview");var branches=this.find("li").prepareBranches(settings);switch(settings.persist){case "cookie":var toggleCallback=settings.toggle;settings.toggle=function(){serialize();if(toggleCallback){toggleCallback.apply(this,arguments);}};deserialize();break;case "location":var current=this.find("a").filter(function(){return this.href.toLowerCase()==location.href.toLowerCase();});if(current.length){current.addClass("selected").parents("ul, li").add(current.next()).show();}break;}branches.applyClasses(settings,toggler);if(settings.control){treeController(this,settings.control);$(settings.control).show();}return this.bind("add",function(event,branches){$(branches).prev().removeClass(CLASSES.last).removeClass(CLASSES.lastCollapsable).removeClass(CLASSES.lastExpandable).find(">.hitarea").removeClass(CLASSES.lastCollapsableHitarea).removeClass(CLASSES.lastExpandableHitarea);$(branches).find("li").andSelf().prepareBranches(settings).applyClasses(settings,toggler);});}});var CLASSES=$.fn.treeview.classes={open:"open",closed:"closed",expandable:"expandable",expandableHitarea:"expandable-hitarea",lastExpandableHitarea:"lastExpandable-hitarea",collapsable:"collapsable",collapsableHitarea:"collapsable-hitarea",lastCollapsableHitarea:"lastCollapsable-hitarea",lastCollapsable:"lastCollapsable",lastExpandable:"lastExpandable",last:"last",hitarea:"hitarea"};$.fn.Treeview=$.fn.treeview;})(jQuery);
	
$(function() {  
      $(".page-sidebar-menu").treeview({  
        collapsed: true,  
        animated: "medium",  
        control: "#sidetreecontrol",  
        persist: "location"
      });  
    }) 
jQuery(function(){
    if(jQuery('.current-menu-item')) {
        jQuery('li.current-menu-item').addClass('active');
    }
	    if(jQuery('.current_page_parent')) {
        jQuery('li.current_page_parent').addClass('active');
    }
});
	$(function(){
     $('.current-menu-item a')
         .prepend('<span class="selected"></span>');
	 $('.collapsable a')
         .not('.collapsable li:not(:has.sub-menu)').prepend('<span class="arrow open"></span>');
	 $('.expandable a')
         .not('.expandable li:not(:has.sub-menu)').prepend('<span class="arrow open"></span>');
});


$(function(){
     $('.breadcrumb .home a')
         .prepend('<i class="icon-home"></i>');
	$('.breadcrumb li')
         .not('.current_item').append(' <i class="icon-angle-right"></i>');
}); 	


	/*---------------------------------
		Readmore
	-----------------------------------*/
	$(".readmore").hover(function(){
	$(this).css("cursor","pointer"); 
	},function(){
	$(this).css("cursor","default");
	});
	$(".moreread").css("display","none");
	$(".readmore").click(function(){
	$(this).next().slideToggle("slow");
	//$(this).next().css("display","block");
	});
	

/*--------------------------------------------------------------------------*
 *  
 *  heightLine JavaScript Library beta4
 *  
 *  MIT-style license. 
 *  
 *  2007 Kazuma Nishihata 
 *  http://www.webcreativepark.net
 *  
 *--------------------------------------------------------------------------*/
new function(){
	
	function heightLine(){
	
		this.className="heightLine";
		this.parentClassName="heightLineParent"
		reg = new RegExp(this.className+"-([a-zA-Z0-9-_]+)", "i");
		objCN =new Array();
		var objAll = document.getElementsByTagName ? document.getElementsByTagName("*") : document.all;
		for(var i = 0; i < objAll.length; i++) {
			var eltClass = objAll[i].className.split(/\s+/);
			for(var j = 0; j < eltClass.length; j++) {
				if(eltClass[j] == this.className) {
					if(!objCN["main CN"]) objCN["main CN"] = new Array();
					objCN["main CN"].push(objAll[i]);
					break;
				}else if(eltClass[j] == this.parentClassName){
					if(!objCN["parent CN"]) objCN["parent CN"] = new Array();
					objCN["parent CN"].push(objAll[i]);
					break;
				}else if(eltClass[j].match(reg)){
					var OCN = eltClass[j].match(reg)
					if(!objCN[OCN]) objCN[OCN]=new Array();
					objCN[OCN].push(objAll[i]);
					break;
				}
			}
		}
		
		//check font size
		var e = document.createElement("div");
		var s = document.createTextNode("S");
		e.appendChild(s);
		e.style.visibility="hidden"
		e.style.position="absolute"
		e.style.top="0"
		document.body.appendChild(e);
		var defHeight = e.offsetHeight;
		
		changeBoxSize = function(){
			for(var key in objCN){
				if (objCN.hasOwnProperty(key)) {
					//parent type
					if(key == "parent CN"){
						for(var i=0 ; i<objCN[key].length ; i++){
							var max_height=0;
							var CCN = objCN[key][i].childNodes;
							for(var j=0 ; j<CCN.length ; j++){
								if(CCN[j] && CCN[j].nodeType == 1){
									CCN[j].style.height="auto";
									max_height = max_height>CCN[j].offsetHeight?max_height:CCN[j].offsetHeight;
								}
							}
							for(var j=0 ; j<CCN.length ; j++){
								if(CCN[j].style){
									var stylea = CCN[j].currentStyle || document.defaultView.getComputedStyle(CCN[j], '');
									var newheight = max_height;
									if(stylea.paddingTop)newheight -= stylea.paddingTop.replace("px","");
									if(stylea.paddingBottom)newheight -= stylea.paddingBottom.replace("px","");
									if(stylea.borderTopWidth && stylea.borderTopWidth != "medium")newheight-= stylea.borderTopWidth.replace("px","");
									if(stylea.borderBottomWidth && stylea.borderBottomWidth != "medium")newheight-= stylea.borderBottomWidth.replace("px","");
									CCN[j].style.height =newheight+"px";
								}
							}
						}
					}else{
						var max_height=0;
						for(var i=0 ; i<objCN[key].length ; i++){
							objCN[key][i].style.height="auto";
							max_height = max_height>objCN[key][i].offsetHeight?max_height:objCN[key][i].offsetHeight;
						}
						for(var i=0 ; i<objCN[key].length ; i++){
							if(objCN[key][i].style){
								var stylea = objCN[key][i].currentStyle || document.defaultView.getComputedStyle(objCN[key][i], '');
									var newheight = max_height;
									if(stylea.paddingTop)newheight-= stylea.paddingTop.replace("px","");
									if(stylea.paddingBottom)newheight-= stylea.paddingBottom.replace("px","");
									if(stylea.borderTopWidth && stylea.borderTopWidth != "medium")newheight-= stylea.borderTopWidth.replace("px","")
									if(stylea.borderBottomWidth && stylea.borderBottomWidth != "medium")newheight-= stylea.borderBottomWidth.replace("px","");
									objCN[key][i].style.height =newheight+"px";
							}
						}
					}
				}
			}
		}
		
		checkBoxSize = function(){
			if(defHeight != e.offsetHeight){
				changeBoxSize();
				defHeight= e.offsetHeight;
			}
		}
		changeBoxSize();
		setInterval(checkBoxSize,1000)
		window.onresize=changeBoxSize;
	}
	
	function addEvent(elm,listener,fn){
		try{
			elm.addEventListener(listener,fn,false);
		}catch(e){
			elm.attachEvent("on"+listener,fn);
		}
	}
	addEvent(window,"load",heightLine);
}



/* Sticky Navigation Menu */
$(window).load(function(){
  $(".header").sticky({ topSpacing: 0 });
});


// UTF-8
/**
 * scrollsmoothly.js
 * Copyright (c) 2008 KAZUMiX
 * http://d.hatena.ne.jp/KAZUMiX/20080418/scrollsmoothly
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * 更新履歴
 * 2009/02/12
 * スクロール先が画面左上にならない場合の挙動を修正
 * 2008/04/18
 * 公開
 *
*/

(function(){
   var easing = 0.25;
   var interval = 20;
   var d = document;
   var targetX = 0;
   var targetY = 0;
   var targetHash = '';
   var scrolling = false;
   var splitHref = location.href.split('#');
   var currentHref_WOHash = splitHref[0];
   var incomingHash = splitHref[1];
   var prevX = null;
   var prevY = null;

   // ドキュメント読み込み完了時にinit()を実行する
   addEvent(window, 'load', init);

   // ドキュメント読み込み完了時の処理
   function init(){
     // ページ内リンクにイベントを設定する
     setOnClickHandler();
     // 外部からページ内リンク付きで呼び出された場合
     if(incomingHash){
       if(window.attachEvent && !window.opera){
         // IEの場合はちょっと待ってからスクロール
         setTimeout(function(){scrollTo(0,0);setScroll('#'+incomingHash);},50);
       }else{
         // IE以外はそのままGO
         scrollTo(0, 0);
         setScroll('#'+incomingHash);
       }
     }
   }

   // イベントを追加する関数
   function addEvent(eventTarget, eventName, func){
     if(eventTarget.addEventListener){
       // モダンブラウザ
       eventTarget.addEventListener(eventName, func, false);
     }else if(window.attachEvent){
       // IE
       eventTarget.attachEvent('on'+eventName, function(){func.apply(eventTarget);});
     }
   }
   
   function setOnClickHandler(){
     var links = d.links;
     for(var i=0; i<links.length; i++){
       // ページ内リンクならスクロールさせる
       var link = links[i];
	  if(link.className != "scroll"){
    continue;
}
       var splitLinkHref = link.href.split('#');
       if(currentHref_WOHash == splitLinkHref[0] && d.getElementById(splitLinkHref[1])){
         addEvent(link, 'click', startScroll);
       }
     }
   }

   function startScroll(event){
     // リンクのデフォルト動作を殺す
     if(event){ // モダンブラウザ
       event.preventDefault();
       //alert('modern');
     }else if(window.event){ // IE
       window.event.returnValue = false;
       //alert('ie');
     }
     // thisは呼び出し元になってる
     setScroll(this.hash);
   }

   function setScroll(hash){
     // ハッシュからターゲット要素の座標をゲットする
     var targetEle = d.getElementById(hash.substr(1));
     if(!targetEle)return;
     //alert(scrollSize.height);
     // スクロール先座標をセットする
     var ele = targetEle;
     var x = 0;
     var y = 0;
     while(ele){
       x += ele.offsetLeft;
       y += ele.offsetTop;
       ele = ele.offsetParent;
     }
     var maxScroll = getScrollMaxXY();
     targetX = Math.min(x, maxScroll.x);
     targetY = Math.min(y, maxScroll.y);
     targetHash = hash;
     // スクロール停止中ならスクロール開始
     if(!scrolling){
       scrolling = true;
       scroll();
     }
   }

   function scroll(){
     var currentX = d.documentElement.scrollLeft||d.body.scrollLeft;
     var currentY = d.documentElement.scrollTop||d.body.scrollTop;
     var vx = (targetX - currentX) * easing;
     var vy = (targetY - currentY) * easing;
     var nextX = currentX + vx;
     var nextY = currentY + vy;
     if((Math.abs(vx) < 1 && Math.abs(vy) < 1)
       || (prevX === currentX && prevY === currentY)){
       // 目標座標付近に到達していたら終了
       scrollTo(targetX, targetY);
       scrolling = false;
       //location.hash = targetHash;
       prevX = prevY = null;
       return;
     }else{
       // 繰り返し
       scrollTo(parseInt(nextX), parseInt(nextY));
       prevX = currentX;
       prevY = currentY;
       setTimeout(function(){scroll()},interval);
     }
   }
   
   function getDocumentSize(){
     return {width:Math.max(document.body.scrollWidth, document.documentElement.scrollWidth), height:Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)};
   }

   function getWindowSize(){
     var result = {};
     if(window.innerWidth){
       var box = d.createElement('div');
       with(box.style){
         position = 'absolute';
         top = '0px';
         left = '0px';
         width = '100%';
         height = '100%';
         margin = '0px';
         padding = '0px';
         border = 'none';
         visibility = 'hidden';
       }
       d.body.appendChild(box);
       var width = box.offsetWidth;
       var height = box.offsetHeight;
       d.body.removeChild(box);
       result = {width:width, height:height};
     }else{
       result = {width:d.documentElement.clientWidth || d.body.clientWidth, height:d.documentElement.clientHeight || d.body.clientHeight};
     }
     return result;
   }
   
   function getScrollMaxXY() {
     if(window.scrollMaxX && window.scrollMaxY){
       return {x:window.scrollMaxX, y:window.scrollMaxY};
     }
     var documentSize = getDocumentSize();
     var windowSize = getWindowSize();
     return {x:documentSize.width - windowSize.width, y:documentSize.height - windowSize.height};
   }
   
 }());
 
 /**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);
