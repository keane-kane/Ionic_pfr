<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        if( $id != 0){
            $data = $this->userRepository->findOneBy(['archive' => false, 'id' => $id]);
           return $data;
      }
        elseif(($context["item_operation_name"] ?? null) == "GET")
        $id = explode("/",$context["request_uri"])[3];
       // dd($id);
        return $this->userRepository->findOneBy(['archive' => false, 'email' => $id]);
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {

        return $this->userRepository->findBy(['archive' => false]);
    }
}
