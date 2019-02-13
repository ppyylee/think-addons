# think-addons
Simple Addons
 
 #### 开发文档
> PHP>=5.4.0

> think-helper >= 1.0.4

1. 安装think-addons

   `composer require l-addons/think-addons`

2. 目录结构
    ```
    www                     项目目录
    ├─addons                插件目录
    │  ├─addonsone           插件模块目录(自定义)
    │  │  ├─controller      控制器目录
    │  │  ├─view            视图目录
    │  │  ├─model           模型目录
    │  │  ├─...             其他TP5目录结构
    │  │  ├─Addonsone.php   插件基类（以模块名命名）
    │  │  └─config.php      插件配置
    │  ├─addonstwo            插件模块目录(自定义)
    │  │  ├─...      
    ```
3. 插件开发基类规范
    ```
    //Addonsonone.php
      
    namespace addons\assistAct;
    use think\Addons;
    
    class Addonsone extends Addons
    {
        /**
         * 工具信息描述
         * @var array
         */
        protected $info = [
            'name' => '',   //插件名称
            'title' => '',  //插件标题
            'description' => '',//插件描述
            'status' => false, 
            'author' => '', //插件作者
            'version' => '' //插件版本
        ];
    
        /**
         * 工具创建
         */
        public function build(){
            //TODO 创建插件所需要的环境
            //例如：数据库添加表
        }
    
        /**
         * 工具的安装
         */
        public  function install()
        {
            //生成安装文件
            @touch($this->addons_path.'install.lock');
            //TODO  其他安装流程
    
    
        }
    
        /**
         * 工具的卸载
         */
        public  function uninstall()
        {
            file_exists($this->addons_path.'install.lock') && unlink($this->addons_path.'install.lock');
    
            //TODO 更新数据库等
    
        }


        //添加插件钩子，易于区分，命名规范以Hook结尾
        public function wapadminHook()
        {
              // return $this->fetch('index');  
    
        }
    
    
    }
    
    ```
    
4. 助手函数
> URL生成 `aurl()`

    ```
    //用法
    aurl('addonsone://admin/index',array('id'=>1))
    //    addonsone://admin/index    模块名://控制器名/操作名
    /addons/exec/addonsone-admin-index
    ```
5. 配置文件 `extra/addons`

    ```
    添加配置文件extra/addons.php
    return [
        'autoload'=>true,  //自动加载钩子（debug模式）
            'hooks'=>[     //当autoload为false时，加载hooks中的钩子
                'wapadminHook'
            ]
    ];
    ```
    
6. 命令行模式
> php think  addons:build 

```
    --addons addonsone   用，分隔多个
    --install  创建后执行安装
    --uninstall  执行卸载
```