<?php
namespace GOCHILLA\Slug\Controller;
use GOCHILLA\Slug\Utility\HelperUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;

/**
 * Ajax class for slug module
 *
 * This class contains all functions to perform the ajax requests
 *
 * @category   Module
 * @package    Slug
 * @author     Simon Köhler <info@simon-koehler.com>
 * @copyright  2018-2019 GOCHILLA s.a.
 */

class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    
    /**
     * @var HelperUtility
     */
    protected $helper;
    
    /**
     * function savePageSlug
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
        $response->getBody()->write($statement);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
    
    /**
     * function saveNewsSlug
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
        $response->getBody()->write($statement);
        return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
    
    /**
     * function slugExists
     *
     * @return void
     */
    public function slugExists(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
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
     * function generatePageSlug
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
     * function generateNewsSlug
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
    
}