<?php

namespace zgldhdjw\QiniuStorage;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use zgldhdjw\QiniuStorage\Plugins\DownloadUrl;
use zgldhdjw\QiniuStorage\Plugins\Fetch;
use zgldhdjw\QiniuStorage\Plugins\ImageExif;
use zgldhdjw\QiniuStorage\Plugins\ImageInfo;
use zgldhdjw\QiniuStorage\Plugins\AvInfo;
use zgldhdjw\QiniuStorage\Plugins\ImagePreviewUrl;
use zgldhdjw\QiniuStorage\Plugins\LastReturn;
use zgldhdjw\QiniuStorage\Plugins\PersistentFop;
use zgldhdjw\QiniuStorage\Plugins\PersistentStatus;
use zgldhdjw\QiniuStorage\Plugins\PrivateDownloadUrl;
use zgldhdjw\QiniuStorage\Plugins\Qetag;
use zgldhdjw\QiniuStorage\Plugins\UploadToken;
use zgldhdjw\QiniuStorage\Plugins\PrivateImagePreviewUrl;
use zgldhdjw\QiniuStorage\Plugins\VerifyCallback;
use zgldhdjw\QiniuStorage\Plugins\WithUploadToken;

class QiniuFilesystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend(
            'qiniu',
            function ($app, $config) {
                if (isset($config['domains'])) {
                    $domains = $config['domains'];
                } else {
                    $domains = [
                        'default' => $config['domain'],
                        'https'   => null,
                        'custom'  => null
                    ];
                }

                $qiniu_adapter = new QiniuAdapter(
                    $config['access_key'],
                    $config['secret_key'],
                    $config['bucket'],
                    $domains,
                    $config['notify_url'] ?? null,
                    $config['access'] ?? 'public'
                );

                $file_system = new Filesystem($qiniu_adapter);
                $file_system->addPlugin(new PrivateDownloadUrl());
                $file_system->addPlugin(new DownloadUrl());
                $file_system->addPlugin(new AvInfo());
                $file_system->addPlugin(new ImageInfo());
                $file_system->addPlugin(new ImageExif());
                $file_system->addPlugin(new ImagePreviewUrl());
                $file_system->addPlugin(new PersistentFop());
                $file_system->addPlugin(new PersistentStatus());
                $file_system->addPlugin(new UploadToken());
                $file_system->addPlugin(new PrivateImagePreviewUrl());
                $file_system->addPlugin(new VerifyCallback());
                $file_system->addPlugin(new Fetch());
                $file_system->addPlugin(new Qetag());
                $file_system->addPlugin(new WithUploadToken());
                $file_system->addPlugin(new LastReturn());

                return $file_system;
            }
        );
    }

    public function register()
    {
        //
    }
}
