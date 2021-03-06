<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * FedoraConnector Omeka plugin allows users to reuse content managed in
 * institutional repositories in their Omeka repositories.
 *
 * The FedoraConnector plugin provides methods to generate calls against Fedora-
 * based content disemminators. Unlike traditional ingestion techniques, this
 * plugin provides a facade to Fedora-Commons repositories and records pointers
 * to the "real" objects rather than creating new physical copies. This will
 * help ensure longer-term durability of the content streams, as well as allow
 * you to pull from multiple institutions with open Fedora-Commons
 * respositories.
 *
 * PHP version 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package     omeka
 * @subpackage  fedoraconnector
 * @author      Scholars' Lab <>
 * @author      Ethan Gruber <ewg4x@virginia.edu>
 * @author      Adam Soroka <ajs6f@virginia.edu>
 * @author      Wayne Graham <wayne.graham@virginia.edu>
 * @author      Eric Rochester <err8n@virginia.edu>
 * @copyright   2010 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache 2 License
 * @version     $Id$
 * @link        http://omeka.org/add-ons/plugins/FedoraConnector/
 * @tutorial    tutorials/omeka/FedoraConnector.pkg
 */

require_once '../FedoraConnectorPlugin.php';

/**
 * This class sets up the system for testing this plugin.
 *
 * This borrows from the SimplePages plugin rather extensively.
 */
class FedoraConnector_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    const PLUGIN_NAME = 'FedoraConnector';

    public function setUp() {

        parent::setUp();

        // Authenticate and set the current user.
        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        // Add the plugin hooks and filters (including the install hook).
        $pluginBroker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($pluginBroker, self::PLUGIN_NAME);

        $pluginHelper = new Omeka_Test_Helper_Plugin();
        $pluginHelper->setUp(self::PLUGIN_NAME);

    }

    public function _addPluginHooksAndFilters($pluginBroker, $pluginName) {

        // Set the current plugin so the add_plugin_hook function works.
        $pluginBroker->setCurrentPluginDirName($pluginName);

        new FedoraConnectorPlugin;

    }

    public function _createItem($name)
    {

        $item = new Item;
        $item->featured = 0;
        $item->public = 1;
        $item->save();

        $element_text = new ElementText;
        $element_text->record_id = $item->id;
        $element_text->record_type_id = 2;
        $element_text->element_id = 50;
        $element_text->html = 0;
        $element_text->text = $name;
        $element_text->save();

        return $item;

    }

    public function _createItems($count)
    {

        for ($i=0; $i<$count; $i++) {
            $this->_createItem('TestingItem' . $i);
        }

    }

}


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
