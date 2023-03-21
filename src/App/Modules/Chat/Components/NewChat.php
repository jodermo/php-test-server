<div class="new-chat">
    <div class="background-image">
        <div class="image-container">
            <img src="assets/img/chat_background.png">
        </div>
    </div>
    <h2>Chatroom Name:</h2>
    <br>
    <form method="post" action="/?page=chat">
        <input type="hidden" id="key" name="key" value="<?php echo $app->chat->getKey() ?>">
        <input type="text" id="new_chat_room" name="new_chat_room" value="Very Important Meeting" required><br><br>
        <input class="button" type="submit" value="Create Chatroom">
    </form>
</div>