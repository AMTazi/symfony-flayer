<?php

namespace App\DataFixtures;

use App\Entity\Flayer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Generator\Instantiator\FakeInstantiator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	protected $faker;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{

		$this->encoder = $encoder;
		$this->faker = Factory::create();

	}

	public function load(ObjectManager $manager)
    {
		$user = new User();
		$password = $this->encoder->encodePassword( $user, "secret");

		$user->setEmail( $this->faker->email);
		$user->setRoles( ['ROLE_USER']);
		$user->setPassword( $password);

		$manager->persist($user);
        $manager->flush();




	    $flayer = new Flayer();

	    $flayer
			->setStreet( $this->faker->streetName)
			->setCity( $this->faker->city)
			->setState( $this->faker->city)
			->setCountry( $this->faker->country)
			->setZip( $this->faker->postcode)
			->setPrice( $this->faker->numberBetween(160000,180000))
			->setDescription( $this->faker->paragraph)
			->setTitle( $title = $this->faker->sentence)
			->setSlug( Flayer::makeSlug(  $title ));
	    $user->addFlayer( $flayer);

	    $manager->persist($flayer);
	    $manager->flush();
    }
}
