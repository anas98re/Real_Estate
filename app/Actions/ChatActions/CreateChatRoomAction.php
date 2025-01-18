<?php

namespace App\Actions\ChatActions;

use App\ApiHelper\PermissionsHelper;
use App\Repository\ChatRepository;
use Illuminate\Http\Request;

class CreateChatRoomAction
{
    protected $ChatRepository;

    public function __construct(ChatRepository $ChatRepository)
    {
        return $this->ChatRepository = $ChatRepository;
    }

    public function __invoke(Request $request)
    {
        if (!PermissionsHelper::hasPermission('publicChat.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ChatRepository->createChatRoom($request);
    }
}
