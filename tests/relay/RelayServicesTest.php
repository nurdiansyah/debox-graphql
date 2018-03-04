<?php
namespace Tests\Relay;

use Debox\Graphql\Plugin;
use Debox\Graphql\Support\Facades\GraphQL;
use Debox\Graphql\Support\Facades\Relay;
use Debox\Tests\Mocks\UserType;
use GraphQL\Type\Definition\ObjectType;
use Orchestra\Testbench\TestCase;

class RelayServicesTest extends TestCase {
    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication() {
        $app = parent::createApplication();
        $app->register(Plugin::class);
        return $app;
    }

    protected function setUp() {
        parent::setUp();
        GraphQL::addType(UserType::class, 'User');
    }


    public function testConnectFieldFromEdgeType() {
        Relay::connectionFieldFromEdgeType(GraphQL::type('User'));
        $this->assertCount(3, GraphQL::getTypes());
        $this->assertArrayHasKey('UserEdge', GraphQL::getTypes());
        $this->assertInstanceOf(ObjectType::class, GraphQL::type('UsersConnection'));
        $this->assertInstanceOf(ObjectType::class, GraphQL::type('UserEdge'));
    }

}

?>
