<?php

namespace App\Http\Controllers\Contacts;

use App\DTOs\UpdateContactDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\UseCases\Contact\UpdateContactUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\Contact\ContactNotFoundException;

/**
 * HTTP controller for contact update operations.
 *
 * Handles contact update requests by delegating business logic
 * to the use case layer while managing HTTP responses and errors.
 */
class UpdateContactController extends Controller
{
    /**
     * Create a new update contact controller instance.
     *
     * @param UpdateContactUseCase $updateContactUseCase Business logic handler
     */
    public function __construct(
        private UpdateContactUseCase $updateContactUseCase
    ) {}

    /**
     * Update an existing contact.
     *
     * Processes validated contact data through the use case layer
     * and returns appropriate HTTP responses based on operation result.
     *
     * @param ContactRequest $request Validated contact request
     * @param int $contactId Contact identifier to update
     * @return ContactResource|JsonResponse|RedirectResponse
     */
    public function __invoke(ContactRequest $request, int $contactId): ContactResource|JsonResponse|RedirectResponse
    {
        try {
            $validatedContactData = $request->validated();
            $updateContactData = UpdateContactDto::fromArray($contactId, $validatedContactData);

            $updatedContact = $this->updateContactUseCase->execute($updateContactData);

            return (new ContactResource($updatedContact))
                ->response()
                ->setStatusCode(200);
        } catch (ContactNotFoundException $e) {
            return response()->json([
                'message' => 'Contact not found'
            ], 404);
        } catch (\InvalidArgumentException $businessRuleError) {
            return back()
                ->withInput()
                ->withErrors(['email' => $businessRuleError->getMessage()]);
        }
    }
}
