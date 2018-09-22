<?php

namespace App\UI\HTTP\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
    /**
     * @Route("{react_link}", name="dashboard", defaults={"react_link": null})
     */
    public function dashboard(): Response
    {
//        requirements={"react_link"=".+"}
        return $this->render('dashboard/index.html.twig');
    }
}
