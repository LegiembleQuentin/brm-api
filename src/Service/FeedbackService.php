<?php

namespace App\Service;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeedbackService {
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, RestaurantService $restaurantService, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return Feedback[]
     */
    public function getFeedbacks() : array
    {
        $feedbackRepo = $this->em->getRepository(Feedback::class);
        return $feedbackRepo->findAll();
    }

}