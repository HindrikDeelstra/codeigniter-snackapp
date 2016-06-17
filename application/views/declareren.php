<h1>Snacks declareren</h1>

<h3>Checksum is:&nbsp;&euro;&nbsp;<?php echo number_format($checksum, 2, ',', '.'); ?></h3>
<form method="POST" action="/index.php/beheer/gedeclareerd">
Selecteer de gebruiker die de snacks heeft gekocht:
<select name="uid">
<?php foreach ($userstats as $id => $user) :?>
    <option value="<?php echo $id; ?>"><?php echo $user['naam']; ?></option>
<?php endforeach; ?>
</select></br>
Vul het aankoopbedrag in:
<input type="tekst" name="bedrag" value="<?php echo number_format(abs($checksum), 2); ?>"/></br>
Vul de datum van aankoop in:
<input type="text" name="datum" value="<?php echo $datum; ?>"/></br></br>
<input type="submit" name="invoegen" value="Invoegen"/>
</form>

