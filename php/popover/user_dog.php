<?
$app->metaData;
$list = "";
$data = $app->metaData[$user];

foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
    if ($key == "dog") {
        $date = new DateTime();
        $date->setTimestamp(strtotime($leDate));
        $list .= "<tr><td>Received at</td><td>".date_format($date, "d.m.y")."</td></tr>";
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
