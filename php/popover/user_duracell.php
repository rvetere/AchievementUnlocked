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
    Highest amount of lines of Code compared to the amount of commits
</p>

<table class="table table-striped">
    <tbody>
    <?= $list ?>
    </tbody>
</table>
