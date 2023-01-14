<?php

declare(strict_types=1);

namespace Auth\Domain\User\Model;

use Auth\Domain\User\Event\UserEmailWasChanged;
use Auth\Domain\User\Event\UserPasswordHashWasChanged;
use Auth\Domain\User\Event\UserWasRegisteredV2;
use Shared\Domain\Aggregate\Aggregate;
use Shared\Domain\Aggregate\AggregateEventable;
use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\Aggregate\AggregateReconstructable;
use Shared\Domain\Aggregate\AggregateRoot;

class User extends AggregateRoot implements Aggregate, AggregateEventable, AggregateReconstructable
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected Username $username;
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected UserEmail $email;
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected UserPasswordHash $passwordHash;

    public static function register(
        Username $username,
        UserEmail $email,
        UserPasswordHash $passwordHash,
    ): User {
        $user = new self(UserId::random());

        $user->recordAndApply(
            new UserWasRegisteredV2(
                $user->id()->value(),
                $username->value(),
                $email->value(),
                $passwordHash->value(),
                ['member'],
            )
        );

        return $user;
    }

    protected function onUserWasRegisteredV2(UserWasRegisteredV2 $event): void
    {
        $this->id = UserId::fromString($event->getAggregateId());
        $this->username = Username::fromString($event->username);
        $this->email = UserEmail::fromString($event->email);
        $this->passwordHash = UserPasswordHash::fromString($event->passwordHash);
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function changeEmail(UserEmail $email): void
    {
        $this->recordAndApply(
            new UserEmailWasChanged(
                $this->id()->value(),
                $email->value(),
            )
        );
    }

    protected function onUserEmailWasChanged(UserEmailWasChanged $event): void
    {
        $this->email = new UserEmail($event->email);
    }

    public function changePasswordHash(UserPasswordHash $passwordHash): void
    {
        $this->recordAndApply(
            new UserPasswordHashWasChanged(
                $this->id()->value(),
                $passwordHash->value(),
            )
        );
    }

    protected function onUserPasswordHashWasChanged(UserPasswordHashWasChanged $event): void
    {
        $this->passwordHash = new UserPasswordHash($event->passwordHash);
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPasswordHash(): UserPasswordHash
    {
        return $this->passwordHash;
    }
}
