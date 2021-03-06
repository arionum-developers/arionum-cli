<?php

namespace pxgamer\ArionumCLI;

use PHPUnit\Framework\TestCase;
use pxgamer\ArionumCLI\Output\Factory;

class ApplicationTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanConstructADefaultApplication(): void
    {
        $app = new Application();

        $this->assertAttributeSame(Application::NAME, 'name', $app);
        $this->assertAttributeSame('source', 'version', $app);
        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $app);
    }

    /**
     * @test
     * @return void
     */
    public function itCanConstructAnApplicationWithCustomParameters(): void
    {
        $app = new Application('foo', 'bar');

        $this->assertAttributeSame('foo', 'name', $app);
        $this->assertAttributeSame('bar', 'version', $app);
        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $app);
    }
}
