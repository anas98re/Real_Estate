<?php

namespace App\Actions\ChatActions;

use App\Repository\ChatRepository;
use Illuminate\Http\Request;

class ConnectUserAction
{
    protected $ChatRepository;

    public function __construct(ChatRepository $ChatRepository)
    {
        return $this->ChatRepository = $ChatRepository;
    }

    public function __invoke(Request $request)
    {
        return $this->ChatRepository->connectUser($request);
    }
}
