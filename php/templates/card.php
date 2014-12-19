<?
$leData = $app->metaData[$user];
?>

<div class="card" data-user="<?= $user ?>">
    <div class="user">
        <?= $leData["name"] ?>
    </div>
    <div class="avatar">
        <img src="<?= $leData["avatar"] ?>">
    </div>

    <div class="shelv">
        <div class="level-0">
            <figure class="achievement forever-alone <?= $app->isAchievementActive("forever-alone", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Forever Alone" data-placement="right"
                   data-content="<?= $app->getPopoverAchiv("user_forever-alone.php", $user) ?>"></a>
            </figure>

            <figure class="achievement roundhouse <?= $app->isAchievementActive("roundhouse", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Roundhouse Kick" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_roundhouse.php", $user) ?>"></a>
            </figure>

            <figure class="achievement ovo <?= $app->isAchievementActive("ovo", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Ovo Master" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_ovo.php", $user) ?>"></a>
            </figure>

            <figure class="achievement duracell <?= $app->isAchievementActive("duracell", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Duracell" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_duracell.php", $user) ?>"></a>
            </figure>

            <figure class="achievement necromancer <?= $app->isAchievementActive("necromancer", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Necromancer" data-placement="right"
                   data-content="<?= $app->getPopoverAchiv("user_necromancer.php", $user) ?>"></a>
            </figure>

            <figure class="achievement penny <?= $app->isAchievementActive("penny", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Penny" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_penny.php", $user) ?>"></a>
            </figure>

            <figure class="achievement raj <?= $app->isAchievementActive("raj", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Raj" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_raj.php", $user) ?>"></a>
            </figure>

            <figure class="achievement tumbleweed <?= $app->isAchievementActive("tumbleweed", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Tumbleweed" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_tumbleweed.php", $user) ?>"></a>
            </figure>

            <figure class="achievement dog <?= $app->isAchievementActive("dog", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Dog" data-placement="right"
                   data-content="<?= $app->getPopoverAchiv("user_dog.php", $user) ?>"></a>
            </figure>

            <figure class="achievement cat <?= $app->isAchievementActive("cat", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Cat" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_cat.php", $user) ?>"></a>
            </figure>

            <figure class="achievement labday-winner <?= $app->isAchievementActive("labday", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Labday Winner" data-placement="left"
                   data-content="<?= $app->getPopoverAchiv("user_labday-winner.php", $user) ?>"></a>
            </figure>
        </div>
        <div class="level-1">

        </div>
        <div class="level-2">

        </div>
        <div class="level-3">

        </div>
    </div>
</div>