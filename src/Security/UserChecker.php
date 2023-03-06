<?php
namespace App\Security;

use App\Entity\LoginJournal;
use App\Entity\User as AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function __construct(EntityManagerInterface $userManager){
        $this->userManager = $userManager;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

    }

    public function checkPostAuth(UserInterface $user ): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        $loginJournal = $this->userManager->getRepository(LoginJournal::class)->findBy(
            ['user' => $user]
        );

        if($loginJournal)
        {
            $loginJournal[0]->setLastLogin(new \DateTime('now'));
            $this->userManager->persist($loginJournal[0]);
            $this->userManager->flush();
        }
        else
        {
            $_loginJournal = new LoginJournal();
            $_loginJournal->setUser($user);
            $_loginJournal->setFirstLogin(new \DateTime('now'));
            $_loginJournal->setLastLogin(new \DateTime('now'));

            $this->userManager->persist($_loginJournal);
            $this->userManager->flush();
        }

    }
}