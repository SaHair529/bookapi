<?php

namespace App\ResponseCreator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookResponseCreator
{
    public static function index_ok(array $books): JsonResponse
    {
        $responseData = [];
        foreach($books as $book) {
            $responseData[$book->getId()] = [
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'written_at' => $book->getWrittenAt()
            ];
        }

        return new JsonResponse($responseData);
    }

    public static function index_invalidDates(): JsonResponse
    {
        return new JsonResponse([
            'error' => 'invalid dates format'
        ], Response::HTTP_BAD_REQUEST);
    }
}