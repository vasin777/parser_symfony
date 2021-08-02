<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Env\Request;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=2550)
     */
    private $img;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Set img
     *
     * @param string img
     */
    public function getImg()
    {
        return $this->img;
    }
    /**
     * Set img
     *
     * @param string $img
     */
    public function setImg($img)
    {
        $path = parse_url($img, PHP_URL_PATH);
        $filename = rand(1000000,9999999).".".$path;
        $filename  =  preg_replace('{\/}','',$filename);
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/img/".$filename, file_get_contents($img));
        $this->img = $filename;
    }
}
