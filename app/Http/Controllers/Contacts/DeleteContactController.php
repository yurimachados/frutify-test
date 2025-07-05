<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\UseCases\Contact\DeleteContactUseCase;

/**
 * Handle contact deletion.
 */
class DeleteContactController extends Controller
{
    public function __construct(
        private DeleteContactUseCase $deleteContactUseCase
    ) {}

    /**
     * Remove the specified contact from storage.
     *
     * @param int $contactId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $contactId)
    {
        try {
            // Execute use case
            $this->deleteContactUseCase->execute($contactId);

            return response()->json([
                'message' => 'Contact deleted successfully'
            ], 200);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Contact not found'
            ], 404);
        }
    }
}
