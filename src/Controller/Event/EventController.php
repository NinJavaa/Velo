<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Entity\EventConfig;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
class EventController extends AbstractFOSRestController
{
    /**
     * @var EventRepository
     */
    private $repo;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $encoders;
    private $normalizers;
    private $serializer;

    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManager)
    {
        $this->repo = $eventRepository;
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
     * @Rest\Get("/getEvent{id}")
     */
    public function getEventAction(int $id)
    {
        return $this->json([
            'message' => 'Weclome to your new Controller',
            'path' => 'src/Controller/WelcomeController.php',
        ]);
    }

    /**
     * @Rest\QueryParam(name="eventName", description="title of the list", nullable=false)
     * @Rest\QueryParam(name="distance", description="title of the list", nullable=false)
     * @Rest\QueryParam(name="location", description="title of the list", nullable=false)
     * @Rest\QueryParam(name="startDate", description="title of the list", nullable=false)
     * @Rest\QueryParam(name="endDate", description="title of the list", nullable=false)
     * @Rest\QueryParam(name="isTheme", description="title of the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @Rest\Put("/putEvent")
     * @return
     */
    public function putEventAction(ParamFetcher $paramFetcher)
    {
        $eventName = $paramFetcher->get('eventName');
        $distance = $paramFetcher->get('distance');
        $location = $paramFetcher->get('location');
        $startDate = $paramFetcher->get('startDate');
        $endDate = $paramFetcher->get('endDate');
        $isTheme = $paramFetcher->get('isTheme');
        if($eventName && $distance && $location && $startDate && $endDate && $isTheme){
            $event = new Event();
            $event->setEventName($eventName);
            $event->setDistance($distance);
            $event->setLocation($location);
            $event->setStartDate( (new \DateTime())->setTimestamp($startDate));
            $event->setEndDate( (new \DateTime())->setTimestamp($endDate));
            $event->setIsTheme($isTheme);
            $this->entityManager->persist($event);
            $this->entityManager->flush();
            $jsonObject = $this->serializer->serialize($event, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
        }
        return new Response('$title => this cannot be null', Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
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

    /**
     * @Rest\Get("/backGround")
     */
    public function backGround(ParamFetcher $paramFetcher)
    {
        var_dump(($paramFetcher->get('image')));
        return count($_FILES);
    }
}
