<?php

$app->chat->start();

echo '<div class="chat-wrapper content-wrapper">';
echo $app->chat->chatTitle ? '<h2 class="chat-title">' . $app->chat->chatTitle . '</h3>' : null;

if ($_GET["clear_chat"]) {
    include(__DIR__ . '/Components/ClearChat.php');
} else if ($app->chat->chatRoom && $app->chat->chatActive) {
    include(__DIR__ . '/Components/ChatLink.php');
    include(__DIR__ . '/Components/ChatRoom.php');
    include(__DIR__ . '/Components/ChatInput.php');
} else if ($app->chat->chatRoom && !$app->chat->chatActive) {
    include(__DIR__ . '/Components/ChatLinkContent.php');
    include(__DIR__ . '/Components/EnterChat.php');
} else {
    include(__DIR__ . '/Components/NewChat.php');
}
echo '</div>';
