<?php
 
namespace Simplex\Tests;
 
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Framework1stTest extends \PHPUnit\Framework\TestCase
{
  public function testNotFoundHandling()
  {
    $framework = $this->getFrameworkForException(new ResourceNotFoundException());
 
    $response = $framework->handle(new Request());
 
    $this->assertEquals(404, $response->getStatusCode());
  }
  protected function getFrameworkForException($exception)
  {
    $matcher = $this->getMockBuilder('Symfony\Component\Routing\Matcher\UrlMatcherInterface')->getMock();
    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->throwException($exception))
    ;
    $resolver = $this->getMockBuilder('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface')->getMock();
 
    return new Framework($matcher, $resolver);
  }
}