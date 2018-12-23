<?php

namespace App\UI\HTTP\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardController
 * @package App\UI\HTTP\Rest\Controller
 */
class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}
