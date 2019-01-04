<?php
if (!defined('TYPO3_MODE')) {
  die ('Access denied.');
}

/* 
 * This file was created by Simon Köhler
 * at GOCHILLA s.a.
 * www.gochilla.com
 */
 
// Configure new fields:
$fields = array(
  'tx_slug_locked' => array(
    'label' => 'LLL:EXT:slug/Resources/Private/Language/locallang_db.xlf:tx_slug_domain_model_page.slug_lock',
    'exclude' => 1,
    'config' => array(
        'type' => 'check',
        'renderType' => 'checkboxToggle',
        'items' => [
            [
                0 => '',
                1 => '',
                'labelChecked' => 'Enabled',
                'labelUnchecked' => 'Disabled',
            ]
        ],
    ),
  )
);
 
// Add new fields to pages:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $fields);
$GLOBALS['TCA']['pages']['columns']['slug']['config']['size'] = 100;
$GLOBALS['TCA']['pages']['palettes']['title']['showitem'] = 'title;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.title_formlabel, --linebreak--, slug, tx_slug_locked, --linebreak--, nav_title;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.nav_title_formlabel, --linebreak--, subtitle;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.subtitle_formlabel';