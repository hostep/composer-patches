<?php
/**
 * Copyright © Vaimo Group. All rights reserved.
 * See LICENSE_VAIMO.txt for license details.
 */
namespace Vaimo\ComposerPatches;

class Config
{
    const CONFIG_ROOT = 'extra';
    
    const LIST = 'patches';
    const DEV_LIST = 'patches-dev';
    const FILE = 'patches-file';
    const DEV_FILE = 'patches-file-dev';
    const EXCLUDED_PATCHES = 'excluded-patches';
    const APPLIED_FLAG = 'patches_applied';
    const PATCHER_CONFIG = 'patcher-config';

    const PATCHER_PLUGIN_MARKER = 'patcher_plugin';

    const PACKAGE_CONFIG_FILE = 'composer.json';

    /**
     * @var \Vaimo\ComposerPatches\Utils\ConfigUtils
     */
    private $configUtils;
    
    public function __construct() 
    {
        $this->configUtils = new \Vaimo\ComposerPatches\Utils\ConfigUtils();
    }
    
    public function shouldPreferOwnerPackageConfig()
    {
        return (bool)getenv(Environment::PREFER_OWNER);
    }
    
    public function shouldResetEverything()
    {
        return (bool)getenv(Environment::FORCE_REAPPLY) || (bool)getenv('COMPOSER_FORCE_PATCH_REAPPLY');
    }
    
    public function shouldExitOnFirstFailure()
    {
        return (bool)getenv(Environment::EXIT_ON_FAIL) || (bool)getenv('COMPOSER_EXIT_ON_PATCH_FAILURE');
    }
    
    public function getSkippedPackages()
    {
        $skipList = getenv(Environment::PACKAGE_SKIP)
            ? getenv(Environment::PACKAGE_SKIP)
            : getenv('COMPOSER_SKIP_PATCH_PACKAGES');
            
        return array_filter(
            explode(',', $skipList)
        );
    }
    
    public function getApplierConfig(array $overrides = array())
    {
        $config = array(
            'patchers' => array(
                'GIT' => array(
                    'check' => 'git apply -p{{level}} --check {{file}}',
                    'patch' => 'git apply -p{{level}} {{file}}'
                ),
                'PATCH' => array(
                    'check' => 'patch -p{{level}} --no-backup-if-mismatch --dry-run < {{file}}',
                    'patch' => 'patch -p{{level}} --no-backup-if-mismatch < {{file}}'
                )
            ),
            'operations' => array(
                'check' => 'Validation',
                'patch' => 'Patching'
            ),
            'sequence' => array(
                'patchers' => array('PATCH', 'GIT'),
                'operations' => array('check', 'patch')
            ),
            'levels' => array('0', '1', '2')
        );

        $config = $this->configUtils->mergeApplierConfig($config, $overrides);
        
        return $this->configUtils->sortApplierConfig($config);
    }
}
