<?php 
session_start();

$dialog = $_GET['d'];
$e = $_GET['e'];

if($dialog == 'add-edit-network'){
  include_once('dialogs/add-edit-network.php');
  return;
}

if($dialog == 'warning-no-group-selected'){
  include_once('dialogs/warning-no-group-selected.php');
  return;
}



if($dialog == 'select-network'){
  include_once('dialogs/select-network.php');
  return;
}

if($dialog == 'select-atleast-one-network'){
  include_once('dialogs/select-atleast-one-network.php');
  return;
}

if($dialog == 'select-atleast-one-weekday'){
  include_once('dialogs/select-atleast-one-weekday.php');
  return;
}

if($dialog == 'enter-email'){
  include_once('dialogs/enter-email.php');
  return;
}

if($dialog == 'enter-valid-email'){
  include_once('dialogs/enter-valid-email.php');
  return;
}


if($dialog == 'load'){
  include_once('dialogs/load.php');
  return;
}
if($dialog == 'download-wait'){
  include_once('dialogs/download-wait.php');
  return;
}

if($dialog == 'confirm-delete-customrule'){
  include_once('dialogs/confirm-delete-customrule.php');
  return;
}

if($dialog == 'confirm-delete-customtitle'){
  include_once('dialogs/confirm-delete-customtitle.php');
  return;
}

if($dialog == 'upload-espn-excel'){
  include_once('dialogs/upload-espn-excel.php');
  return;
}

if($dialog == 'add-edit-custom-title'){
  include_once('dialogs/add-edit-custom-title.php');
  return;
}

if($dialog == 'warning-no-rows-selected'){
  include_once('dialogs/warning-no-rows-selected.php');
  return;
}

if($dialog == 'single-row'){
  include_once('dialogs/single-row.php');
  return;
}


if($dialog == 'confirm-delete-schedule'){
  include_once('dialogs/confirm-delete-schedule.php');
  return;
}
if($dialog == 'custom-break-email-form'){
  include_once('dialogs/custom-break-email-form.php');
  return;
}

if($dialog == 'confirm-delete-group-network'){
  include_once('dialogs/confirm-delete-group-network.php');
  return;
}

if($dialog == 'download-queued'){
  include_once('dialogs/download-queued.php');
  return;
}

if($dialog == 'download-error'){
  include_once('dialogs/download-error.php');
  return;
}

if($dialog == 'select-atleast-one-network-instance'){
  include_once('dialogs/select-atleast-one-network-instance.php');
  return;
}

if($dialog == 'shcheduler-select-networks-instances-grid'){
  include_once('dialogs/shcheduler-select-networks-instances-grid.php');
  return;
}

if($dialog == 'network-selector-grid'){
  include_once('dialogs/network-selector-grid.php');
  return;
}

if($dialog == 'confirm-delete-update-schedule'){
  include_once('dialogs/confirm-delete-update-schedule.php');
  return;
}
if($dialog == 'changes-email'){
  include_once('dialogs/changes-email.php');
  return;
}

if($dialog == 'confirm-change-mark-complete'){
  include_once('dialogs/confirm-change-mark-complete.php');
  return;
}

if($dialog == 'confirm-change-delete'){
  include_once('dialogs/confirm-change-delete.php');
  return;
}

if($dialog == 'changes-email-reply'){
  include_once('dialogs/changes-email-reply.php');
  return;
}

if($dialog == 'changes-email-forward'){
  include_once('dialogs/changes-email-forward.php');
  return;
}

if($dialog == 'custom-rule-set-items'){
  include_once('dialogs/custom-rule-set-items.php');
  return;
}

if($dialog == 'confirm-delete-customruleset'){
  include_once('dialogs/confirm-delete-customruleset.php');
  return;
}

if($dialog == 'help-and-tutorial'){
  include_once('dialogs/help-and-tutorial.php');
  return;
}

?>