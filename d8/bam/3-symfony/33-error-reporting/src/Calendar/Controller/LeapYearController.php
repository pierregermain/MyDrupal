<?php

namespace Calendar\Controller;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;
 
class LeapYearController
{
  public function indexAction(Request $request, $year)
  {
    if ($year < 0) {
      throw new \Exception('The year needs to be a positive number.');
    }
    $leapyear = new LeapYear();
    if ($leapyear->isLeapYear($year)) {
      $response = new Response('Yep, this is a leap year!' . rand());
    } else {
      $response = new Response('Nope, this is not a leap year.' . rand());
    }
   
    $response->setTtl(10);
   
    return $response;
  }
}