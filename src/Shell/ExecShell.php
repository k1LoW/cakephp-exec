<?php

namespace Exec\Shell;

use Cake\Core\App;
use Cake\Console\Shell;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

/**
 * Exec shell command.
 */
class ExecShell extends Shell
{
    public $tasks = [];

    protected function _welcome()
    {
    }
    
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('output', array(
            'short' => 'o',
            'help' => 'output : json_encode(result) output filepath',
        ));
        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        if (count($this->args) < 3) {
            return;
        }
        $args = $this->args;
        $location = array_shift($args);
        $className = array_shift($args);
        $methodName = array_shift($args);
        if (strtolower($location) !== 'table') {
            $this->abort('Error: Support Table class only');
        }
        $Table = TableRegistry::get($className);
        $decoded = array();
        foreach ($args as $value) {
            if (json_decode($value, true)) {
                $decoded[] = json_decode($value, true);
            } else {
                if ($value === 'true') {
                    $value = true;
                }
                if ($value === 'false') {
                    $value = false;
                }
                if ($value === 'null') {
                    $value = null;
                }
                $decoded[] = $value;
            }
        }
        $result = call_user_func_array(array($Table, $methodName), $decoded);
        if (array_key_exists('output', $this->params)) {
            if(!empty($this->params['output'])) {
                $outputPath = realpath($this->params['output']);
                file_put_contents($outputPath, json_encode($result), FILE_APPEND);
            } else {
                $this->out($result);
            }
        }        
    }
}
