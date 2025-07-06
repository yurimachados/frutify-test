<?php

namespace Tests\Feature\Contacts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateContactTest extends TestCase
{
    use RefreshDatabase;

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

    #[Test]
    public function it_should_validate_email_uniqueness_on_update(): void
    {
        $contact1 = \App\Models\Contact::factory()->create();
        $contact2 = \App\Models\Contact::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => $contact1->email,
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->put("/contacts/{$contact2->id}", $data);

        $response->assertSessionHasErrors(['email']);
        
        // Verify the contact wasn't updated
        $contact2->refresh();
        $this->assertNotEquals('Updated Name', $contact2->name);
        $this->assertNotEquals($contact1->email, $contact2->email);
    }

    #[Test]
    public function it_should_allow_updating_contact_with_same_email(): void
    {
        $contact = \App\Models\Contact::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => $contact->email,
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->put("/contacts/{$contact->id}", $data);

        $response->assertStatus(200);

        $expected = $data;
        $expected['phone'] = preg_replace('/\D/', '', $expected['phone']);

        $this->assertDatabaseHas('contacts', $expected);
    }

    #[Test]
    public function it_should_return_404_when_updating_nonexistent_contact(): void
    {
        $data = [
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->put("/contacts/999", $data);

        $response->assertStatus(404);
    }
}
