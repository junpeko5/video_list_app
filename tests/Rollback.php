<?php
namespace App\Tests;

trait Rollback {
    public function setUp()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jw@symf4.loc',
            'PHP_AUTH_PW' => 'passw',
        ]);
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function tearDown()
    {
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
