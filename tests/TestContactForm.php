<?php
use PHPUnit\Framework\TestCase;

class TestContactForm extends TestCase {
    public function test_plugin_file_exists() {
        $this->assertFileExists(__DIR__ . '/../simple-frontend-plugin.php');
    }
}
