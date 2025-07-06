<?php

namespace Tests\Unit\UseCases\Contact;

use App\UseCases\Contact\UpdateContactUseCase;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Services\Contact\PhoneServiceInterface;
use App\DTOs\UpdateContactDto;
use App\Models\Contact;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class UpdateContactUseCaseTest extends TestCase
{
    private UpdateContactUseCase $useCase;
    private ContactRepositoryInterface|MockInterface $repository;
    private EmailValidationServiceInterface|MockInterface $emailService;
    private PhoneServiceInterface|MockInterface $phoneService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = Mockery::mock(ContactRepositoryInterface::class);
        $this->emailService = Mockery::mock(EmailValidationServiceInterface::class);
        $this->phoneService = Mockery::mock(PhoneServiceInterface::class);
        
        $this->useCase = new UpdateContactUseCase(
            $this->repository,
            $this->emailService,
            $this->phoneService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_should_throw_exception_when_contact_not_found(): void
    {
        $contactId = 999;
        $dto = new UpdateContactDto(
            $contactId,
            'John Updated',
            'john.updated@example.com',
            '(41) 98899-4422'
        );

        $this->repository
            ->shouldReceive('find')
            ->with($contactId)
            ->once()
            ->andReturn(null);

        $this->expectException(ContactNotFoundException::class);

        $this->useCase->execute($dto);
    }

    #[Test]
    public function it_should_throw_exception_when_email_already_exists(): void
    {
        $contactId = 1;
        $dto = new UpdateContactDto(
            $contactId,
            'John Updated',
            'existing@example.com',
            '(41) 98899-4422'
        );

        $existingContact = new Contact([
            'id' => $contactId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '41988994422'
        ]);

        $this->repository
            ->shouldReceive('find')
            ->with($contactId)
            ->once()
            ->andReturn($existingContact);

        $this->emailService
            ->shouldReceive('isUniqueForContact')
            ->with('existing@example.com', $contactId)
            ->once()
            ->andReturn(false);

        $this->expectException(EmailAlreadyExistsException::class);

        $this->useCase->execute($dto);
    }
}
