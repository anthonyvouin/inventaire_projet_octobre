<?php

namespace App\Test\Controller;

use App\Entity\Material;
use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MaterialControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MaterialRepository $repository;
    private string $path = '/material/';


  

     protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Material::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }
    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

    }

    
    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Sauvegarder', [
            'material[name]' => 'Testing',
            'material[quantity]' => 16,
        ]);


       self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }


    // public function testShow(): void
    // {
    //     $this->markTestIncomplete();
    //     $fixture = new Material();
    //     $fixture->setName('My Title');
    //     $fixture->setQuantity('My Title');

    //     $this->repository->add($fixture, true);

    //     $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

    //     self::assertResponseStatusCodeSame(200);
    //     self::assertPageTitleContains('Material');

    //     // Use assertions to check that the properties are properly displayed.
    // }

    public function testEdit(): void
    {
        $fixture = new Material();
        $fixture->setName('Title');
        $fixture->setQuantity(800);

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Envoyer', [
            'material[name]' => 'TitleTest',
            'material[quantity]' => 878,
        ]);

        // self::assertResponseRedirects('/material/');

        $fixture = $this->repository->findAll();

        self::assertSame('TitleTest', $fixture[0]->getName());
        self::assertSame(878, $fixture[0]->getQuantity());
    }
  

    public function testRemove(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Material();
        $fixture->setName('TitleTest');
        $fixture->setQuantity(800);

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Supprimer');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
    }
}
