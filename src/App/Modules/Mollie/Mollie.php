<div class="content-wrapper payment-wrapper">
    <div class="background-image">
        <div class="image-container">
            <img src="assets/img/payment_background2.png">
        </div>
    </div>
    <div class="page-content">
        <?php

        if ($_GET["submit"]) {
            include(__DIR__ . '/Components/PaymentSubmit.php');
        } else if ($_GET["success"]) {
            include(__DIR__ . '/Components/PaymentSuccess.php');
        } else if ($_GET["pay"]) {
            include(__DIR__ . '/Components/PaymentForm.php');
        }  else if ($_GET["connect"]) {
            include(__DIR__ . '/Components/MollieConnect.php');
        }else if ($_GET["webhook"]) {
            $app->mollie->checkWebHook();
        }else {
            include(__DIR__ . '/Components/ConnectForm.php');
        }
        include(__DIR__ . '/Components/MollieInfo.php');
        ?>
    </div>

</div>