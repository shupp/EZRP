/*
 * @author David Friedman
 * @author Bill Shupp
 */
function ezrpInit() {
    var urlPrefix = '/ezrp/';
    var spinnerURL = urlPrefix + 'spinner.php';
    var prepareURL = urlPrefix + 'prepare.php';
    var verifyURL  = urlPrefix + 'verify.php';

    $('.ezrp-init').click(function() {
        $('.ezrp-widget').dialog({
                resizable: false,
                draggable: false,
                modal: true
            }
        );
    });

    (function() {
        var popup;

         $('li.ezrp-google').click(function(e) {

            popup && !popup.closed && popup.close();
            popup = openPopup(500,500);

            popup.window.location = spinnerURL + '?service=google';
            $.ajax({
                type: 'POST',
                url: prepareURL,
                data: "ezrpd=google",
                success: function(data) {
                    response = $.parseJSON(data);
                    if (response.success) {
                        popup.window.location = response.url;
                    } else {
                        alert('failed to log in with google ' + response.message);
                    }
                },
                failure: function(data) {
                    alert('failed to log in with google');
                },
            });

            e.stopPropagation();
            e.preventDefault();
        });

         $('a.yahoo-auth').click(function(e) {

            popup && !popup.closed && popup.close();
            popup = openPopup(500,500);

            popup.window.location = spinnerURL + '?service=yahoo';

            // e.stopPropagation();
            // e.preventDefault();
        });

         $('li.ezrp-twitter').click(function(e) {

            popup && !popup.closed && popup.close();
            popup = openPopup(800,370);

            popup.window.location = spinnerURL + '?service=twitter';

            // e.stopPropagation();
            // e.preventDefault();

        });

        function checkPopup(url) {
            if (popup && !popup.closed) {
                popup.location = url;
                popup.focus();
            } else {
                popup = openPopup(500,500,url);
            }
        }
    })();

    function getCoords(w, h) {
        return {
            x: (self.screenX || self.screenLeft) + (($(self).width() - w) / 2),
            y: (self.screenY || self.screenTop) + (($(self).height() - h) / 2)
        };
    }

    function openPopup(w, h, u, n) {
        var d, c = getCoords(w,h), p = open(u || 'about:blank', n || '_blank', 'width=' + w + ',height=' + h + ',left=' + c.x + ',top=' + c.y);

        return p;
    }
}
