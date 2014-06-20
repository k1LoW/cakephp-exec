<?php
/**
 * Exec
 *
 */
class Exec{

    public function __construct(){
    }

    /**
     * exec
     *
     */
    public static function exec($location, $className, $methodName){
        $args = func_get_args();
        $location = array_shift($args);
        $className = array_shift($args);
        $methodName = array_shift($args);
        $phpPath = '/usr/bin/env php';

        if (defined('EXEC_PHP_PATH')) {
            $phpPath = EXEC_PHP_PATH;
        }

        $cakePath = CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'Console' . DS . 'cake.php';
        $resultPath = CACHE . 'result_' . date('YmdHis') . '_' . sha1(uniqid("", true));

        $command = 'exec' . ' ' . $phpPath . ' -q';
        $command .= ' ' . $cakePath;
        $command .= ' -app ' . APP .' exec.exec';
        $command .= ' -o ' . $resultPath;
        $command .= ' ' . $location;
        $command .= ' ' . $className;
        $command .= ' ' . $methodName;
        foreach ($args as $value) {
            $command .= ' ' . escapeshellarg(json_encode($value));
        }
        $command .= ' > /dev/null &';

        exec($command, $output, $r);
        return $resultPath;
    }
}
