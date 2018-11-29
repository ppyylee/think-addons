<?php
// +----------------------------------------------------------------------
// | TP5 Addons [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.i-wiki.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: L1PY <642188573@qq.com>
// +----------------------------------------------------------------------

namespace think\addons\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;

class Build extends Command
{

    protected function configure()
    {

        $this->setName('addons:build')
            ->addOption('addons', null, Option::VALUE_REQUIRED, 'The addons to build')
            ->addOption('install', null, Option::VALUE_NONE, 'Build addons and install it')
            ->addOption('uninstall', null, Option::VALUE_NONE, 'uninstall addons')
            ->setDescription('Addons ready to build');
    }

    /**
     * Excute build
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     */
    public function execute(Input $input, Output $output)
    {
        $addons = $input->getOption('addons');
        $install = $input->getOption('install');
        $uninstall = $input->getOption('uninstall');
        $install_action = 'build';
        if($install && $uninstall){
            $this->output(2,'Option install and uninstall must be used separately');
            die;
        }
        $install  && $install_action = 'install';
        $uninstall  && $install_action = 'uninstall';

        try {
            if (!$addons){
                $this->output(2,'try use option `addons` as `--addons`  ');
            }
            $this->build($addons,$install_action);
        } catch (Exception $e) {
            $this->output(0,var_export($e));
        }



    }

    protected function build($addons,$install_action){
         if(!is_string($addons)){
             $this->output(2,'addons name should be string');
             die;
         }

         if(stripos($addons,',')){
            $addons = array_filter(array_unique(explode(',',$addons),SORT_STRING));
             foreach ($addons as $v){
                 $this->build($v,$install_action);
             }
             die;
         }

         $addons = strtolower($addons);
        $class = "\\addons\\{$addons}\\{$addons}";
        if (class_exists($class) && is_subclass_of($class, "\\think\\addons")) {
            $handler = new $class;
        }else{
            $this->output(0,'addons class '.$addons.'  not exsists');
            die;
        }

        switch ($install_action){
            case 'build':
                $handler->build();
                break;
            case 'install':
                $handler->build();
                $handler->install();
                break;
            case 'uninstall':
                $handler->uninstall();
                break;
        }
        $this->output(1,'Addons '.$install_action.' success: '.$addons);
    }



    protected function output($status,$info){
        switch ($status){
            case '0':
                $this->output->writeln('<error>Addons build failed:</error> ' . $info);
                break;
            case '1':
                $this->output->writeln('<info>Addons build processed:</info> ' . $info);
                break;
            case '2':
                $this->output->writeln('<error>Addons build option error:</error> ' . $info);
                break;
        }
    }

}