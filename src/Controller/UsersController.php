<?php


namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{


    #[Route("/insert/user", name: 'newUser')]
    public function newUser(Request $request, 
    EntityManagerInterface $doctrine,
     UserPasswordHasherInterface $hasher )
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

// esta parte nos sirve para insertar la imagen del usuario

            // $ficheroImagen = $form->get('ficheroImagen')->getData();
            // if ($ficheroImagen) {

            //     $newFilename = uniqid() . '.' . $ficheroImagen->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $ficheroImagen->move(
            //             $this->getParameter('kernel.project_dir') . '/public/asset/image',
            //             $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //     }

            //     // updates the 'brochureFilename' property to store the PDF file name
            //     // instead of its contents
            //     $pokemon->setImagen('/asset/image/' . $newFilename);
            // }
            //      Gestionamos los datos del formulario
            $doctrine->persist($user);
            $doctrine->flush();
            $this->addFlash("exito", "usuario insertado correctamente :)");
            return $this->redirectToRoute("listPokemon");
        }
        return $this->renderForm("pokemon/CreatePokemon.html.twig", ["PokemonForm" => $form]);
    }

    #[Route("/insert/admin", name: 'newAdmin')]
    public function newAdmin(Request $request, 
    EntityManagerInterface $doctrine,
     UserPasswordHasherInterface $hasher )
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles(["ROLE_ADMIN","ROLE_USER"]);
            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $doctrine->persist($user);
            $doctrine->flush();
            $this->addFlash("exito", "usuario insertado correctamente :)");
            return $this->redirectToRoute("listPokemon");
        }
        return $this->renderForm("pokemon/CreatePokemon.html.twig", ["PokemonForm" => $form]);
    }

}
