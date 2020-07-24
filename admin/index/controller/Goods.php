<?php
namespace app\index\controller;

use think\Controller;
use \think\Cache;
use org\Upload;
class Goods    extends Common
{










   //修改节点名称
    public function upda(){
       $id=$_POST['id'];

      // var_dump($id);die
      
       $name=$_POST['name'];
              $m=M('goods_type');

        /** @var TYPE_NAME $data */
        $data=$m->where(['id' => $id])->save(['name' => $name]);
     if($data){
           return 1;
     }else{

     }

    }
   
    //分类展示界面
    public function product_category()
    {

        return $this->fetch();
    }

   
    /*
      
      获取分类数据方法1：
       */
   
   public function product_category_ajax(){
            $m=M('goods_type');
            $data=$m->field('id,pid,name')->select();
            return json_encode($data); 
        }

      /*
      
      获取分类数据方法2：使用redis缓存产品分类
      页面需要同步处理，不知道ztree怎么处理
       */
   
    // public function product_category_ajax(){
    //         // $m=M('goods_type');
    //         // $data=$m->field('id,pid,name')->select();
    //         // return json_encode($data);
        
    //     // 改用缓存
    //           $options = [
    //         'type'  =>  'Redis', // 缓存类型为File
    //         'expire'=>  200, // 缓存有效期为永久有效
    //         'prefix'=>  '',//缓存的前缀
          
    //     ];
    //     $redis=Cache::connect($options);
      
    //     if(!$redis->get('types1')){
    //          $m=M('goods_type');
    //         $type=$m->where("pid=0")->select();//获取一级分类
    //         $type2=array();
    //         $type3=array();
    //         $type4=array();
    //         foreach($type as $key=>$value){
    //             $type[$key]['child']=array();//二级分类的名字
    //             $type2=$m->where("pid=".$value['id'])->select();//获取二级分类

    //             foreach($type2 as $k=>$v){
                    
    //                 array_push($type[$key]['child'],$v);//合并一级与二级分类
    //                 $type[$key]['child'][$k]['child2']=array();////三级分类的名字
                   
    //                     $type3=$m->where("pid=".$v['id'])->select();//获取三级分类
    //                     foreach($type3 as $v2){


    //                           array_push($type[$key]['child'][$k]['child2'],$v2);//合并一级二级三级分类
    //                     }
                      
                   
    //             }

    //         }
    //         $redis->set('types1',$type);

    //         $data = $type;
   
    //         return json_encode($data);
    //     }else{
    //         // return $redis->get('types');
    //         $data = $redis->get('types1');
    //    // var_dump($data);die;
    //         return json_encode($data);


            
    //     }


    // }

    //删除分类信息
    public function product_category_del(){
        $id=$_GET['id'];
        $m=M('goods_type');
        $data=$m->where("pid=".$id)->find();

        if($data){
            $str="分类下面还子分类,不允许删除";
            echo json_encode($str);
        }else{
            
            $re=$m->delete($id);
            if($re){
                echo 1;
            }
        }
    }


    public function product_category_add(){
    	$m=M('goods_type');
    	$data=$m->field("*,concat(path,',',id) as paths")->order('paths')->select();

        // var_dump($data);die;
    	
    	foreach($data as $k=>$v){
    		$data[$k]['name']=str_repeat("|------",$v['level']).$v['name'];
    	}
    	  // var_dump($data);die;
          

    	$this->assign('data',$data);

       // $this->assign('data1',$data);
    	return  $this->fetch();
        //添加完新分类后（点击提交按钮后） 如何刷新左上角分类树

    }

    //iframe 中添加分类信息到数据库
    public function goods_type_add(){
    		$data['name']=$_POST['name'];
    		$data['pid']=$_POST['pid'];
    		$m=M('goods_type');
            // 分类不为空，且不是顶级分类
    		if($data['name'] !=" "  && $data['pid'] !=0){
    			
	    		$path=$m->field("path")->find($data['pid']);
	    		$data['path']=$path['path'];
	    		$data['level']=substr_count($data['path'],",");
	    		$re=$m->add($data);//返回插入id


	    		$path['id']=$re;
	    		$path['path']=$data['path'].','.$re;
	    		$path['level']=substr_count($path['path'],",");
	    		$res=$m->save($path);
	    		if($res){
                             
                    //提示 添加成功 转到哪个路径  2秒后跳转
                  return   $this->success("添加成功","product_category_add",1);
                  // return'<script>alert("添加成功");parent.location.href="product_category_add"
                  // </script>';

                }else{
                  return   $this->error("添加失败","product_category_add",2);
                }// 分类不为空，且是顶级分类
    		}else if($data['name'] !="" && $data['pid'] ==0){
    			
	    		//$path=$m->field("path")->find($data['pid']);
	    		$data['path']=$data['pid'];
	    		$data['level']=1;
	    		$re=$m->add($data);//返回插入id


	    		$path['id']=$re;
	    		$path['path']=$data['path'].','.$re;
	    		
	    		$res=$m->save($path);
	    		

                if($res){

                  return   $this->success("添加成功","product_category_add","",1);
                  //   echo '<script>alert("添加成功");parent.location.href="product_category_add"
                  // </script>';

                }else{
                  return   $this->error("添加失败","product_category_add","",2);
                }

    		}else{
    			
                return   $this->error("添加失败,内容不能为空1","product_category_add","",2);

    		}
    		

    }

    //商品列表分类页
    public function product_list(){
        $m=M('goods');
        $where="";
        if(!empty($_GET['id'])){
            $where="tid={$_GET['id']}";
        }

        $data=$m->where($where)->select();
       $this->assign('data',$data);
       return  $this->fetch();
    }

     //添加商品页
    public function product_add(){
        $m=M('goods_type');
        $data=$m->field("*,concat(path,',',id) as paths")->order('paths')->select();
        
        foreach($data as $k=>$v){
            $data[$k]['name']=str_repeat("|------",$v['level']).$v['name'];
        }
        
        $this->assign('data',$data);

    	return  $this->fetch();
    }

     //添加商品到数据库

    public function product_add_goods(){


        $m=M('goods');
        $data['goodsname']=$_POST['goodsname'];
        //获取分类的id及pid
       
        $str=explode(",",$_POST['tid']);
        $data['tid']=$str[0];
        $data['tpid']=$str[1];
        
       
        $data['unit']=$_POST['unit'];
        $data['attributes']=$_POST['attributes'];
        //拼接图片
        $data['imagepath']=implode(',', $_POST['imagepath']);
        
        $data['number']=$_POST['number'];
        $data['barcode']=$_POST['barcode'];
        $data['curprice']=$_POST['curprice'];
        $data['oriprice']=$_POST['oriprice'];
        $data['cosprice']=$_POST['cosprice'];
        $data['inventory']=$_POST['inventory'];
        $data['restrict']=$_POST['restrict'];
        $data['already']=$_POST['already'];
        $data['freight']=$_POST['freight'];
        $data['status']=$_POST['status'];
        $data['reorder']=$_POST['reorder'];
        $data['text']=$_POST['editorValue'];
        

    
        if($m->add($data)){

          return   $this->success("添加成功","product_list","",2);
        }else{
          return   $this->error("添加失败","product_list","",2);
        }
        
    }
      //添加商品图片数据库
    public function prduct_add_goods_ajax(){
        var_dump($_FILES);
        echo 1;
    }
    

    //编辑商品

    public function product_edit(){
        $m=M('goods_type');
        $g=M('goods');
        $goods=$g->find($_GET['id']);

        $data=$m->field("*,concat(path,',',id) as paths")->order('paths')->select();
        
        foreach($data as $k=>$v){
            $data[$k]['name']=str_repeat("|------",$v['level']).$v['name'];
        }

        $images=explode(',',$goods['imagepath']);
        
        $i=M('goods_files');
        $image=[];
        foreach($images as $v){
            array_push($image,$i->find($v));
        }

        $this->assign("image",$image);
        $this->assign('data',$data);//分类数据
        $this->assign('goods',$goods);//商品数据

        return  $this->fetch();
    }
//点击 添加商品按钮
    public function product_edit_save(){
            $m=M('goods');
            $data['goodsname']=$_POST['goodsname'];
           
            //获取分类的id及pid
           
            $str=explode(",",$_POST['tid']);
            $data['tid']=$str[0];
            $data['tpid']=$str[1];
            
           
            $data['unit']=$_POST['unit'];
            $data['attributes']=$_POST['attributes'];
            $data['imagepath']="";
            $data['number']=$_POST['number'];
            $data['barcode']=$_POST['barcode'];
            $data['curprice']=$_POST['curprice'];
            $data['oriprice']=$_POST['oriprice'];
            $data['cosprice']=$_POST['cosprice'];
            $data['inventory']=$_POST['inventory'];
            $data['restrict']=$_POST['restrict'];
            $data['already']=$_POST['already'];
            $data['freight']=$_POST['freight'];
            $data['status']=$_POST['status'];
            $data['reorder']=$_POST['reorder'];
            $data['text']=$_POST['editorValue'];

            if($m->where("id=".$_POST['id'])->save($data)){

              return   $this->success("修改成功","product_list","",2);
            }else{
              return   $this->error("修改失败","product_list","",2);
            }


    }


    //删除商品
    public function product_edit_delete(){
            $id=$_GET['id'];
            $m=M('goods');
            $result=$m->delete($id);
            if($result){
                echo 1;
            }else{
                echo 0;
            }
    }


    //商品图片上传
    public function product_add_images(){
        

        $upload = new  \org\Upload();// 实例化上传类    
        $upload->maxSize   =     3145728 ;// 设置附件上传大小    
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
        $upload->rootPath  =      './static/files/'; // 设置附件上传目录    // 上传文件
        $upload->saveName=time().rand(1111,9999);
        $date=date("Y-m-d",time());//已上传日期为子目录名
        $upload->saveExt="png";//上传的文件后缀
          $info   =   $upload->upload();   
          if(!$info) {// 上传错误提示错误信息  

              $this->error($upload->getError());  

           }else{// 上传成功 
            
            $m=M('goods_files');
            $data['filepath']='/static/files/'.$date."/".$upload->saveName.".".$upload->saveExt;
            $result=$m->add($data);
            $file=['id'=>$result,'imagepath'=>$data['filepath']];
            echo json_encode($file);

           }
    }

    //商品图片删除
    public function product_del_images(){
        $m=M('goods_files');
        $result=$m->delete($_GET['id']);
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
    
}
