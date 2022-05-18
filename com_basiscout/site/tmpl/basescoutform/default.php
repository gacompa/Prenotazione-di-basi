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
use \Scoutcodera\Component\Basiscout\Site\Helper\BasiscoutHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_basiscout', JPATH_SITE);

$user    = Factory::getUser();
$canEdit = BasiscoutHelper::canUserEdit($this->item, $user);


?>

<div class="basescout-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
		<?php throw new \Exception(Text::_('COM_BASISCOUT_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_BASISCOUT_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_BASISCOUT_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-basescout"
			  action="<?php echo Route::_('index.php?option=com_basiscout&task=basescoutform.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo isset($this->item->ordering) ? $this->item->ordering : ''; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo isset($this->item->checked_out) ? $this->item->checked_out : ''; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo isset($this->item->checked_out_time) ? $this->item->checked_out_time : ''; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'Basiscout')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'Basiscout', Text::_('COM_BASISCOUT_TAB_BASISCOUT', true)); ?>
	<?php echo $this->form->renderField('nome'); ?>

	<?php echo $this->form->renderField('luogo'); ?>

	<?php echo $this->form->renderField('descrizione'); ?>

	<?php echo $this->form->renderField('posti_interno'); ?>

	<?php echo $this->form->renderField('posti_esterno'); ?>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<span class="fas fa-check" aria-hidden="true"></span>
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn btn-danger"
					   href="<?php echo Route::_('index.php?option=com_basiscout&task=basescoutform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
					   <span class="fas fa-times" aria-hidden="true"></span>
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_basiscout"/>
			<input type="hidden" name="task"
				   value="basescoutform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
