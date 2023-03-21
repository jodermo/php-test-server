<h2>Payment Success</h2>
<div class="php-output">
    <h3>Transaktion Data</h3>
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
    <?php echo $app->arrayToHtmlTable($app->mollie->response, 'Response Data'); ?>
    <?php include(__DIR__ . '/PaymentError.php'); ?>
</div>
<form method="post" action="/?page=mollie">
    <input class="button" type="submit" value="New Transaction">
</form>