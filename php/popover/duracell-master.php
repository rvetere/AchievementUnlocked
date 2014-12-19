<?
$app->metaData;
$list = "";
foreach ($app->metaData as $data) {
    foreach (isset($data["awards"]) ? $data["awards"] : array() as $key => $leDate) {
        if ($key == "duracell") {
            $date = new DateTime();
            $date->setTimestamp(strtotime($leDate));
            $list .= "<tr><td>".date_format($date, "d.m.y")."</td><td>".$data["name"]."</td></tr>";
        }
    }
}
?>

<p class="green-text">
    Most created Tasks which are assigned to themselves.
</p>

<table class="table table-striped">
    <thead>
    <tr>
        <th>Date</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    <?= $list ?>
    </tbody>
</table>