document.addEventListener('DOMContentLoaded', function() {
  // Handle mark as read buttons
  var markAsReadButtons = document.querySelectorAll('.mark-as-read');
  markAsReadButtons.forEach(function(button) {
      button.addEventListener('click', function() {
          var notificationId = this.getAttribute('data-notification-id');
          
          // Send AJAX request to mark as read
          fetch('/buyer/notifications/' + notificationId + '/read', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
              }
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Reload the page to reflect the updated status
                  location.reload();
              } else {
                  alert('Error marking notification as read.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('An error occurred while marking the notification as read.');
          });
      });
  });
});