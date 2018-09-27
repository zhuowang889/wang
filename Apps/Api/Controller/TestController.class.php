<?php
namespace Api\Controller;
use Think\Controller;

class TestController extends Controller {
    
    public function index()
    {
       $x =  new UserCenterController();
       dump($x->index($_POST));
    }
}