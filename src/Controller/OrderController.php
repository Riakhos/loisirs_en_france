<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Partner;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    private Cart $cart;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, Cart $cart)
    {
        $this->em = $em;
        $this->cart = $cart;
    }

    /**
     * 1ère Étape du tunnel d'achat
     * chooseDateTime()
     * fonction pour passer une commande
     * 
     * @param Request $request
     * @return Response
     */
    #[Route('/commande/date-et-heure', name: 'app_order_date_time')]
    public function chooseDateTime(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour passer une commande.'
            );
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les produits du panier (Activités, Événements, Offres)
        $products = $this->cart->getCart();
        if (empty($products)) {
            $this->addFlash(
                'warning',
                'Votre panier est vide.'
            );
            return $this->redirectToRoute('app_cart');
        }
        
        // Générer les détails de la commande AVANT d'afficher le formulaire
        $orderDetails = [];
        $subtotal = 0;
        $tvaDetails = [];

        foreach ($products as &$product) {
            $priceTTC = $product['object']->getPrice(); // Prix TTC
            $tvaRate = $product['object']->getTva(); // TVA en %
            $qty = $product['qty'];
        
            // Calcul du prix HT
            $priceHT = $priceTTC / (1 + ($tvaRate / 100));
        
            // Calcul du sous-total HT
            $subtotalHT = $priceHT * $qty;

            // Ajout au total HT global
            $subtotal += $subtotalHT;
        
            // Calcul du montant de TVA
            $tvaAmount = ($priceTTC - $priceHT) * $qty;
        
            // Stocker les montants de TVA par taux
            if (!isset($tvaDetails[$tvaRate])) {
                $tvaDetails[$tvaRate] = 0;
            }
            $tvaDetails[$tvaRate] += $tvaAmount;
        
            // Ajout des détails au produit
            $product['priceHT'] = $priceHT;
            $product['subtotalHT'] = $subtotalHT;
            $product['tvaAmount'] = $tvaAmount;
        }
        
        // Total TVA et total TTC
        $totalTva = array_sum($tvaDetails);
        $totalHT = $subtotal;
        $totalTTC = $totalHT + $totalTva;
        
        $orderDetails = [
            'subtotalHT' => $totalHT,
            'tvaDetails' => $tvaDetails,
            'totalTva' => $totalTva,
            'totalTTC' => $totalTTC,
        ];

        // Stockage dans la session
        $session = $request->getSession();
        $session->set('orderDetails', $orderDetails);
        
        // Initialiser les données de commande
        $orderData = [
            'items' => [],
            'activityName' => [],
            'offerName' => [],
            'eventName' => [],
            'partnerName' => [],
            'cartPrice' => 0
        ];

        // Parcourir les produits du panier
        foreach ($products as $product) {
            $orderData['cartPrice'] += $product['object']->getPrice() * $product['qty'];

            // Récupérer les différentes informations produits pour l'ordre
            $name = !empty($product['object']->getName()) ? $product['object']->getName() : null;

            // Récupération des données du partenaire associé
            $partner = $this->em->getRepository(Partner::class)->find($product['object']->getPartners()->getId());
            
            // Ajouter les informations du produit dans le tableau $orderData
            $orderData['items'][] = [
                'itemId' => $product['object']->getId(),
                'name' => $product['object']->getName(),
                'dateStart' => $product['dateStart'] ?? null,
                'time' => $product['time'] ?? null,
                'activityName' => ($product['type'] === 'activity') ? $name : null,
                'eventName' => ($product['type'] === 'event') ? $name : null,
                'offerName' => ($product['type'] === 'offer') ? $name : null,
                'activityImage' => method_exists($product['object'], 'getImage') ? $product['object']->getImage() : '',
                'eventImage' => method_exists($product['object'], 'getImage') ? $product['object']->getImage() : '',
                'offerImage' => method_exists($product['object'], 'getImage') ? $product['object']->getImage() : '',
                'activityQuantity' => ($product['type'] === 'activity') ? $product['qty'] : 0,
                'eventQuantity' => ($product['type'] === 'event') ? $product['qty'] : 0,
                'offerQuantity' => ($product['type'] === 'offer') ? $product['qty'] : 0,
                'activityPrice' => ($product['type'] === 'activity') ? $product['object']->getPrice() : 0,
                'eventPrice' => ($product['type'] === 'event') ? $product['object']->getPrice() : 0,
                'offerPrice' => ($product['type'] === 'offer') ? $product['object']->getPrice() : 0,
                'activityTva' => ($product['type'] === 'activity') ? $product['object']->getTva() : 0,
                'eventTva' => ($product['type'] === 'event') ? $product['object']->getTva() : 0,
                'offerTva' => ($product['type'] === 'offer') ? $product['object']->getTva() : 0,
                'partnerAddress' => $partner ? $partner->getAddress() : 'Adresse inconnue',
                'partnerCity' => $partner ? $partner->getCity() : 'Ville inconnue',
                'partnerName' => $partner ? $partner->getName() : 'Nom inconnu',
                'partnerPostal' => $partner ? $partner->getPostal() : 'Code postal inconnu',
                'partnerWebsite' => $partner ? $partner->getWebsite() : 'Website inconnu',
                'partnerPhone' => $partner ? $partner->getPhone() : 'Téléphone inconnu',
                'partnerEmail' => $partner ? $partner->getEmail() : 'Adresse mail inconnu',
            ];
        }

        // Créer le formulaire avec les données préparées
        $orderForm = $this->createForm(OrderType::class, $orderData);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $dateAndTime = $orderForm->getData();  // Récupérer la date et l'heure sélectionnées

            // Créer la commande (l'order)
            $order = new Order();
            $order
                ->setUser($this->getUser())
                ->setCreateAt(new \DateTime())
                ->setState(1)
                ->setCartPrice($orderData['cartPrice'])
                ->setActivityName(implode(', ', array_filter(array_map(function($product) {
                    return ($product['type'] === 'activity') ? $product['object']->getName() : null;
                }, $products))))
                ->setOfferName(implode(', ', array_filter(array_map(function($product) {
                    return ($product['type'] === 'offer') ? $product['object']->getName() : null;
                }, $products))))
                ->setEventName(implode(', ', array_filter(array_map(function($product) {
                    return ($product['type'] === 'event') ? $product['object']->getName() : null;
                }, $products))))
                ->setPartnerName(implode(', ', array_filter(array_map(function($product) {
                    $partner = $this->em->getRepository(Partner::class)->find($product['object']->getPartners()->getId());
                    return $partner ? $partner->getName() : null;
                }, $products))))
            ;
            
            foreach ($products as $product) {
                $orderDetail = new OrderDetail();

                 // On vérifie si 'dateStart' existe, sinon on lui attribue la date actuelle
                $dateStart = $product['dateStart'] ?? new \DateTime();
                $time = $product['time'] ?? null;
                
                $orderDetail
                    ->setItemId($product['object']->getId())
                    ->setDateStart($dateStart)
                    ->setTime($time)
                    ->setPartnerName($product['object']->getPartners()->getName())
                    ->setPartnerAddress($product['object']->getPartners()->getAddress())
                    ->setPartnerCity($product['object']->getPartners()->getCity())
                    ->setPartnerPostal($product['object']->getPartners()->getPostal())
                    ->setPartnerWebsite($product['object']->getPartners()->getWebsite())
                    ->setPartnerPhone($product['object']->getPartners()->getPhone())
                    ->setPartnerEmail($product['object']->getPartners()->getEmail())
                    
                    ->setMyOrder($order)
                ;
    
                 // Vérifier le type de produit (activité, événement ou offre)
                if ($product['type'] === 'activity' && isset($product['object'])) {
                    $orderDetail
                        ->setActivityName($product['object']->getName())
                        ->setActivityImage($product['object']->getImage())
                        ->setActivityPrice($product['object']->getPrice())
                        ->setActivityTva($product['object']->getTva())
                        ->setActivityQuantity($product['qty'])
                    ;
                } elseif ($product['type'] === 'event' && isset($product['object'])) {
                    $orderDetail
                        ->setEventName($product['object']->getName())
                        ->setEventImage($product['object']->getImage())
                        ->setEventPrice($product['object']->getPrice())
                        ->setEventTva($product['object']->getTva())
                        ->setEventQuantity($product['qty'])
                    ;
                } elseif ($product['type'] === 'offer' && isset($product['object'])) {
                    $orderDetail
                        ->setOfferName($product['object']->getName())
                        ->setOfferImage($product['object']->getImage())
                        ->setOfferPrice($product['object']->getPrice())
                        ->setOfferTva($product['object']->getTva())
                        ->setOfferQuantity($product['qty'])
                    ;
                }
                
                // Ajouter l'OrderDetail à la commande
                $order->addOrderDetail($orderDetail);
            }

            $this->em->persist($order);
            $this->em->flush();

            // Stocker le formulaire dans la session
            $session = $request->getSession();
            $session->set('orderForm', $orderForm->getData());

            return $this->redirectToRoute('app_order_summary', ['id' => $order->getId()]);
        }

        return $this->render('order/order.html.twig', [
            'controller_name' => 'Passer une commande',
            'orderForm' => $orderForm->createView(),
            'products' => $products,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * 2ème Étape du tunnel d'achat : Récapitulatif + Validation finale
     * orderSummaryAndConfirm()
     * fonction pour résumer la commande et l'enregistrer
     * 
     * @param Cart $cart
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/commande/recapitulatif-et-validation', name: 'app_order_summary')]
    public function orderSummaryAndConfirm(Cart $cart, Request $request, EntityManagerInterface $em): Response
    {
        /** @var Order $order */
        $order = $em->getRepository(Order::class)->find($request->get('id'));

        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('app_home');
        }

        // Récupérer les orderDetails depuis la session
        $session = $request->getSession();
        $orderDetails = $session->get('orderDetails');

        // Controller de la deuxième étape
        $session = $request->getSession();
        $orderData = $session->get('orderForm');

        // Recréer le formulaire avec les données stockées
        $orderForm = $this->createForm(OrderType::class, $orderData);

        $products = $cart->getCart();

        return $this->render('order/summary.html.twig', [
            'controller_name' => 'Confirmer ma commande',
            'products' => $products,
            'order' => $order,
            'total' => $cart->getTotal(),
            'orderForm' => $orderForm->createView(),
            'orderDetails' => $orderDetails,
        ]);
    }
}