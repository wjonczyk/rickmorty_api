<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

//    /**
//     * @Route("/{any}", name="not_found", requirements={"any"=".+"})
//     */
//    public function redirectHomepage(string $any = ''): Response
//    {
//        if ($any !== '' && $any !== 'api' && !preg_match('/^api\/characters\/.+/', $any)) {
//            return $this->redirectToRoute('homepage');
//        }
//
//        return new Response('Hello, World!');
//    }
}
