$(document).ready(function () {
    showNotifications();
    setInterval(showNotifications, 5000);
});

//show life notification badge with ajax
function showNotifications() {
    //return;
    $.ajax({
        url: 'http://localhost/MTGEventMaster/php/fetchNotifications.php',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.count >= 0) {
                let notificationBadge = $('#notification-number');
                if (response.count > 0) {
                    notificationBadge.text(response.count);
                    notificationBadge.show();
                } else {
                    notificationBadge.hide();
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching notifications:', error);
        }
    });
}