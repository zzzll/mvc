<?php
namespace Core;

//定义基础模型
class Model
{
    /**
     * 保存PDO实例
     * @var object
     */
    protected $mypdo;

    /**
     * 初始化模型
     */
    public function __construct() 
    {
        $this->mypdo = MySQLPDO::getInstance();
    }
}