<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\OperationsBancaire;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OperationType;
use App\Repository\OperationsBancaireRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use function PHPSTORM_META\map;

class AccountnController extends AbstractController
{/**
     * This controller display all operation
     *
     * @param OperationsBancaireRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    // #[IsGranted('ROLE_USER')]

    #[Route('/account', 'account.index', methods:['GET', 'POST'])]
    public function index(
        OperationsBancaireRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $Operations = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),

            10
        );

        return $this->render('account/index.html.twig', [
            // 'form' => $form->createView()
            'OperationsBancaire' => $Operations,
        ]);
    }


#[Route('/account', 'account.index', methods: ['GET'])]
    public function indexPublic(
        OperationsBancaireRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('Operation', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(15);
            return $repository->findPublicRecipe(null);
        });

        $Operations = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
            
        );
    //    $Operations->getTotalItemCount();

        return $this->render('account/index.html.twig', [
            'OperationsBancaire' => $Operations
        ]);
    }
/**
     * This controller allow us to create a new operation
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    // #[IsGranted('ROLE_USER')]
    #[Route('/account', 'account.index', methods: ['GET', 'POST'])]
    public function new(Request $request, 
    EntityManagerInterface $manager,
    ): Response
    {

        $Operation = new OperationsBancaire();
    
        $form = $this->createForm(OperationType::class, $Operation);


        $client = HttpClient::create();
        
        // Construisez l'URL de la requête
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
        $url .= '?key=AIzaSyBQWUBanBuvnjUd39VJ9mhF54Tih9iSIU8'; // Remplacez par votre clé API
        $url .= '&location=48.8566,2.3522'; 
        $url .= '&radius=1000';

        $response = $client->request('GET', $url);
        $content = $response->getContent();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        
        $serializer = new Serializer($normalizers, $encoders);
        $getserializer = $this->container->get('serializer');
        $json = $getserializer->serialize($content, 'json');

        $Operation->setUser($this->getUser());

        $form->handleRequest($request);

        $names = [];

        if ($form->isSubmitted() && $form->isValid()) {

            $Operation = $form->getData();
            
            $longitudeGps = $Operation->getLatitude();
            
            $latitudeGps = $Operation->getLongitude();

            $url .= "&location={$latitudeGps},{$longitudeGps}"; 

            $places = $serializer->decode($content, 'json');

            $manager->persist($Operation);
            $manager->flush();


            return $this->redirectToRoute('placename.index', [
                'OperationId' => $Operation->getId(),
                'longitudeGps' => $Operation->getLatitude(),
                'latitudeGps' => $Operation->getLongitude(),
            ]);
        }

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
        ]);

        
    }
}


