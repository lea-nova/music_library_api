<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;


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



    #[Route('api/albums', name: 'app_albums_create', methods: ['POST'])]
    public function create(
        SerializerInterface $serializer,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $album = $serializer->deserialize($request->getContent(), Album::class, "json");
        $entityManager->persist($album);
        $entityManager->flush();
        return $this->json($album, 200, [], [
            'groups' => ['albums.index', 'albums.nom']
        ]);
    }

    #[Route('api/albums/{id}', name: 'app_albums_update', methods: ['PUT'])]
    public function update(
        SerializerInterface $serializer,
        Request $request,
        EntityManagerInterface $entityManager,
        AlbumRepository $albumRepository

    ): JsonResponse {

        $album = $serializer->deserialize($request->getContent(), Album::class, "json");
        $oldAlbum = $albumRepository->findOneBy(["nom" => $album->getNom()]);
        if (!$oldAlbum) {
            throw $this->createNotFoundException(sprintf(
                "Pas d'album trouvé avec '%id' et de nom '%s'",
                $album->getId(),
                $album->getNom()
            ));
        }

        $oldAlbum->setNom($album->getNom());
        $oldAlbum->setArtiste($album->getArtiste());
        $oldAlbum->setAnnee($album->getAnnee());
        $oldAlbum->setLabel($album->getLabel());
        $oldAlbum->setFormat($album->getFormat());
        $oldAlbum->setGenre($album->getGenre());
        $entityManager->flush();
        return $this->json($album, 200, [], [
            'groups' => ['albums.index', 'albums.nom']
        ]);
    }

    #[Route('api/albums/{id}', name: 'app_albums_delete', methods: ['DELETE'])]
    public function delete(
        SerializerInterface $serializer,
        Request $request,
        EntityManagerInterface $entityManager,
        AlbumRepository $albumRepository

    ): JsonResponse {

        $album = $serializer->deserialize($request->getContent(), Album::class, "json");
        $oldAlbum = $albumRepository->findOneBy(["nom" => $album->getNom()]);
        if (!$oldAlbum) {
            throw $this->createNotFoundException(sprintf(
                "Pas d'album trouvé avec '%id' et de nom '%s'",
                $album->getId(),
                $album->getNom()
            ));
        }
        $entityManager->remove($oldAlbum);
        $entityManager->flush();
        return $this->json($oldAlbum, 200, [], [
            'groups' => ['albums.index', 'albums.nom']
        ]);
    }
}

// {
//     "nom": "Drop 6",
//     "artiste": "Little Simz",
//     "annee": "2020",
//     "label": "Age 101 music",
//     "genre": "rap "
// }