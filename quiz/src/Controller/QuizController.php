<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategorieRepository;
use App\Repository\QuestionRepository;
use App\Repository\TrueCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    public function categorie(Request $request, TrueCategorieRepository $repository): Response
    {
        $categories = $repository -> findAll();
        return $this -> render('quiz/categorie.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/{name}_{id}', name: 'categorie', requirements: ['id' => '\d+'])]
    public function index(Request $request, TrueCategorieRepository $repository, int $id): Response
    {
        $quizz = $repository -> findQuizz($id);
        return $this -> render('quiz/index.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    #[Route('/{title}_{id_categorie_id}/{name}_{id}', name: 'quizz', requirements: ['id' => '\d+'])]
    public function quizz(Request $request, QuestionRepository $repository, int $id, int $id_categorie_id)
    {
        $categorie_infos = $repository -> findCategorie($id_categorie_id);
        foreach ($categorie_infos as $categorie_info)
        {
            $id_categorie = $categorie_info['id_categorie'];
            $title = $categorie_info['title'];
        }

        $quizz = $repository -> findQuestions($id);
        $reponses = [];
        foreach ($quizz as $question)
        {
            $id_question = $question['id'];
            $id_quizz = $question['id_quizz'];
            $name = $question['name'];
            $answer = $repository -> findAnswers($id_question);
            array_push($reponses, $answer);
        }
        return $this -> render('quiz/quizz.html.twig', [
            'quizz' => $quizz,
            'reponses' => $reponses,
            'id_quizz' => $id_quizz,
            'id_categorie' => $id_categorie,
            'title' => $title,
            'name' => $name
        ]);
    }

    #[Route('/{title}_{id_categorie}/{name}_{id}/result', name: "result", methods: ["POST"])]
    public function submitForm(Request $request, QuestionRepository $repository, int $id, int $id_categorie)
    {
        $score = 0;
        $categorie_infos = $repository -> findCategorie($id_categorie);
        foreach ($categorie_infos as $categorie_info)
        {
            $categorie = $categorie_info['id_categorie'];
            $title = $categorie_info['title'];
        }

        $quizz = $repository -> findQuestions($id);
        $reponses = [];
        $green = [];
        $red = [];
        foreach ($quizz as $question)
        {
            $id_question = $question['id'];
            $id_quizz = $question['id_quizz'];
            $name = $question['name'];
            $answer = $repository -> findAnswers($id_question);
            array_push($reponses, $answer);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $responses = $request -> request -> all();
            foreach ($responses as $key => $response)
            {
                $key = trim($key, 'reponse_');
                $good_answer = $repository -> findGoodAnswer($key);
                if ((int)$response === $good_answer)
                {
                    $score ++;
                    $green[] = $repository -> findResponse($response);
                }
                else
                {
                    $red[] = $repository -> findResponse($response);
                    $green[] = $good_answer;
                }
            }
        }

        return $this -> render('quiz/result.html.twig', 
        [
            'title' => $title,
            'id_categorie' => $categorie,
            'name' => $name,
            'id' => $id_quizz,
            'reponses' => $reponses,
            'green' => $green,
            'red' => $red,
            'score' => $score

        ]);
    }
}