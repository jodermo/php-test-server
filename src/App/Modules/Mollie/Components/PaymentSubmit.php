<h2>Pament Created:</h2>
<?php $app->mollie->paymentUrl ? include(__DIR__ . '/PaymentLink.php') : null ?>
<?php !$app->mollie->paymentUrl ? include(__DIR__ . '/PaymentError.php') : null ?>
<div class="php-output">
    <h3>Transaktion Data:</h3>
    <table>
        <tbody>
            <tr>
                <th>Api Key</th>
                <td><?php echo $app->mollie->apiKey ?></td>
            </tr>
            <tr>
                <th>Access Key</th>
                <td><?php echo $app->mollie->accessKey ?></td>
            </tr>
        </tbody>
    </table>
    <?php echo $app->arrayToHtmlTable($app->mollie->request, 'Request Data'); ?>
    <?php echo $app->arrayToHtmlTable($app->mollie->response, 'Response Data'); ?>
</div>
<form method="post" action="/?page=mollie">
    <input class="button" type="submit" value="New Transaction">
</form>