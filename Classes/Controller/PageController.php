<?php
namespace GOCHILLA\Slug\Controller;
use GOCHILLA\Slug\Utility\HelperUtility;
use GOCHILLA\Slug\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/* 
 * This file was created by Simon Köhler
 * at GOCHILLA s.a.
 * www.gochilla.com
 */

class PageController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    
    /**
    * @var PageRepository
    */
    public $pageRepository;
    
    
    /**
     * @var IconFactory
     */
    protected $iconFactory;
    
    /**
     * @var HelperUtility
     */
    protected $helper;
    
    protected $languages;
    protected $sites;


    /**
    * @param PageRepository $pageRepository
    */
    public function __construct(PageRepository $pageRepository) {
         $this->pageRepository = $pageRepository;
         $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
         $this->helper = GeneralUtility::makeInstance(HelperUtility::class);
    }
    
    /**
     * List all slugs from the pages table
     */
    protected function listAction()
    {
        $backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('slug');
        
        // Check if filter variables are available, otherwise set default values from ExtensionConfiguration
        if($this->request->hasArgument('filter')){
            $filterVariables = $this->request->getArgument('filter');
        }
        else{
            $filterVariables['maxentries'] = $backendConfiguration['defaultMaxEntries'];
            $filterVariables['orderby'] = $backendConfiguration['defaultOrderBy'];
            $filterVariables['order'] = $backendConfiguration['defaultOrder'];
            $filterVariables['key'] = '';
        }
                
        // Set the order by options for fluid viewhelper f:form.switch
        $orderbyOptions = [
            ['value' => 'crdate', 'label' => 'Creation date'],
            ['value' => 'title', 'label' => 'Title'],
            ['value' => 'slug', 'label' => 'Slug'],
            ['value' => 'sys_language_uid', 'label' => 'Language'],
            ['value' => 'is_siteroot', 'label' => 'Site root'],
            ['value' => 'doktype', 'label' => 'Doctype']
        ];
        
        $this->view->assignMultiple([
            'pages' => $this->pageRepository->findAllFiltered($filterVariables),
            'filter' => $filterVariables,
            'backendConfiguration' => $backendConfiguration,
            'beLanguage' => $GLOBALS['BE_USER']->user['lang'],
            'extEmconf' => $this->helper->getEmConfiguration('slug'),
            'orderbyOptions' => $orderbyOptions
        ]);
        
    }
  
}