<?php


namespace Debox\Graphql\Relay;


use Debox\Graphql\Support\GraphQLMutation;

class Mutation extends GraphQLMutation {
    protected $inputType;

    protected function inputType() {
        return null;
    }

    public function getInputType() {
        $inputType = $this->inputType();
        return $inputType ? $inputType : $this->inputType;
    }

    public function setInputType($inputType) {
        $this->inputType = $inputType;
    }

    public function args() {
        return [
            'input' => [
                'name' => 'input',
                'type' => $this->getInputType()
            ]
        ];
    }

    protected function getMutationResponse($response, $clientMutationId) {
        $mutationResponse = new MutationResponse();
        $mutationResponse->setNode($response);
        $mutationResponse->setClientMutationId($clientMutationId);
        return $mutationResponse;
    }

    protected function resolveClientMutationId($root, $args) {
        return array_get($args, 'input.clientMutationId');
    }

    public function getResolver() {
        $resolver = parent::getResolver();
        return function () use ($resolver) {
            $args = func_get_args();
            $response = call_user_func_array($resolver, $args);
            $clientMutationId = call_user_func_array([$this, 'resolveClientMutationId'], $args);
            $response = $this->getMutationResponse($response, $clientMutationId);
            return $response;
        };
    }
}