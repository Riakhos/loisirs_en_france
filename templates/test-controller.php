<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class OrderController extends AbstractController
{
    /**
     * order()
     * fonction pour passer une commande
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/commande', name: 'app_order')]
    public function order(Request $request, EntityManagerInterface $em ): Response
    {
        $order = new Order();
        $order->setCreateAt(new \DateTime());

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Commande créée avec succès.');

            return $this->redirectToRoute('order_list');
        }
        
        return $this->render('cart/order.html.twig', [
            'controller_name' => 'Passer une commande',
            'orderForm' =>$form->createView(),
            'order' => $order,
        ]);
    }
}