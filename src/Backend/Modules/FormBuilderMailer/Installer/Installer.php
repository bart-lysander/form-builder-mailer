<?php

namespace Backend\Modules\FormBuilderMailer\Installer;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the Form Builder Mailer module
 *
 * @author webleads <fork@webleads.nl>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        // install the module in the database
        $this->addModule('FormBuilderMailer');

        // install the locale, this is set here because we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'FormBuilderMailer');
        $this->setActionRights(1, 'FormBuilderMailer', 'Settings');

        // settings navigation
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'FormBuilderMailer', 'form_builder_mailer/settings');

        BackendModel::setModuleSetting('FormBuilderMailer', 'enabled', true);
        BackendModel::subscribeToEvent(
            'Formbuilder',
            'after_submission',
            'FormBuilderMailer',
            array('Backend\Modules\FormBuilderMailer\Engine\Model', 'afterFormSubmission')
        );
    }
}
