<?php 
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SendEmailRequest;
use App\Service\SendEmailService;
use Illuminate\Http\JsonResponse;
use App\Resources\SendEmailResource;

class SendEmailController extends Controller
{
    protected $sendEmailService;

    public function __construct(SendEmailService $sendEmailService)
    {
        $this->sendEmailService = $sendEmailService; 
    }

    public function sendEmail(SendEmailRequest $request)
    {
        $validated = $request->validated();

        $files = $request->file('file'); // this will be an array
        if (!$files) {
        $files = []; // no files uploaded
        }

        $result = $this->sendEmailService->send($validated, $files);

        // Return formatted resource
        return new SendEmailResource($result); 
    }
}