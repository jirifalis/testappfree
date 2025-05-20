<?php declare(strict_types=1);

namespace App\Entity\ACL;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'groups')]
class Group implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100, unique: true)]
    private string $name;

    #[ORM\OneToMany(targetEntity: GroupPermission::class, mappedBy: "group", fetch: "EAGER")]
    private Collection $permissions;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->permissions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * @return Collection<int, GroupPermission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(GroupPermission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }
        return $this;
    }

    public function removePermission(GroupPermission $permission): self
    {
        $this->permissions->removeElement($permission);
        return $this;
    }

    public function getPermissionByResource(Resource $resource): ?GroupPermission
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getResource() === $resource) {
                return $permission;
            }
        }
        return null;
    }

    public function hasResource(Resource $resource): bool
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getResource() === $resource) {
                return true;
            }
        }
        return false;
    }

    public function removePermissionByResource(Resource $resource): self
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getResource() === $resource) {
                $this->permissions->removeElement($permission);
            }
        }
        return $this;
    }

    public function jsonSerialize(): array
    {
        $permissions = $this->permissions->toArray();
        $extra = implode(', ', array_map(function ($group) {
            return $group->getResource()->getName();
        }, $permissions));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => $permissions,
            'extra' => $extra
        ];
    }
}