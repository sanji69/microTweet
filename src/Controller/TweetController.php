<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use App\Service\UploadFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tweet")
 */
class TweetController extends AbstractController
{
    /**
     * @Route("/", name="tweet_index", methods={"GET"})
     */
    public function index(TweetRepository $tweetRepository): Response
    {
        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tweet_new", methods={"GET","POST"})
     * @param Request $request
     * @param UploadFileService $fileService
     * @return Response
     */
    public function new(Request $request, UploadFileService $fileService): Response
    {
        dd($request);
        $fileService->upload();
    }

    /**
     * @Route("/{id}", name="tweet_show", methods={"GET"})
     */
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tweet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tweet $tweet): Response
    {
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tweet_index');
        }

        return $this->render('tweet/edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tweet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tweet $tweet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tweet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tweet_index');
    }
}
