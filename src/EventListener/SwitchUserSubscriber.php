<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SwitchUserSubscriber implements EventSubscriberInterface
{
    public function onSwitchUser(SwitchUserEvent $event): void
    {
//        $request = $event->getRequest();
//
//        if ($request->hasSession() && ($session = $request->getSession())) {
//            $session->set(
//                '_locale',
//                // assuming your User has some getLocale() method
//                $event->getTargetUser()->getLocale()
//            );
//        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // constant for security.switch_user
            SecurityEvents::SWITCH_USER => 'onSwitchUser',
        ];
    }
}