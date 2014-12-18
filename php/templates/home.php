<?

?>

<div id="st-container" class="st-container st-effect-14">
<!--
    example menus
    these menus will be on top of the push wrapper
-->
<nav class="st-menu st-effect-13" id="menu-1">
    <h2 class="icon icon-stack">Sidebar</h2>
    <ul>
        <li><a href="#1">Home</a></li>
    </ul>
</nav>
<nav class="st-menu st-effect-13" id="menu-2">
    <h2 class="icon icon-stack">Sidebar</h2>
    <ul>
        <li><a class="compare" href="#">Remo Vetere</a></li>
        <li><a class="compare" href="#">Sybille Hausherr</a></li>
    </ul>
</nav>

    <!-- content push wrapper -->
<div class="st-pusher">
    <!--
        example menus
        these menus will be under the push wrapper
    -->

    <div class="st-content"><!-- this is the wrapper for the content -->
        <div class="st-content-inner"><!-- extra div for emulating position:fixed of the menu -->
            <!-- Top Navigation -->
            <div class="home-nav">
                <ul class="section-navi">
                    <li><a id="showMenu" href="#menu" class="main-menu"><i class="icon_list4"></i>&nbsp;Menu</a></li>
                </ul>
            </div>

            <div class="main clearfix">
                <section>
                    <div class="hall-of-fame">
                        <figure class="achieve forever-alone">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Forever Alone"
                               data-content="<?= $app->getPopover("forever-alone.php") ?>"></a>
                        </figure>

                        <figure class="achieve ovo-master">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Ovo Master"
                               data-content="<?= $app->getPopover("ovo-master.php") ?>"></a>
                        </figure>

                        <figure class="achieve duracell-master">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Duracell Master"
                               data-content="<?= $app->getPopover("ovo-master.php") ?>"></a>
                        </figure>

                        <figure class="achieve sheldon-award">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Sheldon Award"
                               data-content="<?= $app->getPopover("sheldon-award.php") ?>"></a>
                        </figure>

                        <figure class="achieve catdog-award">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Catdog Award"
                               data-content="<?= $app->getPopover("catdog-award.php") ?>"></a>
                        </figure>

                        <figure class="achieve zombie-award">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Zombie Award"
                               data-content="<?= $app->getPopover("zombie-award.php") ?>"></a>
                        </figure>

                        <figure class="achieve chuck-norris-award">
                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                               title="Achievement: Chuck Norris Award"
                               data-content="<?= $app->getPopover("chuck-norris-award.php") ?>"></a>
                        </figure>

                        <div class="horizontal-scroll black-white">
                        </div>
                        <div class="horizontal-scroll">
                        </div>
                    </div>
                </section>

                <section class="userview">
                    <? $user = "Giorgio Armani"; include($app->getSelfDir()."/templates/card.php") ?>
                    <? $user = "Löli Küderchübel"; include($app->getSelfDir()."/templates/card.php") ?>
                </section>

                <section>

                </section>
            </div><!-- /main -->
        </div><!-- /st-content-inner -->
    </div><!-- /st-content -->
</div><!-- /st-pusher -->
</div>
