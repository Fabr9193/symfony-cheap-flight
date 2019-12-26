<?php

namespace App\Controller;

use App\Entity\Research;
use App\Form\SearchType;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function  index(Request $request) {

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

        $link = 'https://api.skypicker.com/flights';
        $client = HttpClient::create();
        $query = $request->getQueryString();
        $response = $client->request('GET', $link ."?" .$query);
        $contentType = $response->getHeaders()['content-type'][0];
        $res = new Response();
        $test = new Body();
        $test->append($response->getContent());
        $res->setStatusCode($response->getStatusCode());
        $res->setContent($response->getContent());

        return $res;
    }
}