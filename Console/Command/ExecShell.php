<?php
App::uses('Shell', 'Console');

/**
 * ExecShell class
 *
 */
class ExecShell extends Shell {

    public $tasks = array();

/**
 * startup
 *
 */
    public function startup() {
        parent::startup();
    }

/**
 * _welcome
 *
 */
    public function _welcome(){
    }

 /**
  * getOptionParser
  *
  */
    public function getOptionParser(){
        $parser = parent::getOptionParser();
        $parser->addOption('output', array(
            'short' => 'o',
            'help' => 'output : json_encode(result) output filepath',
        ));
        return $parser;
    }

/**
 * main
 *
 */
    public function main() {
        if (count($this->args) < 3) {
            return;
        }
        $args = $this->args;
        $location = array_shift($args);
        $className = array_shift($args);
        $methodName = array_shift($args);
        App::uses($className, $location);
        $this->{$className} = ClassRegistry::init($className); // @todo: Model only...
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

        $result = call_user_func_array(array($this->{$className}, $methodName), $decoded);

        if (array_key_exists('output', $this->params)) {
            if (!empty($this->params['output'])) {
                $outputPath = realpath($this->params['output']);
                file_put_contents($outputPath, json_encode($result), FILE_APPEND);
            }
            $this->out($result);
        }
    }
}
