<?php
namespace Rest\Controller;
use Think\Controller\RestController;
use Think\Cache;
class  BaseController extends RestController{
	public 		$uid 			= 0;
	public 		$redis 			= null;
    private   	$preKey 		= 'Companys:';		#客户的基本信息、配置信息
    public function _initialize(){
    	$this->redis = Cache::getInstance('Redis');
    }

    public function rest(){
    	$raw_post_data = file_get_contents('php://input', 'r');
    	$this->AuthUser();
    	switch($this->_method){
	      case 'get': // get请求处理代码
	            $this->get($raw_post_data);
	           break;
	      case 'put': // put请求处理代码
	      		$this->put($raw_post_data);
	           break;
	      case 'post': // post请求处理代码
	       		$this->post($raw_post_data);
	           break;
	      case 'delete':
	      		$this->delete($raw_post_data);
	      	   break;
	     }
    }
    #认证获取配置信息
    private function AuthUser(){
    	
    	$keyid = isset($_REQUEST['keyid'])?$_REQUEST['keyid']:'';
    	if(empty($keyid)){
    		exit('base-error001');
    	}

    	$this->setDbconfig($keyid);
    	$this->uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:0;

    }
    #配置数据库连接信息
    private function setDbconfig($keyid){
    	#获取配置信息、数据库连接信息	
    	$config = $this->redis->hget($this->preKey.$keyid);
    	if(empty($config)){  		
    		#测试-初始化配置
    		$config = array(
    			'DB_TYPE'   => 'mysql', 		// 数据库类型
				'DB_HOST'   => '127.0.0.1', 	// 服务器地址
				'DB_NAME'   => 'saascrm', 		// 数据库名
				'DB_USER'   => 'root', 			// 用户名
				'DB_PWD'    => 'root', 			// 密码
				'DB_PORT'   => 3306, 			// 端口
				'DB_PREFIX' => '', 				// 数据库表前缀 
				'DB_CHARSET'=> 'utf8', 			// 字符集
    		);
    		$res = $this->redis->hset($this->preKey.$keyid,$config);
    	}
    	
    	C($config);
    }
}