
    document.addEventListener('DOMContentLoaded', function() {
    const delbutton = document.querySelectorAll('.deletebtn');
    delbutton.forEach(btn => {
        btn.addEventListener('click', function() {
            const username = btn.getAttribute('data-username');
            const projectname = btn.getAttribute('data-projectname');
            const projectid = btn.getAttribute('data-projectid');
            console.log(username, projectname, projectid);

            fetch('delete_invoice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&projectname=${encodeURIComponent(projectname)}&projectid=${encodeURIComponent(projectid)}`
            })
            .then((response) => {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.json();
            })
            .then((data) => {
                if (data.status === 'success') {
                    alert('Invoice Deleted');
                    window.location.reload();
                } else {
                    alert(data.message); // Better to show the actual error message from the server
                }
            })
            .catch((err) => {
                alert('Some error occurred: ' + err.message);
                console.error(err);
            });
        });
    });
});
