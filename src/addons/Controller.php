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
namespace think\addons;

use think\Request;
use think\Config;
use think\Loader;


/**
 * 插件基类控制器
 * Class Controller
 * @package think\addons
 */
class Controller extends \think\Controller
{
    /**
     * @var string 插件模块名称
     */
    protected $addon ;
    /**
     * @var string 插件控制器名称
     */
    protected $controller;
    /**
     * @var string 插件操作名称
     */
    protected $action;

    /**
     * @var array 视图默认配置
     */
    protected $config = [
        'type' => 'Think',
        'view_path' => '',
        'view_suffix' => 'html',
        'strip_space' => true,
        'view_depr' => DS,
        'tpl_begin' => '{',
        'tpl_end' => '}',
        'taglib_begin' => '{',
        'taglib_end' => '}',
    ];

    /**
     * Construct
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {
        $this->request = is_null($request) ? Request::instance() : $request;
        // 处理路由参数
        $param = explode('-', $this->request->param('route', ''));
        // 是否自动转换控制器和操作名
        $convert = Config::get('url_convert');
        // 格式化路由的插件位置
        $this->action = $convert ? strtolower(array_pop($param)) : array_pop($param);
        $this->controller = $convert ? strtolower(array_pop($param)) : array_pop($param);
        $this->addon = $convert ? strtolower(array_pop($param)) : array_pop($param);
        //加载插件配置信息
        $config_file = ADDON_PATH . $this->addon . DS .'config.php';
        if (is_file($config_file)) {
            Config::load($config_file);
        }
        $view_path = Config::get('template.view_path') ?: 'view';
        Config::set('template.view_path', ADDON_PATH . $this->addon . DS . $view_path . DS);
        $this->config = Config::get('template') ?: $this->config;
        parent::__construct($request);
    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $controller = Loader::parseName($this->controller);
        if ('think' == strtolower($this->config['type']) && $controller && 0 !== strpos($template, '/')) {
            $depr = $this->config['view_depr'];
            $template = str_replace(['/', ':'], $depr, $template);
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = str_replace('.', DS, $controller) . $depr . $this->action;
            } elseif (false === strpos($template, $depr)) {
                $template = str_replace('.', DS, $controller) . $depr . $template;
            }
        }
        return parent::fetch($template, $vars, $replace, $config);
    }
}
