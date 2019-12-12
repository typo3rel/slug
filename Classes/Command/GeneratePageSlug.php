<?php

namespace SIMONKOEHLER\Slug\Command;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SIMONKOEHLER\Slug\Utility\HelperUtility;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\SlugHelper;

/**
 * Command class for slug module
 *
 * This class contains all functions to generate Slugs
 *
 * @category   Module
 * @package    Slug
 * @author     rene <rel@animate.de>
 * @copyright  2018-2019 rel s.a.
 */
class GeneratePageSlugCommand extends Command {

    /**
     * @var HelperUtility
     */
    protected $helper;


	/**
	 * Configure the command by defining the name, options and arguments
	 */
	protected function configure () {
		$this->setName('slug:generate');
		$this->setDescription('Generate missing Page Slugs');
		$this->setHelp('');
	}

	protected function execute (InputInterface $input, OutputInterface $output) {

		$newSlugs = $this->generatePageSlugs();
		foreach ($newSlugs as $uid => $slug) {
			/* todo get table and field from input */
			$this->saveRecordSlug($uid, $slug, 'pages', 'slug');
		}
	}

	/**
	 * function generatePageSlug
	 *
	 * @return array
	 */
	public function generatePageSlugs() : array {
		$fieldConfig = $GLOBALS['TCA']['pages']['columns']['slug']['config'];

		/** @var SlugHelper $slugHelper */
		$slugHelper = GeneralUtility::makeInstance(SlugHelper::class, 'pages', 'slug', $fieldConfig);
		$this->helper = GeneralUtility::makeInstance(HelperUtility::class);
		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
		$slugs = [];

		/** @var Statement $statement */
		$statement = $queryBuilder
			->select('*')
			->from('pages')
			->where(
				$queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter('',\PDO::PARAM_STR))
			)
			->orWhere(
				$queryBuilder->expr()->isNull('slug')
			)->execute();

		while ($row = $statement->fetch()) {
			$slugGenerated = $slugHelper->generate($row, $row['pid']);
			$slugs[$row['uid']] = $this->helper->returnUniqueSlug('page', $slugGenerated, $row['uid'], 'pages', 'slug');
		}
		
		return $slugs;
	}

	/**
	 * @param int    $uid
	 * @param string $slug
	 * @param string $table
	 * @param string $slugField
	 */
	public function saveRecordSlug(int $uid, string $slug, string $table, string $slugField): void {

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
		$queryBuilder
			->update($table)
			->where(
				$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid,\PDO::PARAM_INT))
			)
			->set($slugField,$slug) // Function "createNamedParameter" is NOT needed here!
			->execute();
	}
}