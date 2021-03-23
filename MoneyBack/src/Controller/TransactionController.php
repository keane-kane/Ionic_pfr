<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;

class TransactionController extends AbstractController

{

   private $ripo;

    public function __construct(TransactionRepository $ripo)
    {
        $this->ripo = $ripo;
    }


    /**
     * @Route(
     *      name="get_clients",
     *      path="api/transactions/{code}",
     *      methods={"GET"},
     *      defaults={
     *          "__controller"="App\Controller\Promos\PromosController::findByCode",
     *          "__api_resource_class"=Transaction::class,
     *          "__api_item_operation_name"="get_clients"
     *     }
     * )
     */
    public function findByCode($code)
    {
        $clientR = $this->ripo->findOneByIdOrCode($code);
        return $this->json($clientR, Response::HTTP_OK);

    } 
    /**
     * @Route(
     *      path="api/transacannler",
     *      methods={"GET"},
     * )
     */
    public function findByAnnuler()
    {
        $clientR = $this->ripo->findBy(['codeValide' => false]);
        return $this->json($clientR, Response::HTTP_OK);

    }
  
}