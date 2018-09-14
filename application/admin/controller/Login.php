<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16
 * Time: 15:24
 */

namespace app\admin\controller;

use think\Controller;
use app\admin\service\UserService;
class Login extends Controller
{
    /**
     * 用户登录
     * @return array|mixed
     * @author 原点 <467490186@qq.com>
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login()
    {
        if(get_user_id()){
            $this->redirect(url('/admin/index'));
        }else{
            if(!request()->isPost()){
                return $this->fetch();
            }else{
                $data=input();
                $result=UserService::login($data);
                return $result;
            }
        }

    }
    /**
     * 用户退出
     * @return array
     * @author 原点 <467490186@qq.com>
     */
    public function logout()
    {
        session('user_auth', null);
        session('user_auth_sign', null);
        return ['msg'=>'退出成功','url'=>url('/admin/login')];
    }
    /**
     * 修改密码
     * @return array|mixed
     * @author 原点 <467490186@qq.com>
     * @throws \think\Exception\DbException
     */
    public function editPassword()
    {
        if(request()->isPost()){
            $data=input();
            $uid=get_user_id();
            $res=UserService::editPassword($uid,$data['oldpassword'],$data['password']);
            return $res;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 解锁
     */
    public function unlock()
    {
        if(!$this->request->isPost()){
            $this->error('非法请求');
        }
        $uid=get_user_id();
        if(!$uid){
            $this->error('登录信息过期',url('/admin/login'));
        }
        $password=input('password','','trim');
        $psd=model('user')->where('uid','=',get_user_id())->value('password');
        if(password_verify($password,$psd)){
           $this->success('解锁成功');
        }else{
            $this->error('密码错误');
        }
    }
}