<script type="text/javascript" src="/js/sortabletable.js"></script>
<link type="text/css" rel="StyleSheet" href="/css/sortabletable.css" />

<h1>Snackstats sinds <?php echo $firstorder; ?></h1>
<h2>Snackstats per gebruiker:</h2>
<table class="sort-table" id="table" cellspacing="0">
        <col />
        <col />
        <col />
        <col />
        <col />
        <col />
        <col />
        <thead>
                <tr>
            <td>UId</td>
                        <td>Naam</td>
                        <td>Ingelegd</td>
                        <td>Laatste inleg</td>
                        <td>Gekocht</td>
                        <td>Laatst besteld</td>
                        <td>Saldo</td>
                </tr>
    </thead>

<?php foreach ($users As $id => $user): ?>
<?php if(($user['saldo'] == 0) && (strtotime($user['laatstgekocht']) < strtotime('-1 months'))) continue; ?>
<tr>
<td><?php echo $id ?></td>
<td style="text-align:left"><?php echo $user['naam']; ?></td>
<td><?php echo $user['ingelegd']; ?></td>
<td><?php echo $user['laatsteinleg']; ?></td>
<td><?php echo $user['gekocht']; ?></td>
<td><?php echo $user['laatstgekocht']; ?></td>
<td><?php echo $user['saldo']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<table>
<tr>
<td><b>Checksum: &euro; <?php echo number_format($checksum, 2, ',', '.'); ?></b></td>
</tr>
</table>

</br></br>
<h2>Snackstats per snack:</h2>
<table class="sort-table" id="table2" cellspacing="0">
        <col />
        <col />
        <col />
        <col />
        <thead>
                <tr>
                        <td>Snack</td>
                        <td>Aantal</td>
                        <td>Bedrag</td>
                        <td>Laatste besteldatum</td>
                </tr>
        </thead>

<?php $aantal = 0; ?>
<?php $bedrag = 0; ?>
<?php foreach ($foodstats As $snack): ?>
<tr>
<td style="text-align:left"><?php echo $snack->snack; ?></td>
<td><?php echo $snack->aantal; ?></td>
<td><?php echo $snack->bedrag; ?></td>
<td><?php echo $snack->last?></td>
</tr>
<?php $aantal += $snack->aantal; ?>
<?php $bedrag += $snack->bedrag; ?>
<?php endforeach; ?>
</table>
<table>
<tr><td style="text-align:left"><b>Totaal:        <?php echo $aantal; ?> snacks gekocht, voor een bedrag van &euro; <?php echo number_format($bedrag, 2, ',', '.'); ?> !</b></td></tr>
</table>

<script type="text/javascript">
var st = new SortableTable(document.getElementById("table"), ["Number", "String", "Number", "Date", "Number", "Date", "Number"]);
</script>

<script type="text/javascript">
var st2 = new SortableTable(document.getElementById("table2"), ["String", "Number", "Number", "Date"]);
</script>


