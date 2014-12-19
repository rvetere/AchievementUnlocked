<?
$app->metaData;
$list = "";
foreach ($app->metaData["hall_of_fame"] as $key => $data) {
    if ($key == "Sheldon Award") {
        foreach (is_array($data) ? $data : array() as $idx => $leDate) {
            foreach ($leDate as $name => $unixTstamp) {
                $date = new DateTime();
                $date->setTimestamp(strtotime($unixTstamp));
                $list .= "<tr><td>".date_format($date, "d.m.y")."</td><td>".$name."</td></tr>";
            }
        }
    }
}
?>

<p class="green-text">
    You earned in the same week the Penny and Raj Award
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