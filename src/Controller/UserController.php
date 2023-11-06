<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    #[Route('/adduser', name: 'app_adduser', methods: "POST")]
    public function addUser(Request $request,SerializerInterface $serializer): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        //$jsonData = $serializer->serialize($data, 'json');
        return $this->json([$decoded]);
    }
        //Add admin function, no token necessary.
    #[Route('/addadmin', name: 'app_addadmin', methods: "POST")]
    public function addAdmin(Request $request,SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        if (!empty($decoded)) {
            $email = $decoded->email;
            $password = $decoded->password;
            $username = $decoded->username;
            $roles = ['ROLE_ADMIN'];
            $enabled = $decoded->enabled;
            //$datetime = 'now';
            $created_at = date_create_immutable();

            $user = new User();
            $hashedPassword = $passwordEncoder->hashPassword($user, $password);


            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($hashedPassword);
            $user->setEnabled($enabled);
            $user->setRoles($roles);
            $user->setCreatedAt($created_at);
            $em->persist($user);
            $em->flush();


        } else {
            return $this->json(["message" => "Error, empty body data"]);
        }
        return $this->json(["message" => "success"]);

    }
}
