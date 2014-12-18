/* Author: Remo Vetere

 */

/*
 ================================
 Creating the App Namespace
 ================================
 */

var app = app || new Object();
window.app = app;

// Let's get the party started!
$(function() {
//    $('ul.nav a').bind('click',function(event){
//        var $anchor = $(this);
//        /*
//         if you want to use one of the easing effects:
//         $('html, body').stop().animate({
//         scrollLeft: $($anchor.attr('href')).offset().left
//         }, 1500,'easeInOutExpo');
//         */
//        $('html, body').stop().animate({
//            scrollLeft: $($anchor.attr('href')).offset().left
//        }, 1000);
//        event.preventDefault();
//    });

//    $(".main").onepage_scroll({
//        sectionContainer: "section",     // sectionContainer accepts any kind of selector in case you don't want to use section
//        easing: "ease",                  // Easing options accepts the CSS3 easing animation such "ease", "linear", "ease-in",
//        // "ease-out", "ease-in-out", or even cubic bezier value such as "cubic-bezier(0.175, 0.885, 0.420, 1.310)"
//        animationTime: 1000,             // AnimationTime let you define how long each section takes to animate
//        pagination: true,                // You can either show or hide the pagination. Toggle true for show, false for hide.
//        updateURL: false,                // Toggle this true if you want the URL to be updated automatically when the user scroll to each page.
//        beforeMove: function(index) {},  // This option accepts a callback function. The function will be called before the page moves.
//        afterMove: function(index) {},   // This option accepts a callback function. The function will be called after the page moves.
//        loop: false,                     // You can have the page loop back to the top/bottom when the user navigates at up/down on the first/last page.
//        keyboard: true,                  // You can activate the keyboard controls
//        responsiveFallback: false,        // You can fallback to normal page scroll by defining the width of the browser in which
//        // you want the responsive fallback to be triggered. For example, set this to 600 and whenever
//        // the browser's width is less than 600, the fallback will kick in.
//        direction: "vertical"            // You can now define the direction of the One Page Scroll animation. Options available are "vertical" and "horizontal". The default value is "vertical".
//    });

    $('.achieve .invisible').popover({
        'html': true
    });

    $('.st-menu .compare').bind('click', function(e) {
        e.preventDefault;

        $(this).addClass('active');
        if ($('.st-menu .active').length == 2) {
            // we got two, compare them :)
            SidebarMenuEffects.bodyClickFn();


            var data = {
                'users': []
            };
            $('.card').each(function(i, el) {
                var userEl = $('.st-menu .active:eq(' + i + ')');
                var user = userEl.html();
                data.users.push(user);
            });

            $.ajax({
                type: "POST",
                url: '/load/cards',
                data: data,
                error: function() {
                    console.log('WTF?!');
                },
                success: function(data) {
                    $('section').addClass('drop-out');
                    setTimeout(function() {
                        $('section.active').html(data['html']);
                        setTimeout(function() {
                            $('section').removeClass('drop-out');
                        }, 25);
                    }, 500);
                },
                dataType: 'json'
            });
        }
    });

    $('.achieve')
        .bind('mouseover', function(e) {
            $(this).parents('section').addClass('show-them');
        })
        .bind('mouseout', function(e) {
            $(this).parents('section').removeClass('show-them');
        });

//    $('*').scroll(function(e) {
//        $('section.userview.section, .home-nav').css('left', $(this).scrollLeft());
//    });

    $('#horiz_container_outer').horizontalScroll();

    $('.scroll-down').bind('click', function(e) {
        $('.st-content').animate({ scrollTop: $('section.userview').height()}, 'slow');
    })
});