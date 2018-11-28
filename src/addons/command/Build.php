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

class Build extends Command
{

    protected function initialize(Input $input, Output $output)
    {

    }

    protected function configure()
    {

        $this->setName('addons:build')
            ->addOption('addons', null, Option::VALUE_REQUIRED, 'The addon to build')
            ->addOption('all', null, Option::VALUE_IS_ARRAY, 'Retrieve all addons to build',[])
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
        $all = $input->getOption('all');
        $install = $input->getOption('install');
        $uninstall = $input->getOption('uninstall');
        $install_action = 'build';
        if($install && $uninstall){
            //输出错误
        }
        $install  && $install_action = 'install';
        $uninstall  && $install_action = 'uninstall';
        if($all && is_array($all)){
            $this->buildAll($all,$install_action);
        }elseif ($addons){
            $this->build($addons,$install_action);
        }

    }

    protected function build($addons,$install_action){
         if(!is_string($addons)){
             $this->output(2,'addons name should be string');
         }
         $addons = strtolower($addons);
        switch ($install_action){
            case 'build':
                $this->output(0,'build'.$addons);
                break;
            case 'install':
                $this->output(1,'install'.$addons);
                break;
            case 'uninstall':
                $this->output(2,'addons uninstall'.$addons);
                break;
        }

    }

    protected function buildAll($all,$install_action){

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