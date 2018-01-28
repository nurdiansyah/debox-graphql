<?php
namespace debox\graphql\relay\type;


interface NodeContract {
    public function resolveById($id);
}