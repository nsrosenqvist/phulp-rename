<?php namespace NSRosenqvist\Phulp;

use Phulp\Source;

class Rename implements \Phulp\PipeInterface
{
    private $dirname;
    private $extension;
    private $filename;
    private $prefix;
    private $suffix;

    private $callback;

    public function __construct($rules)
    {
        if (is_callable($rules)) {
            $this->callback = $rules;
        }
        else {
            foreach ($rules as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
    }

    public function execute(Source $src)
    {
        foreach ($src->getDistFiles() as $key => $file) {
            $path = '';
            $info = pathinfo($file->getDistpathname());

            if ($callback = $this->callback) {
                unset($info['basename']);
                $info['dirname'] = dirname($file->getDistpathname());
                $info['prefix'] = '';
                $info['suffix'] = '';
                $info['extension'] = $info['extension'] ?? null;

                $info = $callback($info);
                $path .= ($info['dirname']) ? $info['dirname'].DIRECTORY_SEPARATOR : '';
                $path .= $info['prefix'].$info['filename'].$info['suffix'];
                $path .= ($info['extension']) ? '.'.$info['extension'] : '';
            }
            else {
                $dirname = $this->dirname ?? dirname($file->getDistpathname());
                $extension = $this->extension ?? $info['extension'] ?? null;

                $path .= ($dirname) ? $dirname.DIRECTORY_SEPARATOR : '';
                $path .= $this->prefix ?? '';
                $path .= $this->filename ?? $info['filename'];
                $path .= $this->suffix ?? '';
                $path .= ($extension) ? '.'.$extension : '';
            }

            $file->setDistpathname($path);
        }
    }
}
