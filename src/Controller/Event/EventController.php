<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Entity\EventConfig;
use App\Repository\EventConfigRepository;
use App\Repository\EventRepository;
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

class EventController extends ApiController
{
    /**
     * @var EntityManager
     **/
    private $entityManager;

    /**
     * @var EventRepository
     **/
    private $eventRepository;

    /**
     * @var EventConfigRepository
     **/
    private $eventConfigRepository;

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

    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
        $this->encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $this->normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @Rest\Get("/getEvents")
     */
    public function getEvents()
    {
        $objectToSerialize = $this->repo->findAll();
        // Serialize your object in Json
        $jsonObject = $this->serializer->serialize($objectToSerialize, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);    }

    /**
     * @Rest\Get("/getEvent/{id}")
     */
    public function getEventAction(Request $request)
    {
        $request = $this->transformJsonBody($request);
        $event = $this->eventRepository->findOneBy(['id' => $request->get('id')]);
       // $jsonObject = $this->serializer($event,$this->serializer);
        //return $this->respond($jsonObject);
        $jsonObject = $this->serializer->serialize($event, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);

    }

    /**
     * @Rest\Post("/postEvent")
     */
    public function putEventAction(Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate Variables Needed !!!!!
        if (! $request->get('eventName')) {
            return $this->respondValidationError('Please provide a event!');
        }
        // Create and persist the new event Config using cascade since that the relation is composition oneToOne
            $event = new Event();
            $eventConfig = $request->get('eventConfig');
            $event->setEventName( $request->get('eventName'));
            $event->setDistance($request->get('distance'));
            $event->setLocation($request->get('location'));
            $event->setStartDate( (new \DateTime())->setTimestamp($request->get('startDate')));
            $event->setEndDate( (new \DateTime())->setTimestamp($request->get('endDate')));
            $event->setIsTheme($request->get('isTheme'));
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            $jsonObject = $this->serializer->serialize($event, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Rest\Delete("/deleteEvent/{id}")
     */
    public function deleteEventAction(int $id)
    {
        return $this->json([
            'message' => 'Weclome to your new Controller',
            'path' => 'src/Controller/WelcomeController.php',
        ]);
    }

    /**
     * @Rest\Patch("/updateEvent/{id}")
     */
    public function patchEventAction(int $id)
    {
        return $this->json([
            'message' => 'Weclome to your new Controller',
            'path' => 'src/Controller/WelcomeController.php',
        ]);
    }


}
