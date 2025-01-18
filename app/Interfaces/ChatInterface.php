<?php

namespace App\Interfaces;

interface ChatInterface
{
    public function connectUser($request);
    public function disConnectUser($request);
    public function addMessageToChat($request, $id);

    public function createChatRoom($request);
    public function addUserToChatRoom($request, $id);
    public function getChatRoomMessages($roomId);
    public function addMessageToChatRoom($request, $roomId);

}
