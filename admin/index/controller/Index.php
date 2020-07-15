<?php
namespace app\index\controller;

use think\Controller;
	
class Index     extends Common
{
	
	
    public function index(){

//       phpinfo();die;
        return  $this->fetch();
    }

    public function welcome(){

    	
        return  $this->fetch();
    }

  
    
    
}
