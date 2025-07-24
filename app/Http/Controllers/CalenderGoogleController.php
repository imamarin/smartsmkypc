<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use ICal\ICal;

class CalenderGoogleController extends Controller
{
    //
    public function index()
    {
        $icalUrl = 'https://calendar.google.com/calendar/ical/15f6383db8ae56176e38591a35dc2f5f6e58481008a611fe921dec6c68ec2454%40group.calendar.google.com/public/basic.ics';

        $ical = new ICal();
        $ical->initUrl($icalUrl);

        $events = $ical->events();

        foreach ($events as $event) {
            $dateStart = Carbon::parse($event->dtstart)->format('d-m-Y');
            $dateEnd = Carbon::parse($event->dtend)->format('d-m-Y');
            echo "Event: " . $event->summary . "<br>";
            echo "Start: " . $dateStart . "<br>";
            echo "End: " . $dateEnd . "<br><br>";
        }
    }
}
