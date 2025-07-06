<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateContactsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_be_able_to_create_a_new_contact(): void
    {
        $data = [
            'name' => 'Rodolfo Meri',
            'email' => 'rodolfomeri@contato.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->post('/contacts', $data);

        // The right status code for creation is 201
        // 200 is used for successful updates or retrievals
        $response->assertStatus(201);

        $expected = $data;
        $expected['phone'] = preg_replace('/\D/', '', $expected['phone']);

        $this->assertDatabaseHas('contacts', $expected);
    }

    #[Test]
    public function it_should_validate_information(): void
    {
        $data = [
            'name' => 'ro',
            'email' => 'email-errado@',
            'phone' => '419'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone'
        ]);

        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_be_able_to_list_contacts_paginated_by_10_items_per_page(): void
    {
        \App\Models\Contact::factory(20)->create();

        $response = $this->get('/contacts');

        $response->assertStatus(200);

        $response->assertViewIs('contacts.index');

        $response->assertViewHas('contacts');

        $contacts = $response->viewData('contacts');

        $this->assertCount(10, $contacts);
    }

    #[Test]
    public function it_should_be_able_to_delete_a_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $response = $this->delete("/contacts/{$contact->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    #[Test]
    public function the_contact_email_should_be_unique(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $data = [
            'name' => 'Rodolfo Meri',
            'email' => $contact->email,
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors([
            'email'
        ]);

        $this->assertDatabaseCount('contacts', 1);
    }

    #[Test]
    public function it_should_be_able_to_update_a_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $data = [
            'name' => 'Rodolfo Meri',
            'email' => 'emailatualizado@email.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->put("/contacts/{$contact->id}", $data);

        $response->assertStatus(200);

        $expected = $data;

        $expected['phone'] = preg_replace('/\D/', '', $expected['phone']);

        $this->assertDatabaseHas('contacts', $expected);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    /**
     * ==========================================
     * Extra tests
     * ==========================================
     */


    #[Test]
    public function it_should_be_able_to_search_contacts_by_name(): void
    {
        \App\Models\Contact::factory()->create(['name' => 'João Silva']);
        \App\Models\Contact::factory()->create(['name' => 'Maria Santos']);
        \App\Models\Contact::factory()->create(['name' => 'Pedro Oliveira']);

        $response = $this->get('/contacts?search=João');

        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
        $response->assertViewHas('contacts');
        $response->assertViewHas('search', 'João');

        $contacts = $response->viewData('contacts');
        $this->assertCount(1, $contacts);
        $this->assertEquals('João Silva', $contacts->first()->name);
    }

    #[Test]
    public function it_should_be_able_to_search_contacts_by_email(): void
    {
        \App\Models\Contact::factory()->create(['email' => 'joao@email.com']);
        \App\Models\Contact::factory()->create(['email' => 'maria@email.com']);
        \App\Models\Contact::factory()->create(['email' => 'pedro@email.com']);

        $response = $this->get('/contacts?search=joao@email.com');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(1, $contacts);
        $this->assertEquals('joao@email.com', $contacts->first()->email);
    }

    #[Test]
    public function it_should_be_able_to_search_contacts_by_phone(): void
    {
        \App\Models\Contact::factory()->create(['phone' => '11999887766']);
        \App\Models\Contact::factory()->create(['phone' => '11888776655']);
        \App\Models\Contact::factory()->create(['phone' => '11777665544']);

        $response = $this->get('/contacts?search=11999887766');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(1, $contacts);
        $this->assertEquals('11999887766', $contacts->first()->phone);
    }

    #[Test]
    public function it_should_return_empty_results_when_no_contacts_match_search(): void
    {
        \App\Models\Contact::factory()->create(['name' => 'João Silva']);
        \App\Models\Contact::factory()->create(['name' => 'Maria Santos']);

        $response = $this->get('/contacts?search=Carlos');

        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
        $response->assertViewHas('search', 'Carlos');

        $contacts = $response->viewData('contacts');
        $this->assertCount(0, $contacts);
    }

    #[Test]
    public function it_should_validate_search_parameters(): void
    {
        $response = $this->get('/contacts?search=' . str_repeat('a', 256));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['search']);
    }

    #[Test]
    public function it_should_validate_per_page_parameter(): void
    {
        $response = $this->get('/contacts?per_page=101');

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['per_page']);
    }

    #[Test]
    public function it_should_search_case_insensitive(): void
    {
        \App\Models\Contact::factory()->create(['name' => 'João Silva']);
        \App\Models\Contact::factory()->create(['name' => 'Maria Santos']);

        $response = $this->get('/contacts?search=joão');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(1, $contacts);
        $this->assertEquals('João Silva', $contacts->first()->name);
    }

    #[Test]
    public function it_should_soft_delete_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $response = $this->delete("/contacts/{$contact->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    #[Test]
    public function it_should_restore_soft_deleted_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $result = $repository->restore($contact->id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'deleted_at' => null
        ]);
    }

    #[Test]
    public function it_should_permanently_delete_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $result = $repository->forceDelete($contact->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    #[Test]
    public function it_should_get_trashed_contacts(): void
    {
        $activeContact = \App\Models\Contact::factory()->create(['name' => 'Active Contact']);
        $trashedContact = \App\Models\Contact::factory()->create(['name' => 'Trashed Contact']);
        $trashedContact->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $trashedContacts = $repository->getTrashedPaginated();

        $this->assertCount(1, $trashedContacts->items());
        $this->assertEquals('Trashed Contact', $trashedContacts->items()[0]->name);
    }

    #[Test]
    public function it_should_get_all_contacts_including_trashed(): void
    {
        $activeContact = \App\Models\Contact::factory()->create(['name' => 'Active Contact']);
        $trashedContact = \App\Models\Contact::factory()->create(['name' => 'Trashed Contact']);
        $trashedContact->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $allContacts = $repository->getAllPaginated();

        $this->assertCount(2, $allContacts->items());
    }

    #[Test]
    public function it_should_not_find_trashed_contact_with_regular_methods(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $foundContact = $repository->find($contact->id);

        $this->assertNull($foundContact);
    }

    #[Test]
    public function it_should_search_trashed_contacts(): void
    {
        $contact1 = \App\Models\Contact::factory()->create(['name' => 'João Silva']);
        $contact2 = \App\Models\Contact::factory()->create(['name' => 'Maria Santos']);
        $contact1->delete();
        $contact2->delete();

        $repository = app(\App\Repositories\Contracts\ContactRepositoryInterface::class);
        $trashedContacts = $repository->getTrashedPaginated(10);

        $this->assertCount(2, $trashedContacts->items());
        $names = collect($trashedContacts->items())->pluck('name')->toArray();
        $this->assertContains('João Silva', $names);
        $this->assertContains('Maria Santos', $names);
    }
}
