<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Event\RouteCollectionEvent;

class FixRouteNamesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Luister naar het routing-event dat getriggerd wordt bij het opbouwen van de routes
        return [
            'kernel.route_collection' => 'onRouteCollection',
        ];
    }

    public function onRouteCollection(RouteCollectionEvent $event): void
    {
        $routes = $event->getRouteCollection();

        foreach ($routes->all() as $name => $route) {
            $path = $route->getPath();

            // Check of het pad van de route eindigt op 'ss'
            if (str_ends_with($path, 'ss')) {
                // Haal de laatste 's' weg
                $newPath = substr($path, 0, -1);
                $route->setPath($newPath);
            }
        }
    }
}
