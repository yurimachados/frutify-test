<?php

namespace Tests\Feature\Contacts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListContactsTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_should_show_empty_list_when_no_contacts_exist(): void
    {
        $response = $this->get('/contacts');

        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
        $response->assertViewHas('contacts');

        $contacts = $response->viewData('contacts');
        $this->assertCount(0, $contacts);
    }

    #[Test]
    public function it_should_validate_per_page_parameter(): void
    {
        $response = $this->get('/contacts?per_page=101');

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['per_page']);
    }

    #[Test]
    public function it_should_accept_valid_per_page_values(): void
    {
        \App\Models\Contact::factory(25)->create();

        $response = $this->get('/contacts?per_page=25');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(25, $contacts);
    }

    #[Test]
    public function it_should_paginate_correctly(): void
    {
        \App\Models\Contact::factory(15)->create();

        $response = $this->get('/contacts?page=2');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(5, $contacts);
    }
}
