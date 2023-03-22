<h2>Mollie Connect API</h2>
<form method="post" action="/?page=mollie&connect=1">
    <input type="text" id="mollie_client_id" name="mollie_client_id" placeholder="Client ID*" value="<?php echo $app->mollie->clientId ?>" required>
    <input type="text" id="mollie_client_secret" name="mollie_client_secret" placeholder="Client Secret*" value="<?php echo $app->mollie->clientSecret ?>" required>
    <input type="text" id="mollie_client_redirect_url" name="mollie_client_redirect_url" value="<?php echo $app->mollie->clientRedirectUrl ?>" placeholder="Redirect URL">
    <?php echo $app->arrayToHtmlCheckboxes( $app->mollie->scopes, 'mollie_client_scopes'); ?>
    <input class="button" type="submit" value="Connect to Mollie">
</form>