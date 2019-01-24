<?php
class AccountController extends BaseController
{
    private function account_err_report($msg, $current=1)
    {
        $this->current=$current;
        return $this->msg1=$msg;
    }

    public function actionIndex()
    {
        $this->current=0;
        if ($this->islogin) {
            $this->jump("{$this->ATSAST_DOMAIN}/");
        } else {
            $this->title="登录 / 注册";
        }
        $this->msg1=$this->msg2="";
        $action=arg("action");
        if ($action==="register") { //如果是注册

            $db=new Model("users");
            $password=arg("password");
            $name=arg("name");
            $pattern="/^(\w){6,100}$/";
            if (empty($password) || empty($name)) {
                return self::account_err_report("请不要皮这个系统");
            }
            if (!preg_match($pattern, $password)) {
                return self::account_err_report("请设置6位以上100位以下密码，只能包含字母、数字及下划线");
            }

            $result=$db->find(array("name=:name",":name"=>$name));

            if ($result) {
                return self::account_err_report("用户名已被使用");
            }

            $OPENID=sha1(strtolower($name).$this->ATSAST_SALT.md5($password));
            $uid=$db->create([
                'name'=>$name,
                'OPENID'=>$OPENID,
            ]);
            $_SESSION['OPENID']=$OPENID;
            $this->jump("{$this->ATSAST_DOMAIN}/");

        } elseif ($action==="login") { //如果是登录

            $name=arg("name");
            $password=arg("password");

            if (empty($password) || empty($name)) {
                return self::account_err_report("请不要皮这个系统", 0);
            }

            $OPENID=sha1(strtolower($name).$this->ATSAST_SALT.md5($password));
            $db=new Model("users");
            $result=$db->find(array("OPENID=:OPENID",":OPENID"=>$OPENID));
            if (empty($result)) {
                return self::account_err_report("用户名或密码错误", 0);
            } else {
                $_SESSION['OPENID']=$OPENID;
                $this->jump("{$this->ATSAST_DOMAIN}/");
            }
        }
    }

    public function actionLogout()
    {
        session_unset();
        session_destroy();
        $this->jump("{$this->ATSAST_DOMAIN}/");
    }
}
