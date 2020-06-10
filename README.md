 2.0 复制系统让你推广不难
===============



### 主要特性
* 获取落地页复制内容，设备，地区
* 获取在面浏览时间
* 基于`ThinkPHP 5.1`重构
* 基于缓存redis，微信调用。（快速，避免出现不能展现微信号）
* 目录结构清晰方便二次开发维护
* CMF核心库及应用使用`composer`加载
* 每个应用都是独立分开的，可使用compose独立使用




### 环境推荐
> php7.1

> mysql 5.6+

> 打开rewrite


### 最低环境要求
> php5.6+

> mysql 5.5+ (mysql5.1安装时选择utf8编码，不支持表情符)

> 打开rewrite


### 完整版目录结构
~~~
thinkcmf  根目录
├─api                     api目录
│  ├─demo                 演示应用api目录
│  │  ├─controller        控制器目录
│  │  ├─model             模型目录
│  │  └─ ...              更多类库目录
├─app                     应用目录
│  ├─demo                 演示应用目录
│  │  ├─controller        控制器目录
│  │  ├─model             模型目录
│  │  └─ ...              更多类库目录
│  ├─ ...                 更多应用
│  ├─app.php              应用(公共)配置文件[可选]
│  ├─command.php          命令行工具配置文件[可选]
│  ├─common.php           应用公共(函数)文件[可选]
│  ├─database.php         数据库配置文件[可选]
│  ├─tags.php             应用行为扩展定义文件[可选]
├─data                    数据目录（可写）
│  ├─config               动态配置目录（可写）
│  ├─route                动态路由目录（可写）
│  ├─runtime              应用的运行时目录（可写）
│  └─ ...                 更多
├─public                  WEB 部署目录（对外访问目录）
│  ├─plugins              插件目录
│  ├─static               官方静态资源存放目录(css,js,image)，勿放自己项目文件
│  ├─themes               前后台主题目录
│  │  ├─admin_simpleboot3 后台默认主题
│  │  └─default           前台默认主题
│  ├─upload               文件上传目录
│  ├─api.php              API入口
│  ├─index.php            入口文件
│  ├─robots.txt           爬虫协议文件
│  ├─router.php           快速测试文件
│  └─.htaccess            apache重写文件
├─extend                  扩展类库目录[可选]
├─vendor                  第三方类库目录（Composer）
│  ├─thinkphp             ThinkPHP目录
│  └─...             
├─composer.json           composer 定义文件
├─LICENSE                 授权说明文件
├─README.md               README 文件
├─think                   命令行入口文件
~~~
## 如果没有独立开发能力，可联系我wx：719362307
### 对外提供服务版本
http://wtj.dwtongji.com/ 





