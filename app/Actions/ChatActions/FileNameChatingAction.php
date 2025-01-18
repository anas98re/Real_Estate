<?php

namespace App\Actions\ChatActions;

use App\Repository\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileNameChatingAction
{
    protected $ChatRepository;

    public function __construct(ChatRepository $ChatRepository)
    {
        return $this->ChatRepository = $ChatRepository;
    }

    public function __invoke(Request $request)
    {
        Log::debug("wqqqqqqqqqqqqqqqqqqqqqqqqqwwwwwwwwwwwwwwwwwwww " . $request);

        $filename = $request->route('filename');
        return $this->ChatRepository->getFile($request, $filename);
    }
}
