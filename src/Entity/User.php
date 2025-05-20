<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\ACL\Group;
use App\Entity\ACL\UserGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    /**
     * @var Collection<int, UserGroup>
     */
    #[ORM\OneToMany(targetEntity: UserGroup::class, mappedBy: 'user')]
    #[ORM\JoinTable(name: 'users_groups')]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function hasGroup(Group $group): bool
    {
        foreach ($this->groups as $g) {
            if ($g->getGroup()->getId() == $group->getId()) {
                return true;
            }
        }
        return false;
    }

    public function addGroup(UserGroup $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    public function removeGroup(UserGroup $group): static
    {
        $this->groups->removeElement($group);
        return $this;
    }


    public function jsonSerialize(): array
    {
        $groups = array_map(function (UserGroup $userGroup) {
            return [
                'id' => $userGroup->getGroup()->getId(),
                'name' => $userGroup->getGroup()->getName()
            ];
        }, $this->groups->toArray());


        $extra = implode(', ', array_map(function ($group) {
            return $group['name'];
        }, $groups));

        return [
            'id' => $this->id,
            'name' => $this->username,
            'extra' => $extra,
            'groups' => $groups,
        ];
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
