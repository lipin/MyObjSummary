<?php
# ģʽ�޶�
if (PHP_SAPI != 'cli') {
    echo 'ֻ����CLI������ģʽ����' ;
    exit;
}
# �������� ����ļ�
$index = $argv[0] ;
array_shift($argv);

if(!$argv) {
    echo "��ȷ��ʽ:
         php $index [����m|Сʱh]";
         style();
}
# ִ�в���
$v  = $argv[0];
$e = substr($v, -1);
# Ĭ��ʱ��
$s = 1 ;
$tip = '' ;
if(in_array($e,array('m','M','h','H'))) {
    $v = rtrim($v, $e);
    # �ж�����
    $check = preg_replace("/([0-9])_*,*/" , false , $v);
    if($check) {
        echo "ʱ�䲻��,����������" ;
        style();
    }
    # ʱ������
    if( ($e=='m') || ($e=='M')) {
        $s      =   $v*60;
        $tip    =   '����';
    }else if(($e=='h') || ($e=='H')) {
        $s      = $v*60*60;
        $tip    =   'Сʱ';
    }else{
        echo "ʱ���ʽ����,����m����h��β";
        style();
    }
}else{
    style('ʱ���ʽ����,����m����h��β');
}

# �ɹ���ʾ
echo "�ɹ�������Ϣ����:
            �����õ���Ϣ���Ϊ $v $tip " ;
# �Զ�������
$conf = array(
    # ʱ��·��
    'jump'=>[
        'request'=>'http://',
        'host'   =>'127.0.0.1',
        'dir'    =>'/Zin/time/index.php'
    ],
    # ʱ������
    'time'=>[
        'r'=> 1.5 ,  # ��Ϣ���
        't'=> 60 ,   # ��λ 60��
    ],

);
# ��Ϣ����
$msg = [
    '��,����Ŷ,����Ϣ��Ϣ��',
    '�۾�����,������Ϣ��Ϣ��',
    '��ô����,�������߰�',
    '��������,���ϲ�����',
    'ˮ����,��ȥ��ˮ',
    '��Ϣ��Ϊ�˸��õع���',
    '��Ҫ��������,�����ܲ�����',
    '�õ�����Ϣ��Ϣ��'
];
# ʱ��·��
$strConf = implode($conf['jump'],'') ;
# ��Ϣ����
$t = 0;
while(true) {
    $show = $msg[rand(0,(count($msg)-1))];
    $r = $conf['time']['r']*$conf['time']['t'] ;
    sleep($s);
    exec("msg ".$_SERVER['USERNAME']." /TIME:2 $show");
    $file  = 'log.txt';
    file_put_contents($file, "") ;
    file_put_contents($file, $r,FILE_APPEND) ;
    exec("explorer $strConf");
    sleep($r) ;
    if($t == 0) {
        file_put_contents('rest.txt','') ;
    }
    $t++;
    file_put_contents('rest.txt',"�����Ѿ���Ϣ".$t."��"."\r\n",FILE_APPEND) ;
}

# ��ʽ����
function style($s='')
{
    if($s) {
        echo "^_^:"."\n".'    ' ;
        echo $s ;
    }
    echo "\n"; exit ;
}