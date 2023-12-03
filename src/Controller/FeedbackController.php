<?php

namespace App\Controller;

use App\Filter\FeedbackFilter;
use App\Service\FeedbackService;
use DateTimeImmutable;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class FeedbackController extends AbstractController
{
    private $serializer;
    private $feedbackService;

    public function __construct(SerializerInterface $serializer, FeedbackService $feedbackService)
    {
        $this->serializer = SerializerBuilder::create()->build();;
        $this->feedbackService = $feedbackService;
    }

    #[Route('/feedbacks', methods: ['GET'])]
    public function getFeedbacks(Request $request): Response
    {
        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $this->serializer->deserialize($jsonQuery, FeedbackFilter::class, 'json', DeserializationContext::create()->setGroups(['default']));

            if($request->query->get('date') != 'null' && $request->query->get('date') != 'undefined'){
                $date = DateTimeImmutable::createFromFormat('D M d Y H:i:s e+', $request->query->get('date'));
                $filters->setDate($date);
            }
            $warning = $request->query->get('warning') === 'true';
            $filters->setWarning($warning);

            $feedbacks = $this->feedbackService->findByFilter($filters);
//            $feedbacks = $this->feedbackService->getFeedbacks();

            $feedbacksJson = $this->serializer->serialize($feedbacks, 'json', SerializationContext::create()->setGroups(['default', 'feedback']));

        }catch (Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($feedbacksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
