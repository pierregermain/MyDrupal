<?php
 
namespace Simplex\Tests;
 
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

//require_once 'PHPUnit/Autoload.php';

class FrameworkTest extends \PHPUnit\Framework\TestCase  // \PHPUnit_Framework_TestCase
{
  public function testNotFoundHandling()
  {
    $framework = $this->getFrameworkForException(new ResourceNotFoundException());
 
    $response = $framework->handle(new Request());
 
    $this->assertEquals(404, $response->getStatusCode());
  }
 
  protected function getFrameworkForException($exception)
  {
    $matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->throwException($exception))
    ;
    $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
 
    return new Framework($matcher, $resolver);
  }
}