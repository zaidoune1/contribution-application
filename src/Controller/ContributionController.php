<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OperationsBancaire;
use App\Repository\OperationsBancaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\OperationType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;




class ContributionController extends AbstractController
{

    #[Security("is_granted('ROLE_USER') and user === OperationsBancaire.getUser()")]

    #[Route('/contribution', 'contribution.index', methods: ['GET', 'POST'])]
    public function index(
        
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        OperationsBancaireRepository $repository,
        Request $request,

    ): Response {


        $OperationsBancaire = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            100
        );

        $getIdOperations = $repository->findBy(['user' => $this->getUser()]);

        $countNonValidé = 0;

        foreach($getIdOperations as $oneId) {
            $placename = $oneId->getPlacename();

            if($placename === null) {
                $countNonValidé = $countNonValidé + 1;
            }else {
                $countNonValidé;
            }
        }
        
        return $this->render('/contribution/index.html.twig', [
            'OperationsBancaire' => $OperationsBancaire,
            'countNonValidé' => $countNonValidé,
        ]);

    }

    #[Route('/contribution/delete/{id}', name: 'delete.index', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager,OperationsBancaire $operation, $id ) : Response 
    
    {

        $operation = $manager->find(OperationsBancaire::class, $id);


        $manager->remove($operation);
        $manager->flush();

        return $this->redirectToRoute('contribution.index');   
    }

}





