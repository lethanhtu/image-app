<?php

 namespace App\Security;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

 class ImageVoter extends Voter
 {
     const DELETE = 'delete';
     const LIKE = 'like';

     protected function supports($attribute, $subject)
     {
        if(!in_array($attribute, array(self::DELETE, self::LIKE))) {
            return false;
        }

        if(!$subject instanceof Image) {
            return false;
        }

        return true;

     }

     public function voteOnAttribute($attribute, $subject, TokenInterface $token)
     {
         $user = $token->getUser();

         if(!$user instanceof User) {
             return false;
         }

         switch($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $user);
            case self::LIKE:
                return $this->canLike($subject, $user);

         }

         throw new \LogicException('Image voter doesn\'t work properly');
     }

     public function canDelete($image, $user)
     {
         if($image->getUploadedBy() === $user || $user->getRole() === User::ROLE_ADMIN) {
             return true;
         }

         return false;
     }

     public function canLike($subject, $user)
     {
         return true;
     }
 }
