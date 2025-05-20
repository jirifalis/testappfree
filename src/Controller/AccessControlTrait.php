<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessControl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

trait AccessControlTrait
{

    private const string FORBIDDEN_MESSAGE = 'Forbidden';
    private const int FORBIDDEN_CODE = 403;

    protected AccessControl $accessControl;

    #[Required]
    public function setAccessControl(AccessControl $accessControl): void
    {
        $this->accessControl = $accessControl;
    }

    public function isAllowed(string $resource): bool
    {
        return $this->accessControl->isAllowed($resource);
    }

    protected function forbiddenJsonResponse(string $message = self::FORBIDDEN_MESSAGE): JsonResponse
    {
        return new JsonResponse($message, self::FORBIDDEN_CODE);
    }

    protected function forbiddenResponse(string $message = self::FORBIDDEN_MESSAGE): Response
    {
        return new Response($message, self::FORBIDDEN_CODE);
    }

}