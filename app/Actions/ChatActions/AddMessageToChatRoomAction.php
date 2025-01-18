<?php

namespace App\Actions\ChatActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\ChatRepository;
use Illuminate\Http\Request;

class AddMessageToChatRoomAction
{
    protected $ChatRepository;

    public function __construct(ChatRepository $ChatRepository)
    {
        return $this->ChatRepository = $ChatRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('roomId');
        return $this->ChatRepository->addMessageToChatRoom($request, $id);
    }
}
