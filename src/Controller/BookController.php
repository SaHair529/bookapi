<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\ResponseCreator\BookResponseCreator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/list', name: 'book_list')]
    public function index(Request $req, BookRepository $bookRep): JsonResponse
    {
        if (!is_null($req->query->get('authors')) || 
            !is_null($req->query->get('genres')) ||
            !is_null($req->query->get('dates')))
        {
            $authors = $req->query->get('authors');
            $genres = $req->query->get('genres');
            $dates = $req->query->get('dates');

            $relations = [];
            if ($authors !== null) 
                $relations['authors'] = explode(',', $authors);
            if ($genres !== null) 
                $relations['genres'] = explode(',', $genres);
            if ($dates !== null) {
                $datesArray = explode('-', $dates);
                $start = DateTimeImmutable::createFromFormat('d.m.Y', $datesArray[0]);
                $end = DateTimeImmutable::createFromFormat('d.m.Y', $datesArray[1]);
                if ($start === false || $end === false) {
                    return BookResponseCreator::index_invalidDates();
                }
                $relations['dates'] = [
                    'start' => $start,
                    'end' => $end
                ];

            }
            
            return BookResponseCreator::index_ok($bookRep->findByRelations($relations));
        }
        
        return BookResponseCreator::index_ok($bookRep->findAll());
    }
}
