<?php

namespace Tests\Unit\Services\Contact;

use App\Services\Contact\EmailValidationService;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class EmailValidationServiceTest extends TestCase
{
    private EmailValidationService $service;
    private ContactRepositoryInterface|MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(ContactRepositoryInterface::class);
        $this->service = new EmailValidationService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_should_check_if_email_exists(): void
    {
        $email = 'test@example.com';
        
        $this->repository
            ->shouldReceive('emailExists')
            ->with($email)
            ->once()
            ->andReturn(true);

        $result = $this->service->exists($email);

        $this->assertTrue($result);
    }

    #[Test]
    public function it_should_return_false_when_email_does_not_exist(): void
    {
        $email = 'nonexistent@example.com';
        
        $this->repository
            ->shouldReceive('emailExists')
            ->with($email)
            ->once()
            ->andReturn(false);

        $result = $this->service->exists($email);

        $this->assertFalse($result);
    }

    #[Test]
    public function it_should_check_if_email_is_unique_for_contact(): void
    {
        $email = 'test@example.com';
        $contactId = 1;
        
        $this->repository
            ->shouldReceive('findByEmail')
            ->with($email)
            ->once()
            ->andReturn(null);

        $result = $this->service->isUniqueForContact($email, $contactId);

        $this->assertTrue($result);
    }
}
