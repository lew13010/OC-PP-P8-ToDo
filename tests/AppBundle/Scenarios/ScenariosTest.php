<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 17/07/2017
 * Time: 17:54
 */

namespace Tests\AppBundle\Scenarios;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\HelperTrait;

class ScenariosTest extends WebTestCase
{
    use HelperTrait;

    public function testScenarios()
    {
        // Creation d'un client
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        // Recuperation de l'entity manager
        $em = $client->getContainer()->get('doctrine')->getManager();

        // Creation d'un utilisateur
        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = self::getUniqId();
        $form['user[password][first]'] = self::getUniqId();
        $form['user[password][second]'] = self::getUniqId();
        $form['user[email]'] = self::getUniqId().'@test.fr';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));

        // Creation d'un client avec les nouveaux identifiants
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => self::getUniqId(),
            'PHP_AUTH_PW'   => self::getUniqId(),
        ));
        
        // Creation d'une tache
        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form(); 
        $form['task[title]'] = 'Tache Scenario';
        $form['task[content]'] = 'contenu';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));

        // Recuperation de l'utilisateur en BDD
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => self::getUniqId()));

        // Modification de l'utilisateur
        $crawler = $client->request('GET', '/users/'.$user->getId().'/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = self::getUniqIdBis();
        $form['user[password][first]'] = self::getUniqIdBis();
        $form['user[password][second]'] = self::getUniqIdBis();
        $form['user[email]'] = self::getUniqIdBis().'@test.fr';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/users'));

        // Changement de client avec les identifiants modifiés
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => self::getUniqIdBis(),
            'PHP_AUTH_PW'   => self::getUniqIdBis(),
        ));

        // Reccuperation de la tache
        $task = $em->getRepository('AppBundle:Task')->findOneBy(array('title' => 'Tache Scenario'));

        // Modification d'une tache
        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'TITRE modifier';
        $form['task[content]'] = 'contenu modifier';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));

        // Supression de la tache modifié
        $task = $em->getRepository('AppBundle:Task')->findOneBy(array('title' => 'TITRE modifier'));
        $client->request('POST', '/tasks/'.$task->getId().'/delete');
        $this->assertTrue($client->getResponse()->isRedirect('/tasks'));

        // Suppression de l'utilisateur 'TEST'
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => self::getUniqIdBis()));
        $em->remove($user);
        $em->flush();
    }
}