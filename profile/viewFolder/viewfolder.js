document.addEventListener('DOMContentLoaded', function() {
    const viewbutton = document.querySelectorAll('.viewbtn');
    viewbutton.forEach(btn => {
        btn.addEventListener('click', function() {
            const username = btn.getAttribute('data-username');
            const projectname = btn.getAttribute('data-projectname');
            console.log(username, projectname);

            fetch('viewfolder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&projectname=${encodeURIComponent(projectname)}`
            })
            .then((response) => {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.json();
            })
            .then((data) => {
                if (data.status === 'success') {
                    window.location.href= 'user.php'
                    console.log(data.services)
                    
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
