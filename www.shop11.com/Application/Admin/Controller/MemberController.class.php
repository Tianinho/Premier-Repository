<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends Controller {
    
    public function ok(){

    	for ($i=1;$i<=10;$i++){
    		$j = rand(1,100);
    		echo $j;
    		echo '&nbsp';
    	}
    }
}