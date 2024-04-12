<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[date]' => 'Testing',
            'reservation[totalprix]' => 'Testing',
            'reservation[nbinvite]' => 'Testing',
            'reservation[idClient]' => 'Testing',
            'reservation[idEvenement]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDate('My Title');
        $fixture->setTotalprix('My Title');
        $fixture->setNbinvite('My Title');
        $fixture->setIdClient('My Title');
        $fixture->setIdEvenement('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDate('Value');
        $fixture->setTotalprix('Value');
        $fixture->setNbinvite('Value');
        $fixture->setIdClient('Value');
        $fixture->setIdEvenement('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[date]' => 'Something New',
            'reservation[totalprix]' => 'Something New',
            'reservation[nbinvite]' => 'Something New',
            'reservation[idClient]' => 'Something New',
            'reservation[idEvenement]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getTotalprix());
        self::assertSame('Something New', $fixture[0]->getNbinvite());
        self::assertSame('Something New', $fixture[0]->getIdClient());
        self::assertSame('Something New', $fixture[0]->getIdEvenement());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDate('Value');
        $fixture->setTotalprix('Value');
        $fixture->setNbinvite('Value');
        $fixture->setIdClient('Value');
        $fixture->setIdEvenement('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/reservation/');
        self::assertSame(0, $this->repository->count([]));
    }
}
