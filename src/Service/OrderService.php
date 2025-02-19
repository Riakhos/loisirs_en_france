<?php

namespace App\Service;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
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
        $orderDetail->setActivityQuantity($itemData['activityQuantity']);
        $orderDetail->setEventQuantity($itemData['eventQuantity']);
        $orderDetail->setOfferQuantity($itemData['offerQuantity']);
        $orderDetail->setActivityPrice($itemData['activityPrice']);
        $orderDetail->setEventPrice($itemData['eventPrice']);
        $orderDetail->setOfferPrice($itemData['offerPrice']);
        $orderDetail->setActivityTva($itemData['activityTva']);
        $orderDetail->setEventTva($itemData['eventTva']);
        $orderDetail->setOfferTva($itemData['offerTva']);
        $orderDetail->setPartnerAddress($itemData['partnerAddress']);
        $orderDetail->setPartnerCity($itemData['partnerCity']);
        $orderDetail->setPartnerName($itemData['partnerName']);
        $orderDetail->setPartnerPostal($itemData['partnerPostal']);
        $orderDetail->setMyOrder($order);
    }

    public function prepareOrderData($cart)
    {
        $orderData = ['items' => []];
        $totalPrice = 0;

        foreach ($cart as $product) {
            $totalPrice += $product['object']->getPrice() * $product['qty'];

            // Récupérer les différentes informations produits pour l'ordre
            $this->populateOrderData($product, $orderData);
        }

        $orderData['cartPrice'] = $totalPrice;

        return $orderData;
    }

    private function populateOrderData($product, &$orderData)
    {
        if (method_exists($product['object'], 'getActivityName')) {
			$orderData['activityName'][] = $product['object']->getActivityName();
		} else {
			$orderData['activityName'][] = ''; // Valeur par défaut si non définie
		}
		
		if (method_exists($product['object'], 'getOfferName')) {
			$orderData['offerName'][] = $product['object']->getOfferName();
		} else {
			$orderData['offerName'][] = ''; // Valeur par défaut si non définie
		}
		
		if (method_exists($product['object'], 'getEventName')) {
			$orderData['eventName'][] = $product['object']->getEventName();
		} else {
			$orderData['eventName'][] = ''; // Valeur par défaut si non définie
		}

		if (method_exists($product['object'], 'getActivityImage')) {
			$orderData['activityImage'][] = $product['object']->getActivityImage();
		} else {
			$orderData['activityImage'][] = ''; // Valeur par défaut si non définie
		}

		if (method_exists($product['object'], 'getEventImage')) {
			$orderData['eventImage'][] = $product['object']->getEventImage();
		} else {
			$orderData['eventImage'][] = ''; // Valeur par défaut si non définie
		}

		if (method_exists($product['object'], 'getOfferImage')) {
			$orderData['offerImage'][] = $product['object']->getImage();
		} else {
			$orderData['offerImage'][] = ''; // Valeur par défaut si non définie
		}

		$orderData['activityQuantity'][] = method_exists($product['object'], 'getActivityQuantity') ? $product['object']->getActivityQuantity() : 1;
		$orderData['eventQuantity'][] = method_exists($product['object'], 'getEventQuantity') ? $product['object']->getEventQuantity() : 1;
		$orderData['offerQuantity'][] = method_exists($product['object'], 'getOfferQuantity') ? $product['object']->getOfferQuantity() : 1;

		$orderData['activityPrice'][] = method_exists($product['object'], 'getActivityPrice') ? $product['object']->getActivityPrice() : 0;
		$orderData['eventPrice'][] = method_exists($product['object'], 'getEventPrice') ? $product['object']->getEventPrice() : 0;
		$orderData['offerPrice'][] = method_exists($product['object'], 'getOfferPrice') ? $product['object']->getOfferPrice() : 0;

		$orderData['activityTva'][] = method_exists($product['object'], 'getActivityTva') ? $product['object']->getActivityTva() : 0;
		$orderData['eventTva'][] = method_exists($product['object'], 'getEventTva') ? $product['object']->getEventTva() : 0;
		$orderData['offerTva'][] = method_exists($product['object'], 'getOfferTva') ? $product['object']->getOfferTva() : 0;

		$orderData['partnerAddress'][] = method_exists($product['object'], 'getPartnerAddress') ? $product['object']->getPartnerAddress() : '';
		$orderData['partnerCity'][] = method_exists($product['object'], 'getPartnerCity') ? $product['object']->getPartnerCity() : '';
		$orderData['partnerName'][] = method_exists($product['object'], 'getPartnerName') ? $product['object']->getPartnerName() : '';
		$orderData['partnerPostal'][] = method_exists($product['object'], 'getPartnerPostal') ? $product['object']->getPartnerPostal() : '';

		$orderData['items'][] = [
			'itemId' => $product['object']->getId(),
			'name' => $product['object']->getName(),
			'dateStart' => new \DateTime('+1 day'), // Date par défaut (ajustable)
			'time' => null, // Optionnel en fonction du loisir
			'activityImage' => $product['object']->getImage() ?? '',
            'eventImage' => $product['object']->getImage() ?? '',
            'offerImage' => $product['object']->getImage() ?? '',
			'activityQuantity' => !empty($orderData['activityQuantity']) ? implode(', ', $orderData['activityQuantity']) : 1,
			'eventQuantity' => !empty($orderData['eventQuantity']) ? implode(', ', $orderData['eventQuantity']) : 1,
			'offerQuantity' => !empty($orderData['offerQuantity']) ? implode(', ', $orderData['offerQuantity']) : 1,
			'activityPrice' => !empty($orderData['activityPrice']) ? implode(', ', $orderData['activityPrice']) : 0,
			'eventPrice' => !empty($orderData['eventPrice']) ? implode(', ', $orderData['eventPrice']) : 0,
			'offerPrice' => !empty($orderData['offerPrice']) ? implode(', ', $orderData['offerPrice']) : 0,
			'activityTva' => !empty($orderData['activityTva']) ? implode(', ', $orderData['activityTva']) : 0,
			'eventTva' => !empty($orderData['eventTva']) ? implode(', ', $orderData['eventTva']) : 0,
			'offerTva' => !empty($orderData['offerTva']) ? implode(', ', $orderData['offerTva']) : 0,
			'partnerAddress' => !empty($orderData['partnerAddress']) ? implode(', ', $orderData['partnerAddress']) : '',
			'partnerCity' => !empty($orderData['partnerCity']) ? implode(', ', $orderData['partnerCity']) : '',
			'partnerName' => !empty($orderData['partnerName']) ? implode(', ', $orderData['partnerName']) : '',
			'partnerPostal' => !empty($orderData['partnerPostal']) ? implode(', ', $orderData['partnerPostal']) : '',
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