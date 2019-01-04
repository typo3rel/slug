<?php
namespace GOCHILLA\Slug\Controller;
use GOCHILLA\Slug\Utility\HelperUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;

/* 
 * This file was created by Simon Köhler
 * at GOCHILLA s.a.
 * www.gochilla.com
 */

class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    
    /**
     * @var HelperUtility
     */
    protected $helper;
    
    /**
     * action savePageSlug
     *
     * @return void
     */
    public function savePageSlug(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $queryParams = $request->getQueryParams();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $statement = $queryBuilder
            ->update('pages')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($queryParams['uid'],\PDO::PARAM_INT))
            )
            ->set('slug',$queryParams['slug']) // Function "createNamedParameter" is NOT needed here!
            ->execute();
         
        $output = 'Slug: '.$queryParams['slug'];
        $response->getBody()->write($output);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
    
    /**
     * action saveNewsSlug
     *
     * @return void
     */
    public function saveNewsSlug(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $queryParams = $request->getQueryParams();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $statement = $queryBuilder
            ->update('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($queryParams['uid'],\PDO::PARAM_INT))
            )
            ->set('path_segment',$queryParams['slug']) // Function "createNamedParameter" is NOT needed here!
            ->execute();
         
        $output = 'Path Segment: '.$queryParams['slug'];
        $response->getBody()->write($output);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
    
    /**
     * action exists
     *
     * @return void
     */
    public function existsAction(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $queryParams = $request->getQueryParams();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $result = $queryBuilder
            ->count('slug')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($queryParams['slug']))
            )
            ->execute()
            ->fetchColumn(0);
        $response->getBody()->write($result);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
      
    }
    
    /**
     * action generatePageSlug
     *
     * @return void
     */
    public function generatePageSlug(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $fieldConfig = $GLOBALS['TCA']['pages']['columns']['slug']['config'];
        $slugHelper = GeneralUtility::makeInstance(SlugHelper::class, 'pages', 'slug', $fieldConfig);
        
        $queryParams = $request->getQueryParams();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $statement = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($queryParams['uid'],\PDO::PARAM_INT))
            )
            ->execute();
        $slug = '';
        while ($row = $statement->fetch()) {
            $slug .= $slugHelper->generate($row, $row['pid']);
        }
        $response->getBody()->write($slug);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
      
    }
    
    /**
     * action generateNewsSlug
     *
     * @return void
     */
    public function generateNewsSlug(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $fieldConfig = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'];
        $slugHelper = GeneralUtility::makeInstance(SlugHelper::class, 'tx_news_domain_model_news', 'path_segment', $fieldConfig);
        
        $queryParams = $request->getQueryParams();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($queryParams['uid'],\PDO::PARAM_INT))
            )
            ->execute();
        $slug = '';
        while ($row = $statement->fetch()) {
            $slug .= $slugHelper->generate($row, $row['pid']);
        }
        $response->getBody()->write($slug);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
      
    }
    
    /**
     * action getPageInfo
     *
     * @return void
     */
    public function getPageInfo(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $queryParams = $request->getQueryParams();
        
        switch ($queryParams['type']) {
            case 'page':
                $response->getBody()->write($this->googlePreview($queryParams['uid']));
            break;
            case 'news':
                $response->getBody()->write($this->getNewsRecordInfo($queryParams['uid']));
            break;
            default:
                $response->getBody()->write('Hello World');
            break;
        }
        
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
      
    }
    
    private function googlePreview($uid) {
        $this->helper = GeneralUtility::makeInstance(HelperUtility::class);
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $statement = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where(
               $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->execute();
        while ($row = $statement->fetch()) {
            $isocode = $this->helper->getIsoCodeByLanguageUid($row['sys_language_uid']);
            if($isocode !== ''){
                $row['isocode'] = '/'.$isocode;
            }
            $output  = '<div class="google-preview"><a href="#">';
            $output .= '<h3 class="main">'.($row['seo_title'] ?: $row['title']).'</h3>';
            $output .= '<div class="url">'.$row['isocode'].$row['slug'].'</div>';
            $output .= '</a><div class="text">'.($row['description'] ?: 'Lorem Ipsum dolor sit ahmet. Esto no es bueno cuando tu haces cosas malas.').'</div></div>';
            return $output;
        }
        
    }
    
    private function getNewsRecordInfo($uid) {
        
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
               $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid))
            )
            ->execute();
        while ($row = $statement->fetch()) {
           return $row;
        }
        
    }
    
}