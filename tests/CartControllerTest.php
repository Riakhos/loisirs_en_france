<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends WebTestCase
{
	public function testAddActivityToCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Simuler l'ajout d'une activité au panier
		$activityId = 1; // ID d'une activité existante
		$client->request('GET', '/cart/add/activity/' . $activityId);

		// Vérifier que la réponse est une redirection (code 302)
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

		// Vérifier que le message flash "success" est défini
		$flashBag = $client->getContainer()->get('session')->getFlashBag();
		$this->assertTrue($flashBag->has('success'));
		$this->assertContains("L'activité a été correctement ajoutée à votre panier.", $flashBag->get('success')[0]);

		// Vérifier que l'activité est bien dans le panier
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count(), "Le panier devrait contenir des articles.");
	}

	public function testAddOfferToCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Nous devons créer un mock ou une offre en base de données pour que la requête fonctionne
		// Pour simplifier, nous supposerons que l'offre avec ID 1 existe
		$offerId = 1;

		// Simuler une requête POST (ou GET si votre action fonctionne avec GET)
		$client->request('GET', '/cart/add/offer/' . $offerId);

		// Vérifiez si la réponse est correcte (code 3xx indique une redirection)
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND); // Code 302 ou 303 pour une redirection

		// Vérifier si un message flash "success" est défini
        $flashBag = $client->getContainer()->get('session')->getFlashBag();

        // Vérifier si le message flash de type 'success' existe
        $this->assertTrue($flashBag->has('success'));
        
        // Vérifier le contenu exact du message flash
        $this->assertContains("L'offre spéciale a été correctement ajoutée à votre panier.", $flashBag->get('success')[0]);

		// Vérifier que l'offre est bien dans le panier (ici, on vérifie simplement que le panier contient un élément)
		$crawler = $client->request('GET', '/mon-panier'); // Page du panier
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count(), "Le panier devrait contenir des articles.");
	}

	public function testAddExclusiveToCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Simuler l'ajout d'une offre exclusive au panier
		$exclusiveId = 1; // ID d'une offre exclusive existante
		$client->request('GET', '/cart/add/exclusive/' . $exclusiveId);

		// Vérifier que la réponse est une redirection (code 302)
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

		// Vérifier que le message flash "success" est défini
		$flashBag = $client->getContainer()->get('session')->getFlashBag();
		$this->assertTrue($flashBag->has('success'));
		$this->assertContains("L'offre exclusive a été correctement ajoutée à votre panier.", $flashBag->get('success')[0]);

		// Vérifier que l'offre exclusive est bien dans le panier
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count(), "Le panier devrait contenir des articles.");
	}

	public function testAddNonExistingOfferToCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Utiliser un ID d'offre inexistant
		$nonExistingOfferId = 999; // Assurez-vous que cet ID n'existe pas dans votre base de données

		// Simuler une requête pour ajouter l'offre inexistante
		$client->request('GET', '/cart/add/offer/' . $nonExistingOfferId);

		// Vérifier que la réponse est une redirection
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

		// Vérifier si un message flash "error" est défini
        $flashBag = $client->getContainer()->get('session')->getFlashBag();
        $this->assertTrue($flashBag->has('error'));

        // Vérifier le contenu exact du message flash
        $this->assertContains("L'offre spéciale avec l'ID 999 n'existe pas.", $flashBag->get('error')[0]);

		// Vérifier que le panier est toujours vide
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertEquals(0, $crawler->filter('div.cart-item')->count(), "Le panier ne devrait pas contenir d'articles.");
	}
		
		public function testRemoveItemFromCart()
	{
		// Créer un client de test
		$client = static::createClient();

		// Ajouter une offre au panier avant de tester la suppression
		$client->request('GET', '/cart/add/offer/1');

		// Vérifier que l'article a été ajouté au panier
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count());

		// Simuler la suppression de l'article
		$client->request('GET', '/cart/decrease/1');

		// Vérifier que l'article a bien été supprimé (ou que sa quantité a été diminuée)
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertEquals(0, $crawler->filter('div.cart-item')->count());
	}

	public function testDecreaseItemQuantityInCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Ajouter une offre au panier avant de tester la diminution
		$client->request('GET', '/cart/add/offer/1');

		// Vérifier que l'article a été ajouté au panier
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count());

		// Simuler la diminution de la quantité de l'offre dans le panier
		$client->request('GET', '/cart/decrease/1');

		// Vérifier que l'article a bien été diminué (ou retiré)
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertEquals(0, $crawler->filter('div.cart-item')->count(), "L'article doit être supprimé du panier.");
	}

	public function testRemoveAllItemsFromCart()
	{
		// Créer un client de test pour simuler une requête HTTP
		$client = static::createClient();

		// Ajouter une offre au panier avant de tester la suppression complète
		$client->request('GET', '/cart/add/offer/1');

		// Vérifier que l'article a été ajouté au panier
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertGreaterThan(0, $crawler->filter('div.cart-item')->count());

		// Simuler la suppression totale du panier
		$client->request('GET', '/cart/remove');

		// Vérifier que le panier est vide
		$crawler = $client->request('GET', '/mon-panier');
		$this->assertEquals(0, $crawler->filter('div.cart-item')->count(), "Le panier doit être vide après suppression.");
	}

}