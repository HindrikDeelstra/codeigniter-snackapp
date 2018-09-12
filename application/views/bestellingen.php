<h1>Bestellingen sinds <?php echo $vanaf; ?></h1>
<form method="POST" action="">
    Alle bestellingen na: <input type="text" name="vanaf" value="<?php echo $vanaf; ?>" /> <input type="submit" value="ZIEN!" />
</form>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="200px"><b>Snack</b></td>
        <td width="200px"><b>Aantal</b></td>
    </tr>
    <?php foreach ($bestellingen As $id => $s): ?>
    <tr>
        <td> <?php echo $s['naam'] . ' '. $s['bij'] ?></td>
        <td> <?php echo $s['aantal']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</br></br>
<h3>Cafetaria Trio is vanaf 12:00 uur te bereiken op: +31.534322453</h3>
Navigeer naar:
Wooldriksweg 29, 7512 AN Enschede
<!--<h3>Restaria Boswinkel is vanaf 11:30 uur te bereiken op: +31.534304574</h3>

Navigeer naar:
Wethouder Elhorststraat 112
7543 TG Enschede!-->
