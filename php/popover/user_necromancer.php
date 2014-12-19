<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "necromancer") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    Most created Tasks which are assigned to themselves.
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
