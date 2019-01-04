<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'GOCHILLA.Slug',
                'site',
                'slugs',
                '',
                [
                    'Page' => 'list,site,menu',
                    'Extension' => 'newsList',
                ],
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:slug/Resources/Public/Icons/slug-be-module.png',
                    'labels' => 'LLL:EXT:slug/Resources/Private/Language/locallang_slugs.xlf',
                ]
            );

        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('slug', 'Configuration/TypoScript', 'Slug');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_slug_domain_model_page', 'EXT:slug/Resources/Private/Language/locallang_csh_tx_slug_domain_model_page.xlf');
    }
);
 