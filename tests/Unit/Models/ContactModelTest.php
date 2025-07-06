<?php

namespace Tests\Unit\Models;

use App\Models\Contact;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_create_contact_with_fillable_attributes(): void
    {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '41988994422'
        ]);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('John Doe', $contact->name);
        $this->assertEquals('john@example.com', $contact->email);
        $this->assertEquals('41988994422', $contact->phone);
        $this->assertNotNull($contact->id);
        $this->assertNotNull($contact->created_at);
        $this->assertNotNull($contact->updated_at);
    }

    #[Test]
    public function it_should_use_soft_deletes(): void
    {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '41988994422'
        ]);

        $contact->delete();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
        $this->assertNotNull($contact->fresh()->deleted_at);
    }

    #[Test]
    public function it_should_have_fillable_attributes(): void
    {
        $contact = new Contact();
        $fillable = $contact->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('phone', $fillable);
    }

    #[Test]
    public function it_should_cast_timestamps(): void
    {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '41988994422'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $contact->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $contact->updated_at);
    }
}
