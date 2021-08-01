<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;

class ParserController extends AbstractController
{
    /**
     * @Route("/parser", name="parser")
     */
    public function index(): Response
    {
        //url страницы для парсинга
       // for ($i=1; $i<25; $i++) {
            $url = 'https://podtrade.ru/catalog/sistemy_lineynogo_peremeshcheniya/'; //?PAGEN_1=. $i;

        //создание http запроса
        $client = HttpClient::create();

        //создание запроса
        $response = $client->request('GET', $url);

        $statusCode = $response->getStatusCode();

        //проверка статуса
        if ($statusCode == '200'){
            $content = $response->getContent();

        //создание контента
            $crawler = new Crawler($content);

        // получение данных
            $links = $crawler->filter('div.section-block-view-item-inner')->each(function (Crawler $node){
                $href = $node->filter('a')->attr('href');
                $title = $node->filter('div.section-block-view-item-inner > div.block-view-title > a')->text();
                $price = $node->filter('div.block-view-price')->text();
                $img = $node->filter('img')->attr('src');
                $node=compact('href', 'title', 'price', 'img');
                return $node;
            });

        foreach ($links as $link){
            dump($link);
            }

         }
       // }


        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController'
        ]);
    }
}
