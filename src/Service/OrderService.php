<?php

namespace App\Service;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Partner;
use App\Entity\OrderDetail;
use Doctrine\Persistence\Proxy;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private $entityManager;
    private $cart;

    public function __construct(EntityManagerInterface $entityManager, Cart $cart)
    {
        $this->entityManager = $entityManager;
        $this->cart = $cart;
    }

    public function processOrder(array $orderData, $user)
    {
        // dd($orderData);
        $order = new Order();
        $order->setUser($user);
        $order->setCreateAt(new \DateTime());
        $order->setState(0); // 0 = en attente
        $order->setCartPrice($orderData['cartPrice']);
        $order->setActivityName(implode(', ', $orderData['activityName']));
        $order->setOfferName(implode(', ', $orderData['offerName']));
        $order->setEventName(implode(', ', $orderData['eventName']));

        foreach ($orderData['items'] as $itemData) {
            $orderDetail = new OrderDetail();
            $this->mapItemDataToOrderDetail($itemData, $orderDetail, $order);
            $this->entityManager->persist($orderDetail);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Nettoyage du panier
        $this->cart->remove();
    }

    private function mapItemDataToOrderDetail(array $itemData, OrderDetail $orderDetail, Order $order)
    {
        $orderDetail->setItemId($itemData['itemId']);
        $orderDetail->setDateStart($itemData['dateStart']);
        if (!empty($itemData['time'])) {
            $orderDetail->setTime($itemData['time']);
        }
        $orderDetail->setActivityImage($itemData['activityImage']);
        $orderDetail->setEventImage($itemData['eventImage']);
        $orderDetail->setOfferImage($itemData['offerImage']);
        
        // Pour chaque quantité, vérifiez si c'est un tableau avant d'y accéder
        $orderDetail->setActivityQuantity(is_array($itemData['activityQuantity']) ? (int) $itemData['activityQuantity'][0] : (int) $itemData['activityQuantity']);
        $orderDetail->setEventQuantity(is_array($itemData['eventQuantity']) ? (int) $itemData['eventQuantity'][0] : (int) $itemData['eventQuantity']);
        $orderDetail->setOfferQuantity(is_array($itemData['offerQuantity']) ? (int) $itemData['offerQuantity'][0] : (int) $itemData['offerQuantity']);

        // Pour les prix et TVA
        $orderDetail->setActivityPrice(is_array($itemData['activityPrice']) ? (float) $itemData['activityPrice'][0] : (float) $itemData['activityPrice']);
        $orderDetail->setEventPrice(is_array($itemData['eventPrice']) ? (float) $itemData['eventPrice'][0] : (float) $itemData['eventPrice']);
        $orderDetail->setOfferPrice(is_array($itemData['offerPrice']) ? (float) $itemData['offerPrice'][0] : (float) $itemData['offerPrice']);

        $orderDetail->setActivityTva(is_array($itemData['activityTva']) ? (float) $itemData['activityTva'][0] : (float) $itemData['activityTva']);
        $orderDetail->setEventTva(is_array($itemData['eventTva']) ? (float) $itemData['eventTva'][0] : (float) $itemData['eventTva']);
        $orderDetail->setOfferTva(is_array($itemData['offerTva']) ? (float) $itemData['offerTva'][0] : (float) $itemData['offerTva']);

        $orderDetail->setPartnerAddress($itemData['partnerAddress']);
        $orderDetail->setPartnerCity($itemData['partnerCity']);
        $orderDetail->setPartnerName($itemData['partnerName']);
        $orderDetail->setPartnerPostal($itemData['partnerPostal']);
        $orderDetail->setMyOrder($order);
    }

    public function prepareOrderData($cart)
    {
        $orderData = [
            'items' => [],
            'activityName' => [],
            'offerName' => [],
            'eventName' => [],
            'partner' => []
        ];
        
        $totalPrice = 0;
        
        foreach ($cart as $product) {
            $totalPrice += $product['object']->getPrice() * $product['qty'];
        
            // Récupérer les différentes informations produits pour l'ordre
            $this->populateOrderData($product, $orderData);
        }
        // dd($orderData);
        $orderData['cartPrice'] = $totalPrice;
        
        return $orderData;
    }

    private function populateOrderData($product, &$orderData)
    {
        // Vérification si le nom existe pour chaque type de produit
        $name = !empty($product['object']->getName()) ? $product['object']->getName() : 'Inconnu';

        // Pour les noms
        $orderData['activityName'][] = ($product['type'] === 'activity') ? $name : 'Inconnu';
        $orderData['offerName'][] = ($product['type'] === 'offer') ? $name : 'Inconnu';
        $orderData['eventName'][] = ($product['type'] === 'event') ? $name : 'Inconnu';

        // Pour les images
        $image = !empty($product['object']->getImage()) ? $product['object']->getImage() : 'Inconnu';
        $orderData['activityImage'][] = ($product['type'] === 'activity') ? $image : 'Inconnu';
        $orderData['eventImage'][] = ($product['type'] === 'event') ? $image : 'Inconnu';
        $orderData['offerImage'][] = ($product['type'] === 'offer') ? $image : 'Inconnu';

        // Quantités
        $orderData['activityQuantity'][] = ($product['type'] === 'activity') ? $product['qty'] : 0;
        $orderData['eventQuantity'][] = ($product['type'] === 'event') ? $product['qty'] : 0;
        $orderData['offerQuantity'][] = ($product['type'] === 'offer') ? $product['qty'] : 0;

        // Prix
        $orderData['activityPrice'][] = ($product['type'] === 'activity') ? $product['object']->getPrice() : 0;
        $orderData['eventPrice'][] = ($product['type'] === 'event') ? $product['object']->getPrice() : 0;
        $orderData['offerPrice'][] = ($product['type'] === 'offer') ? $product['object']->getPrice() : 0;

        // TVA
        $orderData['activityTva'][] = ($product['type'] === 'activity') ? $product['object']->getTva() : 0;
        $orderData['eventTva'][] = ($product['type'] === 'event') ? $product['object']->getTva() : 0;
        $orderData['offerTva'][] = ($product['type'] === 'offer') ? $product['object']->getTva() : 0;

        // Récupération des données de partenaire
        $partner = $this->entityManager->getRepository(Partner::class)->find($product['object']->getPartners()->getId());
        $orderData['partnerAddress'][] = $partner ? $partner->getAddress() : 'Adresse inconnue';
        $orderData['partnerCity'][] = $partner ? $partner->getCity() : 'Ville inconnue';
        $orderData['partnerName'][] = $partner ? $partner->getName() : 'Nom inconnu';
        $orderData['partnerPostal'][] = $partner ? $partner->getPostal() : 'Code postal inconnu';

        // Ajouter les éléments dans items en vérifiant le type
		$orderData['items'][] = [
            'itemId' => $product['object']->getId(),
            'name' => $product['object']->getName(),
            'dateStart' => new \DateTime('+1 day'),
            'time' => null,
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
        ];
    }

    public function calculateOrderDetails(Cart $cart)
    {
        $subtotal = 0;
        $tvaDetails = [];
        $totalTva = 0;

        // Parcourir les articles du panier
        foreach ($cart->getCart() as $item) {
            $price = 0;
            $tva = 0;
            $quantity = $item['qty'] ?? 1;

            // Vérification du type de l'élément et récupération du prix/TVA correspondant
            if ($item['type'] === 'activity' && method_exists($item['object'], 'getPriceWt')) {
                $price = $item['object']->getPriceWt();
                $tva = method_exists($item['object'], 'getTva') ? $item['object']->getTva() : 0;
            } elseif ($item['type'] === 'event' && method_exists($item['object'], 'getPriceWt')) {
                $price = $item['object']->getPriceWt();
                $tva = method_exists($item['object'], 'getTva') ? $item['object']->getTva() : 0;
            } elseif ($item['type'] === 'offer' && method_exists($item['object'], 'getPriceWt')) {
                $price = $item['object']->getPriceWt();
                $tva = method_exists($item['object'], 'getTva') ? $item['object']->getTva() : 0;
            }

            // Calcul du sous-total HT
            $subtotal += $price * $quantity;

            // Détails des TVA par taux
            if ($tva > 0) {
                $tvaDetails[$tva] = ($tvaDetails[$tva] ?? 0) + ($price * $quantity * ($tva / 100));
                $totalTva += ($price * $quantity * ($tva / 100));
            }
        }

        return [
            'subtotal' => $subtotal,
            'tvaDetails' => $tvaDetails,
            'totalTva' => $totalTva,
            'total' => $subtotal + $totalTva,
        ];
    }
}