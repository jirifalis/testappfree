<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\AclRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index(): JsonResponse
    {
        return $this->json(['index']);
    }

    #[Route("/test")]
    public function test(AclRepository $acl): JsonResponse
    {
        $x = $acl->findAccessibleResourcesByUserId(1);

        var_dump($x);
        exit;
        return $this->json([
            'message' => 'TEST',
            'status' => 'OK',
        ]);
    }

    #[Route("/users")]
    public function users(UserRepository $ur): JsonResponse
    {
        $ur->findAll();
        return $this->json([
            'message' => 'TEST',
            'status' => 'OK',
        ]);
    }
}
