<?php

namespace App\Actions\ChatActions;

use App\Repository\ChatRepository;
use Illuminate\Http\Request;

class GetPrivateChatMessagesAction
{
    protected $ChatRepository;

    public function __construct(ChatRepository $ChatRepository)
    {
        return $this->ChatRepository = $ChatRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        return $this->ChatRepository->getPrivateChatMessages($id);
    }
}
