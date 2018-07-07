<?php

namespace Debox\Graphql\Console;

use Illuminate\Console\GeneratorCommand as BaseGeneratorCommand;
use Illuminate\Filesystem\Filesystem;

abstract class GeneratorCommand extends BaseGeneratorCommand {
    /**
     * GeneratorCommand constructor.
     */
    public function __construct() {
//        $fileSystem = new Filesystem();
//        $fileSystem->basename('')
        parent::__construct(new Filesystem());
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [];
    }
}
