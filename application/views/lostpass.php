<h1>Wachtwoord vergeten</h1>
<?php if (isset($melding)):
    echo $melding;
endif; ?>
<p>
    <b>Nee, een wachtwoord vergeten is geen reden om geen snack te bestellen. Vul je e-mailadres in en je ontvangt een e-mail met instructies!</b>
</p>
<form method="POST" action="/index.php/snack/lostpass">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>E-mailadres:</td>
            <td><input type="text" name="email" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Maak nieuw wachtwoord!" /></td>
        </tr>
    </table>
</form>
<p><b>Let op!</b> Met dit formulier krijg je geen nieuw wachtwoord toegestuurd. Je moet eerst in de e-mail een bevestigingslink aanklikken om het wachtwoord te kunnen resetten. Hierna ontvang je pas een nieuw wachtwoord.</p>

