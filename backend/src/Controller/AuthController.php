<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\LoginRequest;
use App\DTO\Request\RegisterRequest;
use App\DTO\Response\UserResponse;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    #[Route('/register', methods: ['POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $dto = RegisterRequest::fromRequest($request);
        $errors = $validator->validate($dto);
        
        if (count($errors) > 0) {
            return $this->json(['message' => 'Validation failed'], 422);
        }
        
        $user = new User($dto->email, $dto->fullName);
        $user->setPasswordHash($hasher->hashPassword($user, $dto->password));
        
        $em->persist($user);
        $em->flush();
        
        return $this->json(UserResponse::fromEntity($user)->toArray(), 201);
    }
}
