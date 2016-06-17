<h1>Bestel je snacks</h1>
<p><strong>Hieronder zie je een overzicht met beschikbare snacks. Vul het aantal in en klik op "Verder" om door te gaan naar de
bevestiging. Mis je iets? <a href="mailto:h.deelstra@oxilion.nl">Mail</a> het mij.</strong></p>
<div class="blok">
    Je saldo bedraagt &euro; <?php echo number_format($this->user->krediet, 2); ?>.
</div>
<form method="POST" action="/index.php/snack/order/">
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="top first">Snack</td>
        <td class="top prijs">Prijs p.st.</td>
        <td class="top bij">Saus</td>
        <td class="top aantal">Aantal</td>
    </tr>
    <?php foreach ($snacks As $i => $item): ?>
        <tr>
            <td><?php echo $item->naam; ?></td>
            <td>&euro; <?php echo number_format($item->prijs,2); ?></td>
            <td>
                <?php
                    if ($item->heeftbijproducten == 1):
                ?>
                <select name="bij[<?php echo $item->id; ?>]">
                    <option value="0">Geen</option>
                <?php echo $strBijproducten; ?>
                </select>
                <?php
                    else:
                ?>
                <input type="hidden" name="bij[<?php echo $item->id; ?>]" value="0" />
                <?php endif; ?>
            </td>
            <td><input type="text" name="snack[<?php echo $item->id; ?>]" size="3" /></td>
        </tr>
    <?php endforeach; ?>
        <tr>
            <td colspan="4" align="right">
                <input type="submit" value="Verder" />
            </td>
        </tr>
</table>
</form>

