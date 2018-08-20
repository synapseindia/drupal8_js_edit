<?php
namespace Drupal\js_edit\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class JavascriptEditForm extends FormBase {
	
  /**
   * {@inheritdoc}
   */
	public function getFormId() {
		return 'js_edit_form';
	}
  
  /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state) {
		
		$form = array();
		$form['#attached']['library'][] = 'js_edit/js_edit';
		
		$module_handler = \Drupal::service('module_handler');
		$path = $module_handler->getModule('js_edit')->getPath();
		
		$file = $path.'/'.'js/js_added.js';
		if (file_exists($file)) {
			$js['js_text'] = file_get_contents($file);
		}
		else {
			$js['js_text'] = '';
		}

		$form['title'] = [
			'#type' => 'Item',
			'#markup' => '<h3>Add custom Js</h3>',
		];
		
		$form['js_path'] = [
			'#type' => 'hidden',
			'#value' => $path,
		];

		$form['js_code'] = [
			'#title' => t('JS Code'),
			'#type' => 'textarea',
			'#required' => TRUE,
			'#default_value' => $js['js_text'],
			'#rows' => 15,
		];

		$form['js_text_ace'] = [
			'#prefix' => '<span class="disable-ace">Disable Code Editor</span>',
			'#markup' => '<div class="ace-editor"><div id="editor">' . $js['js_text'] . '</div></div>',
		];

		$form['submit'] = [
			'#type' => 'submit',
			'#value' => 'Save Js'
		];		
		return $form;
	}
	
   /**
   * {@inheritdoc}
   */
	public function validateForm(array &$form, FormStateInterface $form_state) {
		
		$path = $form_state->getValue('js_path');
		if($path){
			$file_dir = $path.'/'.'js/';
			$dir = drupal_realpath($file_dir);

			if (!file_prepare_directory($dir, FILE_MODIFY_PERMISSIONS)) {
				$form_state->setError($form, t('The directory %directory is not writable', array('%directory' => $dir)));
			}
		}
		else{
			$form_state->setError($form, t('Some error occured'));
		}
		
	}
  
	/**
	* {@inheritdoc}
	*/
	public function submitForm(array &$form, FormStateInterface $form_state) {
		global $base_url;
		$values = $form_state->getValues();
		$path = $values['js_path'];
		$js_code = $values['js_code'];
		
		$js_file = $path.'/'.'js/js_added.js';
		$data = array($js_code);
		
		file_unmanaged_save_data($data, $js_file, FILE_EXISTS_REPLACE);
		drupal_set_message(t('Your JS was saved.'));
	}

}




