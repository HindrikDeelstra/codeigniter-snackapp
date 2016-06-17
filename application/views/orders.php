<script type="text/javascript" src="/js/sortabletable.js"></script>
<link type="text/css" rel="StyleSheet" href="/css/sortabletable.css" />

<h1>Orders van deze week</h1>
<h2>Snackorders per gebruiker:</h2>
<table class="sort-table" id="table" cellspacing="0">
    <col />
    <col />
    <col />
    <col />
    <col />
    <col />
    <thead>
        <tr>
            <td>Datum + Tijd</td>
            <td>UId</td>
            <td>Usernaam</td>
            <td>Snack</td>
            <td>Aantal</td>
            <td>Prijs</td>
        </tr>
    </thead>
<?php $som = 0; ?>
<?php foreach ($orders as $order) : ?>
<tr><td><?php print($order->datumtijd); ?></td>
<td><?php print($order->user_id); ?></td>
<td style="text-align: left"><?php print($order->usernaam); ?></td>
<td style="text-align: left"><?php print($order->snack); ?></td>
<td><?php print($order->aantal); ?></td>
<td><?php print(number_format($order->prijs, 2)); ?></td></tr>
<?php $som += ($order->aantal * $order->prijs); ?>
<?php endforeach; ?>
</table>
<table>
<tr><td><b>Totaal besteld: &euro; <?php print(number_format($som, 2, ',', '.')); ?></b></td></td>
</table>

<script type="text/javascript">
var st = new SortableTable(document.getElementById("table"), ["String", "Number", "String", "String", "Number", "Number"]);
</script>

