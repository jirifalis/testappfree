<?php declare(strict_types=1);

namespace App\EventListener;

use App\Controller\ResourceInterface;
use App\Service\AccessControl;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: 'kernel.controller')]
readonly class AccessValidation
{
    public function __construct(
        private AccessControl $accessControl,
    ) {
    }

    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (
            is_array($controller) &&
            $controller[0] instanceof ResourceInterface
        ) {
            $resource = $controller[0]->getResourceName();
            if (!$this->accessControl->isAllowed($resource)) {
                $event->setController($this->getForbiddenController());
            }
        }
    }


    public function getForbiddenController(): callable
    {
        return function () {
            return new Response(
                'Resource forbidden',
                Response::HTTP_FORBIDDEN
            );
        };
    }

}
