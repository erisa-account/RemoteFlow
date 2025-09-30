<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\StatusService;

class StatusController extends Controller
{
    protected $statusService;

    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function index()
    {
        $statuses = $this->statusService->getAll();
        return response()->json($statuses);
    }
}