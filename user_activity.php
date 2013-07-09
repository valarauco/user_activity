<?php

/*
 * user_activity plugin
 *
 * Copyright (c) 2013 Reymer Antonio Vargas Solano. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 */

class user_activity extends rcube_plugin
{
  function init()
  {
    $this->add_hook('login_after', array($this, 'login_after'));
  }

  function login_after($args)
  {
    $rcmail = rcmail::get_instance();

    $client_ip = $_SERVER['REMOTE_ADDR'];
    $user_id = $rcmail->user->ID;

    $now = date('Y-m-d H:i:s');

    $query = $rcmail->db->query(
      "SELECT counter
       FROM user_activity
       WHERE ip_address = ? AND user_id = ?",
      $client_ip, $user_id);
    $result = $rcmail->db->fetch_assoc($query);
    write_log("user_activity","result: ".print_r($result,true). "\n USER-ID: $user_id");

    if ($result)
      $this->update_user_activity($now, $result['counter'], $client_ip, $user_id);
    else
      $this->insert_user_activity($client_ip, $user_id, $now);
  }

  private function insert_user_activity($client_ip, $user_id, $now)
  {
    $rcmail = rcmail::get_instance();

    $query = $rcmail->db->query(
      "INSERT INTO user_activity
       (ip_address, user_id, first, last, counter)
       VALUES (?, ?, ?, ?, ?)",
      $client_ip, $user_id, $now, $now, 1);
  }

  private function update_user_activity($now, $counter, $client_ip, $user_id)
  {
    $rcmail = rcmail::get_instance();

    $query = $rcmail->db->query(
      "UPDATE user_activity
       SET last = ?, counter = ?
       WHERE ip_address = ? AND user_id = ?",
      $now, $counter + 1, $client_ip, $user_id);
  }
}

?>
