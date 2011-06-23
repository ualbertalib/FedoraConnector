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
 * A homebrew colum sorter, implemented so as to keep more control
 * over how the record loop is handled in the view.
 *
 * @param object $request The incoming request dispatched by the 
 * front controller.
 *
 * @return string $order The sorting parameter for the query.
 */
function fedorahelpers_doColumnSortProcessing($sort_field, $sort_dir)
{

    if (isset($sort_dir)) {
        $sort_dir = ($sort_dir == 'a') ? 'ASC' : 'DESC';
    }

    return (isset($sort_field)) ? trim(implode(' ', array($sort_field, $sort_dir))) : '';

}

/**
 * Return nodes by file and xpath query.
 *
 * @param string $uri The uri of the document.
 * @param string $xpath The XPath query.
 *
 * @return object The matching nodes.
 */
function fedorahelpers_getQueryNodes($uri, $xpath)
{

    $xml = new DomDocument();
    $xml->load($uri);
    $query = new DOMXPath($xml);
    return $query->query($xpath);

}

/**
 * Same as getQueryNodes, but pulls out single result.
 *
 * @param string $uri The uri of the document.
 * @param string $xpath The XPath query.
 *
 * @return object The matching node.
 */
function fedorahelpers_getQueryNode($uri, $xpath)
{

    $xml = new DomDocument();
    $xml->load($uri);
    $query = new DOMXPath($xml);
    $nodes = $query->query($xpath);

    $node = null;
    foreach ($nodes as $n) {
        $node = $n->nodeValue;
    }

    return $node;

}

/**
 * Retrieves items to populate the listings in the itemselect view.
 *
 * @param string $page The page to fetch.
 * @param string $order The constructed SQL order clause.
 * @param string $search The string to search for.
 *
 * @return array $items The items.
 */
function fedorahelpers_getItems($page, $order, $search)
{

    $db = get_db();
    $itemTable = $db->getTable('Item');

    // Nasty query. Fallback from weird issue with left join where item id was
    // getting overwritten. Fix.
    $select = $db->select()
        ->from(array('item' => $db->prefix . 'items'))
        ->columns(array('item_id' => 'item.id', 
            'Type' =>
            "(SELECT name from `$db->ItemType` WHERE id = item.item_type_id)",
            'item_name' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 50)",
            'creator' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 39)"
            ));

    if (isset($page)) {
        $select->limitPage($page, get_option('per_page_admin'));
    }
    if (isset($order)) {
        $select->order($order);
    }
    if (isset($search)) {
        $select->where("(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 50) like '%" . $search . "%'");
    }

    return $itemTable->fetchObjects($select);

}

/**
 * Format item add date for datastream create workflow.
 *
 * @param string $date The date in datetime.
 *
 * @return string $date The formatted date.
 */
function fedorahelpers_formatDate($date)
{

    $date = new DateTime($date);
    return '<strong>' . $date->format('F j, Y') . '</strong> at ' .
       $date->format('g:i a');

}
