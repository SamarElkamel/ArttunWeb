<?php

namespace App\Security;

use App\Entity\user\User;
use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $usererpo;

    public function __construct(private UrlGeneratorInterface $urlGenerator,UserRepository $userrepo)
    {
        $this->usererpo = $userrepo;
    }

    public function authenticate(Request $request): Passport
    {
        $adresseMail = $request->request->get('adresseMail', '');
        $password = $request->request->get('password', '');
        $request->getSession()->set(Security::LAST_USERNAME, $adresseMail);
        //check if user matches user in database logic
        $user = $this->usererpo->findOneBy(['adresseMail' => $adresseMail]);
        if($user!=null && $user->getMdp()==$password) {
            $request->getSession()->set('name',$user->getNom());
            $request->getSession()->set('prenom',$user->getPrenom());
            $request->getSession()->set('type',$user->getType());
            $request->getSession()->set('photo',$user->getPhoto());

            return new SelfValidatingPassport(new UserBadge($adresseMail), []);
        }
        return new Passport(
            new UserBadge($adresseMail),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_user_crud'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
