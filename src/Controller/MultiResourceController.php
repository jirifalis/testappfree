<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/resource/multi")]
class MultiResourceController extends AbstractController
{

    use AccessControlTrait;

    #[Route("/{id}")]
    public function index(int $id): JsonResponse
    {
        if (!$this->isAllowed('alfa') ||
            !$this->isAllowed('beta')) {
            return $this->forbiddenJsonResponse();
        }

        // ... do some stuff

        return new JsonResponse(['resource' => 'multi', 'id' => $id]);
    }
}