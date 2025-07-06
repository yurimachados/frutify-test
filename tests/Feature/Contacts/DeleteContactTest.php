<?php

namespace Tests\Feature\Contacts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteContactTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_be_able_to_delete_a_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $response = $this->delete("/contacts/{$contact->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('contacts', $contact->toArray());
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
    public function it_should_return_404_when_deleting_nonexistent_contact(): void
    {
        $response = $this->delete("/contacts/999");

        $response->assertStatus(404);
    }

    #[Test]
    public function it_should_not_delete_already_deleted_contact(): void
    {
        $contact = \App\Models\Contact::factory()->create();
        $contact->delete();

        $response = $this->delete("/contacts/{$contact->id}");

        $response->assertStatus(404);
    }
}
