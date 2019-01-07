<?php
use GOCHILLA\Slug\Controller;

/* 
 * This file was created by Simon Köhler
 * at GOCHILLA s.a.
 * www.gochilla.com
 */

return [
    'savePageSlug' => [
        'path' => '/slug/save',
        'target' => Controller\AjaxController::class . '::savePageSlug'
    ],
    'slugExists' => [
        'path' => '/slug/exists',
        'target' => Controller\AjaxController::class . '::slugExists'
    ],
    'generatePageSlug' => [
        'path' => '/slug/generate',
        'target' => Controller\AjaxController::class . '::generatePageSlug'
    ],
    'getPageInfo' => [
        'path' => '/slug/getPageInfo',
        'target' => Controller\AjaxController::class . '::getPageInfo'
    ],
    'saveNewsSlug' => [
        'path' => '/slug/save/news',
        'target' => Controller\AjaxController::class . '::saveNewsSlug'
    ],
    'generateNewsSlug' => [
        'path' => '/slug/generate/news',
        'target' => Controller\AjaxController::class . '::generateNewsSlug'
    ],
    'loadTreeItemSlugs' => [
        'path' => '/slug/load/tree/slugs',
        'target' => Controller\AjaxController::class . '::loadTreeItemSlugs'
    ]
];