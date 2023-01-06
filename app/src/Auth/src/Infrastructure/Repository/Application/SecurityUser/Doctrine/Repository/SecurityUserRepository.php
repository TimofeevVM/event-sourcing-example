<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository\Application\SecurityUser\Doctrine\Repository;

use Auth\Application\Exception\NotFoundException;
use Auth\Application\Model\UserSecurity;
use Auth\Application\Model\UserSecurityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class SecurityUserRepository implements UserSecurityRepository
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function findByUsername(string $username): UserSecurity
    {
        $qb = new QueryBuilder($this->em->getConnection());
        $user = $qb->from('security_user', 'u')
            ->select(['u.username', 'u.password_hash', 'u.id'])
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->fetchAllAssociative()[0];

        if (isset($user['id']) && isset($user['username']) && isset($user['password_hash'])) {
            return new UserSecurity(
                (string) $user['id'],
                (string) $user['username'],
                (string) $user['password_hash']
            );
        }

        throw new NotFoundException('User not found');
    }
}
