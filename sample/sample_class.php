<?php
# クラスのサンプルコード

# インターフェース
interface InterfaceClass
{
    public function show();
}

# 抽象クラス
abstract class AbstractClass
{
    // 定義を強制するメソッド
    abstract protected function show_abstract();

    // 一般的なメソッド
    public function print(){
        echo "Abstract abstract";
    }

}

class Sample extends AbstractClass implements InterfaceClass
{
    private $value_private;
    public $value_public;
    protected $value_protected;

    public function __construct(){
        $this->value_private = "a";
        $this->value_public = "b";
        $this->value_protected = "c";
    }

    public function show(){
        echo "インターフェースから強制された関数<br>";
        echo $this->value_private;
        echo $this->value_public;
        echo $this->value_protected;
        echo "<br>";
    }

    public function show_abstract(){
        echo "抽象クラスから強制された関数";
    }

}

$sample = new Sample();
$sample->show();
$sample->show_abstract();
$sample->print();

?>