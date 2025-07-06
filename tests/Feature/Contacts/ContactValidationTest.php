<?php

namespace Tests\Feature\Contacts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactValidationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_validate_required_fields(): void
    {
        $response = $this->post('/contacts', []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone'
        ]);

        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_validate_name_minimum_length(): void
    {
        $data = [
            'name' => 'ab',
            'email' => 'valid@email.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_validate_name_maximum_length(): void
    {
        $data = [
            'name' => str_repeat('a', 256),
            'email' => 'valid@email.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_validate_email_format(): void
    {
        $data = [
            'name' => 'Valid Name',
            'email' => 'invalid-email',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_validate_phone_format(): void
    {
        $data = [
            'name' => 'Valid Name',
            'email' => 'valid@email.com',
            'phone' => '123'
        ];

        $response = $this->post('/contacts', $data);

        $response->assertSessionHasErrors(['phone']);
        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_accept_valid_phone_formats(): void
    {
        $validPhones = [
            '(41) 98899-4422',
            '11999887766',
            '+55 11 99988-7766',
            '(11) 9 9988-7766'
        ];

        foreach ($validPhones as $index => $phone) {
            $data = [
                'name' => 'Valid Name',
                'email' => 'valid' . $index . '@email.com',
                'phone' => $phone
            ];

            $response = $this->post('/contacts', $data);

            $response->assertStatus(201);
        }

        $this->assertDatabaseCount('contacts', count($validPhones));
    }

    #[Test]
    public function it_should_validate_all_fields_together(): void
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
}
