<?php


namespace App\Service;


use App\Entity\Department;
use App\Repository\DepartmentRepository;

class CalendarService
{
    private const VISIT_TIME_IN_MINUTES = 30;

    /** @var DepartmentRepository */
    private $timetable;

    /**
     * CalendarService constructor.
     * @param DepartmentRepository $timetable
     */
    public function __construct(DepartmentRepository $timetable)
    {
        $this->timetable = $timetable;
    }


    public function generateAvailableBookSlots(array $bookedVisit): array
    {
        return $this->reduceSlots(
            $this->generateDayOfFreeSlots(),
            $bookedVisit
    );
    }

    private function generateDayOfFreeSlots()
    {
        /** @var Department $timetable */
        $timetable = $this->timetable->findOneBy(['active' => 1]);
        $slots = [];
        $startDay = new \DateTime(date(sprintf('Y-m-d %s:00:00', $timetable->openAtHour())));
        $endDay = new \DateTime(date(sprintf('Y-m-d %s:00:00', $timetable->closeAtHour())));
        $now = new \DateTime();

        while ($startDay < $endDay) {

            if ($now < $startDay) {
                $slot = $startDay->format('H:i');
                $slots[$slot] = 1;
            }

            $startDay->modify('+'.self::VISIT_TIME_IN_MINUTES.' minutes');
        }

        return $slots;
    }

    private function reduceSlots(array $fullAvailability , array $bookedVisits)
    {
        foreach ($bookedVisits as $visit) {
            $bookedSlot = $visit['visitDate']->format('H:i');
            unset($fullAvailability[$bookedSlot]);
        }

        return $fullAvailability;
    }
}