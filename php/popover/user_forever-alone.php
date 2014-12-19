<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "forever-alone") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    You create a task, who is assigned to yourself and closed by yourself
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
