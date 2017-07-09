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

class UserControllerTest extends WebTestCase
{
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
        $form['user[username]'] = 'phpunit';
        $form['user[password][first]'] = 'phpunit';
        $form['user[password][second]'] = 'phpunit';
        $form['user[email]'] = 'phpunit@phpunit.fr';
        $form['user[roles]'] = 'ROLE_USER';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    }

    public function testEditUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $em = $client->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:User')->findOneBy(array(),array('id' => 'desc'));

        $crawler = $client->request('GET', '/users/'.$repo->getId().'/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'modif';
        $form['user[password][first]'] = 'modif';
        $form['user[password][second]'] = 'modif';
        $form['user[email]'] = 'modif@modif.fr';
        $form['user[roles]'] = 'ROLE_ADMIN';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/users'));
    }
}