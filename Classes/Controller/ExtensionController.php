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
    protected $backendConfiguration;
   
    
    /**
    * @param ExtensionRepository $extensionRepository
    */
    public function __construct(ExtensionRepository $extensionRepository) {
         $this->extensionRepository = $extensionRepository;
         $this->helper = GeneralUtility::makeInstance(HelperUtility::class);
         $this->backendConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('slug');
    }
    
    public function additionalTableAction() {
        
        $backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('slug');
        
         // Check if filter variables are available, otherwise set default values from ExtensionConfiguration
        if($this->request->hasArgument('filter')){
            $filterVariables = $this->request->getArgument('filter');
        }
        else{ 
            $filterVariables['maxentries'] = $backendConfiguration['recordMaxEntries'];
            $filterVariables['orderby'] = $backendConfiguration['recordOrderBy'];
            $filterVariables['order'] = $backendConfiguration['recordOrder'];
            $filterVariables['key'] = '';
        }
        
        // Set the order by options for fluid viewhelper f:form.switch
        $filterOptions['orderby'] = [
            ['value' => 'crdate', 'label' => $this->helper->getLangKey('filter.form.select.option.creation_date')],
            ['value' => 'title', 'label' => $this->helper->getLangKey('filter.form.select.option.title')],
            ['value' => 'path_segment', 'label' => $this->helper->getLangKey('filter.form.select.option.path_segment')],
            ['value' => 'sys_language_uid', 'label' => $this->helper->getLangKey('filter.form.select.option.sys_language_uid')],
        ];
        
        $filterOptions['order'] = [
            ['value' => 'DESC', 'label' => $this->helper->getLangKey('filter.form.select.option.descending')],
            ['value' => 'ASC', 'label' => $this->helper->getLangKey('filter.form.select.option.ascending')]
        ];
        
        $filterOptions['maxentries'] = [
            ['value' => '10', 'label' => '10'],
            ['value' => '20', 'label' => '20'],
            ['value' => '30', 'label' => '30'],
            ['value' => '40', 'label' => '40'],
            ['value' => '50', 'label' => '50'],
            ['value' => '60', 'label' => '60'],
            ['value' => '70', 'label' => '70'],
            ['value' => '80', 'label' => '80'],
            ['value' => '90', 'label' => '90'],
            ['value' => '100', 'label' => '100'],
            ['value' => '150', 'label' => '150'],
            ['value' => '200', 'label' => '200'],
            ['value' => '300', 'label' => '300'],
            ['value' => '400', 'label' => '400'],
            ['value' => '500', 'label' => '500']
        ];
        
        if($this->request->hasArgument('table')){
            $table = $this->request->getArgument('table');
            if($this->extensionRepository->checkIfTableExists($table)){
                $records = $this->extensionRepository->getAdditionalRecords(
                        $table,
                        $filterVariables,
                        $this->settings['additionalTables']
                        );

                $this->view->assignMultiple([
                    'filter' => $filterVariables,
                    'filterOptions' => $filterOptions,
                    'records' => $records,
                    'table' => $this->request->getArgument('table')
                ]);
            }
            else{
                $this->view->assignMultiple([
                    'message' => "Table doesn't exist!"
                ]);
            }
        }
        else{
            $this->view->assignMultiple([
                'message' => "Table argument not given!"
            ]);
        }
        
        $this->view->assignMultiple([
            'backendConfiguration' => $backendConfiguration,
            'additionalTables' => $this->settings['additionalTables']
        ]);
        
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
        
        // Set the order by options for fluid viewhelper f:form.switch
        $filterOptions['orderby'] = [
            ['value' => 'crdate', 'label' => $this->helper->getLangKey('filter.form.select.option.creation_date')],
            ['value' => 'title', 'label' => $this->helper->getLangKey('filter.form.select.option.title')],
            ['value' => 'path_segment', 'label' => $this->helper->getLangKey('filter.form.select.option.path_segment')],
            ['value' => 'sys_language_uid', 'label' => $this->helper->getLangKey('filter.form.select.option.sys_language_uid')],
        ];
        
        $filterOptions['order'] = [
            ['value' => 'DESC', 'label' => $this->helper->getLangKey('filter.form.select.option.descending')],
            ['value' => 'ASC', 'label' => $this->helper->getLangKey('filter.form.select.option.ascending')]
        ];
        
        $filterOptions['maxentries'] = [
            ['value' => '10', 'label' => '10'],
            ['value' => '20', 'label' => '20'],
            ['value' => '30', 'label' => '30'],
            ['value' => '40', 'label' => '40'],
            ['value' => '50', 'label' => '50'],
            ['value' => '60', 'label' => '60'],
            ['value' => '70', 'label' => '70'],
            ['value' => '80', 'label' => '80'],
            ['value' => '90', 'label' => '90'],
            ['value' => '100', 'label' => '100'],
            ['value' => '150', 'label' => '150'],
            ['value' => '200', 'label' => '200'],
            ['value' => '300', 'label' => '300'],
            ['value' => '400', 'label' => '400'],
            ['value' => '500', 'label' => '500']
        ];
        
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
                'filterOptions' => $filterOptions,
                'newsRecords' => $this->extensionRepository->getNewsList($filterVariables),
                'extEmconf' => $this->helper->getEmConfiguration('slug'),
                'backendConfiguration' => $backendConfiguration,
                'additionalTables' => $this->settings['additionalTables']
            ]);
        }
        
    }
    
}