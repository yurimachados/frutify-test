<?php

namespace Tests\Unit\DTOs;

use App\DTOs\CreateContactDto;
use App\DTOs\UpdateContactDto;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactDtosTest extends TestCase
{
    #[Test]
    public function it_should_create_create_contact_dto(): void
    {
        $dto = new CreateContactDto(
            'John Doe',
            'john@example.com',
            '(41) 98899-4422'
        );

        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@example.com', $dto->email);
        $this->assertEquals('(41) 98899-4422', $dto->phone);
    }

    #[Test]
    public function it_should_create_update_contact_dto(): void
    {
        $dto = new UpdateContactDto(
            1,
            'John Updated',
            'john.updated@example.com',
            '(41) 98899-4422'
        );

        $this->assertEquals(1, $dto->id);
        $this->assertEquals('John Updated', $dto->name);
        $this->assertEquals('john.updated@example.com', $dto->email);
        $this->assertEquals('(41) 98899-4422', $dto->phone);
    }

    #[Test]
    public function it_should_create_update_dto_from_array(): void
    {
        $data = [
            'name' => 'John From Array',
            'email' => 'john.array@example.com',
            'phone' => '(41) 98899-4422'
        ];

        $dto = UpdateContactDto::fromArray(1, $data);

        $this->assertEquals(1, $dto->id);
        $this->assertEquals('John From Array', $dto->name);
        $this->assertEquals('john.array@example.com', $dto->email);
        $this->assertEquals('(41) 98899-4422', $dto->phone);
    }
}
