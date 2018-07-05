<?php
namespace common\library\upload;

use Yii;
use yii\web\UploadedFile;
use yii\base\Component;
use BaiduBce\BceBaseClient;
use BaiduBce\BceClientConfigOptions;
use BaiduBce\Util\Time;
use BaiduBce\Util\MimeTypes;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Services\Bos\BosClient;




class  NewUpload extends Component
{
    const bucket =  'YOU bucket';
    public static $BaiduBos;

    const BosConfig=[
            'credentials' =>
                    [
                        'accessKeyId' => 'AID',
                        'secretAccessKey' => 'SID',
                    ],
                    [
//                        'endpoint' => 'http://bj.bcebos.com',
//                        'stsEndpoint' => 'http://sts.bj.baidubce.com',
                    ]
    ];

    public function __construct()
    {
        parent::__construct();
        self::$BaiduBos=new BosClient(self::BosConfig);
    }
    //库列表
    public function listBuckets()
    {
        $response = self::$BaiduBos->listBuckets();
        return $response;
    }
    //库是否存在
    public function doesBucketExist($name)
    {
        $response = self::$BaiduBos->doesBucketExist($name);
        return $response;
    }
    //通过文件上传
    public function Upload(UploadedFile $uploadedFile,$bucket)
    {

        if(!$this->doesBucketExist($bucket)){
            throw new Yii\base\UnknownPropertyException('库不存在,请传入或者获取正确的库名称');
        }
        if(!empty($uploadedFile->name) && !empty($uploadedFile->tempName)){
            $Key=substr(microtime(),3,8).$uploadedFile->name.'.jpg';
            return self::$BaiduBos->putObjectFromFile($bucket,$Key,$uploadedFile->tempName);
        }else{
            throw new Yii\base\UnknownPropertyException('上传信息错误,请重试');
        }

    }
    //返回当前库图片列表
    public function listObjects()
    {
        return self::$BaiduBos->listObjects(self::bucketA);
    }

    //创建库
   public function CreateBucket($name)
   {
       $exist = self::$BaiduBos->doesBucketExist($name);
       if(!$exist){
           self::$BaiduBos->createBucket($name);
       }else{
            throw new Yii\base\UnknownPropertyException('此库名已被占用');
       }
   }
}