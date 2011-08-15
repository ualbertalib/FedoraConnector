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

<div class="field">

    <label for="fedora_connector_server">Omitted Datastreams:</label>

    <textarea name="fedora_connector_omitted_datastreams" id="fedora_connector_omitted_datastreams" rows="1" cols="40"><?php echo get_option('fedora_connector_omitted_datastreams'); ?></textarea>

    <p class="explanation">List datastream IDs, comma-separated, that should be 
        omitted from the datastream selection checkbox list and object metadata 
        dropdown menu. Default: RELS-EXT,RELS-INT,AUDIT.</p>

</div>

<div class="field">

    <label for="enable_virgo_import" style="width: 360px;">Enable custom import functionality from Virgo?</label>
    <input type="checkbox" name="enable_virgo_import" id="enable_virgo_import" value="1" <?php if (get_option('fedora_connector_enable_virgo_import') == 1) { echo  'checked="checked"'; } ?>>

    <p class="explanation">Check this box to enable a custom workflow designed to make it easy to import .csv files generated by Virgo
and generate Fedora-integrated items from the data.</p>

</div>

<?php
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
