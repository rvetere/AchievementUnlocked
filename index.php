<?
include_once("php/app.php");
$app = new App();
$renderT = microtime_float();

if (strlen($_GET["ajax"]) > 0 || strlen($_POST["ajax"]) > 0) {
    $app->renderAjax($app);
    exit;
}

// new slim way
if ($app->isRESTRequest) {
    $app->renderSlim();
    exit;
}
if ($app->isScriptRequest) {
    $app->renderScript();
    exit;
}
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7 <?= $app->getHtmlClass() ?>" lang="en" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8 <?= $app->getHtmlClass() ?>" lang="en" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9 <?= $app->getHtmlClass() ?>" lang="en" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <!-- Use the .htaccess and remove these lines to avoid edge case issues.
         More info: h5bp.com/i/378 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?= $app->getPageTitle() ?></title>

    <!-- Mobile viewport optimized: h5bp.com/viewport -->
    <meta name="viewport" content="width=device-width">
    <!-- Prevent mobile from zooming -> http://davidwalsh.name/zoom-mobile-browsers  -->
    <!--        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>-->

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

    <!-- Don't do that with too much fonts, include as much as we can in the fonts.less -> in IE we can have only 31 includes and we need at least 5 for our features in the editor! -->
    <link href='/get/google/fonts/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='/get/google/fonts/css?family=PT+Serif:400,700' rel='stylesheet' type='text/css'>
    <link href='/get/google/fonts/css?family=Indie+Flower' rel='stylesheet' type='text/css'>

    <? if ($app->hasLess()) { ?>

        <!-- LESS converted to css and minified via build script -->
        <link rel="stylesheet/less" href="/less/style.less">

        <script type="text/javascript">
            less = {
                env: "development", // or "production"
                async: false,       // load imports async
                fileAsync: false,   // load imports async when in a page under a file protocol
                poll: 1000,         // when in watch mode, time in ms between polls
                functions: {},      // user functions, keyed by name
                dumpLineNumbers: "comments", // or "mediaQuery" or "all"
                relativeUrls: false // whether to adjust url's to be relative
                // if false, url's are already relative to the
                // entry less file
                //resource
            };
        </script>
        <script src="/js/mylibs/less-1.3.3.min.js"></script>
        <!-- end LESS -->

    <? } else { ?>

        <link rel="stylesheet" type="text/css" href="/css/jquery.horizontal.scroll.css" />
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css">
        <link rel="stylesheet" type="text/css" href="/css/icomoon.css">
        <link rel="stylesheet" type="text/css" href="/css/components.css">
        <link rel="stylesheet" type="text/css" href="/css/sidebarEffects.css">
        <link rel="stylesheet" type="text/css" href="/css/style.css">

    <? } ?>


    <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

    <!-- All JavaScript at the bottom, except this Modernizr build.
         Modernizr enables HTML5 elements & feature detects for optimal performance.
         Create your own custom Modernizr build: www.modernizr.com/download/ -->
    <script src="/js/libs/modernizr-2.8.3.m.js"></script>
</head>
<body class="<?= $app->getBodyClass() ?>">

    <noscript>
        <div class="notice error" style="display: block;"><div class="content">No Script? You DIE!</div><a href="#" class="ignore close">x</a></div>
    </noscript>

    <? $app->render($app) ?>

    <!-- JavaScript at the bottom for fast page loading -->

    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <!--        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>-->
    <!--        <script>window.jQuery || document.write('<script src="/js/libs/jquery-1.9.1.js"><\/script>')</script>-->
    <script src="/js/libs/jquery-1.9.1.js"></script>

    <!-- FB SDK -->
    <div id="fb-root"></div>

    <!-- Our "main" script-tag, the only one with inline-javascript (YSlow) -->
    <script type="text/javascript">
        // define some special variables from the server side
        var serverSide = <?= $app->getServerSide() ?>;
    </script>

    <!-- scripts concatenated and minified via build script -->
    <script src="/js/libs/jquery.easing.1.3.js"></script>
    <script src="/js/libs/jquery.transit.js"></script>
    <script src="/js/libs/jquery-ui-1.10.3.custom.js"></script>
    <script src="/js/mylibs/sidebarEffects.js"></script>
    <script src="/js/mylibs/jquery.horizontal.scroll.js"></script>
    <script src="/js/mylibs/jquery.espy.js"></script>

    <script src="/js/bootstrap.min.js"></script>

    <script src="/js/app/common.js"></script>


    <script src="/js/app.js"></script>
    <!-- end scripts -->
</body>
</html>
