<h2>New Mollie Payment Transaction</h2>
<br>
<form method="post" action="/?page=mollie&submit=1">

    <input type="number" id="transaction_amount" name="transaction_amount" placeholder="Amount*" value="0.99" step="0.01" required><br><br>
    <input type="text" id="transaction_decription" name="transaction_decription" placeholder="Description*" value="Test Payment from <?php echo $app->getConfig('domain'); ?>" required><br><br>
    <input type="text" id="transaction_profile_id" name="transaction_profile_id" placeholder="Profile ID*" value="<?php echo $app->mollie->profileId ?>" required><br><br>
    <input type="text" id="transaction_api_key" name="transaction_api_key" placeholder="API Key*" value="<?php echo $app->mollie->apiKey ?>" required><br><br>
    <input type="text" id="transaction_access_key" name="transaction_access_key" placeholder="Access Key*" value="<?php echo $app->mollie->accessKey ?>" required><br><br>
    <input type="text" id="transaction_webhook_url" name="transaction_webhook_url" value="<?php echo $app->mollie->webhookUrl ?>" placeholder="Webhook URL"><br><br>
    <input type="text" id="transaction_redirect_url" name="transaction_redirect_url" value="<?php echo $app->mollie->redirectUrl ?>" placeholder="Redirect URL"><br><br>
    <input type="text" id="transaction_partner_profile_id" name="transaction_partner_profile_id" value="<?php echo $app->mollie->profileId ?>" placeholder="Receiver Profile ID"><br><br>
    <input type="number" id="transaction_receiver_application_fee" name="transaction_receiver_application_fee" value="10" placeholder="Application Fee (%)"><br><br>
    <div class="checkbox-container">
        <input type="checkbox" id="transaction_test_mode" name="transaction_test_mode" <?php echo $app->mollie->testMode ? 'checked' : null; ?>><br><br>
        <label for="transaction_test_mode">Test Mode</label>
    </div>
    <input class="button" type="submit" value="Create Payment Transaction">
</form>