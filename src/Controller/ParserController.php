<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector;

class ParserController extends AbstractController
{
    /**
     * @Route("/parser", name="parser")
     */
    public function index(): Response
    {
        //url страницы для парсинга
        $url = 'https://podtrade.ru/catalog/sistemy_lineynogo_peremeshcheniya/';

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
            $links = $crawler->filter('div.section-block-view')->each(function (Crawler $node){
                $href = $node->filter('div.section-block-view-item-inner>div.block-view-photo>a')->attr('href');
                $title = $node->filter('div.block-view-title>a')->text();
                $price = $node->filter('div.price_little')->text();
                $img = $node->filter('img')->attr('src');
                return compact('href', 'title', 'price', 'img');
            });
        foreach ($links as $link){
            dump($link);
            }

        }


        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController'
        ]);
    }
}
