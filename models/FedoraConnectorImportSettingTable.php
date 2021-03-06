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
 * @author      David McClure <david.mcclure@virginia.edu>
 * @copyright   2010 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache 2 License
 * @version     $Id$
 * @link        http://omeka.org/add-ons/plugins/FedoraConnector/
 * @tutorial    tutorials/omeka/FedoraConnector.pkg
 */
?>

<?php

/**
 * Table class for the import settings.
 */
class FedoraConnectorImportSettingTable extends Omeka_Db_Table
{

    /**
     * The core algorithm to get the behavior for a given item/field.
     *
     * @param Omeka_record $element The DC element to check for.
     * @param Omeka_record $item The parent item that is being imported to.
     *
     * @return string $behavior The behavior.
     */
    public function getBehavior($element, $item)
    {

        // First check for an item- and field-specific record.
        $fieldAndItemRecord = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id = ' . $item->id)
        );

        // If found, return.
        if ($fieldAndItemRecord != null) {
            return $fieldAndItemRecord->behavior;
        }

        // Then look for an item-specific default.
        $itemDefault = $this->fetchObject(
            $this->getSelect()->where('element_id IS NULL AND item_id = ' . $item->id)
        );

        // If found, return.
        if ($itemDefault != null) {
            return $itemDefault->behavior;
        }

        // Then look for a system-wide field default.
        $fieldDefault = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id IS NULL')
        );

        // If found, return.
        if ($fieldDefault != null) {
            return $fieldDefault->behavior;
        }

        // If nothing else is found, return the master default.
        return get_option('fedora_connector_default_import_behavior');

    }

    /**
     * Returns the default behavior record for the given DC element.
     *
     * @param Omeka_record $element The DC element to check for.
     * @param boolean $forSelect Whether the return should be formatted to work
     * with case branching in the admin templates.
     *
     * @return object Omeka_record The record, or false if no record
     * exists.
     */
    public function getDefaultBehavior($element, $forSelect = false)
    {

        $record = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id IS NULL')
        );

        if (!$forSelect) {
            return ($record != null) ? $record : false;
        } else {
            return ($record != null) ? $record->behavior : 'default';
        }

    }

    /**
     * Returns the default behavior record for the given DC element.
     *
     * @param string $field The name of the DC element to check for.
     * @param boolean $forSelect Whether the return should be formatted to work
     * with case branching in the admin templates.
     *
     * @return object Omeka_record The record, or false if no record
     * exists.
     */
    public function getDefaultBehaviorByField($field, $forSelect = false)
    {

        $element = $this->getTable('Element')
            ->findByElementSetNameAndElementName('Dublin Core', $field);

        $record = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id IS NULL')
        );

        if (!$forSelect) {
            return ($record != null) ? $record : false;
        } else {
            return ($record != null) ? $record->behavior : 'default';
        }

    }

    /**
     * Returns the item-specific behavior record for the given DC element.
     *
     * @param Omeka_record $item The item.
     * @param Omeka_record $element The DC element to check for.
     * @param boolean $forSelect Whether the return should be formatted to work
     * with case branching in the admin templates.
     *
     * @return object Omeka_record The record, or false if no record
     * exists.
     */
    public function getItemBehavior($item, $element, $forSelect = false)
    {

        $record = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id = ' . $item->id)
        );

        if (!$forSelect) {
            return ($record != null) ? $record : false;
        } else {
            return ($record != null) ? $record->behavior : 'default';
        }

    }

    /**
     * Returns the item-specific behavior record for the given DC element.
     *
     * @param Omeka_record $item The item.
     * @param string $field The name of the DC element to check for.
     * @param boolean $forSelect Whether the return should be formatted to work
     * with case branching in the admin templates.
     *
     * @return object Omeka_record The record, or false if no record
     * exists.
     */
    public function getItemBehaviorByField($item, $field, $forSelect = false)
    {

        $element = $this->getTable('Element')
            ->findByElementSetNameAndElementName('Dublin Core', $field);

        $record = $this->fetchObject(
            $this->getSelect()->where('element_id = ' . $element->id . ' AND item_id = ' . $item->id)
        );

        if (!$forSelect) {
            return ($record != null) ? $record : false;
        } else {
            return ($record != null) ? $record->behavior : 'default';
        }

    }

    /**
     * Returns the item-item behavior record for the given item.
     *
     * @param Omeka_record $item The item.
     * @param boolean $forSelect Whether the return should be formatted to work
     * with case branching in the admin templates.
     *
     * @return object Omeka_record The record, or false if no record
     * exists.
     */
    public function getItemDefault($item, $forSelect = false)
    {

        $record = $this->fetchObject(
            $this->getSelect()->where('element_id IS NULL AND item_id = ' . $item->id)
        );

        if (!$forSelect) {
            return ($record != null) ? $record : false;
        } else {
            return ($record != null) ? $record->behavior : 'default';
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
