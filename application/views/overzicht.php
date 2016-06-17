<h1>Bevestig je bestelling</h1>
<p><strong>Je hebt in het vorige scherm het volgende besteld. Controleer even of het klopt, en maak je keuze.</strong></p>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="top first">Snack</td>
        <td class="top prijs">Prijs p.st.</td>
        <td class="top">Subtotaal</td>
    </tr>
    <?php foreach ($order->snacks As $i => $item): ?>
        <tr>
            <td><?php echo $item['aantal']; ?>x <?php echo $item['naam']; ?></td>
            <td>&euro; <?php echo number_format($item['prijs'],2); ?></td>
            <td>&euro; <?php echo number_format($item['prijs'] * $item['aantal'],2); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td class="top first">Totaalprijs</td>
        <td class="top aantal">&nbsp;</td>
        <td class="top prijs">&euro; <?php echo number_format($order->totaal, 2); ?></td>
    </tr>
</table>
<div class="blok">
    Je saldo <u>nu</u> bedraagt &euro; <?php echo number_format($user->krediet, 2); ?>.
    Als je nu op bevestigen klikt, komt je nieuwe saldo uit op &euro; <?php echo number_format($user->krediet - $order->totaal, 2); ?>
</div>
<a class="oeps" href="/">Oeps, terug</a> <a class="goed" href="/index.php/snack/definitief/">Bevestigen</a>
