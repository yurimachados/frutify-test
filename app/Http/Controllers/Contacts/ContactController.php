<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\UseCases\Contact\FindContactUseCase;
use App\UseCases\Contact\ListContactsUseCase;
use Illuminate\View\View;

/**
 * HTTP controller for contact view operations.
 *
 * Handles web requests for displaying contact lists, forms, and individual
 * contact details. Delegates all business logic to use case layer following
 * Clean Architecture principles and avoiding direct data access.
 */
class ContactController extends Controller
{
    /**
     * Create a new contact view controller instance.
     *
     * @param ListContactsUseCase $listContactsUseCase Business logic for contact listing
     * @param FindContactUseCase $findContactUseCase Business logic for finding contacts
     */
    public function __construct(
        private ListContactsUseCase $listContactsUseCase,
        private FindContactUseCase $findContactUseCase
    ) {}

    /**
     * Display paginated listing of contacts.
     *
     * Delegates to use case layer for business logic and
     * renders the index view with paginated results.
     *
     * @return View Contact index view with paginated contacts
     */
    public function index(): View
    {
        $paginatedContacts = $this->listContactsUseCase->execute();

        return view('contacts.index', [
            'contacts' => $paginatedContacts
        ]);
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return View Contact creation form view
     */
    public function create(): View
    {
        return view('contacts.create');
    }

    /**
     * Display the specified contact details.
     *
     * Uses find contact use case to retrieve contact data and renders detail view.
     * Follows Clean Architecture by delegating business logic to use case layer.
     *
     * @param int $contactId Contact identifier
     * @return View Contact detail view
     */
    public function show(int $contactId): View
    {
        $contact = $this->findContactUseCase->execute($contactId);

        if (!$contact) {
            abort(404, 'Contact not found');
        }

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     *
     * Uses find contact use case to retrieve contact data and renders edit form.
     * Follows Clean Architecture by delegating business logic to use case layer.
     *
     * @param int $contactId Contact identifier
     * @return View Contact edit form view
     */
    public function edit(int $contactId): View
    {
        $contact = $this->findContactUseCase->execute($contactId);

        if (!$contact) {
            abort(404, 'Contact not found');
        }

        return view('contacts.edit', compact('contact'));
    }
}
