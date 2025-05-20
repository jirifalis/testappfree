<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/resource/alfa")]
class AlfaController extends AbstractController implements ResourceInterface
{
    use ResourceNameTrait;

    #[Route("/{id}")]
    public function index(int $id): JsonResponse
    {
        // ...
        return new JsonResponse(['resource' => $this->getResourceName(), 'id' => $id]);
    }
}