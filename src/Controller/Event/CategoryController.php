<?php

namespace App\Controller\Event;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class CategoryController extends ApiController
{
    /**
     * @var EntityManager
     **/
    private $entityManager;

    /**
     * @var CategoryRepository
     **/
    private $categoryRepository;

    /**
     * @var Encoders
     **/
    private $encoders;

    /**
     * @var Normalizers
     **/
    private $normalizers;

    /**
     * @var Serializer
     **/
    private $serializer;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $this->normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @Rest\Get("/getCategories")
     */
    public function getCategories()
    {
        $objectToSerialize = $this->categoryRepository->findAll();
        // Serialize your object in Json
        $jsonObject = $this->serializer->serialize($objectToSerialize, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);    }

    /**
     * @Rest\Get("/getCategorieEvents/{id}")
     */
    public function getCategoryEvents(Request $request)
    {
        $request = $this->transformJsonBody($request);
        $categoryDB = $this->categoryRepository->findOneBy(['id' => $request->get('id')]);
        $events = $categoryDB->getEvents();
        // $jsonObject = $this->serializer($event,$this->serializer);
        //return $this->respond($jsonObject);
        $jsonObject = $this->serializer->serialize($events, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);

    }

    /**
     * @Rest\Post("/createCategory")
     */
    public function createCategory(Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate Variables Needed !!!!!
        if (! $request->get('categoryName')) {
            return $this->respondValidationError('Please provide a categoryName!');
        }
        // Create and persist the new event Config using cascade since that the relation is composition oneToOne
        $category = $this->category($request);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $jsonObject = $this->serializer->serialize($category, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }



    /**
     * @Rest\Patch("/updateCategory/{id}")
     */
    public function updateCategory(Request $request)
    {
        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }
        // validate Variables Needed !!!!!
        if (! $request->getContent()) {
            return $this->respondValidationError('Please provide an Event!');
        }

        if (! $request->get('eventName')) {
            return $this->respondValidationError('Please provide an event!');
        }

        $modifiedEvent = $this->serializer->deserialize($request->getContent(),'App\Entity\Event' ,'json');
        $event = $this->eventRepository->findOneBy([
            'id' => $request->get('id')]);

        if (! $modifiedEvent) {
            return $this->respondValidationError('No Event entity with this (id = ' . $modifiedEvent->get('id') .") ". 'exist');
        }
        $event->setEventName($modifiedEvent->getEventName());
        $event->setDistance($modifiedEvent->getDistance());
        $event->setLocation($modifiedEvent->getLocation());
        $event->setStartDate( $modifiedEvent->getStartDate());
        $event->setEndDate( $modifiedEvent->getStartDate());
        $event->setIsTheme($modifiedEvent->getIsTheme());
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        /** ONLY FOR TEST */
        //return $this->json([
        //     'response' => 'Updated Successfully'
        // ]);
        /**              **/

        $jsonObject = $this->serializer($event, $this->serializer);
        return $this->respond($jsonObject);
    }

    /**
     * @Rest\Get("/ping")
     */
    public function healthCheck()
    {
        return $this->json([
            'response' => 'pong',
        ]);
    }


    private  function category(Request $req) {
        $category = new Category();
        $category->setCategoryName( $req->get('categoryName'));
        return $category;

    }


}
