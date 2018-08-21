<?php
namespace App\Model;

use App\Model\Entities\User;
use Doctrine\ORM\EntityManager;
use Kdyby\Translation\Translator;
use Nette\Security as NS;
use Nette\SmartObject;


class Authenticator implements NS\IAuthenticator
{
    use SmartObject;

    /**
     * @var EntityManager
     */
    public $em;

    /** @var Translator */
    private $translator;

    public function __construct(EntityManager $em, Translator $translator)
    {
        $this->em = $em;
        $this->translator=$translator;
    }

    /**
     * Performs an authentication
     * @param  array
     * @return NS\Identity
     * @throws NS\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $criteria=array("email" => $username);

        $user = $this->em->getRepository(User::class)->findOneBy($criteria);
        if (!$user) {
            throw new NS\AuthenticationException($this->translator->translate('main.app.sign.unknown'), self::IDENTITY_NOT_FOUND);
        }

        (NS\Passwords::verify($password, $user->getPassword())) ? $passVerified = TRUE : $passVerified = FALSE;

        if (!$passVerified) {
            throw new NS\AuthenticationException($this->translator->translate('main.app.sign.wrongpasswd'), self::IDENTITY_NOT_FOUND);
        }

        return new NS\Identity($user->getId(), $user->getRole()->getName());
    }

}
