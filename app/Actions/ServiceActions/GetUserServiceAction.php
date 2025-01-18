<?php

namespace App\Actions\ServiceActions;

use App\Repository\ActivityRepository;
use App\Repository\ServiceRepository;
use Illuminate\Http\Request;

class GetUserServiceAction
{
    protected $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository) {
        $this->ServiceRepository = $ServiceRepository;
    }

    public function __invoke(Request $request)
    {
        $perPage = $request->route('perPage');
        return $this->ServiceRepository->getUserServices($perPage);
    }
}
