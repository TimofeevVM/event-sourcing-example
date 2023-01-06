<?php

declare(strict_types=1);

namespace Tests\Auth\Domain\User\Model;

use Auth\Domain\User\Event\UserEmailWasChanged;
use Auth\Domain\User\Event\UserPasswordHashWasChanged;
use Auth\Domain\User\Event\UserWasRegistered;
use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserEmail;
use Auth\Domain\User\Model\Username;
use Auth\Domain\User\Model\UserPasswordHash;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegister(): void
    {
        $user = User::register(
            Username::fromString('somebody'),
            UserEmail::fromString('somebody@gmail.com'),
            UserPasswordHash::fromString('123456'),
        );

        $events = $user->pullEvents();
        $this->assertCount(1, $events);

        /** @var UserWasRegistered $userWasCreated */
        $userWasCreated = $events->current();
        $this->assertInstanceOf(UserWasRegistered::class, $userWasCreated);
        $this->assertSame($user->id()->value(), $userWasCreated->getAggregateId());
        $this->assertSame('somebody', $userWasCreated->username);
        $this->assertSame('somebody@gmail.com', $userWasCreated->email);
        $this->assertSame('123456', '123456');
    }

    public function testChangeEmail(): void
    {
        $user = User::register(
            Username::fromString('somebody'),
            UserEmail::fromString('somebody@gmail.com'),
            UserPasswordHash::fromString('123456'),
        );

        $user->pullEvents();

        $user->changeEmail(
            UserEmail::fromString('new-email@gmail.com')
        );

        /** @var UserEmailWasChanged $userWasEmailChanged */
        $userWasEmailChanged = $user->pullEvents()->current();
        $this->assertSame($user->id()->value(), $userWasEmailChanged->getAggregateId());
        $this->assertInstanceOf(UserEmailWasChanged::class, $userWasEmailChanged);
        $this->assertSame('new-email@gmail.com', $userWasEmailChanged->email);
    }

    public function testChangePassword(): void
    {
        $user = User::register(
            Username::fromString('somebody'),
            UserEmail::fromString('somebody@gmail.com'),
            UserPasswordHash::fromString('123456'),
        );

        $user->pullEvents();

        $user->changePasswordHash(
            UserPasswordHash::fromString('qwerty')
        );

        /** @var UserPasswordHashWasChanged $userWasPasswordHashChanged */
        $userWasPasswordHashChanged = $user->pullEvents()->current();
        $this->assertSame($user->id()->value(), $userWasPasswordHashChanged->getAggregateId());
        $this->assertInstanceOf(UserPasswordHashWasChanged::class, $userWasPasswordHashChanged);
        $this->assertSame('qwerty', $userWasPasswordHashChanged->passwordHash);
    }
}
