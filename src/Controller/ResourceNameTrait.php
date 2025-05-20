<?php declare(strict_types=1);

namespace App\Controller;

use App\Utils\ResourceNameUtils;
use Symfony\Contracts\Service\Attribute\Required;

trait ResourceNameTrait
{

    protected ResourceNameUtils $resourceNameUtils;

    #[Required]
    public function setAclUtils(ResourceNameUtils $resourceNameUtils): void
    {
        $this->resourceNameUtils = $resourceNameUtils;
    }

    public function getResourceName(): string
    {
        return $this->resourceNameUtils->getNameFromClass(get_class($this));
    }
}