<?php

namespace App\Controller;

use App\Entity\Product;
use http\Env\Request;
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



        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController'
        ]);
    }

    /**
     * @Route("/parser/load", name="load")
     */
    public function parser(): Response
    {



        //url страницы для парсинга
         for ($i=1; $i<25; $i++) {
        $url = 'https://podtrade.ru/catalog/sistemy_lineynogo_peremeshcheniya/'.'?PAGEN_1='.$i;

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
                $image = 'https://podtrade.ru'.$img;
                $node=compact('href', 'title', 'price', 'image');
                return $node;
            });
            // Перебор полученного массива данных
            foreach ($links as $link){
              //  dump($link);
                // Соотношения полученных данных столбцам в БД
                $product = new Product();
                $product->setName($link['title'], $product->getName());
                $product->setPrice($link['price'], $product->getPrice());
               // $product->setImg($link['image'], $product->getImg());

                $path = parse_url($link['image'], PHP_URL_PATH);
                $filename = rand(1000000,9999999).".".$path;
                $filename  =  preg_replace('{\/}','',$filename);
                file_put_contents($_SERVER["DOCUMENT_ROOT"]."/img/".$filename, file_get_contents($link['image']));

                $product->setImg($filename, $product->getImg());


                // Сохранение в БД
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

            }

            echo 'Готово';
        }
         }

        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController'
        ]);

    }

}
