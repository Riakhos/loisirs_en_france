<?php

namespace App\Controller;

use App\Service\PdfGeneratorService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvitationController extends AbstractController
{
    #[Route('/invitation', name: 'app_invitation')]
    public function index(): Response
    {
        return $this->render('invitation/invitation.html.twig', [
            'controller_name' => 'InvitationController',
        ]);
    }
    
    #[Route('/output-pdf', name: 'app_output_pdf')]
    public function outputPdf(PdfGeneratorService $pdfGeneratorService): Response
    {
        $html = $this->renderView(view: 'invitation/pdf.html.twig');
        $content = $pdfGeneratorService->output($html);
        
        return new Response($content, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    #[Route('/stream-pdf', name: 'app_stream_pdf')]
    public function streamPdf(PdfGeneratorService $pdfGeneratorService): Response
    {
        $html = $this->renderView(view: 'invitation/pdf.html.twig');
        
        return $pdfGeneratorService->getStreamResponse($html, filename: 'hello.pdf');
    }
}