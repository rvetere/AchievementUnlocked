<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "labday") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    You won the lab-day.. once.. or something
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
