<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "tumbleweed") {
        $list = $app->parseDate($leDate, $list);
    }
}

if ($list == "") {
    $list = "not yet..";
}
?>

<p class="green-text">
    Create a ticket, who is ignored by all the people for one week
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
