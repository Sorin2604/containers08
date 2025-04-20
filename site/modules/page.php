<?php
namespace modules;

class Page {
    private $template;
    
    public function __construct($template) {
        if (!file_exists($template)) {
            die("Template file not found: $template");
        }
        
        $this->template = $template;
    }
    
    public function Render($data) {
        // Read the template content
        $content = file_get_contents($this->template);
        
        if ($content === false) {
            die("Failed to read template file: {$this->template}");
        }
        
        // Replace placeholders with data
        foreach ($data as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $content = str_replace($placeholder, $value, $content);
        }
        
        // Output the rendered content
        echo $content;
    }
}