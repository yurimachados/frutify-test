<?php

namespace Tests\Feature\Contacts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchContactsTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_should_search_partial_matches(): void
    {
        \App\Models\Contact::factory()->create(['name' => 'João Silva']);
        \App\Models\Contact::factory()->create(['name' => 'Maria Joana']);

        $response = $this->get('/contacts?search=Jo');

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(2, $contacts);
    }

    #[Test]
    public function it_should_trim_search_input(): void
    {
        \App\Models\Contact::factory()->create(['name' => 'João Silva']);

        $response = $this->get('/contacts?search=' . urlencode('  João  '));

        $response->assertStatus(200);
        $contacts = $response->viewData('contacts');
        $this->assertCount(1, $contacts);
        $this->assertEquals('João Silva', $contacts->first()->name);
    }
}
