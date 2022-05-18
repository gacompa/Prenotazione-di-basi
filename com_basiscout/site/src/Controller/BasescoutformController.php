<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Basiscout
 * @author     Giorgio <gacompa@gmail.com>
 * @copyright  2022 scoutcodera
 * @license    GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 */

namespace Scoutcodera\Component\Basiscout\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

/**
 * Basescout class.
 *
 * @since  1.0.0
 */
class BasescoutformController extends FormController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_basiscout.edit.basescout.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_basiscout.edit.basescout.id', $editId);

		// Get the model.
		$model = $this->getModel('Basescoutform', 'Site');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_basiscout&view=basescoutform&layout=edit', false));
	}

	/**
	 * Method to save data.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   1.0.0
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		$this->checkToken();

		// Initialise variables.
		$app   = Factory::getApplication();
		$model = $this->getModel('Basescoutform', 'Site');

		// Get the user data.
		$data = Factory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new \Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_basiscout.edit.basescout.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_basiscout.edit.basescout.id');
			$this->setRedirect(Route::_('index.php?option=com_basiscout&view=basescoutform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_basiscout.edit.basescout.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_basiscout.edit.basescout.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_basiscout&view=basescoutform&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_basiscout.edit.basescout.id', null);

		// Redirect to the list screen.
		$this->setMessage(Text::_('COM_BASISCOUT_ITEM_SAVED_SUCCESSFULLY'));
		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_basiscout&view=basiscout' : $item->link);
		$this->setRedirect(Route::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_basiscout.edit.basescout.data', null);
	}

	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = Factory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_basiscout.edit.basescout.id');

		// Get the model.
		$model = $this->getModel('Basescoutform', 'Site');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_basiscout&view=basiscout' : $item->link);
		$this->setRedirect(Route::_($url, false));
	}

	/**
	 * Method to remove data
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   1.0.0
	 */
	public function remove()
	{
		$app   = Factory::getApplication();
		$model = $this->getModel('Basescoutform', 'Site');
		$pk    = $app->input->getInt('id');

		// Attempt to save the data
		try
		{
			// Check in before delete
			$return = $model->checkin($return);
			// Clear id from the session.
			$app->setUserState('com_basiscout.edit.basescout.id', null);

			$menu = $app->getMenu();
			$item = $menu->getActive();
			$url = (empty($item->link) ? 'index.php?option=com_basiscout&view=basiscout' : $item->link);

			if($return)
			{
				$model->delete($pk);
				$this->setMessage(Text::_('COM_BASISCOUT_ITEM_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$this->setMessage(Text::_('COM_BASISCOUT_ITEM_DELETED_UNSUCCESSFULLY'), 'warning');
			}
			

			$this->setRedirect(Route::_($url, false));
			// Flush the data from the session.
			$app->setUserState('com_basiscout.edit.basescout.data', null);
		}
		catch (\Exception $e)
		{
			$errorType = ($e->getCode() == '404') ? 'error' : 'warning';
			$this->setMessage($e->getMessage(), $errorType);
			$this->setRedirect('index.php?option=com_basiscout&view=basiscout');
		}
	}
}
