<?php

namespace Kay;

class View
{
    protected $tmpl_begin = '{%';
    protected $tmpl_end   = '%}';
    private $var = [];
    private $templatePath;
    private $cachePath;

    public function __construct(array $config)
    {
        $this->config = array_merge([
            
        ], $config);
    }
    
    public function getContent(string $file)
    {
        $content = file_get_contents($file);
        return $content;
    }

    public function assign(string $key, $value)
    {
        $this->var[$key] = $value;
    }

    public function display($template)
    {
        echo $this->fetch($template);
    }

    public function compile($content)
    {
        
    }

    public function fetch($template)
    {
        $file = '';
        extract($this->var);
        $content = file_get_contents('./template/template.html');
        $content = preg_replace('/\{\s+if(.*?)\}/', '<?php if(\1): ?>', $content);
        $content = preg_replace('/\{\s+elseif(.*?)\}/', '<?php elseif(\1): ?>', $content);
        $content = preg_replace('/\{\s+endif\s\}/', '<?php endif; ?>', $content);
        $content = preg_replace('/\{(\$.*?)\}/', '<?php echo \1;?>', $content);

        file_put_contents($this->templatePath.'', $content);
        ob_start();
        ob_implicit_flush();
            require $file;
            $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
