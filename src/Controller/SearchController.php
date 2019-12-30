<?php

namespace App\Controller;

use App\Entity\Research;
use App\Form\SearchType;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


class SearchController extends AbstractController
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function  index(Request $request) {
        //TODO: afficher le formulaire (EDIT : Ce sera surement en Vue dans un autre repository)

        // creates a task object and initializes some data for this example
        $research = new Research();


        $form = $this->createForm(SearchType::class, $research);
        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                $this->search($request);

                return $this->redirectToRoute('results');
            }
        }
        return ($this->render('search/new.html.twig', [
            'form' => $form->createView()
        ]));


    }

    /**
     * @Route("/search")
     * @param Request $request
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function  search(Request $request) {
        //FIXME : gestion des erreurs 422 : mauvais nom de départ (n'accepte que Code pays[FR,IT,US] ou code aeroport[CDG, ORY, IAD])
        //ici je récupère les arguments que j'enverrai à l'API
        /**
         * Paramètres en dur
         * [
         * 'flight_type' => 'round',
         * 'partner' => 'picky',
         * 'ret_from_diff_airport' => 0,
         * ]
         * Dates :
         * date_from = date_to pour l'aller
         * nights_in_dst_from = nights_in_dst_to pour le retour
         */
        $link = 'https://api.skypicker.com/flights';
        $client = HttpClient::create();
        $query = $this->fillQuery($request);
        $res = new Response();
        try {
            $response = $client->request('GET', $link . "?" . $query);
            $contentType = $response->getHeaders()['content-type'][0];
            $parsedContent = $this->parseContent($response->getContent());
            $res->headers->set('content-type', $contentType);
            $res->setStatusCode($response->getStatusCode());
            $res->setContent(json_encode($parsedContent));
        }
        catch(ClientException $e){
            $res->headers->set('content-type', 'application/json');
            $res->setStatusCode($e->getCode());
            if ($e->getCode() == 422) {
                $res->setContent(json_encode(['Error' => 'Your fly_from should be either IATA code or 2-letter country code']));
            }
            else {
                $res->setContent(json_encode(['Error' =>$e->getMessage()]));
            }
        }
        return $res;
    }

    private function parseContent(string $rawContent) : array {
        //TODO : retourne les éléments pertinents (Nb total de vols, vol affiches par prix croissant et n'afficher que les destinations et le lien de booking (externe))
        //TODO : on a les codes des airlines il faudrait récupérer les noms entiers AF = airfrance, DY = Norwegian
        $content = json_decode($rawContent);
        $totalResults = count($content->data);
        $mapped = array_map( function($element){
            return [
                'city_from' => $element->cityFrom,
                'city_to' =>$element->cityTo,
                'price' => $element->price,
                'route' => $element->routes,
                'details' => $element->deep_link,
            ];
        },$content->data);
        return ['total_results' => $totalResults, 'data' => $mapped];
    }

    private function fillQuery(Request $request) : string
    {
        $request->query->add([
            'flight_type' => 'round',
            'partner' => 'picky',
            'ret_from_diff_airport' => 0,
            'date_to' => $request->query->get('date_from'),
            'nights_in_dst_to' => $request->query->get('nights_in_dst_from')
            ]);
        return http_build_query($request->query->all());
    }
}