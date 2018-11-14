<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FlayerController extends FOSRestController
{
    /**
     * @Route("/api", name="api_flayer")
     */
    public function index(Request $request)
    {
    	dd($this->getUser());
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/FlayerController.php',
        ]);
    }
}
