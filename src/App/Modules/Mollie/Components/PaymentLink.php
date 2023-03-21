<br>
Payment Link:
<a href="<?php echo $app->mollie->paymentUrl; ?>" target="_blank">
    <?php echo $app->mollie->paymentUrl; ?>
</a><br>
<iframe src="<?php echo $app->mollie->paymentUrl; ?>"></iframe>