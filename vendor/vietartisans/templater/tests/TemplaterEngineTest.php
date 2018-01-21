<?php

class TemplaterEngineTest extends PHPUnit_Framework_TestCase {
    
    function testTwig() {
        $template = new VA\Templater(__DIR__ . '/templates/');
        $this->assertEquals("Hello World, I'm Son", $template->render('test-twig-template.php', ['name' => 'Son']));
    }
    
    function testBlade() {
        $template = new VA\Templater(__DIR__ . '/templates/', 'blade');
        $this->assertEquals("Hello World, I'm Son", $template->render('test-blade-template', ['name' => 'Son']));
    }
    
}