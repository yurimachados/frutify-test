<?php

namespace Tests\Unit\Services\Contact;

use App\Services\Contact\PhoneService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PhoneServiceTest extends TestCase
{
    private PhoneService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhoneService();
    }

    #[Test]
    public function it_should_normalize_phone_number_correctly(): void
    {
        $phoneInputs = [
            '(41) 98899-4422' => '41988994422',
            '41988994422' => '41988994422',
            '+55 41 98899-4422' => '5541988994422',
            '(41) 9 8899-4422' => '41988994422'
        ];

        foreach ($phoneInputs as $input => $expected) {
            $normalized = $this->service->normalize($input);
            $this->assertEquals($expected, $normalized);
        }
    }

    #[Test]
    public function it_should_validate_phone_number_format(): void
    {
        $validPhones = [
            '(41) 98899-4422',
            '41988994422',
            '+55 41 98899-4422',
            '(41) 9 8899-4422',
            '1234567890'
        ];

        foreach ($validPhones as $phone) {
            $this->assertTrue($this->service->isValid($phone));
        }
    }

    #[Test]
    public function it_should_reject_invalid_phone_numbers(): void
    {
        $invalidPhones = [
            '123',
            '41988',
            'not-a-phone',
            '(41) 988',
            '123456789' // less than 10 digits
        ];

        foreach ($invalidPhones as $phone) {
            $this->assertFalse($this->service->isValid($phone));
        }
    }

    #[Test]
    public function it_should_normalize_phone_number(): void
    {
        $phone = '(41) 98899-4422';
        $normalized = $this->service->normalize($phone);
        
        $this->assertEquals('41988994422', $normalized);
    }

    #[Test]
    public function it_should_handle_empty_phone_numbers(): void
    {
        $this->assertFalse($this->service->isValid(''));
        $this->assertFalse($this->service->isValid('   '));
    }

    #[Test]
    public function it_should_remove_all_non_numeric_characters(): void
    {
        $phone = '+55 (41) 9.8899-4422 ext. 123';
        $normalized = $this->service->normalize($phone);
        
        $this->assertEquals('5541988994422123', $normalized);
    }
}
