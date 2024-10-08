<?php

declare(strict_types=1);

namespace App\Test\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DbWebTestCase extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected KernelBrowser $client;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollback();
        $this->em->close();
        parent::tearDown();
    }
}
