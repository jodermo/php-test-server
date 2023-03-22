<h2>Connected</h2>
<div class="php-output">
    <h3>Mollie Connect</h3>
    <table>
        <tbody>
            <tr>
                <th>Client ID</th>
                <td><?php echo $app->mollie->clientId ?></td>
            </tr>
            <tr>
                <th>Client Secret</th>
                <td><?php echo $app->mollie->clientSecret ?></td>
            </tr>
        </tbody>
    </table>
    <?php echo $app->arrayToHtmlTable($app->mollie->response, 'Response Data'); ?>
    <?php include(__DIR__ . '/PaymentError.php'); ?>
</div>
<form method="post" action="/?page=mollie&connect=1&test=1">
    <input class="button" type="submit" value="Test">
</form>