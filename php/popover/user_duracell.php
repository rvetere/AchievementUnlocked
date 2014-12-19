<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "duracell") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    The biggest value, when divided the time between the first and the last daily commits with the lines of code per day
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
