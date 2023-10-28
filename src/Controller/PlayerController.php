<?php

namespace App\Controller;
use App\Entity\Game;
use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/player', name: 'app_player')]
    public function index(): Response
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }

      #[Route('/fetch', name:"affiche_player")]
    function fetch(PlayerRepository $rep)
    {
    return $this->render('player/list.html.twig',
    ["list"=>$rep->findAll()]);
    
    }
#[Route('/add/{idofgame}')]
        function add(ManagerRegistry $man,Request $req,$idofgame,GameRepository $repo)
        {
        
        $player=new Player();
        $form= $this->createForm(PlayerType::class,$player);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {  
            $game = $repo->find($idofgame);
            $player->setGame($game);
            $em=$man->getManager();
            $em->persist($player);
            $em->flush();
        }
        return $this->renderForm('player/add.html.twig',
        ['f'=>$form]);
        }
          #[Route('/editplayer/{id}', name: 'app_editplayer')]
    public function edit(PlayerRepository $repository, $id, Request $request)
    {
        $player = $repository->find($id);
        $form = $this->createForm(PlayerType::class, $player);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); 
            return $this->redirectToRoute("affiche_player");
        }

        return $this->render('player/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/deleteplayer/{id}', name: 'app_deleteplayer')]
    public function delete($id, PlayerRepository $repository)
    {
        $player = $repository->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($player);
        $em->flush();


        return $this->redirectToRoute('affiche_player');
    }
}
