<?php

namespace App\Fixtures;

use App\Entity\User;
use App\Fixtures\Processors\UserProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;

class FixturesService
{
    private EntityManagerInterface $em;
    private UserProcessor $userProcessor;
    private NativeLoader $loader;

    public function __construct(
        EntityManagerInterface $em,
        UserProcessor          $userProcessor,
    )
    {
        $this->em = $em;
        $this->userProcessor = $userProcessor;
        $this->loader = new NativeLoader();
    }

    public function generate(): void
    {
        $objectSet = $this->loader->loadFile(__DIR__ . '/fixtures.yml');

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof User) {
                $this->userProcessor->preProcess($object);
            }
            $this->em->persist($object);
        }
        $this->em->flush();
    }
}