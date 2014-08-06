<?php

namespace Backend\Modules\FormBuilderMailer\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;

/**
 * This is the settings-action, it will display a form to set general settings
 *
 * @author webleads <fork@webleads.nl>
 */
class Settings extends BackendBaseActionEdit
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    /**
     * Loads the settings form
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('settings');

        $this->frm->addCheckbox('enabled', BackendModel::getModuleSetting($this->URL->getModule(), 'enabled', false));
        $this->frm->addCheckbox('log', BackendModel::getModuleSetting($this->URL->getModule(), 'log', true));
        $this->frm->addCheckbox('add_data', BackendModel::getModuleSetting($this->URL->getModule(), 'add_data', true));
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {
                $enabled = (bool)$this->frm->getField('enabled')->getValue();
                BackendModel::setModuleSetting($this->URL->getModule(), 'enabled', $enabled);
                if ($enabled) {
                    BackendModel::subscribeToEvent(
                        'Formbuilder',
                        'after_submission',
                        'FormBuilderMailer',
                        array('\Backend\Modules\FormBuilderMailer\Engine\Model', 'afterFormSubmission')
                    );
                } else {
                    BackendModel::unsubscribeFromEvent(
                        'Formbuilder',
                        'after_submission',
                        'FormBuilderMailer'
                    );
                }
                BackendModel::setModuleSetting(
                    $this->URL->getModule(),
                    'log',
                    (bool)$this->frm->getField('log')->getValue()
                );
                BackendModel::setModuleSetting(
                    $this->URL->getModule(),
                    'add_data',
                    (bool)$this->frm->getField('add_data')->getValue()
                );

                BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

                $this->redirect(BackendModel::createURLForAction('settings') . '&report=saved');
            }
        }
    }
}
