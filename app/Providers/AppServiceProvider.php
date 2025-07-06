<?php

namespace App\Providers;

use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Services\Contact\PhoneServiceInterface;
use App\Contracts\UseCases\Contact\CreateContactUseCaseInterface;
use App\Contracts\UseCases\Contact\UpdateContactUseCaseInterface;
use App\Contracts\UseCases\Contact\DeleteContactUseCaseInterface;
use App\Contracts\UseCases\Contact\FindContactUseCaseInterface;
use App\Contracts\UseCases\Contact\ListContactsUseCaseInterface;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use App\Repositories\Eloquent\ContactRepository;
use App\Services\Contact\EmailValidationService;
use App\Services\Contact\PhoneService;
use App\UseCases\Contact\CreateContactUseCase;
use App\UseCases\Contact\UpdateContactUseCase;
use App\UseCases\Contact\DeleteContactUseCase;
use App\UseCases\Contact\FindContactUseCase;
use App\UseCases\Contact\ListContactsUseCase;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
                // Repository bindings
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);

        // Service bindings
        $this->app->singleton(PhoneServiceInterface::class, PhoneService::class);
        $this->app->bind(EmailValidationServiceInterface::class, EmailValidationService::class);

        // Use Case bindings
        $this->app->bind(CreateContactUseCaseInterface::class, CreateContactUseCase::class);
        $this->app->bind(UpdateContactUseCaseInterface::class, UpdateContactUseCase::class);
        $this->app->bind(DeleteContactUseCaseInterface::class, DeleteContactUseCase::class);
        $this->app->bind(FindContactUseCaseInterface::class, FindContactUseCase::class);
        $this->app->bind(ListContactsUseCaseInterface::class, ListContactsUseCase::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
