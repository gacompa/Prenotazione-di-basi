<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Basiscout
 * @author     Giorgio <gacompa@gmail.com>
 * @copyright  2022 scoutcodera
 * @license    GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

$canEdit = Factory::getUser()->authorise('core.edit', 'com_basiscout');

if (!$canEdit && Factory::getUser()->authorise('core.edit.own', 'com_basiscout'))
{
	$canEdit = Factory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_BASISCOUT_FORM_LBL_BASESCOUT_NOME'); ?></th>
			<td><?php echo $this->item->nome; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_BASISCOUT_FORM_LBL_BASESCOUT_LUOGO'); ?></th>
			<td><?php echo $this->item->luogo; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_BASISCOUT_FORM_LBL_BASESCOUT_DESCRIZIONE'); ?></th>
			<td><?php echo nl2br($this->item->descrizione); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_BASISCOUT_FORM_LBL_BASESCOUT_POSTI_INTERNO'); ?></th>
			<td><?php echo $this->item->posti_interno; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_BASISCOUT_FORM_LBL_BASESCOUT_POSTI_ESTERNO'); ?></th>
			<td><?php echo $this->item->posti_esterno; ?></td>
		</tr>

	</table>

</div>

<?php $canCheckin = Factory::getUser()->authorise('core.manage', 'com_basiscout.' . $this->item->id) || $this->item->checked_out == Factory::getUser()->id; ?>
	<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_basiscout&task=basescout.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_BASISCOUT_EDIT_ITEM"); ?></a>
	<?php elseif($canCheckin && $this->item->checked_out > 0) : ?>
	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_basiscout&task=basescout.checkin&id=' . $this->item->id .'&'. Session::getFormToken() .'=1'); ?>"><?php echo Text::_("JLIB_HTML_CHECKIN"); ?></a>

<?php endif; ?>

<?php if (Factory::getUser()->authorise('core.delete','com_basiscout.basescout.'.$this->item->id)) : ?>

	<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
		<?php echo Text::_("COM_BASISCOUT_DELETE_ITEM"); ?>
	</a>

	<?php echo HTMLHelper::_(
                                    'bootstrap.renderModal',
                                    'deleteModal',
                                    array(
                                        'title'  => Text::_('COM_BASISCOUT_DELETE_ITEM'),
                                        'height' => '50%',
                                        'width'  => '20%',
                                        
                                        'modalWidth'  => '50',
                                        'bodyHeight'  => '100',
                                        'footer' => '<button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button><a href="' . Route::_('index.php?option=com_basiscout&task=basescout.remove&id=' . $this->item->id, false, 2) .'" class="btn btn-danger">' . Text::_('COM_BASISCOUT_DELETE_ITEM') .'</a>'
                                    ),
                                    Text::sprintf('COM_BASISCOUT_DELETE_CONFIRM', $this->item->id)
                                ); ?>

<?php endif; ?>