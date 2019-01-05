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
        
        // Check if filter variables are available, otherwise set default values from ExtensionConfiguration
        if($this->request->hasArgument('filter')){
            $filterVariables = $this->request->getArgument('filter');
        }
        else{ 
            $filterVariables['maxentries'] = $backendConfiguration['newsMaxEntries'];
            $filterVariables['orderby'] = $backendConfiguration['newsOrderBy'];
            $filterVariables['order'] = $backendConfiguration['newsOrder'];
            $filterVariables['key'] = '';
        }
        
        // Checks first if the news table exists, then if the module is active
        if(!$backendConfiguration['newsEnabled']){
            $this->view->assignMultiple([
                'extEmconf' => $this->helper->getEmConfiguration('slug'),
                'message' => 'This module is not activated. Go to: Admin Tools -> Settings -> Extension Configuration and activate the module.'
            ]);
        }
        elseif(!$tableExists){
            $this->view->assignMultiple([
                'extEmconf' => $this->helper->getEmConfiguration('slug'),
                'message' => "Table 'tx_news_domain_model_news' doesn't exist",
            ]);
        }
        else{
            $this->view->assignMultiple([
                'filter' => $filterVariables,
                'newsRecords' => $this->extensionRepository->getNewsList($filterVariables),
                'extEmconf' => $this->helper->getEmConfiguration('slug')
            ]);
        }
        
    }
    
}