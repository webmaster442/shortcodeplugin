<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita194d9d09ebd9910e7db446b9581a65d
{
    public static $files = array (
        'bc33bdda64b68124ebec25fc6f289c9e' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WeDevs\\WeDocs\\' => 14,
        )
    );

    public static $prefixDirsPsr4 = array (
        'WeDevs\\WeDocs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        )
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WeDevs\\WeDocs\\API' => __DIR__ . '/../..' . '/includes/API.php',
        'WeDevs\\WeDocs\\API\\API' => __DIR__ . '/../..' . '/includes/API/API.php',
        'WeDevs\\WeDocs\\Admin' => __DIR__ . '/../..' . '/includes/Admin.php',
        'WeDevs\\WeDocs\\Admin\\Admin' => __DIR__ . '/../..' . '/includes/Admin/Admin.php',
        'WeDevs\\WeDocs\\Admin\\Docs_List_Table' => __DIR__ . '/../..' . '/includes/Admin/Docs_List_Table.php',
        'WeDevs\\WeDocs\\Admin\\Settings' => __DIR__ . '/../..' . '/includes/Admin/Settings.php',
        'WeDevs\\WeDocs\\Ajax' => __DIR__ . '/../..' . '/includes/Ajax.php',
        'WeDevs\\WeDocs\\Frontend' => __DIR__ . '/../..' . '/includes/Frontend.php',
        'WeDevs\\WeDocs\\Installer' => __DIR__ . '/../..' . '/includes/Installer.php',
        'WeDevs\\WeDocs\\Post_Types' => __DIR__ . '/../..' . '/includes/Post_Types.php',
        'WeDevs\\WeDocs\\Shortcode' => __DIR__ . '/../..' . '/includes/Shortcode.php',
        'WeDevs\\WeDocs\\Theme\\Astra' => __DIR__ . '/../..' . '/includes/Theme/Astra.php',
        'WeDevs\\WeDocs\\Theme\\Twenty_Fifteen' => __DIR__ . '/../..' . '/includes/Theme/Twenty_Fifteen.php',
        'WeDevs\\WeDocs\\Theme\\Twenty_Seventeen' => __DIR__ . '/../..' . '/includes/Theme/Twenty_Seventeen.php',
        'WeDevs\\WeDocs\\Theme_Support' => __DIR__ . '/../..' . '/includes/Theme_Support.php',
        'WeDevs\\WeDocs\\Walker' => __DIR__ . '/../..' . '/includes/Walker.php',
        'WeDevs\\WeDocs\\Widget' => __DIR__ . '/../..' . '/includes/Widget.php',
        'WeDevs_Settings_API' => __DIR__ . '/..' . '/tareq1988/wordpress-settings-api-class/src/class.settings-api.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita194d9d09ebd9910e7db446b9581a65d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita194d9d09ebd9910e7db446b9581a65d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita194d9d09ebd9910e7db446b9581a65d::$classMap;

        }, null, ClassLoader::class);
    }
}
