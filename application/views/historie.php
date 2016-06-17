<h1>Orderhistorie</h1>
<p><strong>In onderstaande tabel zie je een overzicht met de bestellingen in het verleden.</strong></p>
<div class="blok">
    Je saldo bedraagt &euro; <?php echo number_format($this->user->krediet, 2); ?>. <hr />
    <b>Bij voorkeur zelf betalen bij de snackbar, maar geld overmaken kan naar:</b><br /><blockquote>
    Naam: Hindrik Deelstra<br />
    Rekening: NL95RABO0356228517<br />
    O.v.v.: Snacktegoed user: <?php echo $this->user->id; ?><br />
</blockquote>
</div>
<?php foreach ($orders As $datum => $bestelling): ?>
<h2><?php echo $datum; ?> (<a href="/index.php/snack/reorder/<?php echo $datum; ?>">RE-ORDER</a>)</h2>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="top first">Snack</td>
        <td class="top prijs">Prijs p.st.</td>
        <td class="top aantal">Aantal</td>
    </tr>
    <?php foreach ($bestelling As $id => $snack): ?>
        <?php if (is_numeric($id)): ?>
        <tr>
            <td><?php echo $snack['aantal']; ?>x <?php echo $snack['naam'] .' '. strtolower($snack['bij']); ?></td>
            <td>&euro; <?php echo number_format($snack['prijs'],2); ?></td>
            <td>&euro; <?php echo number_format($snack['prijs'] * $snack['aantal'],2); ?></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
        <tr>
        <td class="top first">Totaalprijs</td>
        <td class="top aantal">&nbsp;</td>
        <td class="top prijs">&euro; <?php echo number_format($bestelling['totaal'], 2); ?></td>
    </tr>
</table>
<?php endforeach; ?>
<h1>Betalingen</h1>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="top first">Datum</td>
        <td class="top prijs">Bedrag</td>
    </tr>
<?php foreach ($betalingen As $key => $betaling): ?>
    <tr>
        <td class="first"><?php echo $betaling->datum; ?></td>
        <td class="prijs">&euro; <?php echo number_format($betaling->bedrag,2); ?></td>
    </tr>
<?php endforeach; ?>

