<?php
namespace App\EventListener;

use App\Entity\Log;
use App\Entity\ProcurementProcedures;
use App\Entity\User;
use App\Entity\WorkzoneEquipment;
use App\Entity\ZoneRepair;
use DateTime;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\OnFlushEventArgs;
use ReflectionClass;

class DatabaseOnFlushListener
{
    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        // We will add methods there

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof ProcurementProcedures) {
                $uow->computeChangeSets();
                $changeSet = $uow->getEntityChangeSet($entity);
                if ($changeSet) {
                    $reflClass = new ReflectionClass(ProcurementProcedures::class);
                    $reader = new AnnotationReader();
                    $properties = $reflClass->getProperties();
                    $logPropArr = [];
                    foreach ($properties as $p){
                        $classAnnotations = $reader->getPropertyAnnotations($p);
                        foreach ($classAnnotations AS $annot) {
                            if ($annot instanceof \App\Annotations\Log) {
                                array_push($logPropArr, $p->name);
                            }
                        }
                    }
                    foreach ($logPropArr as $prop){
                        if(array_key_exists($prop, $changeSet)){
                            if (
                                $changeSet[$prop][0] instanceof DateTime or
                                $changeSet[$prop][1] instanceof DateTime

                            ) {
                                $old = is_null($changeSet[$prop][0]) ? '' : $changeSet[$prop][0]->format('d-m-Y');
                                $new = is_null($changeSet[$prop][1]) ? '' : $changeSet[$prop][1]->format('d-m-Y');
                            }
                            else
                            {
                                $old = $changeSet[$prop][0];
                                $new = $changeSet[$prop][1];
                            }


                            $log = new Log(
                                'ProcurementProcedures',
                                $entity->getId(),
                                '',
                                $old,
                                $new,
                                $prop,
                                $entity->getVersion()
                            );


                            $em->persist($log);
                            $uow->computeChangeSet($em->getClassMetadata(get_class($log)), $log);
                        }
                    }

                }

            }

            if ($entity instanceof WorkzoneEquipment)
            {
                $uow->computeChangeSets();
                // We check if our entity contains some changes
                $changeSet = $uow->getEntityChangeSet($entity);
                if ($changeSet) {
                    dump($changeSet);
                    $v = json_encode($changeSet);
                    dump($v);
                    dump(json_decode($v));

//                    $log = new Log();
//                    $em->persist($log);
//                    $uow->computeChangeSet($em->getClassMetadata(get_class($log)), $log);
                }
            }
        }
//        dd();
    }

    public function saveEntity()
    {

    }

    private function onProcurementProcedures()
    {

    }
}

