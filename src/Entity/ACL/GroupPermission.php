<?php declare(strict_types=1);

namespace App\Entity\ACL;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'permissions')]
class GroupPermission implements JsonSerializable
{

    public const string PERMISSION = 'permission';
    public const string RESTRICTION = 'restriction';

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Group::class)]
    #[ORM\JoinColumn(name: "group_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Group $group;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Resource::class)]
    #[ORM\JoinColumn(name: "resource_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Resource $resource;

    #[ORM\Column(length: 100)]
    private string $permission;


    public function __construct()
    {
        $this->permission = self::RESTRICTION;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getResource(): Resource
    {
        return $this->resource;
    }

    public function setResource(Resource $resource): self
    {
        $this->resource = $resource;
        return $this;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function setPermission(string $permission): self
    {
        if ($permission !== self::PERMISSION &&
            $permission !== self::RESTRICTION) {
            throw new InvalidArgumentException('Invalid permission');
        }

        $this->permission = $permission;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'resource' => $this->resource->getId(),
            'access' => $this->permission
        ];
    }
}