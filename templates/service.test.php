<?php

namespace App\Service;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Partner;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private EntityManagerInterface $em;
    private Cart $cart;

    public function __construct(EntityManagerInterface $em, Cart $cart)
    {
        $this->em = $em;
        $this->cart = $cart;
    }

    public function processOrder(array $orderData, $user): Order
    {
        $order = (new Order())
            ->setUser($user)
            ->setCreateAt(new \DateTime())
            ->setState(1)
            ->setCartPrice($orderData['cartPrice'])
            ->setActivityName(implode(', ', $orderData['activityName']))
            ->setOfferName(implode(', ', $orderData['offerName']))
            ->setEventName(implode(', ', $orderData['eventName']))
            ->setPartnerName(implode(', ', $orderData['partnerName']));

        foreach ($orderData['items'] as $itemData) {
            $orderDetail = new OrderDetail();
            $this->mapItemDataToOrderDetail($itemData, $orderDetail, $order);
            $this->em->persist($orderDetail);
        }

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

    private function mapItemDataToOrderDetail(array $itemData, OrderDetail $orderDetail, Order $order): void
    {
        $orderDetail->setItemId($itemData['itemId'])
            ->setDateStart($itemData['dateStart'])
            ->setTime($itemData['time'] ?? null)
            
            // Pour les noms
            ->setActivityName($itemData['activityName'] ?? 'Inconnu')
            ->setEventName($itemData['eventName'] ?? 'Inconnu')
            ->setOfferName($itemData['offerName'] ?? 'Inconnu')
            
            // Pour les images
            ->setActivityImage($itemData['activityImage'])
            ->setEventImage($itemData['eventImage'])
            ->setOfferImage($itemData['offerImage'])
            
            // Pour chaque quantité, vérifiez si c'est un tableau avant d'y accéder
            ->setActivityQuantity($itemData['activityQuantity'] ?? 0)
            ->setEventQuantity($itemData['eventQuantity'] ?? 0)
            ->setOfferQuantity($itemData['offerQuantity'] ?? 0)
            
            // Pour les prix
            ->setActivityPrice($itemData['activityPrice'] ?? 0)
            ->setEventPrice($itemData['eventPrice'] ?? 0)
            ->setOfferPrice($itemData['offerPrice'] ?? 0)
            
            // Pour les  TVA
            ->setActivityTva($itemData['activityTva'] ?? 0)
            ->setEventTva($itemData['eventTva'] ?? 0)
            ->setOfferTva($itemData['offerTva'] ?? 0)

            // Pour les partenaires
            ->setPartnerName($itemData['partnerName'])
            ->setPartnerAddress($itemData['partnerAddress'])
            ->setPartnerCity($itemData['partnerCity'])
            ->setPartnerPostal($itemData['partnerPostal'])
            ->setPartnerWebsite($itemData['partnerWebsite'])
            ->setPartnerPhone($itemData['partnerPhone'])
            ->setPartnerEmail($itemData['partnerEmail'])
            
            ->setMyOrder($order);
    }

    public function prepareOrderData(array $cart): array
    {
        $orderData = [
            'items' => [],
            'activityName' => [],
            'offerName' => [],
            'eventName' => [],
            'partnerName' => [],
            'cartPrice' => 0
        ];

        foreach ($cart as $product) {
            $orderData['cartPrice'] += $product['object']->getPrice() * $product['qty'];

            // Récupérer les différentes informations produits pour l'ordre
            $this->populateOrderData($product, $orderData);
        }
        
        return $orderData;
    }

    private function populateOrderData(array $product, array &$orderData): void
    {
        // Vérification si le nom existe pour chaque type de produit
        $name = !empty($product['object']->getName()) ? $product['object']->getName() : null;

        // Récupération des données de partenaire
        $partner = $this->em->getRepository(Partner::class)->find($product['object']->getPartners()->getId());
        
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

    public function calculateOrderDetails($cart)
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