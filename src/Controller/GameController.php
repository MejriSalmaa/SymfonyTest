<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
    #[Route('/fetch',name:"affiche_game")]
        function fetch(GameRepository $rep)
        {
        return $this->render('game/list.html.twig',
        ["list"=>$rep->findAll()]);
        
        }

        #[Route('/add')]
        function add(ManagerRegistry $man,Request $req)
        {
        
        $matchee=new Game();
        $form= $this->createForm(GameType::class,$matchee);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $em=$man->getManager();
            $em->persist($matchee);
            $em->flush();
        }
        return $this->renderForm('game/add.html.twig',
        ['f'=>$form]);
        }
          #[Route('/editgame/{id}', name: 'app_editGame')]
    public function edit(GameRepository $repository, $id, Request $request)
    {
        $game = $repository->find($id);
        $form = $this->createForm(GameType::class, $game);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); 
            return $this->redirectToRoute("affiche_game");
        }

        return $this->render('game/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

 #[Route('/deletebook/{id}', name: 'app_deleteGame')]
    public function delete($id, GameRepository $repository)
    {
        $game = $repository->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();


        return $this->redirectToRoute('affiche_game');
    }
 
}
