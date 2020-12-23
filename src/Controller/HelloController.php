<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{

    /**
     * @Route("/hello/{prenom?world}", name="hello")
     */
    public function hello($prenom = "World", Calculator $calculator, Detector $detector)
    {

        dump($detector->detect(101));
        dump($detector->detect(10));

        $tva = $calculator->calcul(100);

        dump($tva);

        return new Response("Hello $prenom");
    }
}
