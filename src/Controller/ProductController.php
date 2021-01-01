<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     */
    public function category($slug, CategoryRepository $categoryRepository)
    {

        $category = $categoryRepository->findOneBy([
            'slug' => $slug,
        ]);
        // Renvoi une 404 avec notre message via une méthode de l'abstractC
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show($slug, ProductRepository $productRepository)
    {
        // Trouve le produit via le repository et son slug
        $product = $productRepository->findOneBy([
            'slug' => $slug,
        ]);

        // Gerer les erreurs
        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,

        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;

        // On crée un form sur base de notre formType dans lequel on injecte notre produit
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        // lors de la soumission
        if ($form->isSubmitted()) {

            $product->setSlug(strtolower($slugger->slug($product->getName())));

            // on persist en db
            $em->persist($product);
            $em->flush();

            //On redirige sur la page du produit
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }


        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView,
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        // $form->setData($product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView,
        ]);
    }
}
