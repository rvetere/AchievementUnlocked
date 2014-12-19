<?

?>

<div id="st-container" class="st-container st-effect-14">
<!--
    example menus
    these menus will be on top of the push wrapper
-->
<nav class="st-menu st-effect-13" id="menu-1">
    <h2 class="icon icon-stack">Menu</h2>
    <ul>
        <li><a href="#1">Home</a></li>
    </ul>
</nav>
<nav class="st-menu st-effect-13" id="menu-2">
    <h2 class="icon icon-stack">Compare!</h2>
    <ul>
        <? foreach ($app->metaData as $kurzel => $data) { ?>
        <li><a class="compare" href="#" data-kurzel="<?= $kurzel ?>"><?= $data["name"] ?></a></li>
        <? } ?>
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

            <div class="main clearfix">
                <section class="home">
                    <ul id="horiz_container_outer">
                        <li id="horiz_container_inner">
                            <ul id="horiz_container">
                                <li>
                                    <div class="hall-of-fame">
                                        <figure class="achieve forever-alone <?= $app->isHallOfFameActive("Forever Alone") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Forever Alone"
                                               data-content="<?= $app->getPopover("forever-alone.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve ovo-master <?= $app->isHallOfFameActive("OVO Master") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Ovo Master"
                                               data-content="<?= $app->getPopover("ovo-master.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve duracell-master <?= $app->isHallOfFameActive("Duracell Master") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Duracell Master"
                                               data-content="<?= $app->getPopover("duracell-master.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve sheldon-award <?= $app->isHallOfFameActive("Sheldon Award") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Sheldon Award"
                                               data-content="<?= $app->getPopover("sheldon-award.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve catdog-award <?= $app->isHallOfFameActive("Cat&Dog Award") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Catdog Award" data-placement="left"
                                               data-content="<?= $app->getPopover("catdog-award.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve zombie-award <?= $app->isHallOfFameActive("Zombie Award") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Zombie Award" data-placement="left"
                                               data-content="<?= $app->getPopover("zombie-award.php") ?>"></a>
                                        </figure>

                                        <figure class="achieve chuck-norris-award <?= $app->isHallOfFameActive("Chuck Norris Award") ?>">
                                            <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                                               title="Chuck Norris Award"
                                               data-content="<?= $app->getPopover("chuck-norris-award.php") ?>"></a>
                                        </figure>

                                        <div class="horizontal-scroll black-white">
                                        </div>
                                        <div class="horizontal-scroll">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <div id="scrollbar">
                        <a id="left_scroll" class="mouseover_left" href="#"></a>
                        <div id="track">
                            <div id="dragBar"></div>
                        </div>
                        <a id="right_scroll" class="mouseover_right" href="#"></a>
                    </div>

                    <a href="#down" class="scroll-down"><i class="icon_chevron-circle-down"></i></a>
                </section>

                <section class="over">
                    <div class="overview"></div>
                    <a href="#down" class="scroll-down-down"><i class="icon_chevron-circle-down"></i></a>

                    <div class="home-nav">
                        <ul class="section-navi">
                            <li><a id="showMenu" href="#menu" class="main-menu"><i class="icon_list4"></i>&nbsp;compare</a></li>
                        </ul>
                    </div>
                </section>

                <section class="userview">
                    <a href="#random" class="randomize"><i class="icon_chevron-circle-right"></i></a>

                    <? $user = "chdvajo0"; include($app->getSelfDir()."/templates/card.php") ?>
                    <? $user = "chdbrdo0"; include($app->getSelfDir()."/templates/card.php") ?>
                    <br style="clear: both;">
                </section>
            </div><!-- /main -->
        </div><!-- /st-content-inner -->
    </div><!-- /st-content -->
</div><!-- /st-pusher -->
</div>
