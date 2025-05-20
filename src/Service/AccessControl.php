<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\AclRepository;
use App\Security\Security;

class AccessControl
{

    private array $allowedResources = [];

    public function __construct(
        private readonly Security $security,
        private readonly AclRepository $aclRepository
    ) {
        $user = $this->security->getUser();
        $this->allowedResources = $user === null ? [] :
            $aclRepository->findAccessibleResourcesByUserId($user->getId());
    }

    public function isAllowed(string $resource): bool
    {
        return in_array($resource, $this->allowedResources);
    }

    public function getAccessibleResources(): array
    {
        return $this->allowedResources;
    }

    public function getAccessibleResourcesByUser(User $user): array
    {
        return $this->aclRepository
            ->findAccessibleResourcesByUserId($user->getId());
    }

    public function toString(): string
    {
        return implode(',', $this->allowedResources);
    }
}
