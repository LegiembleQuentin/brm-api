<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\UserRestaurant;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $em;
    private $restaurantService;

    public function __construct(EntityManagerInterface $entityManager, RestaurantService $restaurantService)
    {
        $this->em = $entityManager;
        $this->restaurantService = $restaurantService;
    }

    public function addUser($employee)
    {
        $user = new User();

        $token = bin2hex(random_bytes(32));
        $expiry = date_create_immutable('+1');
        $expiry = $expiry->modify('+3 day');

        $user->setEmail($employee->getEmail());
        $user->setUsername($employee->getEmail());

        $role = null;
        if ($employee->getRole = 'DIRECTOR'){$role = 'ROLE_DIRECTOR';}
        else if ($employee->getRole = 'DIRECTOR'){$role = 'ROLE_DIRECTOR';}

        $user->setRoles([$role]);
        $user->setEnabled(false);
        $user->setInvitationToken($token);
        $user->setInvitationTokenExpiry($expiry);
        $user->setCreatedAt(new \DateTimeImmutable());

        $restaurant = $this->restaurantService->getRestaurantById($employee->getRestaurant()->getId());
        $userRestaurant = new UserRestaurant();
        $userRestaurant->setRestaurant($restaurant);
        $userRestaurant->setUser($user);
        $this->em->persist($userRestaurant);

        $user->addUserRestaurant($userRestaurant);

        $this->em->persist($user);
        $this->em->flush();

        //a modifier quand le site sera en ligne lol
        $baseUrl = "http://localhost:4200/verify-token";
        $link = $baseUrl . "?token=" . $token;

        return $link;
    }

}