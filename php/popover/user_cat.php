<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "cat") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    The most commits after 18:00 pm
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
