<?php


namespace App\Security;


use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
// these strings are just invented: you can use anything
    const DELETE = 'delete';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $userLogged = $token->getUser();

        if (!$userLogged instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($user, $userLogged);

            case self::EDIT:
                return $this->canEdit($user, $userLogged);
        }
        throw new LogicException('This code should not be reached!');
    }

    private function canDelete(User $user, User $userLogged): bool
    {
        return $user === $userLogged;
    }

    private function canEdit(User $user, User $userLogged): bool
    {
        return $user === $userLogged;
    }
}