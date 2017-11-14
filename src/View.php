<?php

namespace Kay;

class View
{
    protected $tmpl_begin = '{%';
    protected $tmpl_end   = '%}';
    private $var = [];
    private $tmplPath = './view/';
    private $cachePath = './cache/';
    private $tmplSuffix = '.html';
    private $cacheSuffix = '.php';

    public function __construct(array $config)
    {
        foreach ($config as $key => $item) {
            $this->$key = $item;
        }
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

    public function fetch($template)
    {
        $fileName = $template . $this->tmplSuffix;
        $cacheFile = $this->cachePath . md5($fileName) . $this->cacheSuffix;

        if (!is_file($cacheFile)) {
            $this->compile($fileName, $cacheFile);
        }

        extract($this->var);
        ob_start();
        ob_implicit_flush();
            require $cacheFile;
            $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function compile($fileName, $cacheFile)
    {
        $filePath = $this->tmplPath . $fileName;
        $content = file_get_contents($filePath);
        $this->parse($content);

        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755);
        }

        file_put_contents($cacheFile, $content);
    }

    public function parse(&$content)
    {
        $content = preg_replace('/\{if(.*?)\}/', '<?php if(\1): ?>', $content);
        $content = preg_replace('/\{elseif(.*?)\}/', '<?php elseif(\1): ?>', $content);
        $content = preg_replace('/\{endif\s\}/', '<?php endif; ?>', $content);
        $content = preg_replace('/\{(\$.*?)\}/', '<?php echo \1;?>', $content);
    }
}
