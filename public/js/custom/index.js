var deleteLinks = document.querySelectorAll('.confirm-delete');

for (var i = 0; i < deleteLinks.length; i++) {
    deleteLinks[i].addEventListener('click', function(event) {
        event.preventDefault();
        if (confirm(this.getAttribute('data-confirm'))) {
            document.getElementById(this.getAttribute('data-delete-form')).submit();
        }
    });
}
