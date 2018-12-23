<?php

namespace App\UI\HTTP\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardController.
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
