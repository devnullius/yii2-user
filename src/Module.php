<?php
declare(strict_types=1);

namespace devnullius\user;

use Yii;
use yii\base\Module as BaseModule;
use yii\i18n\PhpMessageSource;

class Module extends BaseModule
{
    public const VERSION = '1.0.0';
    private static string $userTableName = 'system_user';
    private static string $userNetworkTableName = 'system_user_network';
    private static string $userDeviceTableName = 'system_user_device';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'devnullius\user\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
        Yii::$app->params['bsVersion'] = '4.x';
        // custom initialization code goes here
    }

    /**
     * Register translations for this module
     */
    final public function registerTranslations(): void
    {
        if (!isset(Yii::$app->get('i18n')->translations['modules/user/*'])) {
            Yii::$app->get('i18n')->translations['modules/user/*'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }

    final public static function getUserTableName(): string
    {
        return static::$userTableName;
    }

    final public static function getUserNetworkTableName(): string
    {
        return static::$userTableName;
    }

    final public static function getUserDeviceTableName(): string
    {
        return static::$userTableName;
    }

    /**
     * Translate module message
     *
     * @param string      $category
     * @param string      $message
     * @param array       $params
     * @param string|null $language
     *
     * @return string
     */
    public static function t(string $category, string $message, array $params = [], string $language = null): string
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }
}
