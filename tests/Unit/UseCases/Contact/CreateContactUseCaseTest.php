<?php

namespace Tests\Unit\UseCases\Contact;

use App\UseCases\Contact\CreateContactUseCase;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Services\Contact\PhoneServiceInterface;
use App\DTOs\CreateContactDto;
use App\Models\Contact;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class CreateContactUseCaseTest extends TestCase
{
    private CreateContactUseCase $useCase;
    private ContactRepositoryInterface|MockInterface $repository;
    private EmailValidationServiceInterface|MockInterface $emailService;
    private PhoneServiceInterface|MockInterface $phoneService;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(ContactRepositoryInterface::class);
        $this->emailService = Mockery::mock(EmailValidationServiceInterface::class);
        $this->phoneService = Mockery::mock(PhoneServiceInterface::class);

        $this->useCase = new CreateContactUseCase(
            $this->repository,
            $this->emailService,
            $this->phoneService
        );
    }

    #[Test]
    public function it_should_create_contact_successfully(): void
    {
        $dto = new CreateContactDto(
            'John Doe',
            'john@example.com',
            '(41) 98899-4422'
        );

        $contact = new Contact([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '41988994422'
        ]);

        $this->emailService
            ->shouldReceive('exists')
            ->with('john@example.com')
            ->once()
            ->andReturn(false);

        $this->phoneService
            ->shouldReceive('normalize')
            ->with('(41) 98899-4422')
            ->once()
            ->andReturn('41988994422');

        $this->repository
            ->shouldReceive('create')
            ->with([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '41988994422'
            ])
            ->once()
            ->andReturn($contact);

        $result = $this->useCase->execute($dto);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertEquals('41988994422', $result->phone);
    }

    #[Test]
    public function it_should_throw_exception_when_email_already_exists(): void
    {
        $dto = new CreateContactDto(
            'John Doe',
            'existing@example.com',
            '(41) 98899-4422'
        );

        $this->emailService
            ->shouldReceive('exists')
            ->with('existing@example.com')
            ->once()
            ->andReturn(true);

        $this->expectException(EmailAlreadyExistsException::class);

        $this->useCase->execute($dto);
    }

    #[Test]
    public function it_should_normalize_phone_before_saving(): void
    {
        $dto = new CreateContactDto(
            'John Doe',
            'john@example.com',
            '+55 (41) 98899-4422'
        );

        $contact = new Contact([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '5541988994422'
        ]);

        $this->emailService
            ->shouldReceive('exists')
            ->with('john@example.com')
            ->once()
            ->andReturn(false);

        $this->phoneService
            ->shouldReceive('normalize')
            ->with('+55 (41) 98899-4422')
            ->once()
            ->andReturn('5541988994422');

        $this->repository
            ->shouldReceive('create')
            ->with([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '5541988994422'
            ])
            ->once()
            ->andReturn($contact);

        $result = $this->useCase->execute($dto);

        $this->assertEquals('5541988994422', $result->phone);
    }
}
