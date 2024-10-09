<?php

declare(strict_types=1);

namespace App\Security\Listener;

use App\Security\Attribute\SecuredEndpoint;
use App\Service\JWT\JWTValidationService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use ReflectionObject;

class SecurityListener
{
    public function __construct(
        private readonly JWTValidationService $JWTValidationService,
        private readonly RouterInterface $router,
    ){}

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        $args = $event->getController();

        if (is_array($args)) {
            $controller = $args[0] ?? null;
            $method     = $args[1] ?? null;
        } else {
            return;
        }

        if (!$controller || !$method) {
            return;
        }

        $controllerReflection = new ReflectionObject($controller);
        $attributes  =   $controllerReflection->getAttributes();

        $response = new RedirectResponse($this->router->generate('error'));
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === SecuredEndpoint::class) {
                $authorizationToken = ltrim(explode(':', $request->headers->get('Authorization'))[1]);
                if (!$this->JWTValidationService->validate($authorizationToken)) {
                    $event->setController(fn () => $response);
                }
                $request->attributes->set('authorization_token', $authorizationToken);
            }
        }
    }
}
