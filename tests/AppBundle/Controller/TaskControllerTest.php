<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 17/06/2017
 * Time: 23:00
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{

    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'TITRE';
        $form['task[content]'] = 'contenu';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    }

    public function testEditTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $em = $client->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Task')->findOneBy(array(),array('id' => 'desc'));
        $crawler = $client->request('GET', '/tasks/'.$repo->getId().'/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'TITRE modifier';
        $form['task[content]'] = 'contenu modifier';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    }

    public function testToggleTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $em = $client->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Task')->findOneBy(array(),array('id' => 'desc'));

        $client->request('GET', '/tasks/'.$repo->getId().'/toggle');


        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    }

    public function testDeleteTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $em = $client->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:Task')->findOneBy(array(),array('id' => 'desc'));

        $client->request('POST', '/tasks/'.$repo->getId().'/delete');

        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));
    }
}
