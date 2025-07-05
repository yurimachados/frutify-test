<?php

namespace App\Http\Controllers\Contacts;

use App\DTOs\CreateContactDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\UseCases\Contact\CreateContactUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * HTTP controller for contact creation operations.
 *
 * Handles contact creation requests by delegating business logic
 * to the use case layer while managing HTTP responses and errors.
 */
class CreateContactController extends Controller
{
    /**
     * Create a new contact creation controller instance.
     *
     * @param CreateContactUseCase $createContactUseCase Business logic handler
     */
    public function __construct(
        private CreateContactUseCase $createContactUseCase
    ) {}

    /**
     * Store a newly created contact.
     *
     * Processes validated contact data through the use case layer
     * and returns appropriate HTTP responses based on operation result.
     *
     * @param ContactRequest $request Validated contact request
     * @return ContactResource|JsonResponse|RedirectResponse
     */
    public function __invoke(ContactRequest $request): ContactResource|JsonResponse|RedirectResponse
    {
        try {
            $validatedContactData = $request->validated();
            $createContactData = CreateContactDto::fromArray($validatedContactData);

            $createdContact = $this->createContactUseCase->execute($createContactData);

            return (new ContactResource($createdContact))
                ->response()
                ->setStatusCode(201);

        } catch (\InvalidArgumentException $businessRuleError) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => $businessRuleError->getMessage()]);
        }
    }
}
