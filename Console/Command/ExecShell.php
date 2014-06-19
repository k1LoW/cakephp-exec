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
        $parser->addOption('result', array(
            'short' => 'r',
            'help' => 'result : json_encoded result value filepath',
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

        if (array_key_exists('result', $this->params)) {
            if (empty($this->params['result'])) {
                $resultPath = CACHE . 'result_' . date('YmdHis');
            } else {
                $resultPath = $this->params['result'];
            }
            file_put_contents($resultPath, json_encode($result));
        }
    }
}
