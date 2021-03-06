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


require_once dirname(dirname(__FILE__)) . '/libraries/FedoraConnector/AbstractRenderer.php';

/**
 * This class defines a display adapter for an image.
 */
class Image_Renderer extends FedoraConnector_AbstractRenderer
{

    // Some image types are handled elsewhere or won't render with a basic <img> tag. These
    // are excluded in the regular expression in canDisplay().
    private static $_excludedImageFormats = array(
        'jp2',
        'x-mrsid-image'
    );

    /**
     * This tests whether this renderer can display a datastream.
     *
     * @param Omeka_Record $datastream The data stream.
     *
     * @return boolean True if this can display the datastream.
     */
    function canDisplay($datastream) {

        $excludeString = implode('|', self::$_excludedImageFormats);
        return (bool)(preg_match('/^image\/(?!' . $excludeString . ')/', $datastream->mime_type));

    }

    /**
     * This tests whether this renderer can preview a datastream.
     *
     * @param Omeka_Record $datastream The data stream.
     *
     * @return boolean True if this can display the datastream.
     */
    function canPreview($datastream) {

        return $this->canDisplay($datastream);

    }

    /**
     * This displays a datastream.
     *
     * @param Omeka_Record $datastream The data stream.
     *
     * @return string The display HTML for the datastream.
     */
    function display($datastream) {

        $url = $datastream->getContentUrl();
        $html = "<img alt='image' src='{$url}' />";
        return $html;

    }

    /**
     * This displays a datastream's preview.
     *
     * @param Omeka_Record $datastream The data stream.
     *
     * @return string The preview HTML for the datastream.
     */
    function preview($datastream) {

        $url = $datastream->getContentUrl();
        $html = "<img alt='image' src='{$url}' class='fedora-preview' />";
        return $html;

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
