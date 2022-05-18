<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Basiscout
 * @author     Giorgio <gacompa@gmail.com>
 * @copyright  2022 scoutcodera
 * @license    GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 */

namespace Scoutcodera\Component\Basiscout\Site\Service;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Menu\AbstractMenu;

/**
 * Class BasiscoutRouter
 *
 */
class Router extends RouterView
{
	private $noIDs;
	/**
	 * The category factory
	 *
	 * @var    CategoryFactoryInterface
	 *
	 * @since  1.0.0
	 */
	private $categoryFactory;

	/**
	 * The category cache
	 *
	 * @var    array
	 *
	 * @since  1.0.0
	 */
	private $categoryCache = [];

	public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
	{
		$params = Factory::getApplication()->getParams('com_basiscout');
		$this->noIDs = (bool) $params->get('sef_ids');
		$this->categoryFactory = $categoryFactory;
		
		
			$basiscout = new RouterViewConfiguration('basiscout');
			$this->registerView($basiscout);
			$ccBasescout = new RouterViewConfiguration('basescout');
			$ccBasescout->setKey('id')->setParent($basiscout);
			$this->registerView($ccBasescout);
			$basescoutform = new RouterViewConfiguration('basescoutform');
			$basescoutform->setKey('id');
			$this->registerView($basescoutform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}


	
		/**
		 * Method to get the segment(s) for an basescout
		 *
		 * @param   string  $id     ID of the basescout to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getBasescoutSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an basescoutform
			 *
			 * @param   string  $id     ID of the basescoutform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getBasescoutformSegment($id, $query)
			{
				return $this->getBasescoutSegment($id, $query);
			}

	
		/**
		 * Method to get the segment(s) for an basescout
		 *
		 * @param   string  $segment  Segment of the basescout to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getBasescoutId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an basescoutform
			 *
			 * @param   string  $segment  Segment of the basescoutform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getBasescoutformId($segment, $query)
			{
				return $this->getBasescoutId($segment, $query);
			}

	/**
	 * Method to get categories from cache
	 *
	 * @param   array  $options   The options for retrieving categories
	 *
	 * @return  CategoryInterface  The object containing categories
	 *
	 * @since   1.0.0
	 */
	private function getCategories(array $options = []): CategoryInterface
	{
		$key = serialize($options);

		if (!isset($this->categoryCache[$key]))
		{
			$this->categoryCache[$key] = $this->categoryFactory->createCategory($options);
		}

		return $this->categoryCache[$key];
	}
}
