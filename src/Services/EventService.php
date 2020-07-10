<?php

final class eventService
{

    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;


    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getEvent(int $eventId): ?event
    {
        return $this->eventRepository->findById($eventId);
    }

    public function getAllEvents(): ?array
    {
        return $this->eventRepository->findAll();
    }

    public function addEvent(string $title, string $content): event
    {
        $event = new event();
        $event->setTitle($title);
        $event->setContent($content);
        $this->eventRepository->save($event);

        return $event;
    }

    public function updateEvent(int $eventId, string $title, string $content): ?event
    {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            return null;
        }
        $event->setTitle($title);
        $event->setContent($content);
        $this->eventRepository->save($event);

        return $event;
    }

    public function deleteEvent(int $eventId): void
    {
        $event = $this->eventRepository->findById($eventId);
        if ($event) {
            $this->eventRepository->delete($event);
        }
    }

}