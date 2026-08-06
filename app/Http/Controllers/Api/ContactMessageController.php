<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    use ApiResponse;

    /**
     * POST /contact — stores the message. Sending an actual email is a
     * stub/TODO for a later phase per spec §8.
     */
    public function store(ContactMessageRequest $request): JsonResponse
    {
        $message = ContactMessage::create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
        ]);

        // TODO: notify the AmaX inbox by email once real contact details are provided.

        return $this->success(null, 'Your message has been sent. We will get back to you soon.', 201);
    }
}
