<?php

namespace App\Controller\Api;

use App\Entity\Flayer;
use App\Entity\Photo;
use App\Entity\User;
use App\Service\FlayerService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Swagger\Annotations\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class FlayerController
 * @package App\Controller\Api
 *
 */
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
	 *
	 *
	 * @Tag(name="Flayers")
	 *
	 * @SWG\Response(response="200", description="List all the available flayers")
	 *
	 * @Security(name="Bearer")
	 *
	 *
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
	 *
	 * @Tag(name="Flayers")
	 *
	 * @SWG\Response(response="200", description="Show an individual Flayer represented by its **slug**.",@Model(type=User::class))
	 *
	 * @SWG\Parameter(name="slug", description="The flayer representation", required=true, type="string",in="path")
	 *
	 * @Security(name="Bearer")
	 *
	 * @param Flayer $flayer
	 * @return View
	 */
	public function show( Flayer $flayer ): View
	{
		return View::create(compact( 'flayer'),Response::HTTP_OK);
    }


	/**
	 * @Rest\Post("flayers", name="api.v1.flayers.store")
	 * @ParamConverter("flayer", converter="fos_rest.request_body")
	 *
	 *
	 * @Tag(name="Flayers")
	 *
	 * @SWG\Response(response="201", description="Store a new Flayer")
	 *
	 * @SWG\Response(
	 *     response="400",
	 *     description="Http Bad request response thrown when the fields are invalid.",
	 *     examples={
	 *          "application/json": {
	 *              "violations":  {
	 *                  {
	 *                      "property_path": "street",
	 *                      "message": "This value should not be blank."
	 *                  },
	 *              }
	 *          }
	 *     },
	 * )
	 *
	 *
	 * @SWG\Parameter(name="slug", description="The flayer representation", required=true, type="string",in="path")
	 *
	 * @SWG\Parameter(
	 *     name="title",
	 *     description="The Title of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="title",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="title",
	 *                  title=":title",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="description",
	 *     description="The Description of the flayer",
	 *     required=true,
	 *     type="text",
	 *     in="body",
	 *     parameter="description",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="description",
	 *                  title=":description",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 *  * @SWG\Parameter(
	 *     name="price",
	 *     description="The Price of the flayer",
	 *     required=true,
	 *     type="integer",
	 *     in="body",
	 *     parameter="price",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="price",
	 *                  title=":price",
	 *                  type="integer",
	 *                  minimum=100000
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="street",
	 *     description="The Street name",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="street",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="street",
	 *                  title=":street",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="zip",
	 *     description="The Postal code",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="zip",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="zip",
	 *                  title=":zip",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 * @SWG\Parameter(
	 *     name="city",
	 *     description="The City of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="city",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="city",
	 *                  title=":city",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="state",
	 *     description="The State of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="state",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="state",
	 *                  title=":state",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="Country",
	 *     description="The Country of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="country",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="country",
	 *                  title=":country",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @Security(name="Bearer")
	 *
	 * @param Flayer $flayer
	 * @param Request $request
	 * @param ConstraintViolationListInterface $violations
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
	 *
	 * @Tag(name="Flayers")
	 *
	 * @SWG\Response(response="200", description="Update a given flayer represented by its slug")
	 *
	 * @SWG\Response(
	 *     response="400",
	 *     description="Http Bad request response thrown when the fields are invalid.",
	 *     examples={
	 *          "application/json": {
	 *              "violations":  {
	 *                  {
	 *                      "property_path": "street",
	 *                      "message": "This value should not be blank."
	 *                  },
	 *              }
	 *          }
	 *     },
	 * )
	 *
	 *
	 * @SWG\Parameter(name="slug", description="The flayer representation", required=true, type="string",in="path")
	 *
	 * @SWG\Parameter(
	 *     name="title",
	 *     description="The Title of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="title",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="title",
	 *                  title=":title",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="description",
	 *     description="The Description of the flayer",
	 *     required=true,
	 *     type="text",
	 *     in="body",
	 *     parameter="description",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="description",
	 *                  title=":description",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 *  * @SWG\Parameter(
	 *     name="price",
	 *     description="The Price of the flayer",
	 *     required=true,
	 *     type="integer",
	 *     in="body",
	 *     parameter="price",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="price",
	 *                  title=":price",
	 *                  type="integer",
	 *                  minimum=100000
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="street",
	 *     description="The Street name",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="street",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="street",
	 *                  title=":street",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="zip",
	 *     description="The Postal code",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="zip",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="zip",
	 *                  title=":zip",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 * @SWG\Parameter(
	 *     name="city",
	 *     description="The City of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="city",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="city",
	 *                  title=":city",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="state",
	 *     description="The State of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="state",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="state",
	 *                  title=":state",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @SWG\Parameter(
	 *     name="Country",
	 *     description="The Country of the flayer",
	 *     required=true,
	 *     type="string",
	 *     in="body",
	 *     parameter="country",
	 *     schema=@SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(
	 *                  property="country",
	 *                  title=":country",
	 *                  type="string"
	 *          )
	 *      )
	 * )
	 *
	 * @Security(name="Bearer")
	 *
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
	 * @Rest\Delete("flayers/{slug}/photos")
	 *
	 *
	 * @Tag(name="Flayers")
	 *
	 * @SWG\Response(response="200", description="Delete a given flayer represented by its slug")
	 *
	 * @SWG\Parameter(name="slug", description="The flayer representation", required=true, type="string",in="path")

	 * @Security(name="Bearer")
	 *
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

	/**
	 * @Rest\Post("flayers/{slug}/photos")
	 *
	 * @Tag(name="Photos")
	 *
	 * @SWG\Response(response="201", description="Delete a given flayer represented by its slug")
	 *
	 * @SWG\Response(
	 *     response="400",
	 *     description="Http Bad request response thrown when the fields are invalid.",
	 *     examples={
	 *          "application/json": {
	 *              "violations":  {
	 *                  {
	 *                      "property_path": "[0]",
	 *                      "message": "The mime type of the file is invalid ('image/png'). Allowed mime types are 'jpg','jpeg'."
	 *                  },
	 *              }
	 *          }
	 *     },
	 * )
	 * @SWG\Parameter(name="slug", description="The flayer representation", required=true, type="string",in="path")
	 * @param Flayer $flayer
	 * @param Request $request
	 *
	 * @param ValidatorInterface $validator
	 *
	 * @return View
	 */
	public function upload(Flayer $flayer, Request $request ,ValidatorInterface $validator): View
	{

		$photos =  $request->files->get('photos');

		$constraints = new All([
			'constraints' => [
				new File([
					"mimeTypes" => ["jpg","jpeg"]
				])
			]
		]);


		if (count($violations = $validator->validate($photos,$constraints)) > 0)
		{

			return View::create(compact( 'violations'),Response::HTTP_BAD_REQUEST);

		}

		if ( $uploaded = ($paths = $this->flayer_service->uploadPhotos($flayer, $photos)) > 0 )
		{
			return View::create(['message' => "Something went wrong!"],Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		return View::create(compact( 'uploaded','paths'), Response::HTTP_CREATED);
	}

}
