<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Basiscout
 * @author     Giorgio <gacompa@gmail.com>
 * @copyright  2022 scoutcodera
 * @license    GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 */

namespace Scoutcodera\Component\Basiscout\Site\Field;

defined('JPATH_BASE') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Form\FormField;

/**
 * Supports an HTML select list of categories
 *
 * @since  1.0.0
 */
class CreatedbyField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since  1.0.0
	 */
	protected $type = 'createdby';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		// Load user
		$user_id = $this->value;

		if ($user_id)
		{
			$user = Factory::getUser($user_id);
		}
		else
		{
			$user   = Factory::getUser();
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $user->id . '" />';
		}

		if (!$this->hidden)
		{
			$html[] = "<div>" . $user->name . " (" . $user->username . ")</div>";
		}

		return implode($html);
	}
}
