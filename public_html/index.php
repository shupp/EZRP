<html>
<head>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
</head>
<body>
<script src="http://www.google.com/jsapi"></script>
<script src="/ezrp/ezrp.js"></script>
<script>
google.load("jquery", "1");
google.load("jqueryui", "1");
google.setOnLoadCallback(function() {
    ezrpInit();
});
</script>

<a class="ezrp-init" href="#" onclick="return false;">Sign up or Join!</a>

<div class="ezrp-widget" title="Choose a provider below!" style="display: none;">
    <ul>
        <li class="ezrp-twitter">Twitter
        <li class="ezrp-google">Google
        <li class="ezrp-openid">OpenID
                                <div class="ezrp-openid-form" style="display: none;">
                                OpenID Identifier: <input type="text" id="ezrp-openid-identifier">
                                <input type="submit" id="ezrp-openid-submit" value="login">
    </ul>
</div>
</body>
</html>
