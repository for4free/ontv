<?php
$error = @$_GET['error'] ? $_GET['error'] : 'ckplayer';
header("Location: /?__error=$error");