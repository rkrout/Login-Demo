<?php 

require_once("../utils/helper-utils.php");
require_once("../utils/session-utils.php");

delete_session("user_id");
delete_session("user_name");
delete_session("user_email");

redirect("/auth/login.php");
