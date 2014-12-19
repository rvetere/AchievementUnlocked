<?
$app->metaData;
$list = "";
foreach ($app->metaData["hall_of_fame"] as $key => $data) {
    if ($key == "Zombie Award") {
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
    You earned the Cat and Gary Award in one day!
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