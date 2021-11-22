<?php

namespace App\EntityManager;

use Doctrine\ORM\EntityManager;

class Transaction
{
    public static function beginTransaction()
    {
        $em = self::getEntityManager();
        $em->beginTransaction();
    }

    public static function commit()
    {
        $em = self::getEntityManager();
        $em->commit();
    }

    public static function rollback()
    {
        $em = self::getEntityManager();
        $em->rollback();
    }

    private static function getEntityManager(): EntityManager
    {
        global $app;

        $container = $app->getContainer();

        return $container->get('doctrine.orm.entity_manager');
    }
}