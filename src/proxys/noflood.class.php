<?php
/**
 * noflood.class.php
 *
 * Copyright © 2006 Stephane Gully <stephane.gully@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details. 
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301  USA
 */
require_once dirname(__FILE__)."/../pfci18n.class.php";
require_once dirname(__FILE__)."/../pfcuserconfig.class.php";
require_once dirname(__FILE__)."/../pfcproxycommand.class.php";

/**
 * pfcProxyCommand_noflood
 * this proxy will protect the chat from flooders
 * @author Stephane Gully <stephane.gully@gmail.com>
 */
class pfcProxyCommand_noflood extends pfcProxyCommand
{
  function run(&$xml_reponse, $clientid, $param, $sender, $recipient, $recipientid)
  {
    $c =& $this->c;
    $u =& $this->u;

    $cmdtocheck = array("send", "nick", "me", "notice");
    if ( in_array($this->name, $cmdtocheck) )
    {
      $container =& $c->getContainerInstance();
      $nickid        = $container->getNickId($sender);
      $isadmin       = $container->getMeta("isadmin", "nickname", $nickid);
      $lastfloodtime = $container->getMeta("floodtime", "nickname", $nickid);
      $nbflood       = $container->getMeta("nbflood", "nickname", $nickid);
      $floodtime     = time();
      
      if ($floodtime - $lastfloodtime <= $c->proxys_cfg[$this->proxyname]["delay"])
        $nbflood++;
      else
        $nbflood = 0;
      
      if ($nbflood>$c->proxys_cfg[$this->proxyname]["limit"])
      {
        // kick the flooder
        $msg = _pfc("Please don't post so many message, flood is not tolerated");
        $xml_reponse->addScript("alert('".addslashes($msg)."');");
	// @todo kick the user
        return;
      }

      if ($nbflood == 0)
        $container->setMeta($floodtime, "floodtime", "nickname", $nickid);
      $container->setMeta($nbflood, "nbflood", "nickname", $nickid);
    }
    
    // forward the command to the next proxy or to the final command
    $this->next->run(&$xml_reponse, $clientid, $param, $sender, $recipient, $recipientid);
  }
}

?>