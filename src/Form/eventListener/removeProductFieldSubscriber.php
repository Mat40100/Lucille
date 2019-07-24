<?php


namespace App\Form\eventListener;

use App\Form\ProductType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class removeProductFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event)
    {
        $orphan = $event->getData();
        $form = $event->getForm();

        if (null !== $orphan->getId()) {
            $form->remove('product');
        }
    }

}