<?php /* Smarty version 2.6.31, created on 2018-03-03 01:18:29
         compiled from layout.tpl */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="/css/ethna.css" type="text/css" />
<title>Sample</title>
</head>
<body>
<div id="header">
    <h1>Sample</h1>
</div>

<div id="main">
<?php echo $this->_tpl_vars['content']; ?>

</div>

<div id="footer">
    Powered By Ethnam - <?php echo @ETHNA_VERSION; ?>
.
</div>
</body>
</html>