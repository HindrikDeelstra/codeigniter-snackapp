<h1>Nieuw wachtwoord verzonden</h1>
<p><b>Oke, vooruit. Je nieuwe wachtwoord is verstuurd! Je kunt hieronder inloggen met je nieuwe wachtwoord.</b></p>
<form method="POST" action="/index.php/snack/login">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>E-mailadres:</td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td>Wachtwoord</td>
            <td><input type="password" name="pass" autocomplete="off" value="" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Login" /></td>
        </tr>
    </table>
</form>
