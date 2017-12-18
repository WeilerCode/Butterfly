<?php

namespace Weiler\Butterfly\Http\Controllers;

use Illuminate\Http\Request;

class ImgController extends Controller
{
    private $memberPath;                    //用户图片地址
    private $updatePath;                    //上传图片地址

    private $imageSize  = false;            //根据getimagesize获取的图片大小和MIME
    private $fileOpen   = false;            //打开后的文件流

    private $memberDefault;                 //默认头像名
    private $pictureDefault;                //默认图片

    private $width;                         //获取图片的尺寸
    private $height;                        //获取图片的高度
    private $thumbWidth;                    //缩略图宽
    private $thumbHeight;                   //缩略图高

    public function __construct()
    {
        // 304
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $browserCachedCopyTimestamp = strtotime(preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']));
            if($browserCachedCopyTimestamp + 604800 > time()){
                header("http/1.1 304 Not Modified");
                header ("Expires: " . gmdate ("r", (time() + 604800)));
                header ("Cache-Control: max-age=604800");
                exit;
            }
        }
        // 载入设置
        $this->memberPath       = config('butterfly.upload.member_path');
        $this->updatePath       = config('butterfly.upload.update_path');
        $this->memberDefault    = config('butterfly.upload.member_default');
        $this->pictureDefault   = config('butterfly.upload.picture_default');
    }

    /**
     * 包装用户图片
     * 用户头像的路径为 用户图片地址/用户ID/图片名
     * @param Request $request
     * $uid          用户ID
     * $sourceName   源图片名
     * $size         图片大小 例:200x200
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMember(Request $request)
    {
        $uid = $request->input('uid');
        $sourceName = $request->input('sourceName');
        $size = $request->input('size');
        $sourcePath = $this->memberPath . $uid . "/" . $sourceName;         //源图片的相对地址
        $this->openFile($sourcePath);                                       //载入文件流
        // 判断是否存在文件，不存在则使用默认图片
        if (!$this->imageSize || !$this->fileOpen) {
            $defaultImg = $this->memberPath . $this->memberDefault;
            $this->openFile($defaultImg);                                   //将默认图片加入到文件流
        }

        // 获取缩略图
        if($this->getThumbSize($size))
        {
            $this->thumbFile();
        }

        if ($this->imageSize && $this->fileOpen) {
            $this->showImg();                                       //显示图片
        } else {
            return view('errors.404');
        }
    }

    /**
     * 包装其他上传的图片
     * @param Request $request
     * $sourceName
     * $size
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPicture(Request $request)
    {
        $sourceName = $request->input('sourceName');
        $size = $request->input('size');
        $path1      = substr_replace($sourceName, '',strrpos($sourceName, "."));
        $path2      = substr_replace($path1, '', strrpos($path1, "."));
        $prefix     = str_replace('.', '/', $path2);
        $prefix     = empty($prefix) ? '' : $prefix."/";
        $sourceName = ltrim(str_replace($path2, '', $sourceName), '.');

        $sourcePath = $this->updatePath.$prefix.$sourceName;            //源图片的相对地址
        $this->openFile($sourcePath);                                   //载入文件流
        // 判断是否存在文件，不存在则使用默认图片
        if (!$this->imageSize || !$this->fileOpen) {
            $defaultImg = $this->updatePath.$this->pictureDefault;
            $this->openFile($defaultImg);                          //将默认图片加入到文件流
        }

        // 获取缩略图
        if($this->getThumbSize($size))
        {
            $this->thumbFile();
        }

        if ($this->imageSize && $this->fileOpen) {
            $this->showImg();                                       //显示图片
        } else {
            return view('errors.404');
        }
    }

    /**
     * 显示图片
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function showImg()
    {
        header ("Last-Modified: " . gmdate ('r', time()));
        header ("Expires: " . gmdate ("r", (time() + 604800)));
        header ("Cache-Control: max-age=604800");
        header ("Pragma: public");
        header("Content-type: {$this->imageSize['mime']}");
        switch($this->imageSize['mime'])
        {
            case 'image/png':
                imagepng($this->fileOpen);
                break;
            case 'image/jpeg':
                imagejpeg($this->fileOpen);
                break;
            case 'image/gif':
                imagegif($this->fileOpen);
                break;
            default:
                imagedestroy($this->fileOpen);
                return view('errors.404');
        }

        exit;
    }


    /**
     * 获取缩略图片名
     * @param $sourceName
     * @param $size
     * @return string
     */
    private function getThumbSize($size)
    {
        if(!empty($size))
        {
            $size = explode('x', $size);
            if(is_array($size))
            {
                $this->thumbWidth   = $size[0];
                $this->thumbHeight  = $size[1];
                return true;
            }
        }
        return false;
    }

    /**
     * 打开文件流
     * 先尝试打开请求的缩略图,不成功则尝试打开源图
     * @param $fullPath     String 缩略图相对地址
     * @param $sourcePath   String 源图相对地址
     */
    private function openFile($sourcePath)
    {
        $this->imageSize = @getimagesize($sourcePath);
        if($this->imageSize) {
            $this->width    = $this->imageSize[0];
            $this->height   = $this->imageSize[1];
            switch ($this->imageSize['mime']) {
                case 'image/gif':
                    $this->fileOpen = @imagecreatefromgif($sourcePath);
                    break;
                case 'image/jpeg':
                    $this->fileOpen = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $this->fileOpen = @imagecreatefrompng($sourcePath);
                    break;
            }
        }
    }

    /**
     * 获取缩略图
     */
    private function thumbFile()
    {
        $image_wp=imagecreatetruecolor($this->thumbWidth, $this->thumbHeight);
        // Add transparent background to destination image
        imagefill($image_wp, 0, 0, imagecolorallocatealpha($image_wp, 0, 0, 0, 127));
        imagesavealpha($image_wp, true);
        imagecopyresampled($image_wp, $this->fileOpen, 0, 0, 0, 0, $this->thumbWidth, $this->thumbHeight, $this->width, $this->height);
        $this->fileOpen = $image_wp;
    }
}