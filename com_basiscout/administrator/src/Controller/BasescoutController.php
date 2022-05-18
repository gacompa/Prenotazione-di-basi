<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Basiscout
 * @author     Giorgio <gacompa@gmail.com>
 * @copyright  2022 scoutcodera
 * @license    GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 */

namespace Scoutcodera\Component\Basiscout\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Mail\MailTemplate;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Mail\Exception;
use Joomla\CMS\Mail\MailHelper;
use Joomla\CMS\Log\Log;


/**
 * Basescout controller class.
 *
 * @since  1.0.0
 */
class BasescoutController extends FormController
{
    
    protected $view_list = 'basiscout';
  

    public function edit($key = null, $urlVar = null)
    {
   
		// Do not cache the response to this, its a redirect, and mod_expires and google chrome browser bugs cache it forever!
		Factory::getApplication()->allowCache(false);

		$model = $this->getModel();
		$table = $model->getTable();
		$cid   = (array) $this->input->post->get('cid', array(), 'int');
		$context = "$this->option.edit.$this->context";
		
		// aggiunta di Giorgio
		$app = Factory::getApplication();
		$app->enqueueMessage('GACompa - Funzione edit ridefinita', 'info');
		// fine aggiunta di Giorgio


		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		// Get the previous record id (if any) and the current record id.
		$recordId = (int) (\count($cid) ? $cid[0] : $this->input->getInt($urlVar));
		$checkin = $table->hasField('checked_out');

		// Access check.
		if (!$this->allowEdit(array($key => $recordId), $key))
		{
			$this->setMessage(Text::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Attempt to check-out the new record for editing and redirect.
		if ($checkin && !$model->checkout($recordId))
		{
			// Check-out failed, display a notice but allow the user to see the record.
			$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}
		else
		{
			// Check-out succeeded, push the new record id into the session.
			$this->holdEditId($context, $recordId);
			Factory::getApplication()->setUserState($context . '.data', null);

			$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));


		$app = Factory::getApplication();
		$app->enqueueMessage('GACompa: richiamo della funzione MANDAMAIL', 'info');
//		$mailer = new Mail();
//		$mailer->getInstance('Joomla',true);
//		$mailer->setSender( array( 'giorgio.comparin@gmail.com', 'scoutcodera' ) );
		$testo_esteso=print_r($mailer);
		Log::add($testo_esteso, Log::INFO, 'Testing');
		$result=$this->mandamail();
		$app = Factory::getApplication();
		$app->enqueueMessage('GACompa: fine richiamo della funzione MANDAMAIL', 'info');
		   return true;
		}
    }

    public function mandamail()
    {
        $app = Factory::getApplication();
        $app->enqueueMessage('GACompa: --inizio-- processo di mail', 'info');
        $app->enqueueMessage('GACompa: --nuova istanza di Mail', 'info');
//      $mailer=new Mail();
//   	Log::add($testo_esteso, Log::INFO, 'Testing');
//      $mailer->getInstance('Joomla',true);
//      Log::add($testo_esteso, Log::INFO, 'Testing');

        $mailer = new MailTemplate('com_contact.mail', $app->getLanguage()->getTag());
        $mailer->addRecipient('gacompa@gmail.com');
        $mailer->setReplyTo('giorgio.comparin@gmail.com', 'Giorgio');
        $mailer->addTemplateData($templateData);
        $sent = $mailer->send();
/**        
        $app->enqueueMessage('GACompa: --inizio-- richiamo di sendMail', 'info');
//      $mailer->setSender( array( 'giorgio.comparin@gmail.com', 'scoutcodera' ) );
        Log::add($mailer . '--|0', Log::INFO, 'Testing');
        $app->enqueueMessage('GACompa: --parametri-- ---|' . $mailer->setSender[0] . '|---', 'info');
        Log::add($mailer . '--|1', Log::INFO, 'Testing');
        $mailer=sendMail('giorgio.comparin@gmail.com', 'scoutcodera', 'gacompa@gmail.com', 'the subject', 'Lorem ipsum dolor sit amet, consectetur adipiscingelit.', false);
        Log::add($mailer . '--|2', Log::INFO, 'Testing');
        $app->enqueueMessage('GACompa: --fine-- richiamo di sendMail', 'info');
        $app = Factory::getApplication();
        $app->enqueueMessage('GACompa: --fine-- processo di mail', 'info');
        */
    }
/**    public function abcmandamail()
    {
     	$mailer = new Mail();
		// aggiunta di Giorgio
		$app = Factory::getApplication();
		$app->enqueueMessage('inizio processo di mail', 'info');
/**		$mailer->getInstance('Joomla',true);
		$app = Factory::getApplication();
//		$app->enqueueMessage('creata istanza', 'info');
		$mailer->setSender( array( 'giorgio.comparin@gmail.com', 'scoutcodera' ) );
	        $app = Factory::getApplication();
	        $sender_message='set sender >>>|'.$mailer->setSender[0].'|<<<';
//	        $app->enqueueMessage($sender_message, 'info');
	        $mailer->addRecipient('gacompa@gmail.com' );
		$app = Factory::getApplication();
//		$app->enqueueMessage('add recipient', 'info');
		$mailer->setSubject( 'the subject' );
		$app = Factory::getApplication();
//		$app->enqueueMessage('set subject', 'info');
//		$app->enqueueMessage($mailer->setSubject, 'info');
		$mailer->setBody( 'Lorem ipsum dolor sit amet, consectetur adipiscingelit.' );
		$app = Factory::getApplication();
//		$app->enqueueMessage('set body', 'info');
		$mailer->Send();

//		$resultato=parent::send('giorgio.comparin@gmail.com','giorgio','gacompa@gmail.com','Il soggetto','il corpo della mail');

		if ($mailer->Send())
		{
		    $app = Factory::getApplication();
		    $app->enqueueMessage('mail spedita', 'info');
		}
else
		{
		    $app = Factory::getApplication();
		    $app->enqueueMessage('mail NON spedita', 'error');
		}
		// fine aggiunta di Giorgio
		
//		$mailer=$Mail->sendMail('giorgio.comparin@gmail.com', 'scoutcodera', 'gacompa@gmail.com', 'the subject', 'Lorem ipsum dolor sit amet, consectetur adipiscingelit.', false);

    }
    
}
*/
}
