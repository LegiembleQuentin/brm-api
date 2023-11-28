<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;

use DateTime;
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

            if (!empty($decoded))
            {
                $email = $decoded->email;
                $username = $decoded->username;
                $roles = $decoded->roles;
                $enabled = 0; // = false
                $token = bin2hex(random_bytes(32));
                //Date d'expiration du token
                $expiry = date_create_immutable('+1');
                $expiry = $expiry->modify('+2 day');
                //$expiryToken = $expiry->format('Y-m-d H:i:s');
                $created_at = date_create_immutable();
                $roleEmployee = $decoded->roleEmployee;
                $sexe = $decoded->sexe;
                $name = $decoded->name;
                $fname = $decoded->fname;
                $birthday = DateTime::createFromFormat('Y-m-d H:i:s', $decoded->birth);
                $hire_date = DateTime::createFromFormat('Y-m-d H:i:s', $decoded->hire);
                $phone = $decoded->phone;
                $adress = $decoded->adress;
                $postal = $decoded->postal;
                $secuNum = $decoded->secuNum;
                $contract = $decoded->contract;
                $contractEnd = DateTime::createFromFormat('Y-m-d H:i:s', $decoded->contractEnd);
                $disability = $decoded->disability;
                $disabilityDesc = $decoded->disabilityDesc;
                $enabledEmployee = $enabled;

                $user = new User();
                $employee = new Employee();

                $employee->setEnabled($enabledEmployee);
                $employee->setCreatedAt($created_at);
                $employee->setUser($user->getId());
                $employee->setAdress($adress);
                $employee->setBirthdate($birthday);
                $employee->setContractEndDate($contractEnd);
                $employee->setContractType($contract);
                $employee->setDisability($disability);
                $employee->setDisabilityDesc($disabilityDesc);
                $employee->setFirstname($fname);
                $employee->setHireDate($hire_date);
                $employee->setName($name);
                $employee->setPhone($phone);
                $employee->setSexe($sexe);
                $employee->setPostalCode($postal);
                $employee->setSocialSecurityNumber($secuNum);
                $employee->setRole($roleEmployee);

                $user->setEmail($email);
                $user->setUsername($username);
                $user->setRoles($roles);
                $user->setEnabled($enabled);
                $user->setInvitationToken($token);
                $user->setInvitationTokenExpiry($expiry);
                $user->setCreatedAt($created_at);
                $em->persist($user);
                $em->persist($employee);
                $em->flush();
            }else {
               return $this->json(['Erreur :' => 'les données sont absentes']);
            }
        return $this->json(["tokenUser" => $token]);
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
        $decode = json_decode($request->getContent());
        $token =  $decode->token;
        $user = $em->getRepository(User::class)->findOneBy(['invitationToken' => $token]);
        if(empty($user)) {
            return $this->json([$token]);
        }

        return $this->json(['valid' => true]);
    }

    //#[Route('/test', name: 'test', methods: "GET")]
    //public function test(Request $request, EntityManagerInterface $em): JsonResponse
    //{
        //return $this->json(['valid' => true]);
    //}
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
            $em->persist($user);
            $em->flush();


            $user->setEnabled(1);
            $user->setInvitationToken(null);
        }

        return $this->json(['TOKEN' => $decode->token,'pass' => $decode->password, 'user' => $user->getEmail()]);
    }



}
