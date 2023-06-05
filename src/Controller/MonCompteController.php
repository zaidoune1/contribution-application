<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OperationsBancaireRepository;


class MonCompteController extends AbstractController
{
    #[Route('/mon_compte', name: 'app_mon_compte')]
    public function index(OperationsBancaireRepository $operationsCount): Response

    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home.index');
        };

        $getIdOperations = $operationsCount->findBy(['user' => $this->getUser()]);

        $countNonValidé = 0;

        foreach($getIdOperations as $oneId) {
            $placename = $oneId->getPlacename();

            if($placename === null) {
                $countNonValidé = $countNonValidé + 1;
            }else {
                $countNonValidé;
            }
        }
        
        $OperationsBancaires = $operationsCount->findBy(['user' => $this->getUser()]);

        return $this->render('mon_compte/index.html.twig', [
            'OperationsBancaires' => $OperationsBancaires,
            'countNonValidé' => $countNonValidé,
        ]);
    }
}
