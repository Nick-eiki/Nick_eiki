<?php
namespace common\models;

class Page{
    protected $curPage=1;
    protected $pageSize=10;
    protected $pageNum=0;
   function __construct($pageSize,$num){
        $page=isset($_GET['page']) ? $_GET['page'] : 1;
        $pageNum=ceil($num/$pageSize);
        if($page<1){
            $page=1;
        }else if($page>$pageNum && $pageNum>0){
            $page=$pageNum;
        }
        $this->pageNum=$pageNum;
        $this->pageSize=$pageSize;
        $this->curPage=$page;
    }

    function getStart(){
         return ($this->curPage-1)*$this->pageSize;
    }

    function getPage(){
        $lastPage=$this->curPage-1;
        $nextPage=$this->curPage+1;
        $str="<a href='?page={$lastPage}'>上一页</a>&nbsp;<a href='?page={$nextPage}'>下一页</a>";
        return $str;
    }

    function getPageBlock($bNum){
        $allBNum=$bNum*2+1;

        if($this->pageNum<=$allBNum){
            $s=1;
            $e=$this->pageNum;
        }else{
            if($this->curPage-$bNum<=0){

                $s=1;
                $e=$allBNum;
            }else if($this->curPage+2>=$this->pageNum){
                $s=$this->pageNum-$allBNum+1;
                $e=$this->pageNum;

                $s=$this->curPage-$bNum;
                $e=$this->curPage+$bNum;
            }
        }

        $pageStr=$this->showPage($s, $e);
        $lastPage=$this->curPage-1;
        $nextPage=$this->curPage+1;
        return "<a href='?page=1'>首页</a><a href='?page={$lastPage}'>上一页</a>".$pageStr."<a href='?page={$nextPage}'>下一页</a><a href='?page={$this->pageNum}'>尾页</a>";
    }

    private function showPage($s,$e){
        $pageStr="";
        for($i=$s;$i<=$e;$i++){
            if($i==$this->curPage){
                $pageStr.="<a class='cur' href='?page={$i}'>{$i}</a>";
            }else{
                $pageStr.="<a href='?page={$i}'>{$i}</a>";
            }
        }
        return $pageStr;
    }

}
?>