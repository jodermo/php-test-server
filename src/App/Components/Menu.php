<div class="menu">
    <?php echo (($app->pageName != 'start') ?  '<div class="menu-item"><a href="/">Start Page</a></div>' : null); ?>
    <?php echo (($app->pageName != 'mollie') ?  '<div class="menu-item"><a href="/?page=mollie">Pay To Mollie</a></div>' : null); ?>
    <?php echo (($app->pageName != 'chat') ?  '<div class="menu-item"><a href="/?page=chat">Chat</a></div>' : null); ?>
    <?php echo (($app->chat->chatRoom && $app->chat->chatActive && !$_GET["clear_chat"]) ?  '<div class="menu-item"><a href="/?clear_chat=1&' . $app->chat->chatParams() . '">Clear Chat Hystory</a></div>' : null); ?>
    <?php echo (($app->chat->chatRoom && $app->chat->chatActive && $_GET["clear_chat"]) ?  '<div class="menu-item"><a href="/?' . $app->chat->chatParams() . '">Chat</a></div>' : null); ?>
</div>