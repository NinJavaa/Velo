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
use  App\Controller\Event\ApiController;

class EventConfigController extends ApiController
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

    public function __construct(EntityManagerInterface $entityManager, EventRepository $eventRepository,
                                EventConfigRepository $eventConfigRepository)
    {
        $this->encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $this->normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
        $this->eventConfigRepository = $eventConfigRepository;
    }


    /**
     * @Rest\RequestParam(name="eventID", description="title of the list", nullable=false)
     * @Rest\Get("/config/getConfigs")
     */
    public function index()
    {
        $eventConfigs = $this->eventConfigRepository->transformAll();
        $jsonObject = $this->serializer($eventConfigs,$this->serializer);
        return $this->respond($jsonObject);
    }

    /**
     * @Rest\QueryParam(name="id", description="title of the list", nullable=false)
     * @Rest\Get("/config/getConfig")
     */
    public function getConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);
        $eventConfig = $this->eventConfigRepository->findOneBy(['id' => $request->get('id')]);
        $jsonObject = $this->serializer($eventConfig,$this->serializer);
        return $this->respond($jsonObject);
    }

    /**
     * @Rest\Post("/config/putConf")
     */
    public function createConf(Request $request)
    {
        $request = $this->transformJsonBody($request);
        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate Variables Needed !!!!!
        if (! $request->get('event')) {
            return $this->respondValidationError('Please provide a event!');
        }

        // Create and persist the new event Config using cascade since that the relation is composition oneToOne
        $eventConfig = new EventConfig();
        $event = $this->eventRepository->findOneBy([
            'id' => $request->get('event')]);
        //check not null event is needed
        $eventConfig->setRep($request->get('rep'));
        $eventConfig->setStatus($request->get('status'));
        $eventConfig->setIsArchived($request->get('isArchived'));
        $eventConfig->setDateRep((new \DateTime())->setTimestamp($request->get('dateRep')));
        $eventConfig->setShowDate((new \DateTime())->setTimestamp($request->get('dateRep')));
        $eventConfig->setCommentPermession($request->get('commentPermession'));
        $eventConfig->setShowInviteList($request->get('showInviteList'));
        $event->setEventConfig($eventConfig);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        $jsonObject = $this->serializer($eventConfig, $this->serializer);
        return $this->respond($jsonObject);
    }


}