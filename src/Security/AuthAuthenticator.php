<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $uuid = $request->request->get('uuid', '');

        $request->getSession()->set(Security::LAST_USERNAME, $uuid);

        return new Passport(
            new UserBadge($uuid),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
//        dd($this->getTargetPath($request->getSession(), $firewallName));
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();
        if(in_array('ROLE_INSPECTOR', $user->getRoles(), true))
        {
            return new RedirectResponse($this->urlGenerator->generate('app_inspector_infrastructure_sheet'));
        }
        if(in_array('ROLE_SPECTATOR', $user->getRoles(), true))
        {

            return new RedirectResponse($this->urlGenerator->generate('app_inspector_infrastructure_sheet'));
        }
        if(in_array('ROLE_DISCIPLINE', $user->getRoles(), true))
        {

            return new RedirectResponse($this->urlGenerator->generate('app_inspector_infrastructure_sheet'));
        }
        if(in_array('ROLE_DIRECTORATE', $user->getRoles(), true))
        {

            return new RedirectResponse($this->urlGenerator->generate('app_inspector_infrastructure_sheet'));
        }
        if(in_array('ROLE_REGION', $user->getRoles(), true))
        {
            return new RedirectResponse($this->urlGenerator->generate('app_main'));
        }
        if(in_array('ROLE_ADMIN', $user->getRoles(), true) or in_array('ROLE_SUPERADMIN', $user->getRoles(), true))
        {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    protected function getLoginUrl(Request $request): string
    {
//        if(str_contains($request->getPathInfo(), 'api'))
//            throw new CustomUserMessageAuthenticationException('No API token provided');
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
