<?php

namespace App\Http\Controllers\Contacts;

use App\DTOs\CreateContactDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\UseCases\Contact\CreateContactUseCase;

/**
 * Handle contact creation.
 */
class CreateContactController extends Controller
{
    public function __construct(
        private CreateContactUseCase $createContactUseCase
    ) {}

    /**
     * Store a newly created contact in storage.
     *
     * @param ContactRequest $request
     * @return ContactResource|\Illuminate\Http\JsonResponse
     */
    public function __invoke(ContactRequest $request)
    {
        try {
            // Convert validated data to DTO
            $dto = CreateContactDto::fromArray($request->validated());

            // Execute use case and get created contact
            $contact = $this->createContactUseCase
                ->execute($dto);

            // Always return contact resource with 201 status
            return (new ContactResource($contact))
                ->response()
                ->setStatusCode(201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => [
                    'email' => [$e->getMessage()]
                ]
            ], 422);
        }
    }
}
