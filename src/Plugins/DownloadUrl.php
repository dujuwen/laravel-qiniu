<?php

namespace zgldhdjw\QiniuStorage\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class DownloadUrl
 * 得到公有资源下载地址 <br>
 * $disk        = \Storage::disk('qiniu'); <br>
 * $re          = $disk->getDriver()->downloadUrl('foo/bar1.css'); <br>
 * @package zgldhdjw\QiniuStorage\Plugins
 */
class DownloadUrl extends AbstractPlugin
{

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'downloadUrl';
    }

    public function handle($path = null, $domainType = 'default')
    {
        $adapter = $this->filesystem->getAdapter();
        return $adapter->downloadUrl($path, $domainType);
    }
}
