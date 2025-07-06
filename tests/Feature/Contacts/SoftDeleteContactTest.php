<?php

namespace Tests\Feature\Contacts;

use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SoftDeleteContactTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_restore_soft_deleted_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $repository = app(ContactRepositoryInterface::class);
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

        $repository = app(ContactRepositoryInterface::class);
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

        $repository = app(ContactRepositoryInterface::class);
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

        $repository = app(ContactRepositoryInterface::class);
        $allContacts = $repository->getAllPaginated();

        $this->assertCount(2, $allContacts->items());
    }

    #[Test]
    public function it_should_not_find_trashed_contact_with_regular_methods(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $repository = app(ContactRepositoryInterface::class);
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

        $repository = app(ContactRepositoryInterface::class);
        $trashedContacts = $repository->getTrashedPaginated(10);

        $this->assertCount(2, $trashedContacts->items());
        $names = collect($trashedContacts->items())->pluck('name')->toArray();
        $this->assertContains('João Silva', $names);
        $this->assertContains('Maria Santos', $names);
    }

    #[Test]
    public function it_should_return_false_when_restoring_nonexistent_contact(): void
    {
        $repository = app(ContactRepositoryInterface::class);
        $result = $repository->restore(999);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_should_return_false_when_force_deleting_nonexistent_contact(): void
    {
        $repository = app(ContactRepositoryInterface::class);
        $result = $repository->forceDelete(999);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_should_not_restore_active_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $repository = app(ContactRepositoryInterface::class);
        $result = $repository->restore($contact->id);

        $this->assertFalse($result);
    }
}
