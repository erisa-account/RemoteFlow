<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestRequest;
use App\Http\Requests\Admin\GetTestRequest;
use App\Service\TestService;

class TestController extends Controller
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

     public function getData(GetTestRequest $request)
    {
          
          $tests = $this->testService->getAll($request->validated());

         return redirect()->back()->with('success', 'User got successfully.');
        // Return view or JSON with data
        //return view('test.test', compact('test'));
    }

    public function store(StoreTestRequest $request)
    {
        

        $this->testService->store($request->validated());

        return redirect()->back()->with('success', 'User added successfully.');
    }
}
