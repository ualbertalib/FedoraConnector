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

require_once FEDORA_CONNECTOR_PLUGIN_DIR . '/libraries/FedoraConnector/AbstractImporter.php';

class MODS_Importer extends FedoraConnector_AbstractImporter
{

    /**
     * Checks to see if the datastream is DC format.
     *
     * @param object $datastream The datastream.
     *
     * @return boolean True if format is DC.
     */
    public function canImport($datastream)
    {

        return ($datastream->metadata_stream == 'MODS');

    }

    /**
     * Get xpath queries for finding nodes in the input data for a given DC name.
     *
     * @param string $name The DC name of the element.
     *
     * @return array The array of xpath queries.
     */
    public function getQueries($name)
    {

        switch ($name) {

            case 'Title':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="titleInfo"]'
                    . '/*[local-name()="title"]'
                );

                break;

            case 'Creator':

                $queries = array(
                    '//*[local-name()="mods"]'
                    . '/*[local-name()="name"][*[local-name()="role"] = "creator"]'
                );

                break;

            case 'Subject':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="subject"]'
                    . '/*[local-name()="topic"]'
                );

                break;

            case 'Description':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="abstract"]',
                    '//*[local-name()="mods"]/*[local-name()="note"]',
                    '//*[local-name()="mods"]/*[local-name()="tableOfContents"]'
                );

                break;

            case 'Publisher':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="originInfo"]'
                    . '/*[local-name()="publisher"]'
                );

                break;

            case 'Contributor':

                // Mapping from name/namePart to Contributor specifically is 
                // difficult.  There are likely institutional differences in 
                // mapping.
                $queries = array();

                break;

            case 'Date':

                $prefix = '//*[local-name()="mods"]/*[local-name()="originInfo"]';
                $queries = array(
                    $prefix . '/*[local-name()="dateIssued"]',
                    $prefix . '/*[local-name()="dateCreated"]',
                    $prefix . '/*[local-name()="dateCaptured"]',
                    $prefix . '/*[local-name()="dateOther"]'
                );

                break;

            case 'Type':

                // XXX: Originally, this set $queries to something sane and
                // immediately set it again to an empty array.  I need to test to
                // make sure I'm using the right one.
                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="typeOfResource"]',
                    '//*[local-name()="mods"]/*[local-name()="genre"]'
                );

                break;

            case 'Format':

                // XXX: Originally, this set $queries to something sane and
                // immediately set it again to an empty array.  I need to test to
                // make sure I'm using the right one.
                $prefix
                    = '//*[local-name()="mods"]'
                    . '/*[local-name()="physicalDescription"]';
                $queries = array(
                    $prefix . '/*[local-name()="internetMediaType"]',
                    $prefix . '/*[local-name()="extent"]',
                    $prefix . '/*[local-name()="form"]'
                );

                break;

            case 'Identifier':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="identifier"]',
                    '//*[local-name()="mods"]/*[local-name()="location"]'
                    . '/*[local-name()="uri"]'
                );

                break;

            case 'Source':

                $prefix
                    = '//*[local-name()="mods"]/*[local-name()="relatedItem"]'
                    . '[@type="original"]/*';
                $queries = array(
                    $prefix .  '[local-name()="titleInfo"]/*[local-name()="title"]',
                    $prefix .  '[local-name()="location"]/*[local-name()="url"]'
                );

                break;

            case 'Language':

                $queries = array(
                    '//*[local-name()="mods"]/*[local-name()="language"]'
                );

                break;

            case 'Relation':

                $prefix
                    = '//*[local-name()="mods"]/*[local-name()="relatedItem"]/*';
                $queries = array(
                    $prefix .  '[local-name()="titleInfo"]/*[local-name()="title"]',
                    $prefix .  '[local-name()="location"]/*[local-name()="url"]'
                );

                break;

            case 'Coverage':

                $prefix = '//*[local-name()="mods"]/*[local-name()="subject"]/*';
                $queries = array(
                    $prefix . '[local-name()="temporal"]',
                    $prefix . '[local-name()="geographic"]',
                    $prefix . '[local-name()="hierarchicalGeographic"]',
                    $prefix . '[local-name()="cartographics"]'
                );

                break;

            case 'Rights':

                $queries == array(
                    '//*[local-name()="mods"]/*[local-name()="accessCondition"]'
                );

                break;

        }

        return $queries;

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
