<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\OperationsBancaire;
use App\Form\PlaceType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OperationsBancaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;

class Placename extends AbstractController

{
    #[Route('/placename/{OperationId}/{latitudeGps}/{longitudeGps}', name: 'placename.index')]

    public function index($OperationId , $longitudeGps,$latitudeGps,Request $request, 
    EntityManagerInterface $manager,OperationsBancaireRepository $operationsBancaireRepository): Response
    {
        $client = HttpClient::create();
        $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
        $url .= '?key=AIzaSyBQWUBanBuvnjUd39VJ9mhF54Tih9iSIU8';
        $url .= "&location={$latitudeGps},{$longitudeGps}";
        $url .= '&radius=1000'; 
        $response = $client->request('GET', $url);
        $content = $response->getContent();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        
        $serializer = new Serializer($normalizers, $encoders);
        $s = $this->container->get('serializer');
        $json = $s->serialize($content, 'json');
        $places = $serializer->decode($content, 'json');

        $form = $this->createForm(PlaceType::class, null, [
            'places' => $places['results'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $placename =  $form->getData();
            $string = $placename["places"];

            // Récupérer l'entité OperationsBancaire dont l'id est égal à $OperationId
            $operation = $manager->find(OperationsBancaire::class, $OperationId);
            // Modifier le placename de l'entité avec la valeur de la variable $string
            $operation->setPlaceName($string);

            // Persister l'entité dans la base
            $manager->persist($operation);
            $manager->flush();

            return $this->redirectToRoute('contribution.index');    
    }

            return $this->render('placename/index.html.twig', 
            [ 'form' => $form->createView(),
            'places' => $places['results'], ]
            );
}
}