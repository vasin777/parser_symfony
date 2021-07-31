<?php

require(__DIR__."/vendor/autoload.php");

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector;

$url = 'https://podtrade.ru/catalog/sistemy_lineynogo_peremeshcheniya/';

$client = HttpClient::create();

$response = $client->request('GET', $url);

$statusCode = $response->getStatusCode();

if ($statusCode == '200'){

    $content = $response->getContent();

    $crawler = new Crawler($content);

    $links = $crawler->filter('.section-block-view-items')->each(function ($node){
        $href = $node->attr('href');
        $title = $node->filter('.block-view-title')->text();
        $price = $node->filter('.block-view-price')->text();
        $img = $node->filter('')->attr('src');
        return compact('href', 'title', 'price', 'img');

    });
    foreach ($links as $link){
        dump($link);
    }


}