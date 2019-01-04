<?php
namespace GOCHILLA\Slug\Controller;
use GOCHILLA\Slug\Utility\HelperUtility;
use GOCHILLA\Slug\Domain\Repository\ExtensionRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/* 
 * This file was created by Simon Köhler
 * at GOCHILLA s.a.
 * www.gochilla.com
 */

class ExtensionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
    * @var ExtensionRepository
    */
    public $extensionRepository;
    
    /**
    * @var HelperUtility
    */
    public $helper;
   
    
    /**
    * @param PageRepository $pageRepository
    */
    public function __construct(ExtensionRepository $extensionRepository) {
         $this->extensionRepository = $extensionRepository;
         $this->helper = GeneralUtility::makeInstance(HelperUtility::class);
    }
    
    public function newsListAction() {
        
        $tableExists = $this->extensionRepository->checkIfTableExists('tx_news_domain_model_news');
        
        $backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('slug');
        
        if($this->request->hasArgument('filter')){
            $filterVariables = $this->request->getArgument('filter');
        }
        else{
            // ToDo: Setting default filter variables from configuration!
            $filterVariables['maxentries'] = $backendConfiguration['newsMaxEntries'];
            $filterVariables['orderby'] = $backendConfiguration['newsOrderBy'];
            $filterVariables['order'] = $backendConfiguration['newsOrder'];
            $filterVariables['key'] = '';
        }
        
        if($backendConfiguration['newsEnabled']){
            $this->view->assignMultiple([
                'filter' => $filterVariables,
                'newsRecords' => $this->extensionRepository->getNewsList($filterVariables),
                'extEmconf' => $this->helper->getEmConfiguration('slug'),
                'newsEnabled' => $backendConfiguration['newsEnabled'],
                'tableExists' => $tableExists
            ]);
        }
        else{
            $this->view->assignMultiple([
                'filter' => $filterVariables,
                'newsRecords' => array(),
                'extEmconf' => $this->helper->getEmConfiguration('slug'),
                'newsEnabled' => $backendConfiguration['newsEnabled'],
                'tableExists' => $tableExists,
                'message' => 'This module is not activated. Go to: Admin Tools -> Settings -> Extension Configuration and activate the module. Be careful and make sure to fulfill all requirements!'
            ]);
        }
        
    }
    
}