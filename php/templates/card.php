<div class="card">
    <div class="user">
        <?= $user ?>
    </div>
    <div class="avatar">
        <img src="https://jira.netstream.ch/secure/useravatar?ownerId=chdvere0&avatarId=12104">
    </div>

    <div class="shelv">
        <div class="level-0">
            <figure class="achievement forever-alone <?= $app->isAchievementActive("forever-alone", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Forever Alone"
                   data-content="<?= $app->getPopover("user_forever-alone.php") ?>"></a>
            </figure>

            <figure class="achievement roundhouse <?= $app->isAchievementActive("roundhouse", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Roundhouse Kick"
                   data-content="<?= $app->getPopover("user_roundhouse.php") ?>"></a>
            </figure>

            <figure class="achievement ovo <?= $app->isAchievementActive("ovo", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Ovo Master"
                   data-content="<?= $app->getPopover("user_ovo.php") ?>"></a>
            </figure>

            <figure class="achievement duracell <?= $app->isAchievementActive("duracell", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Duracell"
                   data-content="<?= $app->getPopover("user_duracell.php") ?>"></a>
            </figure>

            <figure class="achievement necromancer <?= $app->isAchievementActive("necromancer", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Necromancer"
                   data-content="<?= $app->getPopover("user_necromancer.php") ?>"></a>
            </figure>

            <figure class="achievement penny <?= $app->isAchievementActive("penny", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Penny"
                   data-content="<?= $app->getPopover("user_penny.php") ?>"></a>
            </figure>

            <figure class="achievement raj <?= $app->isAchievementActive("raj", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Raj"
                   data-content="<?= $app->getPopover("user_raj.php") ?>"></a>
            </figure>

            <figure class="achievement tumbleweed <?= $app->isAchievementActive("tumbleweed", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Tumbleweed"
                   data-content="<?= $app->getPopover("user_tumbleweed.php") ?>"></a>
            </figure>

            <figure class="achievement dog <?= $app->isAchievementActive("dog", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Dog"
                   data-content="<?= $app->getPopover("user_dog.php") ?>"></a>
            </figure>

            <figure class="achievement cat <?= $app->isAchievementActive("cat", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Cat"
                   data-content="<?= $app->getPopover("user_cat.php") ?>"></a>
            </figure>

            <figure class="achievement labday-winner <?= $app->isAchievementActive("labday-winner", $user) ?>">
                <a href="#" tabindex="0" class="invisible" role="button" data-toggle="popover" data-trigger="focus"
                   title="Labday Winner"
                   data-content="<?= $app->getPopover("user_labday-winner.php") ?>"></a>
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