<?php

namespace App\Controller;

use App\Entity\User;

use DateTimeImmutable;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;


use Symfony\Component\Serializer\SerializerInterface;





#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{

    private  JWTTokenManagerInterface $jwtManager;
    public function __construct(JWTTokenManagerInterface $jwtManager )

    {
        $this->jwtManager = $jwtManager;
    }
   



    #[Route('/adduser', name: 'app_adduser', methods: "POST")]
    public function addUser(Request $request,EntityManagerInterface $em): JsonResponse
    {
            $decoded = json_decode($request->getContent());

            $email = $decoded->email;
            $username = $decoded->username;
            $roles = $decoded->roles;
            // enbaled = false par défaut -> passera à true quand le user aura set son password après la verif de son token
            $enabled = 0; // = false
            // Création du token à stocker en bdd // invitation_token
            $token = bin2hex(random_bytes(32));
            //Date d'expiration du token
            $expiry = date_create_immutable('+1');
            $expiry = $expiry->modify('+2 day');
            $expiryToken = $expiry->format('Y-m-d H:i:s');
            $created_at = date_create_immutable();

            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setEnabled($enabled);
            $user->setInvitationToken($token);
            $user->setInvitationTokenExpiry($expiry);
            $user->setCreatedAt($created_at);
            $em->persist($user);
            $em->flush();
        return $this->json(["tokenUser" => $token]);
    }
//    #[Route('/validating', name: 'app_valid', methods: "POST" )]
//    public function validUser(Request $request): JsonResponse
//    {
//
//
//        return $this->json([]);
//    }
        //Add admin function, no token necessary.
    #[Route('/addadmin', name: 'app_addadmin', methods: "POST")]
    public function addAdmin(Request $request,SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $decoded = json_decode($request->getContent());
        if (!empty($decoded)) {
            $email = $decoded->email;
            $password = $decoded->password;
            $username = $decoded->username;
            $roles = $decoded->roles;
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
            return $this->json(['' => 'Error, empty body data']);
        }
        return $this->json(['' => 'registered & enabled']);

    }
    #[Route('/verify-token', name: 'verify_token', methods: "POST")]
    public function verifyToken(Request $request, EntityManagerInterface $em): JsonResponse {
        $token = json_decode($request->getContent())->token;

        $user = $em->getRepository(User::class)->findOneBy(['invitation_token' => $token]);

        if (!$user || $user->getInvitationTokenExpiry() < new \DateTimeImmutable()) {
            return $this->json(['status' => 'invalid']);
        }

        return $this->json(['status' => 'valid']);
    }

}
