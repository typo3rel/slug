<?php
namespace GOCHILLA\Slug\Domain\Repository;
use GOCHILLA\Slug\Utility\HelperUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/***
 *
 * This file is part of the "Slug" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Simon Köhler <info@simon-koehler.com>, GOCHILLA s.a.
 *
 ***/

/**
 * The Extension repository
 */
class ExtensionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
 
    /**
    * @var HelperUtility
    */
    protected $helper;
    
    public function checkIfTableExists($table){
        $tableExists = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table)
            ->getSchemaManager()
            ->tablesExist([$table]);
        if($tableExists){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function getAdditionalRecords($table,$filterVariables,$additionalTables) {
        
        $tableConf = $additionalTables[$table];
        
        $this->helper = GeneralUtility::makeInstance(HelperUtility::class);        
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $query = $queryBuilder
            ->select('*')
            ->from($table)
            ->orderBy($filterVariables['orderby'],$filterVariables['order']);
            
        if($filterVariables['key']){
            $query->where(
                $queryBuilder->expr()->like($tableConf['slugField'],$queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($filterVariables['key']) . '%'))
            );
        }
        
        $statement = $query->execute();
        $output = array();
        while ($row = $statement->fetch()) {
            $row['icon'] = $tableConf['icon'];
            $row['slugField'] = $row[$tableConf['slugField']];
            $row['flag'] = $this->helper->getFlagIconByLanguageUid($row['sys_language_uid']);
            $row['isocode'] = $this->helper->getIsoCodeByLanguageUid($row['sys_language_uid']);
            array_push($output, $row);
        }
        return $output;
    }
    
}