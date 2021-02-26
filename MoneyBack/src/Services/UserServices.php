<?php

namespace App\Services;

$avatar = null;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class UserServices
{


    //function upload
    public function _files($request)
    {
        global $avatar;
        $avatar = $request->files->get("avatar");
        if ($avatar) {
            $avatar = fopen($avatar->getRealPath(), "rb");
            return $avatar;
        }
        return null;
    }

    public function formBinary($request)
    {

        $user = $request->getContent();
        $boundary = str_replace("multipart/form-data; boundary=", "", $request->headers->get('Content-Type'));
        $delimit = [$boundary, " ", "--", "\n", '"', "Content-Disposition:", "form-data;", "name="];
        $tab = str_replace($delimit, "", $user);
        $explo = explode("\r\r", $tab);
        $datas = [];

        for ($i = 0; isset($explo[$i + 1]); $i += 2) {
            $d = str_replace(["\r"], "", $explo[$i]);
            if (strstr($d, "avatar")) {
                $d = "avatar";
                $stream = fopen("php://memory", "r+");
                fwrite($stream, $explo[$i + 1]);
                rewind($stream);
                $datas[$d] = $stream;
            } else {
                $datas[$d] = $explo[$i + 1];
            }
        }
        return $datas;
    }


    //add or update user 
    public function newUser($request, $serializer, $validator, $entity, $manager, $encoder)
    {
        $user = $request->request->all();
        if (!$user) $user = json_decode($request->getContent(), true);

        $user["avatar"] = $this->_files($request);
        
        
        $user = $serializer->denormalize($user, $entity);
        $password = "pass_1234";
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setArchive(0);
        // dd($user);
        $errors = $validator->validate($user);
        //dd($errors);
        if (count($errors)) {
            $errors = $this->serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
        }
       

        $user = $this->flushUser($manager, $user);
    
        return $user;
    }

    //update user
    public function updateUser($request, $useripo, $profilripo, $manager, $encoder)
    {
        $user = $useripo->findOneBy(["id" => $request->attributes->get('id')]);
        $datas =  $this->formBinary($request);
        $user = $this->setterDynamic($datas, $user, $profilripo, $encoder);
        $users = $this->flushUser($manager, $user);
        //dd($users);
        return $users;
    }
    // setter dynamique 
    public function setterDynamic($data, $u, $profilripo, $encoder)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                $setter = 'set' . ucfirst(strtolower($key));
                if (method_exists($u, $setter)) {
                    if ($key == 'profil') {
                        $profile = $data['profil'];
                        $profile = $profilripo->findOneBy(['id' => $profile]);
                        // dd($profile->getLibelle());
                        $u->$setter($profile);
                    } elseif ($key == 'password') {
                        $u->$setter($encoder->encodePassword($u, $data['password']));
                    } else {
                        $u->$setter($value);
                    }
                }
            }
        }
        return $u;
    }


    //function persister and fush object
    public function flushUser($manager, $user)
    {
        $manager->persist($user);
        $manager->flush();
        return $user;
    }
}
