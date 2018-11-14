<?php

namespace App\Controller\Api;

use App\Entity\Flayer;
use App\Entity\User;
use App\Service\FlayerService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FlayerController extends FOSRestController
{
	/**
	 * @var FlayerService
	 */
	private $flayer_service;

	/**
	 * FlayerController constructor.
	 *
	 * @param FlayerService $flayer_service
	 */
	public function __construct(FlayerService $flayer_service)
	{
		$this->flayer_service = $flayer_service;
	}

	/**
	 * @Rest\Get("flayers", name="api.v1.flayers.index")
	 * @param Request $request
	 *
	 * @return View
	 */
    public function index(Request $request):View
    {
        return View::create([
        	'flayers' => ($this->getUser())->getFlayers($request)
        ],Response::HTTP_OK);
    }

	/**
	 * @Rest\Get("flayers/{slug}", name="api.v1.flayers.show")
	 * @param Flayer $flayer
	 *
	 * @return View
	 */
	public function show( Flayer $flayer ): View
	{
		return View::create(compact( 'flayer'),Response::HTTP_OK);
    }

	/**
	 * @Rest\Post("flayers", name="api.v1.flayers.store")
	 * @ParamConverter("flayer", converter="fos_rest.request_body")
	 * @param Flayer $flayer
	 * @param Request $request
	 *
	 * @param ConstraintViolationListInterface $violations
	 *
	 * @return View
	 */
	public function store(Flayer $flayer,Request $request,ConstraintViolationListInterface $violations): View
	{
		if (count($violations) > 0) {

			return View::create(compact( 'violations'),Response::HTTP_BAD_REQUEST);

		}

		$flayer = $this->flayer_service->storeFlayer( $this->getUser(), $request);

		return View::create(compact('flayer'),Response::HTTP_CREATED);

    }


	/**
	 * @Rest\Put("flayers/{slug}/update")
	 * @ParamConverter("flayer", converter="fos_rest.request_body")
	 *
	 * @param Flayer $flayer
	 * @param Request $request
	 * @param ConstraintViolationListInterface $violations
	 *
	 * @return View
	 */
	public function update( Flayer $flayer,Request $request,ConstraintViolationListInterface $violations ): View
	{
		if (count($violations) > 0) {

			return View::create(compact( 'violations'),Response::HTTP_BAD_REQUEST);

		}

		$flayer = $this->flayer_service->updateFlayer( $this->getUser(), $flayer, $request);

		return View::create(compact('flayer'),Response::HTTP_OK);
    }

	/**
	 * @Rest\Delete("flayers/{slug}")
	 *
	 * @param Flayer $flayer
	 *
	 * @return View
	 */
	public function destroy( Flayer $flayer ): View
	{
		return View::create([
			'deleted' => $this->flayer_service->deleteFlayer($flayer)
		],Response::HTTP_OK);
    }


}
