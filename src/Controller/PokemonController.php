<?php


namespace App\Controller;

use App\Entity\Debilidad;
use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Manager\PokemonManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PokemonController extends AbstractController{

    #[Route("/new/pokemon")]
    public function insertPokemon(EntityManagerInterface $doctrine){
        $pokemon = new Pokemon();
        $pokemon -> setNombre("Centiskorch");
        $pokemon -> setDescripcion("Cuando genera calor, su temperatura corporal alcanza aproximadamente los 800 ºC. Usa el cuerpo a modo de látigo para lanzarse al ataque.");
        $pokemon -> setImagen("https://assets.pokemon.com/assets/cms2/img/pokedex/full/330.png");
        $pokemon -> setCodigo(5896);
        $pokemon2 = new Pokemon();
        $pokemon2 -> setNombre("Flygon");
        $pokemon2 -> setDescripcion("Cuando genera calor, su temperatura corporal alcanza aproximadamente los 800 ºC. Usa el cuerpo a modo de látigo para lanzarse al ataque.");
        $pokemon2 -> setImagen("https://assets.pokemon.com/assets/cms2/img/pokedex/full/488.png");
        $pokemon2 -> setCodigo(55);
        $pokemon3 = new Pokemon();
        $pokemon3 -> setNombre("Drowzee");
        $pokemon3 -> setDescripcion("Cuando genera calor, su temperatura corporal alcanza aproximadamente los 800 ºC. Usa el cuerpo a modo de látigo para lanzarse al ataque.");
        $pokemon3 -> setImagen("https://assets.pokemon.com/assets/cms2/img/pokedex/full/365.png");
        $pokemon3 -> setCodigo(57455);
        $debilidad = new Debilidad();
        $debilidad -> setNombre("Fuego");
        $debilidad2 = new Debilidad();
        $debilidad2 -> setNombre("Agua");
        $debilidad3 = new Debilidad();
        $debilidad3 -> setNombre("Psiquico");
        $pokemon -> addDebilidade($debilidad);
        $pokemon -> addDebilidade($debilidad3);
        $pokemon3 -> addDebilidade($debilidad2);
        $pokemon2 -> addDebilidade($debilidad);
        $doctrine -> persist($pokemon);
        $doctrine -> persist($pokemon2);
        $doctrine -> persist($pokemon3);
        $doctrine -> persist($debilidad);
        $doctrine -> persist($debilidad2);
        $doctrine -> persist($debilidad3);
        $doctrine -> flush();
        return new Response("Pokemon insertados correctamente");
    }

    #[Route("/insert/pokemon", name: 'newPokemon')]
     public function newPokemon (
         Request $request,
         EntityManagerInterface $doctrine,
         PokemonManager $manager
    ){
      $form= $this -> createForm(PokemonType::class);   
      $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()){
                $pokemon = $form->getData();
        $ficheroImagen = $form -> get('ficheroImagen') -> getData();
            if ($ficheroImagen) {
                
                $newFilename = uniqid().'.'.$ficheroImagen->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ficheroImagen->move(
                        $this->getParameter('kernel.project_dir').'/public/asset/image',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $pokemon->setImagen('/asset/image/'.$newFilename);
            }
//      Gestionamos los datos del formulario
        $doctrine ->persist($pokemon);
        $doctrine -> flush();
        $this -> addFlash("exito","pokemon insertado correctamente :)");
        //$manager->sendMail("He creado un pokemon");
        return $this -> redirectToRoute("listPokemon");
     }
      return $this -> renderForm("pokemon/CreatePokemon.html.twig", ["PokemonForm"=>$form]); 
     }

     #[Route("/edit/pokemon/{id}", name: 'editPokemon')]
     #[IsGranted("ROLE_ADMIN")]
     public function editPokemon ($id, Request $request, EntityManagerInterface $doctrine){
        $repository = $doctrine -> getRepository(Pokemon::class);
        $pokemon = $repository -> find($id);
      $form= $this -> createForm(PokemonType::class, $pokemon);   
      $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()){
//      Gestionamos los datos del formulario
        $pokemon = $form->getData();
        $ficheroImagen = $form -> get('ficheroImagen') -> getData();
            if ($ficheroImagen) {
                
                $newFilename = uniqid().'.'.$ficheroImagen->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ficheroImagen->move(
                        $this->getParameter('kernel.project_dir').'/public/asset/image',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $pokemon->setImagen('/asset/image/'.$newFilename);
            }
        $doctrine ->persist($pokemon);
        $doctrine -> flush();
        $this -> addFlash("exito","pokemon insertado correctamente :)");
        return $this -> redirectToRoute("listPokemon");
     }
      return $this -> renderForm("pokemon/CreatePokemon.html.twig", ["PokemonForm"=>$form]); 
     }
     
    #[Route("/pokemon/{id}", name: 'showPokemon')]
    public function showPokemon($id, EntityManagerInterface $doctrine){
        $repository = $doctrine -> getRepository(Pokemon::class);
        $pokemon = $repository -> find($id);
        return $this->render("pokemon/Pokemon.html.twig",["pokemon"=>$pokemon]);
    }

    #[Route("/", name: 'listPokemon')]
    public function listPokemon (EntityManagerInterface $doctrine){
        $repository = $doctrine -> getRepository(Pokemon::class);
        $pokemons = $repository -> findAll();
        return $this->render("pokemon/ListPokemon.html.twig", ["arrayPokemons"=>$pokemons]);
    }
}
