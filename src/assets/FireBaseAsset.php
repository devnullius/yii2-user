<?php
declare(strict_types=1);

namespace devnullius\user\assets;

use yii\web\AssetBundle;

class FireBaseAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        //        'js/firebase.js?v=3.7.2.0005',
        'js/firebase_subscribe.js?v=3.7.2.0005',
    ];
}
