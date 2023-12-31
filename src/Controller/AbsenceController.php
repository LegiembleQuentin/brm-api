<?php

namespace App\Controller;

use App\Entity\Absences;
use App\Filter\AbsenceFilter;
use App\Service\AbsenceService;
use DateTimeImmutable;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AbsenceController extends AbstractController
{
    private $serializer;
    private $absenceService;

    public function __construct(AbsenceService $absenceService)
    {
        $this->absenceService = $absenceService;
        $this->serializer = SerializerBuilder::create()->build();
    }

    #[Route('/absences', methods: ['GET'])]
    public function getAbsences(Request $request): Response
    {
        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $this->serializer->deserialize($jsonQuery, AbsenceFilter::class, 'json', DeserializationContext::create()->setGroups(['default']));

            if($request->query->get('date') != 'null' && $request->query->get('date') != 'undefined'){
                $date = DateTimeImmutable::createFromFormat('D M d Y H:i:s e+', $request->query->get('date'));
                $filters->setDate($date);
            }

            $absences = $this->absenceService->findByFilter($filters);

            $absencesJson = $this->serializer->serialize($absences, 'json', SerializationContext::create()->setGroups(['absence', 'default']));

        }catch (Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($absencesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/absence', methods: ['POST'])]
    public function addAbsence(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $absenceData = $content['body'];
            $absenceJson = json_encode($absenceData);

            $absence = $this->serializer->deserialize($absenceJson, Absences::class, 'json');

            $result = $this->absenceService->save($absence);

        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['absence', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/absence', methods:  ['PUT'])]
    public function updateAbsence(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $absenceData = $content['body'];
            $absenceJson = json_encode($absenceData);

            $absence = $this->serializer->deserialize($absenceJson, Absences::class, 'json');

            $result = $this->absenceService->update($absence);
        }catch (Exception $e){
            return new Response('Error processing request ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['absence', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/absence/{id}', methods: ['DELETE'])]
    public function deleteAbsence(int $id): Response
    {
        // GESTION DES ROLES

        try {
            $this->absenceService->delete($id);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {

            return $this->json(['message' => 'Error deleting absence: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/absence/{id}', methods: ['GET'])]
    public function getAbsence(int $id): Response
    {
        try {

            $absence = $this->absenceService->getAbsenceById($id);

            if (!$absence) {
                return $this->json(['message' => 'Absence not found'], Response::HTTP_NOT_FOUND);
            }

            $absenceJson = $this->serializer->serialize($absence, 'json', SerializationContext::create()->setGroups(['absence', 'default']));
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($absenceJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
