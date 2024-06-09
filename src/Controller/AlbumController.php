<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AlbumController extends AbstractController
{
    #[Route('api/albums', name: 'app_albums', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): JsonResponse
    {

        $albums = $albumRepository->findAll();

        return $this->json($albums, 200, [], [
            'groups' => ['albums.index']
        ]);
    }
    #[Route('api/albums', name: 'app_albums', methods: ['POST'])]
    public function create()
    {
    }
}
