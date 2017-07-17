<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 17/06/2017
 * Time: 23:02
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\HelperTrait;

class UserControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testListWithoutLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testIndexWithErrorUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testListWithUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ));
        $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testListWithAdmin()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste Utilisateurs', $client->getResponse()->getContent());
    }

    public function testCreateUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = self::getUniqId();
        $form['user[password][first]'] = self::getUniqId();
        $form['user[password][second]'] = self::getUniqId();
        $form['user[email]'] = self::getUniqId().'@test.fr';
        $form['user[roles]'] = 'ROLE_USER';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));

        $crawler = $client->request('GET', '/users');
        $filter = $crawler->filter('.table > tbody > tr:last-child > td')->getNode(0)->nodeValue; // On recupere dans le DOM le nom d'utilisateur du dernier utilisateur ajouté

        $this->assertEquals(self::getUniqId(), $filter); // On test que l'utilisateur a bien été ajouté
    }

    public function testEditUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $em = $client->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:User')->findOneBy(array('username' => self::getUniqId()));

        $crawler = $client->request('GET', '/users/' . $repo->getId() . '/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = self::getUniqIdBis();
        $form['user[password][first]'] = self::getUniqIdBis();
        $form['user[password][second]'] = self::getUniqIdBis();
        $form['user[email]'] = self::getUniqIdBis() . '@modif.fr';
        $form['user[roles]'] = 'ROLE_ADMIN';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/users'));

        $crawler = $client->request('GET', '/users');
        $filter = $crawler->filter('.table > tbody > tr:last-child > td')->getNode(0)->nodeValue; // On recupere dans le DOM le nom d'utilisateur du dernier utilisateur modifié

        $this->assertEquals(self::getUniqIdBis(), $filter); // On test que le nom d'utilisateur a bien été modifier
    }

    public function testConnectWithEditUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => self::getUniqIdBis(),
            'PHP_AUTH_PW'   => self::getUniqIdBis(),
        ));
        $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
