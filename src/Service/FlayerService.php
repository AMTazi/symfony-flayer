<?php
/**
 * Created by PhpStorm.
 * User: naimo
 * Date: 11/14/18
 * Time: 10:45 AM
 */

namespace App\Service;


use App\Entity\Flayer;
use App\Entity\Photo;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FlayerService {

	/**
	 * @var ObjectManager
	 */
	private $manager;
	/**
	 * @var UploaderHelper
	 */
	private $helper;

	public function __construct(ObjectManager $manager, UploaderHelper $helper)
	{
		$this->manager = $manager;

		$this->helper = $helper;
	}

	public function storeFlayer(User $user, Request $request): Flayer
	{
		$flayer = $this->setFlayerData($user, new Flayer(), $request);

		$this->persistAndFlush( $flayer );

	    return $flayer;

	}

	public function updateFlayer( User $user, Flayer $flayer, $request ): Flayer
	{
		$flayer =  $this->setFlayerData($user,$flayer,$request);

		$this->persistAndFlush( $flayer );

		return $flayer;
	}

	private function setFlayerData(User $user, Flayer $flayer , Request $request ): Flayer
	{
		$flayer
			->setStreet( $request->get( 'street'))
			->setCity( $request->get( 'city'))
            ->setState( $request->get( 'state'))
            ->setCountry( $request->get( 'country'))
            ->setZip( $request->get( 'zip'))
            ->setPrice( $request->get( 'price'))
            ->setDescription( $request->get( 'description'))
            ->setTitle( $request->get( 'title'))
            ->setSlug( Flayer::makeSlug($request->get( 'title')));

		$user->addFlayer( $flayer);

		return $flayer;
	}

	/**
	 * @param $flayer
	 */
	private function persistAndFlush( $flayer ): void
	{
		$this->manager->persist( $flayer );

		$this->manager->flush();
	}

	public function deleteFlayer( $flayer ): bool
	{
		$this->manager->remove( $flayer);

		$this->manager->flush();

		return true;
	}

	public function uploadPhotos( Flayer $flayer, array $photos ): array
	{
		$paths = [];

		foreach ($photos as $p) {

			$photo = new Photo();
			$photo->setImageFile( $p);
			$flayer->addPhoto( $photo);
			$this->manager->persist( $photo);
			$this->manager->flush();

			$paths[] = $this->helper->asset( $photo, 'imageFile');
		}


		return $paths;
	}


}