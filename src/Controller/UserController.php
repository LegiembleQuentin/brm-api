<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{

    private  JWTTokenManagerInterface $jwtManager;
    public function __construct(JWTTokenManagerInterface $jwtManager )

    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/verify-token', name: 'verify_token', methods: "POST")]
    public function verifyToken(Request $request, EntityManagerInterface $em): JsonResponse {
        $decode = json_decode($request->getContent());
        $token =  $decode->token;
        $user = $em->getRepository(User::class)->findOneBy(['invitationToken' => $token]);
        if(empty($user)) {
            return $this->json([$token]);
        }

        return $this->json(['valid' => true]);
    }

    #[Route('/setpassword', name: 'setpassword', methods: "POST")]
    public function setPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $decode = json_decode($request->getContent());
        $token = $decode->token;
        $user = $em->getRepository(User::class)->findOneBy(['invitationToken' => $token]);
        if (empty($user)) {
            return $this->json(['Error :' => 'aucune donnée user']);
        }
        if (empty($decode)) {
            return $this->json(['Error :' => 'aucune donnée']);
        }
        if ($user->getEnabled() == 1) {
            return $this->json(['Error :' => 'compte deja activer']);
        }elseif ($user->getEnabled() == 0) {

            $password = $decode->password;
            $hashedPassword = $passwordEncoder->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $user->setEnabled(1);
            $user->setInvitationToken(null);

            $em->persist($user);
            $em->flush();
        }

        return $this->json(['TOKEN' => $decode->token,'pass' => $decode->password, 'user' => $user->getEmail()]);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $em,JWTTokenManagerInterface $JWTManager,UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $decode = json_decode($request->getContent());
        if (empty($decode)) {
            return $this->json(['Error : ' => 'Body empty']);
        }
        $email = $decode->username;
        $password = $decode->password;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return  $this->json(['error' => 'Identifiants incorrects']);
        }
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            return $this->json(['error' => 'Identifiants ou mot de passe incorrect', 'code' => 401]);
        }
         if (!$passwordEncoder->isPasswordValid($user ,$password)) {
             return $this->json(['error' => 'Identifiants ou mot de passe incorrect', 'code' => 401]);
         }

        $token = $JWTManager->create($user);

    return $this->json(['token' => $token]);
    }
}
