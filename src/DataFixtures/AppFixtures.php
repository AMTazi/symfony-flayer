<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{

		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
    {
		$user = new User();
		$password = $this->encoder->encodePassword( $user, "secret");

		$user->setEmail( 'test@example.com');
		$user->setRoles( ['ROLE_USER']);
		$user->setPassword( $password);

		$manager->persist($user);
        $manager->flush();
    }
}
